<?php


namespace Drupal\community_dashboard\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Dashboard Menu' block.
 *
 * @Block(
 *   id = "dashboard_menu_block",
 *   admin_label = @Translation("Community Dashboard Menu Block"),
 *   category = @Translation("Community Builder")
 * )
 */
class DashboardMenuBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {

    return [
      '#theme' => 'dashboard_menu',
      '#data' => [],
    ];
  }

  public function getCacheMaxAge() {
    return 0;
  }
}
