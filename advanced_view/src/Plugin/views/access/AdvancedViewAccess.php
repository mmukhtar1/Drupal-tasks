<?php

namespace Drupal\advanced_view\Plugin\views\access;

use Drupal\Core\Session\AccountInterface;
use Drupal\views\Plugin\views\access\AccessPluginBase;
use Symfony\Component\Routing\Route;

/**
 * Access plugin that checks access to the view.
 *
 *  Only users with specified permission will be allowed to access the view.
 *
 * @ingroup views_access_plugins
 *
 * @ViewsAccess(
 *   id = "advanced_view_access",
 *   title = @Translation("Advanced View Access"),
 *   help = @Translation("Checks if the current user may view advanced view aggregations.")
 * )
 */
class AdvancedViewAccess extends AccessPluginBase {

  /**
   * Title of the access plugin.
   */
  public function summaryTitle() {
    return $this->t('Advanced View access');
  }

  /**
   * Access check.
   */
  public function access(AccountInterface $account) {
    return $account->hasPermission('advanced_view.access');
  }

  /**
   * Set the permission required to access the view.
   */
  public function alterRouteDefinition(Route $route) {
    $route->setRequirement('_permission', 'advanced_view.access');
  }

}
