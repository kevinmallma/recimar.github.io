<?php
include_once 'templates/header.php';
include_once 'templates/navegacion.php';
include_once 'templates/carga.php';
require_once '../config.php'; //importaciones
use LogicaNegocio\LN_empleado;
use LogicaNegocio\LN_cliente;
use Entidades\Contenedor;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

Contenedor::set('logger', function () { //historial del proyecto
    $logger = new Logger(__CONFIG__['log']['channel']); //nombre
    $file_handler = new StreamHandler(__CONFIG__['log']['path'] . date('Ymd') . '.log'); //lugar
    $logger->pushHandler($file_handler); //crear fichero
    return $logger;
});
?>
<h4 class="my-3"><i class="fas fa-home"></i> <?= $pagina ?></h4>
<div class="p-2 contenido">
    <!--graficos-->
    <div class="row">
        <div class="col-xl-6">
            <figure class="highcharts-figure">
                <div id="container_barra"></div>
                <p class="highcharts-description"></p>
            </figure>
        </div>
        <div class="col-xl-6">
            <figure class="highcharts-figure">
                <div id="container_pie"></div>
            </figure>
        </div>
        <!--graficos-->
        <!--administracion-->
        <div class="col-md-6 col-sm-12">
            <div class="card bg-dark p-n5">
                <div class="card-body text-white">
                    <?php
                    $em_LN = new LN_empleado();
                    $result = $em_LN->buscar_listar();
                    ?>
                    <p class="card-text text-center"><?= count($result) ?></p>
                </div>
                <div class="card-footer bg-transparent border-white text-center text-white">
                    <i class="fa fa-user-lock" aria-hidden="true"></i> Usuarios
                </div>
            </div>
        </div>
        <div class="col-md-6 pt-md-0 pt-2">
            <div class="card bg-dark p-n5">
                <div class="card-body text-white">
                    <?php
                    $cli_LN = new LN_cliente();
                    $result = $cli_LN->buscar_listar();
                    ?>
                    <p class="card-text text-center"><?= count($result) ?></p>
                </div>
                <div class="card-footer bg-transparent border-white text-center text-white">
                    <i class="fa fa-address-book" aria-hidden="true"></i> Clientes
                </div>
            </div>
        </div>
    </div>
    <!--administracion-->
    <?php
    include_once 'templates/footer.php';
