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
    public $clave;

 
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
            $instance->clave=$psw;
            return $instance;
			
		}

	}

    public function setID($id)
    {
        $this->id=$id;
    }

    ///lista uno listar todos Y EN CONTROLLER CREO UNO CON ESTE CONSTR Y HAGO EL ALTA EN BASE DE DATOS
    public function crearUsuario()
    {
        $ingreso=date("Y-m-d"); 
        $claveHash = password_hash($this->clave, PASSWORD_DEFAULT);
        //console.log($claveHash);
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO usuarios (usuario, apellido, tipo, sector, ingreso,clave,baja,modificacion) VALUES (:usuario, :apellido, :tipo, :sector, :ingreso,:clave,:baja,:modificacion)");
        $consulta->bindValue(':usuario', $this->usuario, PDO::PARAM_STR);
        $consulta->bindValue(':apellido', $this->apellido, PDO::PARAM_STR);
        $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        $consulta->bindValue(':sector', $this->sector, PDO::PARAM_STR);
        $consulta->bindValue(':ingreso', $ingreso, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $claveHash, PDO::PARAM_STR);
        $consulta->bindValue(':baja', null, PDO::PARAM_STR);
        $consulta->bindValue(':modificacion', null, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }


    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, usuario, apellido, tipo, sector, ingreso,baja, modificacion FROM usuarios");
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

    public static function obtenerUsuario($usuario)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, usuario, apellido, tipo, sector, ingreso,baja, modificacion FROM usuarios WHERE id = :id");
        $consulta->bindValue(':id', $usuario, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Usuario');
    }

    public function modificarUsuario()
    {
        $egreso=date("Y-m-d"); 
        $claveHash = password_hash($this->clave, PASSWORD_DEFAULT);
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE usuarios SET usuario = :usuario, clave = :clave, apellido= :apellido, tipo=:tipo, sector=:sector, modificacion=:modificacion  WHERE id = :id");
        $consulta->bindValue(':usuario', $this->usuario, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $claveHash, PDO::PARAM_STR);
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
        $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        $consulta->bindValue(':sector', $this->sector, PDO::PARAM_STR);
        $consulta->bindValue(':apellido', $this->id, PDO::PARAM_STR);
        $consulta->bindValue(':modificacion',$egreso , PDO::PARAM_STR);
        $consulta->execute();
    }

    public static function reactivarUsuario($id)
    {
        $egreso=date("Y-m-d");
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE usuarios SET baja = :baja,modificadon=:modificacion WHERE id = :id");
        $consulta->bindValue(':baja',null, PDO::PARAM_STR);
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->bindValue(':modificacion', $egreso, PDO::PARAM_STR);
        $consulta->execute();
        
    }


    public static function borrarUsuario($usuario)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE usuarios SET baja = :baja WHERE id = :id");
        $fecha = date("Y-m-d");
        $consulta->bindValue(':id', $usuario, PDO::PARAM_INT);
        $consulta->bindValue(':baja',$fecha);
        $consulta->execute();

        $consulta = $objAccesoDato->prepararConsulta("INSERT INTO suspendidos (id, fecha) VALUES (:id,:fecha)");
        $consulta->bindValue(':id', $usuario, PDO::PARAM_INT);
        $consulta->bindValue(':fecha',$fecha);
        $consulta->execute();
    }
}