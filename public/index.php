<?php

use League\Container\Container;
use App\Controllers\{
    NewsController,
    HomeController,
};

/** @var Container $container */
$container = require __DIR__ . '/../bootstrap/app.php';

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/', [HomeController::class, 'index']);
    $r->addRoute('GET', '/news/{id}', [NewsController::class, 'show']);
});

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        http_response_code(404);
        echo '404 Not Found';

        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);
        echo '405 Method Not Allowed';

        break;
    case FastRoute\Dispatcher::FOUND:
        $controllerName = $routeInfo[1][0];
        $action = $routeInfo[1][1];
        $params = $routeInfo[2];

        $controller = $container->get($controllerName);

        call_user_func_array(
            [$controller, $action],
            $params,
        );

        break;
}
