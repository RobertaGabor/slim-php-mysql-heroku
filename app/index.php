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
require_once './controllers/MesaController.php';


$app = AppFactory::create();

// $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
// $dotenv->safeLoad();

$app->addErrorMiddleware(true, true, true);


// Routes
//usuarios
$app->group('/usuarios', function (RouteCollectorProxy $group) {
    $group->get('[/]', \UsuarioController::class . ':TraerTodos'); //AQUI
    
    $group->get('/{id}', \UsuarioController::class . ':TraerUno'); //AQUI
    
    $group->post('[/]', \UsuarioController::class . ':CargarUno'); //AQUI
   
    $group->post('/borrar', \UsuarioController::class . ':BorrarUno'); //AQUI
    
    $group->post('/modificar', \UsuarioController::class . ':ModificarUno');//AQUI

    $group->post('/reactivar', \UsuarioController::class . ':ReactivarUno');//AQUI
    
    $group->post('/bajas', \UsuarioController::class . ':TraerBajas');//AQUI

  });
  //mesas
  $app->group('/mesas', function (RouteCollectorProxy $group) {
    $group->get('[/]', \MesaController::class . ':TraerTodas'); //AQUI
    
    $group->get('/{id}', \MesaController::class . ':TraerUna'); //AQUI
    
    $group->post('[/]', \MesaController::class . ':CargarUna'); //AQUI
   
    $group->post('/borrar', \MesaController::class . ':BorrarUna'); //AQUI
    
    $group->post('/modificar', \MesaController::class . ':ModificarUna');//AQUI
  });







  $app->group('/productos', function (RouteCollectorProxy $group) {
    $group->get('[/]', \ProductsController::class . ':TraerTodos'); //AQUI
        
    $group->post('[/]', \ProductsController::class . ':CargarUno'); //AQUI
  });

$app->get('[/]', function (Request $request, Response $response) {    
    $response->getBody()->write("LA COMANDA SUPERSTAR");
    return $response;

});

$app->run();
