<?php

namespace Drupal\advanced_view\Plugin\views\access;

use Drupal\Core\Session\AccountInterface;
use Drupal\views\Plugin\views\access\AccessPluginBase;
use Symfony\Component\Routing\Route;

/**
 * Access plugin that checks access for the current user.
 *
 * @ingroup views_access_plugins
 *
 * @ViewsAccess(
 *   id = "advanced_view_access",
 *   title = @Translation("Advanced View Access"),
 *   help = @Translation("Checks if the current user may view advanced view aggregations.")
 * )
 */
class AdvancedViewAccess extends AccessPluginBase
{

  /**
   * {@inheritdoc}
   */
  public function summaryTitle()
  {
    return $this->t('Advanced View access');
  }

  /**
   * {@inheritdoc}
   */
  public function access(AccountInterface $account)
  {
    return $account->hasPermission('advanced_view.access');
  }

  /**
   * {@inheritdoc}
   */
  public function alterRouteDefinition(Route $route)
  {
    $route->setRequirement('_permission', 'advanced_view.access');
  }
}
