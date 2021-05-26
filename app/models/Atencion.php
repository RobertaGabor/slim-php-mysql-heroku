<?php

class Atenciones
{
    //para poder traerlas y mostrarlas
    private $id;
    public $idCliente;
    public $idMesa;
    public $idPedido;	

    public static function constructAux($idCliente,$idMesa,$idPedido)
	{
        if($idCliente!=null&&$idPedido!=null&&$idMesa!=null)
        {
            $instance= new self();
            $instance->idCliente=$idCliente;
            $instance->idMesa=$idMesa;
            $instance->idPedido=$idPedido;
        }

        return $instance;

	}

    public function crearAtencion()
    {

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO clientes (idCliente, idMesa, idPedido) VALUES (:idCliente, :idMesa, :idPedido)");
        $consulta->bindValue(':idCliente', $this->idCliente, PDO::PARAM_INT);
        $consulta->bindValue(':idPedido', $this->idPedido, PDO::PARAM_INT);
        $consulta->bindValue(':idMesa', $this->idMesa, PDO::PARAM_INT);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    //BORRAR ->EGRESO CUANDO: doy de baja el cliente o cuando ya se termino de comer y pagar

    public static function borrarAtencion($idCliente)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE atencion SET egreso = :egreso WHERE idCliente = :idCliente");
        $fecha = date("Y-m-d H:i:s");
        $consulta->bindValue(':id', $usuario, PDO::PARAM_INT);
        $consulta->bindValue(':egreso',$fecha);
        $consulta->execute();
        //llamo a borrar pedido(ESTE BORRA PRODUCTOS) y borrar atencion(egreso)

    }
}



?>