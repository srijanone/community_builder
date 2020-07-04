<?php

namespace Drupal\community_dashboard\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\user\Entity\User;
use Drupal\image\Entity\ImageStyle;
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
    // Get current user.
    $uid = \Drupal::currentUser()->id();
    $account = User::load($uid);
    $full_name = $account->field_name->value;
    // User Profile pic.
    $profile_picture = 'https://place-hold.it/300x300';
    if (!empty($account->field_profile_picture->entity)) {
      $path = $account->field_profile_picture->entity->getFileUri();
      $profile_picture = ImageStyle::load('user_profile')->buildUrl($path);
    }
    // Render dashboard menu block.
    $dashboard_menu = $this->dashboardMenu();

    return [
      '#theme' => 'admin_dashboard_menu',
      '#data' => [
        'menu' => $dashboard_menu,
        'user_name' => $full_name,
        'user_profile' => $profile_picture
      ],
      '#cache' => [
        'max-age' => 0
      ]
    ];
  }

  /**
   * @return mixed
   */
  public function dashboardMenu(){
    $menu_name = 'dashboard';
    //Set system menu mobile
    $menu_tree = \Drupal::menuTree();
    // Build the typical default set of menu tree parameters.
    $parameters = $menu_tree->getCurrentRouteMenuTreeParameters($menu_name);
    // Load the tree based on this set of parameters.
    $tree = $menu_tree->load($menu_name, $parameters);
    // Transform the tree using the manipulators you want.
    $manipulators = [
      // Only show links that are accessible for the current user.
      ['callable' => 'menu.default_tree_manipulators:checkAccess'],
      // Use the default sorting of menu links.
      ['callable' => 'menu.default_tree_manipulators:generateIndexAndSort'],
    ];
    $tree = $menu_tree->transform($tree, $manipulators);
    // Finally, build a renderable array from the transformed tree.
    $menu = $menu_tree->build($tree);
    if(!empty($theme_alter)){
      $menu['#theme'] = $theme_alter;
    }
    return \Drupal::service('renderer')->render($menu);
  }
}
