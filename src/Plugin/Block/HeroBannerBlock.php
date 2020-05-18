<?php

namespace Drupal\community_builder\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Form\FormStateInterface;


/**
 * Provides a 'Hero Banner' Block.
 *
 * @Block(
 *   id = "hero_banner_block",
 *   admin_label = @Translation("Community - Hero Banner block"),
 *   category = @Translation("Community Builder"),
 * )
 */
class HeroBannerBlock extends BlockBase implements BlockPluginInterface {

  /**
   * Block configuration form.
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);

    $config = $this->getConfiguration();

    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title'),
      '#description' => $this->t('Add Interactive block title'),
      '#default_value' => isset($config['title']) ? $config['title'] : '',
      '#prefix' => '<hr>'
    ];

    $form['short_title'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Short Title'),
      '#description' => $this->t('Add Interactive block short title'),
      '#default_value' => isset($config['short_title']) ? $config['short_title'] : '',
    ];

    // Ask Question Button details form.
    $form['ask_que_fieldset'] = [
      '#type' => 'fieldset',
      '#title' => t('Ask Question Button'),
      '#collapsible' => FALSE,
      '#collapsed' => FALSE,
    ];
    $form['ask_que_fieldset']['ask_que'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Text'),
      '#default_value' => isset($config['ask_que']) ? $config['ask_que'] : '',
    ];
    $form['ask_que_fieldset']['ask_que_link'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Link'),
      '#description' => $this->t('Specify pages by using their paths. ex. "/communities/india"'),
      '#default_value' => isset($config['ask_que_link']) ? $config['ask_que_link'] : '',
    ];

    // Get Support Button details form.
    $form['get_support_fieldset'] = [
      '#type' => 'fieldset',
      '#title' => t('Get Support Button'),
      '#collapsible' => FALSE,
      '#collapsed' => FALSE,
    ];
    $form['get_support_fieldset']['get_support'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Text'),
      '#default_value' => isset($config['get_support']) ? $config['get_support'] : '',
    ];
    $form['get_support_fieldset']['get_support_link'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Link'),
      '#description' => $this->t('Specify pages by using their paths. ex. "/communities/india"'),
      '#default_value' => isset($config['get_support_link']) ? $config['get_support_link'] : '',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    parent::blockSubmit($form, $form_state);
    $values = $form_state->getValues();
    // Save value to block config.
    $this->configuration['title'] = $values['title'];
    $this->configuration['short_title'] = $values['short_title'];
    $this->configuration['ask_que'] = $values['ask_que_fieldset']['ask_que'];
    $this->configuration['ask_que_link'] = $values['ask_que_fieldset']['ask_que_link'];
    $this->configuration['get_support'] = $values['get_support_fieldset']['get_support'];
    $this->configuration['get_support_link'] = $values['get_support_fieldset']['get_support_link'];
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    // Pass data to theme and then twig.
    global $base_url;
    $data = [
      'title' => t($this->configuration['title']),
      'short_title' => t($this->configuration['short_title']),
      'askQue' => [
        'button' => t($this->configuration['ask_que']),
        'link' => $base_url . $this->configuration['ask_que_link'],
      ],
      'getSupport' => [
        'button' => t($this->configuration['get_support']),
        'link' => $base_url . $this->configuration['get_support_link'],
      ]
    ];

    return [
      '#theme' => 'hero_banner_block',
      '#data' => $data,
    ];
  }

}
