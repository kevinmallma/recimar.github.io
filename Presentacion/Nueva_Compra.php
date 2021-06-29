<?php
include_once 'templates/header.php';
include_once 'templates/navegacion.php';
include_once 'templates/carga.php';

use LogicaNegocio\LN_cliente;
use LogicaNegocio\LN_material;
use Entidades\Contenedor;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

Contenedor::set('logger', function () { //historial del proyecto
    $logger = new Logger(__CONFIG__['log']['channel']); //nombre
    $file_handler = new StreamHandler(__CONFIG__['log']['path'] . date('Ymd') . '.log'); //lugar
    $logger->pushHandler($file_handler); //crear fichero
    return $logger;
});
unset($_SESSION['carrito_compra']);
?>
<h4 class="my-3"><i class="fas fa-cart-plus"></i> <?= $pagina ?></h4>
<div class="p-2 contenido">
    <!-- Modal cliente -->
    <?php include_once 'templates/modal_cliente.php'; ?>
    <!-- Modal cliente -->
    <!-- modal agregar material -->
    <div class="modal fade" id="modal_material" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modal_material" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content bg-dark text-white">
                <!--header del modal-->
                <div class="modal-header">
                    <h5 class="modal-title">Agregar material</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="badge badge-danger rounded-circle">
                            <i class="fa fa-times" aria-hidden="true"></i>
                        </span>
                    </button>
                </div>
                <!--header del modal-->
                <form method="post" action="B_modelo-Ncompra.php" id="form-data-material" autocomplete="off">
                    <!-- body del modal -->
                    <div class="modal-body">
                        <div class="form-group mt-n3">
                            <?php
                            $ma = new ln_material(); //instancia
                            $result_ma = $ma->buscar_listar();
                            ?>
                            <label class="col-form-label">Material:</label>
                            <select name="id_material" id="id_material" data-placeholder="-- Seleccionar --" data-allow-clear="1" required>
                                <option value=""></option>
                                <?php foreach ($result_ma as $r) { //llenar con los materiales 
                                ?>
                                    <option value="<?= $r->id ?>"><?= $r->nombre ?></option>
                                <?php } ?>
                            </select>
                            <small class="from-text text-white" id="errorid_material"></small>
                        </div>
                        <div class="form-group mt-n2">
                            <div class="form-check form-check-inline">
                                <input type="hidden" name="material" id="material"/>
                                <input class="form-check-input" type="radio" name="check_precio" id="check_precio1" value="0.00" required>
                                <label class="form-check-label" for="check_precio1" id="label_precio1">0.00</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="check_precio" id="check_precio2" value="0.00" required>
                                <label class="form-check-label" for="check_precio2" id="label_precio2">0.00</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="check_precio" id="check_precio3" value="0.00" required>
                                <label class="form-check-label" for="check_precio3" id="label_precio3">0.00</label>
                            </div><br>
                            <small class="from-text text-white" id="errorprecio"></small>
                        </div>
                        <div class="form-group mt-n2">
                            <div class="input-group">
                                <input type="number" name="peso" id="peso" class="form-control is-invalid numero" step="any" min="0" title="Solo números" placeholder="Peso" required>
                                <div class="input-group-append">
                                    <input type="hidden" name="und" id="und"/>
                                    <span class="input-group-text" id="und_span">UND</span>
                                </div>
                            </div>
                            <small class="from-text text-white" id="errorpeso"></small>
                        </div>
                    </div>
                    <!-- body del modal -->
                    <!-- footer modal -->
                    <div class="modal-footer mt-n3 d-flex justify-content-between">
                        <input type="hidden" name="modelo_compra" id="modelo_compra" value="agregar">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                            <i class="fas fa-window-close"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Agregar
                        </button>
                    </div>
                    <!-- footer modal -->
                </form>
            </div>
        </div>
    </div>
    <!-- modal agregar material -->
    <!-- Formulario de toda la compra -->
    <form name="form-data-compra" action="B_modelo-Ncompra.php" id="form-data-compra" autocomplete="off" method="POST">
        <!-- datos del cliente -->
        <div class="form-group">
            <label class="col-form-label font-weight-bold">Cliente:</label>
            <div class="input-group">
                <?php
                $cli_LN = new LN_cliente();
                $result = $cli_LN->buscar_listar();
                ?>
                <select name="dni_cliente" id="dni_cliente" data-placeholder="-- Seleccionar --" data-allow-clear="1">
                    <option></option>
                    <?php foreach ($result as $r) { ?>
                        <option value="<?= $r->dni ?>"><?= $r->nombres . " " . $r->paterno . " " . $r->materno ?></option>
                    <?php } ?>
                </select>
                <input type="hidden" name="cliente" id="cliente">
                <div class="input-group-append">
                    <button class="btn btn-outline-dark" type="button" id="btn-modal-cliente"><i class="fa fa-plus-square" aria-hidden="true"></i> Agregar</button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4 form-group mt-n3">
                <label for="asisatencia" class="col-form-label font-weight-bold">Nº Visitas:</label>
                <input type="number" name="asistencia" id="asistencia" class="form-control" placeholder="Nº visitas" readonly="" />
            </div>
            <div class="col-sm-4 form-group mt-n3">
                <label for="celular_cliente" class="col-form-label font-weight-bold">Celular:</label>
                <input type="tel" name="celular_cliente" id="celular_cliente" class="form-control" placeholder="Celular" readonly="" />
            </div>
            <div class="col-sm-4  form-group mt-n3">
                <label for="codigo" class="col-form-label font-weight-bold">Codigo:</label>
                <input type="text" name="codigo" id="codigo" class="form-control" placeholder="Codigo" readonly="" />
            </div>
        </div>
        <!-- datos del cliente -->
        <!-- datos de la compra -->
        <button type="button" class="btn btn-dark" id="btn_modal_material" data-toggle="tooltip" data-placement="right" title="Agregar material">
            <i class="fa fa-plus-square" aria-hidden="true"></i> Agregar material
        </button>
        <!--tabla-->
        <div class="mt-1">
            <table id="tabla_datos" class="table table-hover table-sm table-bordered rounded border border-dark text-center dt-responsive nowrap w-100">
                <thead class="thead-dark">
                    <tr>
                        <th>MATERIAL</th>
                        <th>CANTIDAD</th>
                        <th>UND</th>
                        <th>PRECIO</th>
                        <th>SUB TOTAL</th>
                        <th>ACCION</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
        <!--tabla-->
        <!-- datos de la compra -->
        <!-- textArera - total a pagar -->
        <div class="row mt-2 d-flex flex-column-reverse flex-md-row  bd-highlight">
            <div class="col-md-8 form-group">
                <textarea placeholder="Mensaje para el cliente" class="form-control" name="mensaje" id="mensaje" rows="2" maxlength="50"></textarea>
            </div>
            <div class="col-md-4 form-group">
                <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                        <span class="input-group-text rounded-0 font-weight-bold">Descuento:</span>
                        <span class="input-group-text">S/.</span>
                    </div>
                    <input type="number" name="descuento" id="descuento" placeholder="0.00" step="any" min="0" onchange="listar_compra();" class="form-control text-center descuento" aria-describedby="inputGroup-sizing-sm">
                </div>
                <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                        <span class="input-group-text rounded-0 font-weight-bold">Total:</span>
                        <span class="input-group-text ">S/.</span>
                    </div>
                    <input type="number" name="total" id="total" value="0.00" step="any" min="0" class="form-control text-center rounded-0" aria-describedby="inputGroup-sizing-sm" readonly="">
                </div>
            </div>
        </div>
        <!-- textArera - total a pagar -->
        <!-- botones guardar cancelar -->
        <div class="mt-n2 mb-2 text-center text-md-left">
            <button type="button" class="btn btn-danger" id="btn_cancelar" data-toggle="tooltip" data-placement="bottom" title="Cancelar">
                <i class="fas fa-window-close" aria-hidden="true"></i> Cancelar
            </button>
            <input type="hidden" name="modelo_compra" value="guardar">
            <button type="submit" class="btn btn-success" id="btn_guardar" data-toggle="tooltip" data-placement="bottom" title="Guardar">
                <i class="fas fa-save" aria-hidden="true"></i> Guardar
            </button>
        </div>
        <!-- botones guardar cancelar -->
    </form>
    <!-- Formulario de toda la compra -->
    <?php
    include_once 'templates/footer.php';
