<?php
require_once './models/Mesa.php';
require_once './interfaces/IApiUsable.php';

class MesaController extends Mesa implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
      $parametros = $request->getParsedBody();
      $codigo = $parametros['codigo'];
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
      $lista = Mesa::obtenerTodas();
      if($lista!=null)
      {
        $payload = json_encode(array("listaMesas" => $lista));
      }
      else{
        $payload = json_encode(array("listaMesas" => "No se encuentran mesas registradas"));
      }
      

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {

        $tble = $args['codigo']; 
        $mesa = Mesa::obtenerMesa($tble);
        $payload = json_encode($mesa);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    
    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $codigo = $parametros['codigo'];
        $estado = $parametros['estado'];
        $capacidad = $parametros['capacidad'];

        $ms=Mesa::constructAux($codigo,$capacidad);
        $ms->setEstado($estado);        
        $us->modificarMesa();

        $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }


    public function BorrarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $mesaId = $parametros['id'];
        Mesa::borrarMesa($mesaId);

        $payload = json_encode(array("mensaje" => "Mesa borrada con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}
