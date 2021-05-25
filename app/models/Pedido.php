<?php

class pedido
{
    private $id;
    public $estado;
    public $codigo;
    public $precioTotal;
    public $tiempoEstimadoTotal;


    public static function constructAux()
	{
        while(($aux=Pedido::generateRandomCode())==True)
        {
            $instance= new self();
            $instance->estado="sin pedir";
            $instance->precioTotal=0;
            $instance->tiempoEstimadoTotal=null;
            $instance->codigo=$aux;
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
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, codigo, estado, tiempoEstimadoTotal, precioTotal FROM pedidos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public function crearPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedidos (usuario, apellido, tipo, sector, ingreso,clave,baja,modificacion) VALUES (:usuario, :apellido, :tipo, :sector, :ingreso,:clave,:baja,:modificacion)");
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
}



?>