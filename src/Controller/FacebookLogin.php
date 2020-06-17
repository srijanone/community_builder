<?php

namespace Drupal\community_builder\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\user\Entity\User;

/**
 * Defines FacebookLogin class.
 */
class FacebookLogin extends ControllerBase {

  /**
   * Process facebook login.
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function index() {
    // Get user request.
    $request = $param = \Drupal::request()->request->all();
    // Empty check for request.
    if (empty($request['email']) || empty($request['uid']) || empty($request['name'])) {
      $data = [
        'message' => 'missing required parameters',
        'status' => 403
      ];
      return new JsonResponse($data);
    }

    global $base_url;
    // Get facebook app settings from configuration.
    $config = \Drupal::config('community_builder.settings');
    // Get user details from email.
    $user = \Drupal::entityQuery('user')
      ->condition('mail', $request['email'])
      ->execute();
    // Check if user exist or not.
    if (!empty($user)) {
      $this->userLogin($request);
      // Get post login url from config settings.
      // Default redirect on home page.
      $post_login = empty($config->get('facebook.post_login')) ? $base_url :
        $base_url . $config->get('facebook.post_login');
      $user = array_values($user);
      $uid = $user[0];
      // Replace the [uid] token from post login URL with actual value.
      $redirect = str_replace('[uid]', $uid, $post_login);
    } else {
      $uid = $this->createUser($request);
      // Get post register url from config settings.
      // Default redirect on user edit page.
      $post_register = empty($config->get('facebook.post_register')) ? $base_url
        . '/user/[uid]/edit' : $base_url . $config->get('facebook.post_register');
      // Replace the [uid] token from post register URL with actual value.
      $redirect = str_replace('[uid]', $uid, $post_register);
    }

    if (empty($redirect)) {
      // Default return statement.
      $data = [
        'message' => 'Bad Request',
        'status' => 403
      ];
    } else {
      $data = [
        'redirect' => $redirect,
        'status' => 200
      ];
    }

    return new JsonResponse($data);
  }

  /**
   * Crete user from facebook details.
   * @param $request
   *
   * @return string
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function createUser($request) {
    // Get default language.
    $language = \Drupal::languageManager()->getCurrentLanguage()->getId();

    $user = User::create();
    $user->setPassword($request['email']);
    $user->enforceIsNew();
    $user->setEmail($request['email']);
    $user->setUsername($request['email']); // This username must be unique and accept only a-Z,0-9, - _ @ .
    $user->set("init", 'mail');
    $user->set("langcode", $language);
    $user->set("preferred_langcode", $language);
    $user->set("preferred_admin_langcode", $language);
    // Set custom fields.
    $user->set("field_name", $request['name']);
    // Set facebook uid for future reference.
    $user->set("field_fb_id", $request['uid']);
    // Set facebook mutual friend for future reference.
    $user->set("field_facebook_friends", $request['friends']);
    $user->activate();
    //Save user account
    $user->save();
    // No email verification required; log in user immediately.
    _user_mail_notify('register_no_approval_required', $user);
    // Session set for user.
    user_login_finalize($user);
    \Drupal::messenger()->addMessage('Registration successful. You are now logged in.');
    return $user->id();
  }

  /**
   * User login helper function.
   * @param $email
   *
   * @return string
   */
  public function userLogin($request) {
    // Load user by email id.
    $user = user_load_by_mail($request['email']);
    // Set user session for login.
    user_login_finalize($user);
    // Update facebook uid.
    $user->set("field_fb_id", $request['uid']);
    // Update facebook mutual friends.
    $user->set("field_facebook_friends", $request['friends']);
    $user->save();
    \Drupal::messenger()->addMessage('Login successful.');
    return true;
  }

}
