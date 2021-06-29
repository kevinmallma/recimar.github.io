<?php
namespace Dao; //ordenamiento
use PDO;
class Dao_conexion {

    private static $db;

    //obtener conexion
    public static function get() {   //evitar multiples conexiones
        if (!self::$db) {//mientras que sea false
            $pdo = new Pdo(__CONFIG__['db']['host'], __CONFIG__['db']['user'], __CONFIG__['db']['password']); //conexion
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //habilitar los errores
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ); //obtener los datos en objetos

            self::$db = $pdo;
        }
        return self::$db;
    }

    //cerrar conexion
    public static function desconect() {
        return self::$db = null;
    }

}
