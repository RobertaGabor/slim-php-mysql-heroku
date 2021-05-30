<?php
include_once "Producto.php";
class pedido
{
    private $id;
    public $estado; //este modifica el mozo CON MODIFICAER
    public $codigo;
    public $precioTotal; //estos dos se modifrican soloo cuando modifico el producto
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

    public function getID()
    {
        return $this->id;
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

    public static function traerPedidoPorCliente($idCliente)
    {
        $lista=Pedido::obtenerTodos();
        for($i=0;$i<count($lista);$i++)
        {
            if($lista[i]->idCliente==$idCliente)
            {
                return $lista[i];
            }
        }
        return null;
    }

    
    public static function traerPedido($idPedido)
    {
        $lista=Pedido::obtenerTodos();
        for($i=0;$i<count($lista);$i++)
        {
            if($lista[i]->id==$idPedido)
            {
                return $lista[i];
            }
        }
        return null;
    }

    public static function traerPedidoPorCodigo($codPedido)
    {
        $lista=Pedido::obtenerTodos();
        for($i=0;$i<count($lista);$i++)
        {
            if($lista[i]->codigo==$codPedido)
            {
                return $lista[i];
            }
        }
        return null;
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

    public static function borrarPedidoPorCliente($idCliente)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos SET baja = :baja WHERE idCliente = :id");
        $fecha = date("Y-m-d H:i:s");
        $consulta->bindValue(':id', $idCliente, PDO::PARAM_INT);
        $consulta->bindValue(':baja',$fecha);
        $consulta->execute();
        //llamo a borrar pedido(ESTE BORRA PRODUCTOS) 
        if(($ux=Pedido::traerPedidoPorCliente($idCliente))!=null) //esto se podria hacer afuera PRIMERO
        {
            $idPedido=$ux->codigo;
            Producto::borrarProductoPorPedido($idPedido);
        }
        
    }

    public static function borrarPedido($idPedido)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos SET baja = :baja WHERE codigo = :id");
        $fecha = date("Y-m-d H:i:s");
        $consulta->bindValue(':id', $idPedido, PDO::PARAM_INT);
        $consulta->bindValue(':baja',$fecha);
        $consulta->execute();
        //llamo a borrar pedido(ESTE BORRA PRODUCTOS) 
        if(($ux=Pedido::traerPedido($idPedido))!=null) //esto se podria hacer afuera PRIMERO
        {
            Producto::borrarProductoPorPedido($idPedido);
        }
        
    }


    public function calcularTotal()
    {
        $lista=Producto::obtenerTodos();
        $total=0;
        for($i=0;$i<count($lista);$i++)
        {
            if($lista[i]->codPedido==$this.id)
            {
                $total+=$lista[i]->precio*$lista[i]->cantidad;
            }
        }

        return $total;
    }


    //QUE MODIF SOLOC AMBIE MODIFICACION Y LO USE CADA VEZ QUE SE CAMBIA ALGO $fecha = date("Y-m-d H:i:s");
    public function modificarPedido()
    {
        $egreso=date("Y-m-d H:i:s"); 

        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos SET estado=:estado, modificacion=:modificacion WHERE codigo = :codigo");
        $consulta->bindValue(':codigo', $this->codigo, PDO::PARAM_INT);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':modificacion',$egreso , PDO::PARAM_STR);
        $consulta->execute();
    }

}



?>