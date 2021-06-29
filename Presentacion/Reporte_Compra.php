<?php
include_once 'templates/header.php';
include_once 'templates/navegacion.php';
include_once 'templates/carga.php';
//importaciones
use LogicaNegocio\LN_empleado;
use Entidades\Contenedor;

Contenedor::set('logger', function () { //historial del proyecto
    $logger = new \Monolog\Logger(__CONFIG__['log']['channel']); //nombre
    $file_handler = new \Monolog\Handler\StreamHandler(__CONFIG__['log']['path'] . date('Ymd') . '.log'); //lugar
    $logger->pushHandler($file_handler); //crear

    return $logger;
});
?>
<h4 class="my-3"><i class="fas fa-file-alt"></i> <?= $pagina ?></h4>
<div class="p-2 contenido">
    <!-- buscador -->
    <form id="form-data" action="B_modelo-Ncompra.php" method="POST">
        <div class="row">
            <div class="form-group col-md-4">
                <label class="col-form-label font-weight-bold">Fecha Inicio:</label>
                <input type="text" class="form-control" id="fecha_ini" name="fecha_ini" required>
            </div>
            <div class="form-group col-md-4">
                <label class="col-form-label font-weight-bold">Fecha Fin:</label>
                <input type="text" class="form-control" id="fecha_fin" name="fecha_fin" required>
            </div>
            <div class="form-group col-md-4">
                <label class="col-form-label font-weight-bold">Usuario:</label>
                <div class="input-group">
                    <?php
                    $usu = new LN_empleado(); //instancia
                    $result = $usu->buscar_listar();
                    ?>
                    <select name="usuario" id="usuario" data-placeholder="-- Seleccionar --" class="form-control" data-allow-clear="1">
                        <option value=""></option>
                        <?php foreach ($result as $r) { ?>
                            <option value="<?= $r->dni ?>"><?= $r->nombres . " " . $r->paterno . " " . $r->materno ?></option>
                        <?php
                        }
                        ?>
                    </select>
                    <div class="input-group-append">
                        <input type="hidden" name="modelo_compra" id="modelo_compra" value="reporte">
                        <button class="btn btn-outline-dark" type="submit" id="btn_buscar" data-toggle="tooltip" data-placement="top" title="Buscar">
                            <i class="fa fa-search" aria-hidden="true"></i> Buscar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- buscador -->
    <!--tabla-->
    <table id="tabla_datos" class="table table-hover table-sm table-bordered rounded border border-dark text-center dt-responsive nowrap w-100">
        <thead class="thead-dark">
            <tr>
                <th>CODIGO</th>
                <th>COMPRADOR</th>
                <th>CLIENTE</th>
                <th>FECHA-HORA</th>
                <th>MENSAJE</th>
                <th>DESCUENTO</th>
                <th>TOTAL</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
    <!--tabla-->
    <!-- Imprmir - Total -->
    <div class="row my-2 d-flex flex-column-reverse flex-sm-row bd-highlight">
        <div class="col-sm-7 col-md-8 text-center text-sm-left">
            <button type="button" class="btn btn-danger" id="btn_cancelar" data-toggle="tooltip" data-placement="bottom" title="Cancelar">
                <i class="fa fa-times-circle" aria-hidden="true"></i> Cancelar
            </button>
            <button type="button" class="btn btn-info" id="btn_imprimir" data-toggle="tooltip" data-placement="bottom" title="Imprimir">
                <i class="fa fa-print" aria-hidden="true"></i> Imprimir
            </button>
        </div>
        <div class="col-sm-5 col-md-4 input-group mb-2">
            <div class="input-group-prepend">
                <span class="input-group-text font-weight-bold">Total:</span>
                <span class="input-group-text">S/.</span>
            </div>
            <input type="number" class="form-control text-center" id="total" placeholder="0.00" readonly="">
        </div>
    </div>
    <!-- Imprmir - Total -->
    <?php
    include_once 'templates/footer.php';
