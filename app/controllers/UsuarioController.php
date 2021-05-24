<?php
require_once './models/Usuario.php';
require_once './interfaces/IApiUsable.php';

class UsuarioController extends Usuario implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
      $parametros = $request->getParsedBody();
      $nombre = $parametros['usuario'];
      $tipo = $parametros['tipo'];
      $apellido = $parametros['apellido'];
      $clave = $parametros['clave'];

      // Creamos el usuario
      // $usr = new Usuario();
      // $usr->usuario = $usuario;
      // $usr->clave = $clave;
      $usr=Usuario::constructAux($nombre,$apellido,$tipo,$clave);
      if($usr!=null)
      {
        $usr->crearUsuario();

        $payload = json_encode(array("mensaje" => "Usuario creado con exito"));
      }
      else
      {
        $payload = json_encode(array("mensaje" => "Usuario no se pudo crear"));
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
      $lista = Usuario::obtenerBajas();
      if($lista!=null)
      {
        console.log("Entro");///////
        $payload = json_encode(array("listaBajas" => $lista));
      }
      else{
        console.log("No entro");///////
        $payload = json_encode(array("listaBajas" => "No se encuentran usuarios dados de baja"));
      }
      console.log("No se sabe");///////////
      

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
