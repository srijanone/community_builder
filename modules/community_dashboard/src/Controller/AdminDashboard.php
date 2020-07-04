<?php


namespace Drupal\community_dashboard\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Database\Connection;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Session\AccountInterface;

/**
 * Defines AdminDashboard class.
 */
class AdminDashboard extends ControllerBase {

  /**
   * @var AccountInterface $account
   */
  protected $account;

  /**
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  public function __construct(AccountInterface $account, Connection $connection) {
    $this->account = $account;
    $this->connection = $connection;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('current_user'),
      $container->get('database')
    );
  }

  /**
   * Render AdminDashboard callback
   *
   * @return array
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function callback() {
    // Get current user data.
    $account = $this->account;
    // Check for dashboard permission.
    $access = $account->hasPermission('community admin dashboard');
    // Check access else redirect to home page.
    if (!$access) {
      $response = new RedirectResponse('/');
      $response->send();
      return;
    }
    // Get top user for dashboard.
    $top_users = views_embed_view('community_users', 'top_users');

    // Get top posts for dashboard.
    $top_posts = views_embed_view('community_posts', 'top_posts');

    return [
      '#theme' => 'admin_dashboard',
      '#data' => [
        'user_count' => $this->getUserCount(),
        'post_count' => $this->getPostsCount(),
        'comment_count' => $this->getCommentCount(),
        'like_count' => $this->getLikeCount(),
        'top_users' => $top_users,
        'top_posts' => $top_posts,
        '#cache' => [
          'max-age' => 0
        ]
      ],
      '#attached' => [
        'library' => ['community_dashboard/admin_dashboard'],
      ],
    ];
  }

  /**
   * Get user count.
   * @return mixed
   */
  public function getUserCount() {
    $query = $this->connection->select('users_field_data', 'u');
    $query->condition('u.status', 1);
    $query->addExpression('COUNT(u.uid)', 'count');
    return $query->execute()->fetchField();
  }

  /**
   * Get posts node count.
   * @return mixed
   */
  public function getPostsCount() {
    $query = $this->connection->select('node_field_data', 'n');
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
    $query = $this->connection->select('comment_field_data', 'c');
    $query->condition('c.status', 1);
    $query->condition('c.comment_type', 'post_comment');
    $query->addExpression('COUNT(c.cid)', 'count');
    return $query->execute()->fetchField();
  }

  /**
   * Get all like flag count.
   * @return mixed
   */
  public function getLikeCount() {
    $flag_service = \Drupal::service('flag.count');
    // Get like flag.
    $flag = \Drupal::service('flag')->getFlagById('like');
    return $flag_service->getFlagFlaggingCount($flag);
  }

}
