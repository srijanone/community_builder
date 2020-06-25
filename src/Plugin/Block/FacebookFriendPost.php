<?php


namespace Drupal\community_builder\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\user\Entity\User;
use Drupal\views\Views;
/**
 * Provides a 'Facebook Friend Posts' block.
 *
 * @Block(
 *   id = "facebook_friend_post_block",
 *   admin_label = @Translation("Facebook Friend Post"),
 *   category = @Translation("Facebook")
 * )
 */
class FacebookFriendPost extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    // Get current user friends.
    $current_uid = \Drupal::currentUser()->id();
    $user = User::load($current_uid);
    $is_fb_user = !empty($user->get('field_fb_id')->value) ? TRUE : FALSE;
    $posts = '';
    if ($is_fb_user) {
      $fb_friends = json_decode($user->get('field_facebook_friends')->value);
      foreach ($fb_friends as $friend) {
        $uids[] = $friend->id;
      }
      $uids = implode(',', $uids);
      // Get view block display by contextual filter of Facebook user id.
      $view = Views::getView('facebook_friend_post');
      $view->setDisplay('block');
      // Contextual relationship filter.
      $view->setArguments([$uids]);
      $posts = $view->render();
    }

    return [
      '#theme' => 'facebook_friend_post',
      '#block' => [
        'display' => $is_fb_user,
        'result' => $posts,
      ]
    ];
  }

  public function getCacheMaxAge() {
    return 0;
  }
}