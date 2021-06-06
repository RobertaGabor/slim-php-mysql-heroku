<?php
require_once './models/Producto.php';
require_once './interfaces/IApiUsable.php';

class ProductsController extends Producto implements IApiUsable
{
 
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
        $usr = $args['idProducto'];
        $producto = Producto::obtenerProducto($usr);
        $payload = json_encode($producto);

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

        $id = $parametros['idProducto'];
        Producto::borrarProducto($id);
        //eliminar el total en el pedido. el precio x cant del pedido y modifico el tiempo estimado al proximo mas alto

        $payload = json_encode(array("mensaje" => "Producto borrado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}
