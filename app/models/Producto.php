<?php
    include_once "./db/AccesoDatos.php";

    class Producto
    {
        private $id;
        public $nombre;
        public $precio;
        public $cantidad;


        public  static function constructAux($precio,$nombre,$cantidad)
        {
            if($cantidad>0&&$precio>0)
            {
                $instance= new self();
                $instance->precio=$precio;
                $instance->nombre=$nombre;
                $instance->cantidad=$cantidad;
                return $instance;
                
            }
    
        }
    
        public function crearProducto()
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO productos (nombre, precio, cantidad) VALUES (:nombre, :precio, :cantidad)");
            $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
            $consulta->bindValue(':precio', $this->precio, PDO::PARAM_STR);
            $consulta->bindValue(':cantidad', $this->cantidad, PDO::PARAM_STR);
            $consulta->execute();
    
            return $objAccesoDatos->obtenerUltimoId();
        }
    
    
        public static function obtenerTodos()
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre, precio, cantidad FROM productos");
            $consulta->execute();
    
            return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
        }



    }

?>