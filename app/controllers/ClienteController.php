<?php
include_once "./models/Cliente.php";
require_once './interfaces/IApiUsable.php';

class ClienteController extends Cliente implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
      $parametros = $request->getParsedBody();
      $nombre = $parametros['responsable'];
      $cantidad = $parametros['cantidad'];
      $idMozo = $parametros['idMozo'];

      $cliente=Cliente::constructAux($cantidad,$nombre,$idMozo);
      var_dump($cliente);
      if($cliente!=null)
      {
        $idCliente=$cliente->crearCliente();
        //genero pedido al usar CrearPedido devuelve id y lo  pongo en el atributo
        $pedido=Pedido::constructAux($idcliente);
        if($pedido!=null)
        {
          //le seteo el idPedido
          $idPedido=$pedido->crearPedido();
          //genero atencion
          //pido mesa
          $mesa=Mesa::obtenerMesa($cliente->codMesa);
          $idMesa=$mesa->getID();
          $att=Atencion::constructAux($idcliente,$idMesa,$idPedido);
          Atencion::crearAtencion($att);

          //despues cuando agrego productos al pedido que devuelva el id del pedido

        }
        

        $payload = json_encode(array("mensaje" => "Cliente creado con exito ID DE CLIENTE PARA GENERAR PEDIDO: "+$idCliente));
      }
      else
      {
        $payload = json_encode(array("mensaje" => "Cliente no se pudo crear"));
      }

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }



    public function TraerTodos($request, $response, $args)
    {
      $lista = Cliente::obtenerTodos();
      if($lista!=null)
      {
        $payload = json_encode(array("listaClientes" => $lista));
      }
      else{
        $payload = json_encode(array("listaUsuario" => "No se encuentran clietnes registrados"));
      }
      

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }


    
    public function TraerUno($request, $response, $args)
    {
        // Buscamos usuario por nombre
        $usr = $args['id']; //lo pone en el enlace directo poreso no es request es args
        $usuario = Cliente::obtenerUsuario($usr);
        $payload = json_encode($usuario);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

   
    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $id = $parametros['id'];
        $mozo = $parametros['idMozo'];
        $respons = $parametros['responsable'];
        $cant=$parametros['cantidad'];

        $us=Cliente::constructAux($cant,$respons,$mozo);
        $us->setID($id);        
        $us->modificarCliente();

        $payload = json_encode(array("mensaje" => "Cliente modificado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    //borro pedido y atencion y libero mesa
    public function BorrarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $usuarioId = $parametros['id'];     
        Cliente::borrarCliente($usuarioId);
        Pedido::borrarPedidoPorCliente($usuarioId);
        Atencion::borrarAtencion($usuarioId);

        //liberar mesa hacer funcion que llame a modificar pero tome todos los datos y cambie solo estado a lista para cerrar
        //traer ese cliente, buscar su mesa, y a ese codigo cambiarle el estado

        $aux=Cliente::obtenerCliente($usuarioId);
        $aux->estado="lista para cerrar"; //la cierra un socio
        $aux->ModificarMesa();

        $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}
