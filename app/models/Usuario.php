<?php

include_once "Sectores.php";
include_once "./db/AccesoDatos.php";
class Usuario extends Sector
{

    private $id;
    public $usuario;
    public $apellido;
    public $tipo; //si ya hay 3 socios en la abse no se puede
    public $sector;
    public $psw;

 
    public  static function constructAux($usuario,$apellido,$tipo,$psw)
	{
		if(Sector::validarTipo($tipo))
		{
            if($tipo=="socio")
            {
                if(Usuario::validarSocios()==True)
                {
                    return null;
                }  
            }
            $instance= new self();
            $instance->apellido=$apellido;
            $instance->usuario=$usuario;
            $instance->tipo=$tipo;
            $instance->sector=Sector::getSector($tipo);
            $instance->psw=$psw;
            return $instance;
			
		}

	}

    ///lista uno listar todos Y EN CONTROLLER CREO UNO CON ESTE CONSTR Y HAGO EL ALTA EN BASE DE DATOS
    public function crearUsuario()
    {
        $ingreso=date("Y-m-d"); 
        $claveHash = password_hash($this->psw, PASSWORD_DEFAULT);
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO usuarios (usuario, apellido, tipo, sector, ingreso,clave) VALUES (:usuario, :apellido, :tipo, :sector, :ingreso,:clave)");
        $consulta->bindValue(':usuario', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':apellido', $this->apellido, PDO::PARAM_STR);
        $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        $consulta->bindValue(':sector', $this->sector, PDO::PARAM_STR);
        $consulta->bindValue(':ingreso', $ingreso, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $claveHash, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }


    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, usuario, apellido, tipo, sector, ingreso, clave FROM usuarios");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Usuario');
    }

    public static function validarSocios()
    {
        $count=0;
        $lista=obtenerTodos();

            foreach ($lista as $value) 
            {
                if ($value->tipo=="socio")
                {
                    $count+=1;
                    if($count==3)
                    {
                        return True;
                    }
                }
            }
            return False;
    
    }



















    ///MODIFICAR
    public static function obtenerUsuario($usuario)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre, apellido, tipo, sector, ingreso FROM usuarios WHERE id = :id");
        $consulta->bindValue(':usuario', $usuario, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Usuario');
    }
    ///MODIFICAR
    public static function modificarUsuario()
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE usuarios SET usuario = :usuario, clave = :clave WHERE id = :id");
        $consulta->bindValue(':usuario', $this->usuario, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $this->clave, PDO::PARAM_STR);
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
        $consulta->execute();
    }
///MODIFICAR
    public static function borrarUsuario($usuario)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE usuarios SET fechaBaja = :fechaBaja WHERE id = :id");
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':id', $usuario, PDO::PARAM_INT);
        $consulta->bindValue(':fechaBaja', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->execute();
    }
}