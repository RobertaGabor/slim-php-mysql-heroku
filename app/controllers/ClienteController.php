<?php
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
      if($cliente!=null)
      {
        $idCliente=$usr->crearCliente();

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
      $lista = Usuario::obtenerTodos();
      if($lista!=null)
      {
        $payload = json_encode(array("listaUsuario" => $lista));
      }
      else{
        $payload = json_encode(array("listaUsuario" => "No se encuentran usuarios registrados"));
      }
      

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }


    
    public function TraerUno($request, $response, $args)
    {
        // Buscamos usuario por nombre
        $usr = $args['id']; //lo pone en el enlace directo poreso no es request es args
        $usuario = Usuario::obtenerUsuario($usr);
        $payload = json_encode($usuario);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerBajas($request, $response, $args)
    {
      $lista = Bajas::obtenerBajas();
      if($lista!=null)
      {

        $payload = json_encode(array("listaBajas" => $lista));
      }
      else{

        $payload = json_encode(array("listaBajas" => "No se encuentran usuarios dados de baja"));
      }

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }


    
    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $id = $parametros['id'];
        $usuario = $parametros['usuario'];
        $apellido = $parametros['apellido'];
        $tipo=$parametros['tipo'];
        $clave=$parametros['clave'];
        $us=Usuario::constructAux($usuario,$apellido,$tipo,$clave);
        $us->setID($id);        
        $us->modificarUsuario();

        $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }



    public function ReactivarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $id = $parametros['id'];

        Usuario::reactivarUsuario($id);        

        $payload = json_encode(array("mensaje" => "Usuario reactivado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    
    public function BorrarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $usuarioId = $parametros['id'];
        $razon = $parametros['razon'];
        Usuario::borrarUsuario($usuarioId,$razon);

        $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}
