<?php
include_once "Pedido.php";
include_once "Atencion.php";
include_once "Mesa.php";
include_once "Usuario.php";
include_once "./db/AccesoDatos.php";
class Cliente
{

    private $id;
    public $responsable;
    public $cantidad;
    public $codMesa;
    public $idMozo;

 
    public  static function constructAux($cantidad,$responsable,$mozo)
	{
		if($cantidad>0)
		{
            $lista=null;
            $aux=null;
            if (($lista=Mesa::obtenerMesasLibres())!=null&&Usuario::verificarMozo($mozo)==True)
            {
                for($i=0;i<count($lista);$i++)
                {
                    if($lista[i]->capacidad>=$cantidad&&lista[i]->estado=="cerrada")
                    {
                        $aux=$lista[i];
                        break;
                    }
                }

                if($aux!=null)
                {
                    $instance= new self();
                    $instance->responsable=$responsable;
                    $instance->cantidad=$cantidad;
                    $instance->idMozo=$mozo;
                    $instance->codMesa=$aux->codigo;

                    $aux->estado="recien ingresados";
                    $aux->cambiarEstadoMesa();
                    return $instance;                                    
                }

            }

			
		}

	}


    public function setID($id)
    {
        $this->id=$id;
    }



    ///lista uno listar todos Y EN CONTROLLER CREO UNO CON ESTE CONSTR Y HAGO EL ALTA EN BASE DE DATOS
    public function crearCliente()
    {

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO clientes (responsable, cantidad, codMesa, idMozo) VALUES (:responsable, :cantidad, :codMesa, :idMozo)");
        $consulta->bindValue(':responsable', $this->responsable, PDO::PARAM_STR);
        $consulta->bindValue(':codMesa', $this->codMeza, PDO::PARAM_INT);
        $consulta->bindValue(':cantidad', $this->cantidad, PDO::PARAM_INT);
        $consulta->bindValue(':idMozo', $this->idMozo, PDO::PARAM_INT);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, responsable, cantidad, codMesa, idMozo, baja, modificacion FROM clientes");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Cliente');
    }


    public static function obtenerCliente($clienteid)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, responsable, cantidad, codMesa, idMozo, modificacion FROM clientes WHERE id = :id");
        $consulta->bindValue(':id', $clienteid, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Cliente');
    }

    //actualizo modificacion
    public function modificarCliente()
    {
        $egreso=date("Y-m-d H:i:s"); 

        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE clientes SET idMozo=:idMozo,responsable=:responsable, cantidad=:cantidad, modificacion=:modificacion  WHERE id = :id");
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
        $consulta->bindValue(':responsable', $this->responsable, PDO::PARAM_STR);
        $consulta->bindValue(':cantidad', $this->cantidad, PDO::PARAM_INT);
        $consulta->bindValue(':idMozo', $this->idMozo, PDO::PARAM_INT);
        $consulta->bindValue(':modificacion',$egreso , PDO::PARAM_STR);
        $consulta->execute();
    }

    //cuando hago baja de cliente (xq no consume nada al final) hago baja de pedido y atencion
    public static function borrarCliente($usuario)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE clientes SET baja = :baja WHERE id = :id");
        $fecha = date("Y-m-d H:i:s");
        $consulta->bindValue(':id', $usuario, PDO::PARAM_INT);
        $consulta->bindValue(':baja',$fecha);
        $consulta->execute();
        //llamo a borrar pedido(ESTE BORRA PRODUCTOS) y borrar atencion(egreso)

    } 
}