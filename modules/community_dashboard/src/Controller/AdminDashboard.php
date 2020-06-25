<?php


namespace Drupal\community_dashboard\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Utility\LinkGeneratorInterface;
use Drupal\views\Views;

/**
 * Defines AdminDashboard class.
 */
class AdminDashboard extends ControllerBase {

  /**
   * Render AdminDashboard callback
   *
   * @return array
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function callback() {
    // Get top user for dashboard.
    $view = Views::getView('community_users');
    $view->setDisplay('top_users');
    $top_users = $view->render();

    // Get top posts for dashboard.
    $view = Views::getView('community_posts');
    $view->setDisplay('top_posts');
    $top_posts = $view->render();

    return [
      '#theme' => 'admin_dashboard',
      '#data' => [
        'menu' => $this->dashboardMenu(),
        'user_count' => $this->getUserCount(),
        'post_count' => $this->getPostsCount(),
        'comment_count' => $this->getCommentCount(),
        'top_users' => $top_users,
        'top_posts' => $top_posts,
        '#cache' => [
          'max-age' => 0
        ]
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

  /**
   * Get user count.
   * @return mixed
   */
  public function getUserCount() {
    $database = \Drupal::database();
    $query = $database->select('users_field_data', 'u');
    $query->condition('u.status', 1);
    $query->addExpression('COUNT(u.uid)', 'count');
    return $query->execute()->fetchField();
  }

  /**
   * Get posts node count.
   * @return mixed
   */
  public function getPostsCount() {
    $database = \Drupal::database();
    $query = $database->select('node_field_data', 'n');
    $query->condition('n.status', 1);
    $query->condition('n.type', 'posts');
    $query->addExpression('COUNT(n.nid)', 'count');
    return $query->execute()->fetchField();
  }

  /**
   * Get posts comment count.
   * @return mixed
   */
  public function getCommentCount() {
    $database = \Drupal::database();
    $query = $database->select('comment_field_data', 'c');
    $query->condition('c.status', 1);
    $query->condition('c.comment_type', 'post_comment');
    $query->addExpression('COUNT(c.cid)', 'count');
    return $query->execute()->fetchField();
  }

}
