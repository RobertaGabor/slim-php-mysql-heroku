<?php
include_once "Bajas.php";
abstract class Sector extends Bajas
{
    private static $enum = array( "bartender"=>"Barra Tragos", "cervecero"=>"Barra Chopera",
                            "cocinero"=>"Cocina/Candy bar",
                            "mozo"=>"Sin sector",
                            "socio"=>"Control general total",
                            );

    protected static function validarTipo($tipo)
    {
        foreach (Sector::$enum as $key => $value) {
            if($key == $tipo)
            {
                return true;
                break;
            }
        }
            return false;
    }
    

    protected static function getSector($tipo) {
        return Sector::$enum[$tipo];
    }
}



?>