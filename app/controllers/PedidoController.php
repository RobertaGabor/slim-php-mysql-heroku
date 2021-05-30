<?php
include_once "  Pedido.php";
require_once './models/Producto.php';
require_once './interfaces/IApiUsable.php';

class PedidoController extends Pedido implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    { 
      
      $pdt=null;
      $parametros = $request->getParsedBody();
      $nombre = $parametros['nombre'];
      $tipo = $parametros['precio'];
      $apellido = $parametros['cantidad'];
      //SACO ID PEDIDO PBUSCANDO EL ID DE PEDIDO DEL ARGUMENTOS DEL LINK

      $clienteId=args['idCliente'];
      $cliente=Cliente::TraerUno($clienteId);
      
      if($cliente!=null)
      {
        //CA TENGO Q IR A LA ATENCION para sacar en ese idcliente el idpedido
        $atencion=Atencion::traerAtencionPorCliente($clienteId);
        $idPedido=$atencion->$idPedido;
        $codPedido=(Pedido::traerPedido($idPedido))->codigo;
        //llamo a crear producto 
        $pdt=Producto::constructAux($precio,$nombre,$cantidad,$codPedido);
        //modificar precio totald e pedido , leer todos los productos con este id y calcular precio*cant 
        $pedido=Pedido::traerPedido($idPedido);   
        $pedido->precioTotal=$pedido->calcularTotal();     
      }


      if($pdt!=null)
      {
        $pdt->crearProducto();

        $payload = json_encode(array("mensaje" => "Producto creado con exito en el pedido: "+$idPedido));
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
      $lista = Pedido::obtenerTodos();
      if($lista!=null)
      {
        $payload = json_encode(array("listaProductos" => $lista));
      }
      else{
        $payload = json_encode(array("listaProductos" => "No se encuentran pedidos registrados"));
      }
      

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }



    public function TraerUno($request, $response, $args)
    {
        // Buscamos usuario por nombre
        $usr = $args['nroPedido'];
        $usuario = Pedido::traerPedidoPorCodigo($usr);
        $payload = json_encode($usuario);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    
    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $codigo = $parametros['nroPedido'];
        $estado=$parametros['estado'];
        //traer ese pedido cambiar estado y modificar bd
        $pedido=Pedido::traerPedidoPorCodigo($codigo);
        $pedido->estado=$estado;
        $pedido->modificarPedido();

        $payload = json_encode(array("mensaje" => "Pedido modificado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $usuarioId = $parametros['nroPedido'];
        

        $payload = json_encode(array("mensaje" => "Pedido borrado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}
