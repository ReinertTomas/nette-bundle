<?php

declare(strict_types=1);

namespace App\Model\Router;

use Nette\Application\Routers\RouteList;

final class RouterFactory
{
    public function create(): RouteList
    {
        $router = new RouteList;
        $router->addRoute('<presenter>/<action>', 'Homepage:default');
        return $router;
    }
}
