<?php
require_once '../config.php'; //importaciones
use LogicaNegocio\LN_graficos;
use Entidades\Contenedor;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

Contenedor::set('logger', function () { //historial del proyecto
    $logger = new Logger(__CONFIG__['log']['channel']); //nombre
    $file_handler = new StreamHandler(__CONFIG__['log']['path'] . date('Ymd') . '.log'); //lugar
    $logger->pushHandler($file_handler); //crear fichero
    return $logger;
});

if (isset($_POST['modelo'])) {
    if ($_POST['modelo'] == 'compra') { //grafico compra
        $total = array();
        for ($month = 1; $month <= 12; $month++) {
            $ln_anio = new LN_graficos();
            $result = $ln_anio->suma_anios_compra((string) $month, $_POST['anio']);
            $total[] = $result[0]['total'];
        }
        die(json_encode($total));
    }
    if ($_POST['modelo'] == 'material') {
        $total = array();
        for ($month = 1; $month <= 12; $month++) {
            $ln_anio = new LN_graficos();
            $result = $ln_anio->suma_anios_material((int) $_POST['material'], (string) $month, $_POST['anio']);
            $total[] = $result[0]['peso'];
        }
        die(json_encode($total));
    }
} else {
    header('location: 404.html');
    die();
}
