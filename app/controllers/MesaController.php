<?php
require_once './models/Mesa.php';
require_once './interfaces/IApiUsable.php';

class MesaController extends Mesa implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
      $parametros = $request->getParsedBody();
      $codigo = $parametros['codigo'];
      $estado = $parametros['estado'];
      $capacidad = $parametros['capacidad'];

      $tble=Mesa::constructAux($codigo,$capacidad);
      if($tble!=null)
      {
        $tble->crearMesa();

        $payload = json_encode(array("mensaje" => "Mesa creada con exito"));
      }
      else
      {
        $payload = json_encode(array("mensaje" => "Mesa no se pudo crear"));
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
        Usuario::borrarUsuario($usuarioId);

        $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}
