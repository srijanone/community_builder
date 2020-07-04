<?php

/**
 * @file
 * Definition of Drupal\community_builder\Plugin\views\field\UserCommentCount
 */

namespace Drupal\community_builder\Plugin\views\field;

use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\NodeType;
use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\ResultRow;
use Drupal\Core\Database\Connection;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Field handler to flag the node type.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("user_comment_count")
 */
class UserCommentCount extends FieldPluginBase {

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
    $options['node_type'] = array('default' => 'posts');
    $options['comment_type'] = array('default' => 'post_comment');
    $options['comment_field'] = array('default' => 'field_comments');

    return $options;
  }

  /**
   * Provide the options form.
   * @param $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    $types = NodeType::loadMultiple();
    $options = [];
    foreach ($types as $key => $type) {
      $options[$key] = $type->label();
    }
    $form['node_type'] = [
      '#title' => $this->t('Select Content type:'),
      '#type' => 'select',
      '#default_value' => $this->options['node_type'],
      '#options' => $options,
    ];

    $form['comment_type'] = [
      '#title' => $this->t('Add comment type:'),
      '#type' => 'textfield',
      '#default_value' => $this->options['comment_type'],
      '#description' => $this->t('Please add comment type machine name')
    ];

    $form['comment_field'] = [
      '#title' => $this->t('Add comment field:'),
      '#type' => 'textfield',
      '#default_value' => $this->options['comment_field'],
      '#description' => $this->t('Please add comment field machine name used under content type.')
    ];

    parent::buildOptionsForm($form, $form_state);
  }

  /**
   * Return comment count per user.
   * @param \Drupal\views\ResultRow $values
   *
   * @return \Drupal\Component\Render\MarkupInterface|\Drupal\Core\StringTranslation\TranslatableMarkup|\Drupal\views\Render\ViewsRenderPipelineMarkup|string
   */
  public function render(ResultRow $values) {
    $entity = $values->_entity;
    $uid = $entity->id();
    if (!empty($uid)) {
      $query = $this->connection->select('comment_field_data', 'c');
      $query->condition('c.status', 1);
      $query->condition('c.uid', $uid);
      $query->condition('c.field_name', $this->options['comment_field']);
      $query->condition('c.comment_type', $this->options['comment_type']);
      $query->addExpression('COUNT(c.cid)', 'count');
      return $query->execute()->fetchField();
    }
    return $this->t('User ID not found!');
  }
}
