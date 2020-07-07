<?php

namespace Drupal\community_builder\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class CommunitySettingsForm.
 */
class CommunitySettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'community_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['community_builder.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);

    $config = $this->config('community_builder.settings');

    $form['facebook'] = [
      '#type' => 'fieldset',
      '#title' => t('Facebook App settings'),
      '#collapsible' => FALSE,
      '#collapsed' => FALSE,
    ];
    $form['facebook']['markup'] = [
      '#type' => 'markup',
      '#markup' => $this->t('<p>You need to first create a Facebook App
        at <a href="https://developers.facebook.com/apps">https://developers.facebook.com/apps</a></p>'),
    ];
    $form['facebook']['app_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Application ID'),
      '#default_value' => $config->get('facebook.app_id'),
      '#required' => TRUE,
      '#description' => $this->t('Copy the App ID of your Facebook App
      here. This value can be found from your App Dashboard.')
    ];
//    $form['facebook']['app_secret'] = [
//      '#type' => 'textfield',
//      '#title' => $this->t('App Secret'),
//      '#default_value' => $config->get('facebook.app_secret'),
//      '#required' => TRUE,
//      '#description' => $this->t('Copy the App Secret of your Facebook
//        App here. This value can be found from your App Dashboard.')
//    ];
    $form['facebook']['api_version'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Facebook Graph API version'),
      '#default_value' => $config->get('facebook.api_version'),
      '#required' => TRUE,
      '#description' => $this->t('Copy the API Version of your Facebook
        App here. This value can be found from your App Dashboard.')
    ];
    $form['facebook']['site_url'] = [
      '#type' => 'textfield',
      '#disabled' => TRUE,
      '#title' => $this->t('Site URL'),
      '#description' => $this->t('Copy this value to <em>Site URL</em>
        field of your Facebook App settings.'),
      '#default_value' => $GLOBALS['base_url'],
    ];

    $form['facebook']['post_login'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#title' => $this->t('Post login path'),
      '#description' => $this->t('Path where the user should be redirected
            after a successful login. It must begin with <em>/,
            #</em> or <em>?</em>. <br>Supported Token: [uid]'),
      '#default_value' => $config->get('facebook.post_login'),
    ];

    $form['facebook']['post_register'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#title' => $this->t('Post Register path'),
      '#description' => $this->t('Path where the user should be redirected
        after a successful register. It must begin with <em>/,
        #</em> or <em>?</em>. <br>Supported Token: [uid]'),
      '#default_value' => $config->get('facebook.post_register'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('community_builder.settings');
    $config->set('facebook.app_id', $form_state->getValue('app_id'));
//    $config->set('facebook.app_secret', $form_state->getValue('app_secret'));
    $config->set('facebook.api_version', $form_state->getValue('api_version'));
    $config->set('facebook.post_login', $form_state->getValue('post_login'));
    $config->set('facebook.post_register', $form_state->getValue('post_register'));
    $config->save();
    parent::submitForm($form, $form_state);
  }

}
