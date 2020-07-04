<?php

/**
 * @file
 * Definition of Drupal\community_builder\Plugin\views\field\UserLikeCount
 */

namespace Drupal\community_builder\Plugin\views\field;

use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\ResultRow;
use Drupal\Core\Database\Connection;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Field handler to flag the node type.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("user_like_count")
 */
class UserLikeCount extends FieldPluginBase {

  /**
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, Connection $connection) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->connection = $connection;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('database')
    );
  }

  /**
   * @{inheritdoc}
   */
  public function query() {
    // Leave empty to avoid a query on this field.
  }

  /**
   * Define the available options
   * @return array
   */
  protected function defineOptions() {
    $options = parent::defineOptions();
    $options['flag_id'] = array('default' => 'like');

    return $options;
  }

  /**
   * Provide the options form.
   * @param $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {

    $form['flag_id'] = [
      '#title' => $this->t('Flag ID:'),
      '#type' => 'textfield',
      '#default_value' => $this->options['flag_id'],
      '#description' => $this->t('Please add flag id machine name.')
    ];

    parent::buildOptionsForm($form, $form_state);
  }

  /**
   * Return flagging count per user.
   * @param \Drupal\views\ResultRow $values
   *
   * @return \Drupal\Component\Render\MarkupInterface|\Drupal\Core\StringTranslation\TranslatableMarkup|\Drupal\views\Render\ViewsRenderPipelineMarkup|string
   */
  public function render(ResultRow $values) {
    $entity = $values->_entity;
    $uid = $entity->id();
    if (!empty($uid)) {
      $query = $this->connection->select('flagging', 'f');
      $query->condition('flag_id', $this->options['flag_id']);
      $query->condition('uid', $uid);
      $query->addExpression('COUNT(f.id)', 'count');
      return $query->execute()->fetchField();
    }
    return $this->t('User entity not found!');
  }
}
