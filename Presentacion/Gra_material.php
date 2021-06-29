<?php
include_once 'templates/header.php';
include_once 'templates/navegacion.php';
include_once 'templates/carga.php';

use LogicaNegocio\LN_material;
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
<h4 class="my-3"><i class="fas fa-weight"></i> Gráfico material</h4>
<div class="p-2 contenido">
    <!-- buscador -->
    <form id="form-data" action="B_modelo_grafico.php" method="POST">
        <div class="row">
            <div class="form-group col-md-6">
                <?php
                $ma = new LN_material; //instancia
                $result = $ma->buscar_listar();
                ?>
                <label for="material" class="col-form-label font-weight-bold">Material:</label>
                <select name="material" id="material" data-placeholder="-- Seleccionar --" class="form-control" data-allow-clear="1" required>
                    <option value=""></option>
                    <?php foreach ($result as $r) { ?>
                        <option value="<?= $r->id ?>"><?= $r->nombre ?></option>
                    <?php
                    }
                    ?>
                </select>
                <input type="hidden" id="nombre">
                <small class="from-text text-white" id="errormaterial"></small>
            </div>
            <div class="form-group col-md-6">
                <label class="col-form-label font-weight-bold">Año:</label>
                <div class="input-group">
                    <?php
                    $ln_anio = new LN_graficos();
                    $result = $ln_anio->anios_compra();
                    ?>
                    <select name="anio" id="anio" data-placeholder="-- Seleccionar --" class="form-control" data-allow-clear="1" required>
                        <option value=""></option>
                        <?php foreach ($result as $anio) { ?>
                            <option value="<?= $anio['anio'] ?>"><?= $anio['anio'] ?></option>
                        <?php
                        }
                        ?>
                    </select>
                    <div class="input-group-append">
                        <input type="hidden" name="modelo" id="modelo" value="material">
                        <button class="btn btn-outline-dark" type="submit" id="btn_buscar" data-toggle="tooltip" data-placement="top" title="Buscar">
                            <i class="fa fa-search" aria-hidden="true"></i> Buscar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- buscador -->
    <!-- Grafico -->
    <figure class="highcharts-figure">
        <div id="container"></div>
        <p class="highcharts-description"></p>
    </figure>
    <!-- Grafico -->
    <?php
    include_once 'templates/footer.php';
