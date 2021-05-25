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
    $group->get('[/]', \MesaController::class . ':TraerTodos'); //AQUI
    
    $group->get('/{codigo}', \MesaController::class . ':TraerUno'); //AQUI
    
    $group->post('[/]', \MesaController::class . ':CargarUno'); //AQUI
   
    $group->post('/borrar', \MesaController::class . ':BorrarUno'); //AQUI
    
    $group->post('/modificar', \MesaController::class . ':ModificarUno');//AQUI
  });

  //clientes
  $app->group('/clientes', function (RouteCollectorProxy $group) {
    $group->get('[/]', \ClienteController::class . ':TraerTodos'); //AQUI
    
    $group->get('/{id}', \ClienteController::class . ':TraerUno'); //AQUI
    
    $group->post('[/]', \ClienteController::class . ':CargarUno'); //AQUI CUANDO CARGA BUSCA CODIGO DE MESA LIBRE Y GENERO UNA ATENCION EN LA BASE, DEVUELVE ID CLIENTE CON ESO GENERO PEDIDO
   
    $group->post('/borrar', \ClienteController::class . ':BorrarUno'); //AQUI OMDIFICO EGRESO EN ATENCION
    
    $group->post('/modificar', \ClienteController::class . ':ModificarUno');//AQUI

  }); 

  //pedidos
  $app->group('/pedidos', function (RouteCollectorProxy $group) {
      $group->post('/{idCliente}', \ProductoController::class . ':CargarUno'); //AQUI ARMA PEDIDO y conc ada cargar genera nuevo producto EN LA ABSE
      
      $group->post('/borrar', \MesaController::class . ':BorrarUno'); //AQUI SI BORRO ELIMINO LOS PRODUCTOS
    
      $group->post('/modificar', \MesaController::class . ':ModificarUno');//AQUI depende que modifico productos
  
      $group->get('[/]', \MesaController::class . ':TraerTodos'); //AQUI
    
      $group->get('/{nroPedido}', \MesaController::class . ':TraerUno'); //AQUI
  });


  //productos
  $app->group('/productos', function (RouteCollectorProxy $group) {
    $group->get('[/]', \ProductoController::class . ':TraerTodos'); //AQUI
    
    $group->get('/{codigo}', \ProductoController::class . ':TraerUno'); //AQUI
       
    $group->post('/borrar', \ProductoController::class . ':BorrarUno'); //AQUI SI BORRO MODIFICO PEDIDO
    
    $group->post('/modificar', \ProductoController::class . ':ModificarUno');//AQUI SI MODIFICO UN PRODUCTO QUE ME MODIFIQUE PEDIDO
  });





$app->get('[/]', function (Request $request, Response $response) {    
    $response->getBody()->write("LA COMANDA SUPERSTAR");
    return $response;

});

$app->run();
