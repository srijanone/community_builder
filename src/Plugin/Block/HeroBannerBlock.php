<?php

namespace Drupal\community_builder\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Hero Banner' Block.
 *
 * @Block(
 *   id = "hero_banner_block",
 *   admin_label = @Translation("Community - Hero Banner block"),
 *   category = @Translation("Community Builder"),
 * )
 */
class HeroBannerBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

    // Pass data to theme and then twig.
    $data = [
      'title' => t('Engaging people with similar interest'),
      'text' => t('Engaging people with similar interest Engaging people with similar interest Engaging people with similar interest'),
      'buttons' => [
        'button1' => t('Ask Question'),
        'button2' => t('Get Support')
      ]
    ];

    return [
      '#theme' => 'hero_banner_block',
      '#data' => $data,
    ];
  }

}
