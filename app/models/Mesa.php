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
        if ($capacidad>0 && len($codigo)==5 && is_numeric($codigo))
        {
            $instance= new self();
            $instance->codigo=$codigo;
            $instance->capacidad=$capacidad;
            $instance->estado="cerrada";
            return $instance;   
        }

        return null;

    }


    public function crearMesa()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO mesas (codigo, capacidad, estado) VALUES (:codigo, :capacidad, :estado)");
        $consulta->bindValue(':codigo', $this->codigo, PDO::PARAM_INT);
        $consulta->bindValue(':capacidad', $this->capacidad, PDO::PARAM_INT);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

}



?>