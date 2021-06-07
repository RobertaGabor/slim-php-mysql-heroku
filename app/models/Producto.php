<?php
    include_once "./db/AccesoDatos.php";
    include_once "Pedido.php";

    class Producto
    {
        private $id;
        public $nombre;
        public $precio;
        public $cantidad;
        public $sectorDePreparacion;
        public $idChef;
        public $tiempoEstimadoDePreparacion;
        public $codPedido;




        public  static function constructAux($precio,$nombre,$cantidad,$codPedido)
        {

            if($cantidad>0&&$precio>0&&Pedido::corroborarCodes($codPedido))
            {
                $instance= new self();
                $instance->precio=$precio;
                $instance->nombre=$nombre;
                $instance->cantidad=$cantidad;
                $instance->codPedido=$codPedido;
                $instance->sectorDePreparacion=null;
                $instance->idChef=null;
                $instance->tiempoEstimadoDePreparacion=null;
                return $instance;
                
            }
    
        }
    
        public function crearProducto()
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO productos (nombre, precio, cantidad, codPedido) VALUES (:nombre, :precio, :cantidad; :codPedido)");
            $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
            $consulta->bindValue(':precio', $this->precio, PDO::PARAM_STR);
            $consulta->bindValue(':cantidad', $this->cantidad, PDO::PARAM_STR);
            $consulta->bindValue(':codPedido', $this->codPedido, PDO::PARAM_STR);
            $consulta->execute();
    
            return $objAccesoDatos->obtenerUltimoId();
        }
        public static function obtenerProducto($idP)
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre, precio, cantidad, sectorDePreparacion, idChef, tiempoEstimadoDePreparacion, codPedido, baja, modificacion FROM productos WHERE id = :id");
            $consulta->bindValue(':id', $idP, PDO::PARAM_INT);
            $consulta->execute();
    
            return $consulta->fetchObject('Producto');
        }
    
        public static function obtenerTodos()
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT  id, nombre, precio, cantidad, sectorDePreparacion, idChef, tiempoEstimadoDePreparacion, codPedido, baja, modificacion FROM productos");
            $consulta->execute();
    
            return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
        }


        //hacer 2 bajas una por pedido, donde la voy a llamar del controlador de pedido cuando este de de baja
        //otra por id, este va a modificar campos de pedido

        public static function borrarProductoPorPedido($ped)
        {
            $objAccesoDato = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDato->prepararConsulta("UPDATE productos SET baja = :baja WHERE codPedido = :codPedido");
            $fecha = date("Y-m-d H:i:s");
            $consulta->bindValue(':codPedido', $ped, PDO::PARAM_INT);
            $consulta->bindValue(':baja',$fecha);
            $consulta->execute();
        }

        public static function borrarProducto($id)
        {
            $objAccesoDato = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDato->prepararConsulta("UPDATE productos SET baja = :baja WHERE id = :id");
            $fecha = date("Y-m-d H:i:s");
            $consulta->bindValue(':id', $id, PDO::PARAM_INT);
            $consulta->bindValue(':baja',$fecha);
            $consulta->execute();
    
        }


    }

?>