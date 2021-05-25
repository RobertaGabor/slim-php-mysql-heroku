<?php
require_once './models/Producto.php';
require_once './interfaces/IApiUsable.php';

class ProductsController extends Producto implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
      $parametros = $request->getParsedBody();
      $nombre = $parametros['nombre'];
      $tipo = $parametros['precio'];
      $apellido = $parametros['cantidad'];


      $pdt=Producto::constructAux($precio,$nombre,$cantidad);
      if($pdt!=null)
      {
        $pdt->crearProducto();

        $payload = json_encode(array("mensaje" => "Producto creado con exito"));
      }
      else
      {
        $payload = json_encode(array("mensaje" => "Producto no se pudo crear"));
      }

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
      $lista = Producto::obtenerTodos();
      if($lista!=null)
      {
        $payload = json_encode(array("listaProductos" => $lista));
      }
      else{
        $payload = json_encode(array("listaProductos" => "No se encuentran productos registrados"));
      }
      

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }















    public function TraerUno($request, $response, $args)
    {
        // Buscamos usuario por nombre
        $usr = $args['usuario'];
        $usuario = Usuario::obtenerUsuario($usr);
        $payload = json_encode($usuario);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    
    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $nombre = $parametros['nombre'];
        Usuario::modificarUsuario($nombre);

        $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $usuarioId = $parametros['usuarioId'];
        Usuario::borrarUsuario($usuarioId);

        $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}
