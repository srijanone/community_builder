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
    return ['community.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);

    $config = $this->config('community.settings');

    $form['hero_banner'] = [
      '#type' => 'fieldset',
      '#title' => t('Hero Banner Configurations'),
      '#collapsible' => FALSE,
      '#collapsed' => FALSE,
    ];
    $form['hero_banner']['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title'),
      '#default_value' => $config->get('hero_banner.title'),
      '#description' => $this->t('This help text will over hero banner.')
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('community.settings');
    $config->set('hero_banner.title', $form_state->getValue('title'));
    $config->save();
    parent::submitForm($form, $form_state);
  }

}
