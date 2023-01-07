<?php 

namespace Model;

use Model\ActiveRecord;

class Proyecto extends ActiveRecord{

protected static $tabla = 'proyectos';
protected static $columnasDB = ['id', 'proyecto', 'url', 'propietarioId'];

public function __construct($arg = [])
{
    $this->id = $arg['id'] ?? null;
    $this->proyecto = $arg['proyecto'] ?? '';
    $this->url = $arg['url'] ?? '';
    $this->propietarioId = $arg['propietarioId'] ?? '';
}

public function validarProyecto(){

    if(!$this->proyecto){
        self::$alertas['error'][]= 'El nombre del proyeecto es obligatorio';
    }

    return self::$alertas;
}


}

?>