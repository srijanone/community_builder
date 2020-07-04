<?php

namespace Drupal\community_builder\Plugin\views\sort;

use Drupal\views\Plugin\views\sort\SortPluginBase;

/**
 * Sorts entities by user comment count.
 *
 * @ViewsSort("user_comment_count_sort")
 */
class UserCommentCountSort extends SortPluginBase {

  /**
   * Provide a list of options for the default sort form.
   *
   * Should be overridden by classes that don't override sort_form.
   */
  protected function sortOptions() {
    return [
      'ASC' => $this->t('Ascending'),
      'DESC' => $this->t('Descending'),
    ];
  }

  /**
   * Display whether or not the sort order is ascending or descending.
   */
  public function adminSummary() {
    if (!empty($this->options['exposed'])) {
      return $this->t('Exposed');
    }

    // Get the labels defined in sortOptions().
    $sort_options = $this->sortOptions();
    return $sort_options[strtoupper($this->options['order'])];
  }

  /**
   * {@inheritdoc}
   */
  public function query() {
    $this->ensureMyTable();
    $this->query->addOrderBy(NULL, "$this->tableAlias.$this->field", $this->options['order'], $this->tableAlias . '_' . $this->field);
  }

}
