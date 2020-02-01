<?php declare(strict_types=1);

include '../../vendor/autoload.php';

$request = Zend\Diactoros\ServerRequestFactory::fromGlobals(
	$_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
);

$responseFactory = new Zend\Diactoros\ResponseFactory;

$strategy = new League\Route\Strategy\JsonStrategy($responseFactory);
$router   = new League\Route\Router;
$router->setStrategy($strategy);

$router->group('/notes', function(\League\Route\RouteGroup $router) {
	$router->get('/',        'Notes\Module5\Server::getNotes');
	$router->get('/{id}',    'Notes\Module5\Server::getNote');
	$router->post('/',       'Notes\Module5\Server::createNote');
	$router->delete('/{id}', 'Notes\Module5\Server::deleteNote');
})->middleware(new Notes\Module5\AuthMiddleware());

$router->post('/account', 'Notes\Module5\Server::register');
$router->put('/account',  'Notes\Module5\Server::changePassword');
$router->get('/login',   'Notes\Module5\Server::login');
$response = $router->dispatch($request);

// send the response to the browser
(new Zend\HttpHandlerRunner\Emitter\SapiEmitter)->emit($response);