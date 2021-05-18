<?php
abstract class Sector
{
    private $enum = array( "bartender"=>"Barra Tragos", "cervecero"=>"Barra Chopera",
                            "cocinero"=>"Cocina/Candy bar",
                            "mozo"=>"Sin sector",
                            "socio"=>"Control general total");

    protected static function validarTipo($tipo)
    {
        foreach ($enum as $key => $value) {
            if($key == $tipo)
            {
                return true;
                break;
            }
        }
            return false;
    }
    

    protected static function getSector($tipo) {
        return $enum[$tipo];
    }
}



?>