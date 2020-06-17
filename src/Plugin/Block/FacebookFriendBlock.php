<?php


namespace Drupal\community_builder\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Facebook Friends' block.
 *
 * @Block(
 *   id = "facebook_friend_block",
 *   admin_label = @Translation("Facebook Friends"),
 *   category = @Translation("Facebook")
 * )
 */
class FacebookFriendBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {

    return [
      '#theme' => 'facebook_friends',
      '#data' => [],
      '#attached' => [
        'library' => ['community_builder/facebook_graph_api'],
      ],
    ];
  }

  public function getCacheMaxAge() {
    return 0;
  }
}
