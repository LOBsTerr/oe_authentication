<?php

declare(strict_types = 1);

namespace Drupal\oe_authentication\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Listens to the dynamic route events.
 */
class RouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {
    // Replace the core login route.
    if ($route = $collection->get('user.login')) {
      $defaults = $route->getDefaults();
      unset($defaults['_form']);
      $defaults['_controller'] = '\Drupal\oe_authentication\Controller\AuthenticationController::login';
      $route->setDefaults($defaults);
    }

    // Replace the core logout route.
    if ($route = $collection->get('user.logout')) {
      $route->setDefault('_controller', '\Drupal\oe_authentication\Controller\AuthenticationController::logout');
    }
    // Remove these routes as to generate fatal errors wherever
    // functionality is missing.
    // @see user.routing.yml for original definition.
    $routes_to_remove = [
      'user.register',
      'user.admin_create',
      'user.multiple_cancel_confirm',
      'user.pass',
      'user.pass.http',
      'user.login.http',
      'user.logout.http',
      'user.cancel_confirm',
      'user.reset.login',
      'user.reset',
      'user.reset.form',
    ];
    foreach ($routes_to_remove as $route_to_remove) {
      if ($route = $collection->get($route_to_remove)) {
        $route->setRequirement('_access', 'FALSE');
      }
    }
  }

}
