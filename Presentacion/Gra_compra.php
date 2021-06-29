<?php
include_once 'templates/header.php';
include_once 'templates/navegacion.php';
include_once 'templates/carga.php';

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
?>
<h4 class="my-3"><i class="far fa-chart-bar"></i> Gráfico compra</h4>
<div class="p-2 contenido">
    <div class="text-center">
        <!-- Fechas -->
        <div class="btn-group" role="group" aria-label="Basic example">
            <?php
            $ln_anio = new LN_graficos();
            $result = $ln_anio->anios_compra();
            foreach ($result as $anio) :
            ?>
                <button type="button" class="btn btn-outline-success btn_anio <?= $anio['anio'] == date("Y") ? 'active' : '' ?>" data-anio="<?= $anio['anio'] ?>">
                    <?= $anio['anio'] ?>
                </button>
            <?php
            endforeach;
            ?>
        </div>
        <!-- Fechas -->
        <!-- Grafico -->
        <figure class="highcharts-figure mt-2">
            <div id="container"></div>
            <p class="highcharts-description"></p>
        </figure>
        <!-- Grafico -->
    </div>
    <?php
    include_once 'templates/footer.php';
