<?php
require_once '../config.php'; //importaciones
use LogicaNegocio\LN_graficos;
use Entidades\Compra;
use Entidades\Contenedor;
use Entidades\DT_compra;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

Contenedor::set('logger', function () { //historial del proyecto
    $logger = new Logger(__CONFIG__['log']['channel']); //nombre
    $file_handler = new StreamHandler(__CONFIG__['log']['path'] . date('Ymd') . '.log'); //lugar
    $logger->pushHandler($file_handler); //crear fichero
    return $logger;
});

for ($month = 1; $month <= 12; $month++) {
    $ln_anio = new LN_graficos();
    $result = $ln_anio->suma_anios_compra((string) $month, '2021');
    var_dump($result);
    $total[] = $result[0]['total'];
}
die(json_encode($total));
//die(json_encode($result));
