<?php

class pedido
{
    private $id;
    public $estado;
    public $codigo;
    public $precioTotal;
    public $tiempoEstimadoTotal;
    public $idCliente;


    public static function constructAux($idCliente)
	{
        while(($aux=Pedido::generateRandomCode())==True)
        {
            $instance= new self();
            $instance->estado="sin pedir";
            $instance->precioTotal=0;
            $instance->tiempoEstimadoTotal=null;
            $instance->codigo=$aux;
            $instance->idCliente=$idCliente;
        }

        return $instance;
			
		

	}

    private static function generateRandomCode()
    {
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUV';
        return substr(str_shuffle($permitted_chars), 0, 5);
    }

    private static function corroborarCodes($codigo)
    {
        $lista=Pedido::obtenerTodos();
        for($i=0;$i<count($lista);$i++)
        {
            if($lista[i]->codigo==$codigo)
            {
                return True;
            }
        }
        return False;
    }


    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, codigo, estado, tiempoEstimadoTotal, precioTotal, baja, modificacion FROM pedidos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public function crearPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedidos (codigo, estado, tiempoEstimadoTotal, precioTotal, idCliente, baja, modificacion) VALUES (:codigo, :estado, :tiempoEstimadoTotal, :precioTotal, :idCliente, :baja, :modificacion)");
        $consulta->bindValue(':codigo', $this->codigo, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':tiempoEstimadoTotal', $this->tiempoEstimadoTotal, PDO::PARAM_STR);
        $consulta->bindValue(':precioTotal', $this->precioTotal, PDO::PARAM_STR);
        $consulta->bindValue(':idCliente', $this->idCliente, PDO::PARAM_INT);
        $consulta->bindValue(':baja', null, PDO::PARAM_STR);
        $consulta->bindValue(':modificacion', null, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }
}



?>