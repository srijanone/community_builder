<?php

/**
 * Implements hook_views_data_alter().
 */
function community_builder_views_data_alter(array &$data) {
  $data['node']['user_comment_count'] = [
    'title' => t('User Comment Count'),
    'field' => [
      'title' => t('User Comment Count'),
      'help' => t('Display user\'s comment count by uid'),
      'id' => 'user_comment_count',
    ],
    'sort' => [
      'id' => 'standard',
    ]
  ];
  $data['node']['user_like_count'] = [
    'title' => t('User Like Count'),
    'field' => [
      'title' => t('User Like Count'),
      'help' => t('Display user\'s like count by uid'),
      'id' => 'user_like_count',
    ]
  ];
}
