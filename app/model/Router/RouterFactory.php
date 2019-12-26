<?php

declare(strict_types=1);

namespace App\Model\Router;

use Nette\Application\Routers\RouteList;

final class RouterFactory
{
    /**
     * @phpstan-return RouteList><Route>
     */
    public function create(): RouteList
    {
        $router = new RouteList;

        $this->buildAdmin($router);
        $this->buildFront($router);

        return $router;
    }

    /**
     * @phpstan-param RouteList<Route> $router
     * @phpstan-return RouteList<Route>
     */
    protected function buildAdmin(RouteList $router): RouteList
    {
        $list = new RouteList('Admin');
        $list->addRoute('admin/<presenter>/<action>[/<id>]', 'Home:default');

        return $router->add($list);
    }

    /**
     * @phpstan-param RouteList<Route> $router
     * @phpstan-return RouteList<Route>
     */
    protected function buildFront(RouteList $router): RouteList
    {
        $list = new RouteList('Front');
        $list->addRoute('<presenter>/<action>[/<id>]', 'Home:default');

        return $router->add($list);
    }
}
