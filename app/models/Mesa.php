<?php

include_once "./db/AccesoDatos.php";
class Mesa
{
    private $id;
    public $codigo;
    public $capacidad;
    public $estado;

    public  static function constructAux($codigo,$capacidad)
	{
        if ($capacidad>0 && strlen($codigo)==5 && is_numeric($codigo))
        {
            $instance= new self();
            $instance->codigo=$codigo;
            $instance->capacidad=$capacidad;
            $instance->estado="cerrada";
            return $instance;   
        }

        return null;

    }


    public function setEstado($estado)
    {
        $this->estado=$estado;
    }

    public function crearMesa()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO mesas (codigo, capacidad, estado, baja) VALUES (:codigo, :capacidad, :estado, :baja)");
        $consulta->bindValue(':codigo', $this->codigo, PDO::PARAM_INT);
        $consulta->bindValue(':capacidad', $this->capacidad, PDO::PARAM_INT);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':baja', null, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodas()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, codigo, estado, capacidad,baja FROM mesas");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
    }


    public static function obtenerMesa($usuario)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, codigo, estado, capacidad, baja FROM mesas WHERE codigo = :codigo");
        $consulta->bindValue(':codigo', $usuario, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Mesa');
    }

    public static function borrarMesa($usuario)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE mesas SET baja = :baja WHERE codigo = :codigo");
        $fecha = date("Y-m-d");
        $consulta->bindValue(':codigo', $usuario, PDO::PARAM_INT);
        $consulta->bindValue(':baja',$fecha);
        $consulta->execute();
    }

    public function modificarMesa()
    {
        $egreso=date("Y-m-d"); 

        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE mesas SET estado = :estado, capacidad= :capacidad, modificacion=:modificacion  WHERE codigo = :codigo");
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':codigo', $this->codigo, PDO::PARAM_INT);
        $consulta->bindValue(':capacidad', $this->capacidad, PDO::PARAM_INT);
        $consulta->bindValue(':modificacion',$egreso , PDO::PARAM_STR);
        $consulta->execute();
    }


    public function cambiarEstadoMesa()
    {
        $egreso=date("Y-m-d"); 

        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE mesas SET estado = :estado, modificacion=:modificacion  WHERE codigo = :codigo");
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':codigo', $this->codigo, PDO::PARAM_INT);
        $consulta->bindValue(':modificacion',$egreso , PDO::PARAM_STR);
        $consulta->execute();
    }
}



?>