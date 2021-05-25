<?php
class Bajas
{
    public $idEmpleado;
    public $fecha;
    public $razon;


    public static function obtenerBajas()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT idEmpleado, fecha, razon FROM suspendidos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Bajas');
    }
}

?>