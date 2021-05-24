<?php
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';

require_once './db/AccesoDatos.php';

require_once './controllers/UsuarioController.php';


$app = AppFactory::create();

// $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
// $dotenv->safeLoad();

$app->addErrorMiddleware(true, true, true);


// Routes
$app->group('/usuarios', function (RouteCollectorProxy $group) {
    $group->get('[/]', \UsuarioController::class . ':TraerTodos'); //AQUI
    
    $group->get('/{id}', \UsuarioController::class . ':TraerUno'); //AQUI
    
    $group->post('[/]', \UsuarioController::class . ':CargarUno'); //AQUI
   
    $group->post('/borrar', \UsuarioController::class . ':BorrarUno'); //AQUI
    
    $group->post('/modificar', \UsuarioController::class . ':ModificarUno');//AQUI

    $group->post('/reactivar', \UsuarioController::class . ':ReactivarUno');//AQUI

  });

  $app->group('/productos', function (RouteCollectorProxy $group) {
    $group->get('[/]', \ProductsController::class . ':TraerTodos'); //AQUI
        
    $group->post('[/]', \ProductsController::class . ':CargarUno'); //AQUI
  });

$app->get('[/]', function (Request $request, Response $response) {    
    $response->getBody()->write("Slim Framework 4 PHP");
    return $response;

});

$app->run();
