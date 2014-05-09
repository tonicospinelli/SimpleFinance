<?php

require __DIR__ . '/../bootstrap.php';

use Respect\Rest\Router;

$router                   = new Router;
$router->isAutoDispatched = false;

$movementRouter = $router->any(
    '/movements/*',
    'InFog\SimpleFinance\Controller\MovementController',
    array($config->pdo)
);

$indexRouter = $router->any('/', function () use ($movementRouter) {
    return $movementRouter;
});

$router->always(
    'Accept',
    array(
        'text/html'                         => new InFog\Routine\Twig($config->twig),
        'text/plain'                        => $json = new InFog\Routine\Json,
        'application/x-www-form-urlencoded' => $json,
        'application/json'                  => $json,
        'text/json'                         => $json
    )
);

print $router->run();
