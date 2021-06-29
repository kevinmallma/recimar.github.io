<?php

declare (strict_types=1); //validar los parametros de entrada

namespace Entidades; //orden

class Contenedor {

    private static $dependencia = array(); //crear array

    public static function set(string $key, $func) {//dependencias
        self::$dependencia[$key] = $func;
    }

    public static function get(string $key) {
        return self::$dependencia[$key]();
    }

}
