<?php
include_once 'templates/header.php';
include_once 'templates/navegacion.php';
include_once 'templates/carga.php';
?>
<h4 class="my-3"><i class="fas fa-users" aria-hidden="true"></i> <?= $pagina ?></h4>
<div class="p-2 contenido">
    <!-- botones -->
    <button type="button" class="btn btn-dark" id="btn-modal-cliente" data-toggle="tooltip" data-placement="top" title="Agregar">
        <i class="fa fa-plus-square" aria-hidden="true"></i> Agregar
    </button>
    <button type="button" class="btn btn-dark btn_reporte" data-toggle="tooltip" data-placement="top" title="Imprimir">
        <i class="fas fa-file-pdf" aria-hidden="true"></i> Imprimir
    </button>
    <!-- botones -->
    <?php include_once 'templates/modal_cliente.php'; ?>
    <!--tabla-->
    <div class="mt-3">
        <table id="tabla_datos" class="table table-hover table-sm table-bordered rounded border border-dark text-center dt-responsive nowrap w-100">
            <thead class="thead-dark">
                <tr>
                    <th>DNI</th>
                    <th>NOMBRES</th>
                    <th>APELLIDOS</th>
                    <th>CELULAR</th>
                    <th>Nº VISITAS</th>
                    <th>ULT. VENTA</th>
                    <th>ACCION</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
    <!--tabla-->
    <?php
    include_once 'templates/footer.php';
