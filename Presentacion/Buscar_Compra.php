<?php
include_once 'templates/header.php';
include_once 'templates/navegacion.php';
include_once 'templates/carga.php';
?>
<h4 class="my-3"><i class="fas fa-search"></i> <?= $pagina ?></h4>
<div class="p-2 contenido">
    <!-- buscador -->
    <form action="B_modelo-Ncompra.php" id="form-data" method="POST">
        <div class="row">
            <h4 class="col-form-label font-weight-bold col-12">Código:</h4>
            <div class="input-group col-xl-4 col-lg-5 col-md-6">
                <input type="search" class="form-control" id="codigo" name="codigo" placeholder="Código" aria-label="Codigo" aria-describedby="button-addon2" required="">
                <div class="input-group-append">
                    <input type="hidden" name="modelo_compra" id="modelo_compra" value="buscar">
                    <button class="btn btn-outline-dark" type="submit" id="btn_buscar" data-toggle="tooltip" data-placement="right" title="Buscar">
                        <i class="fa fa-search" aria-hidden="true"></i> Buscar
                    </button>
                </div>
            </div>

        </div>
    </form>
    <!-- buscador -->
    <!--Datos-->
    <div class="row mt-3">
        <div class="col-md-4">
            <h6>Cliente:</h6>
            <input type="text" class="form-control" id="cliente" placeholder="Cliente" readonly>
        </div>
        <div class="col-md-4 col-12">
            <h6>Comprador:</h6>
            <input type="text" class="form-control" id="comprador" placeholder="Comprador" readonly>
        </div>
        <div class="col-md-4">
            <h6>Fecha-Hora:</h6>
            <input type="datetime" class="form-control" id="fecha_hora" placeholder="Fecha - Hora" readonly=""/>
        </div>
    </div>
    <!--Datos-->
    <!--tabla-->
    <div class="mt-3">
        <table id="tabla_datos" class="table table-hover table-sm table-bordered rounded border border-dark text-center dt-responsive nowrap w-100">
            <thead class="thead-dark">
                <tr>
                    <th>MATERIAL</th>
                    <th>CANTIDAD</th>
                    <th>UND</th>
                    <th>PRECIO</th>
                    <th>SUB TOTAL</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
    <!--tabla-->
    <!-- textArera - total a pagar -->
    <div class="row mt-2 d-flex flex-column-reverse flex-md-row  bd-highlight">
        <div class="col-md-8 form-group">
            <textarea placeholder="Mensaje para el cliente" class="form-control" id="mensaje" rows="2" readonly=""></textarea>
        </div>
        <div class="col-md-4 form-group">
            <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                    <span class="input-group-text rounded-0 font-weight-bold">Descuento:</span>
                    <span class="input-group-text">S/.</span>
                </div>
                <input type="number" id="descuento" placeholder="0.00" class="form-control text-center descuento" aria-describedby="inputGroup-sizing-sm" readonly>
            </div>
            <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                    <span class="input-group-text rounded-0 font-weight-bold">Total:</span>
                    <span class="input-group-text ">S/.</span>
                </div>
                <input type="number" id="total" placeholder="0.00" class="form-control text-center rounded-0" aria-describedby="inputGroup-sizing-sm" readonly>
            </div>
        </div>
    </div>
    <!-- textArera - total a pagar -->
    <!-- botones guardar cancelar -->
    <div class="mt-n2 mb-2 text-center text-md-left">
        <button type="button" class="btn btn-danger" id="btn_cancelar" data-toggle="tooltip" data-placement="bottom" title="Cancelar">
            <i class="fa fa-times-circle" aria-hidden="true"></i> Cancelar
        </button>
        <button type="button" class="btn btn-info" id="btn_imprimir" data-toggle="tooltip" data-placement="bottom" title="Imprimir">
            <i class="fa fa-print" aria-hidden="true"></i> Imprimir
        </button>
    </div>
    <!-- botones guardar cancelar -->
    <?php
    include_once 'templates/footer.php';
