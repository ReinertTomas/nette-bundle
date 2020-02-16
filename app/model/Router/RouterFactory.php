<?php
declare(strict_types=1);

namespace App\Model\Router;

use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;

final class RouterFactory
{

    /**
     * @return RouteList<Route>
     */
    public function create(): RouteList
    {
        $router = new RouteList;

        $this->buildAdmin($router);
        $this->buildFront($router);

        return $router;
    }

    /**
     * @param RouteList<Route> $router
     * @return RouteList<Route>
     */
    protected function buildAdmin(RouteList $router): RouteList
    {
        $list = new RouteList('Admin');
        $list->addRoute('admin/<presenter>/<action>[/<id>]', 'Home:default');

        return $router->add($list);
    }

    /**
     * @param RouteList<Route> $router
     * @return RouteList<Route>
     */
    protected function buildFront(RouteList $router): RouteList
    {
        $list = new RouteList('Front');
        $list->addRoute('<presenter>/<action>[/<id>]', 'Home:default');

        return $router->add($list);
    }

}
