<?php

class Atencion
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
        $fecha = date("Y-m-d H:i:s");
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO atencion (idCliente, idMesa, idPedido, entrada) VALUES (:idCliente, :idMesa, :idPedido,:entrada)");
        $consulta->bindValue(':idCliente', $this->idCliente, PDO::PARAM_INT);
        $consulta->bindValue(':idPedido', $this->idPedido, PDO::PARAM_INT);
        $consulta->bindValue(':idMesa', $this->idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':entrada',$fecha, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    //BORRAR ->EGRESO CUANDO: doy de baja el cliente o cuando ya se termino de comer y pagar

    public static function borrarAtencion($idCliente)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE atencion SET egreso = :egreso WHERE idCliente = :idCliente");
        $fecha = date("Y-m-d H:i:s");
        $consulta->bindValue(':idCliente', $idCliente, PDO::PARAM_INT);
        $consulta->bindValue(':egreso',$fecha);
        $consulta->execute();
        //llamo a borrar pedido(ESTE BORRA PRODUCTOS) y borrar atencion(egreso)

    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT  id, idCliente, idMesa, idPedido, entrada, salida FROM atencion");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Atencion');
    }

    //devovler atencion con id de clietne
    public static function traerAtencionPorCliente($idCliente)
    {
        $lista=Atencion::obtenerTodos();
        for($i=0;$i<count($lista);$i++)
        {
            if($lista[i]->idCliente==$idCliente)
            {
                return $lista[i];
                
            }

        }

        return null;
    }
}

 

?>