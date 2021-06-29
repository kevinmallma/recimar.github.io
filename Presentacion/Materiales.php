<?php
include_once 'templates/header.php';
include_once 'templates/navegacion.php';
include_once 'templates/carga.php';
?>
<h4 class="my-3"><i class="fas fa-recycle"></i> <?= $pagina ?></h4>
<div class="p-2 contenido">
    <!-- botones -->
    <button type="button" class="btn btn-dark" id="btn-modal-material" data-toggle="tooltip" data-placement="top" title="Agregar">
        <i class="fa fa-plus-square" aria-hidden="true"></i> Agregar
    </button>
    <button type="button" class="btn btn-dark btn_reporte" data-toggle="tooltip" data-placement="top" title="Imprimir">
        <i class="fas fa-file-pdf" aria-hidden="true"></i> Imprimir
    </button>
    <!-- botones -->
    <!-- modal -->
    <div class="modal fade" id="modal_material" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modal_material" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content bg-dark text-white">
                <!-- header del modal -->
                <div class="modal-header">
                    <h5 class="modal-title" id="modal_h5"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="badge badge-danger rounded-circle">
                            <i class="fa fa-times" aria-hidden="true"></i>
                        </span>
                    </button>
                </div>
                <!-- header del modal -->
                <form method="post" id="form-data" action="B_modelo-material.php" autocomplete="off">
                    <!-- body del modal -->
                    <div class="modal-body">
                        <div class="form-group mt-n3">
                            <input type="hidden" name="id" id="id">
                            <label for="nombre" class="col-form-label">Nombre:</label>
                            <input type="text" name="nombre" id="nombre" class="form-control form-control-sm is-invalid" pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ´. ]{3,20}" title="Solo letras" placeholder="Nombre" required>
                            <small class="from-text text-white" id="errornombre"></small>
                        </div>
                        <div class="form-group mt-n3">
                            <label for="und" class="col-form-label">Unidad Medida:</label>
                            <select class="form-control form-control-sm" id="und" name="und" required>
                                <option value="" disabled="" selected="">-- Seleccionar --</option>
                                <option value="KGM">Kilogramo</option>
                                <option value="UNI">Unitario</option>
                                <option value="DOC">Docena</option>
                            </select>
                        </div>
                        <!-- <div class="form-group mt-n3">
                            <label for="precio_v1" class="col-form-label">Precio Venta:</label>
                            <input type="number" name="precio_v1" id="precio_v1" class="form-control form-control-sm is-invalid" step="any" min="0" max="90" title="Solo números" placeholder="Precio venta">
                            <small class="from-text text-white" id="errorprecio_v1"></small>
                        </div> -->
                        <div class="form-group mt-n3">
                            <label for="precio_c1" class="col-form-label">Precio Compra Min:</label>
                            <input type="number" name="precio_c1" id="precio_c1" class="form-control form-control-sm is-invalid" step="any" min="0" max="90" title="Solo números" placeholder="Precio compra">
                            <small class="from-text text-white" id="errorprecio_c1"></small>
                        </div>
                        <div class="form-group mt-n3">
                            <label for="precio_c2" class="col-form-label">Precio Compra Med:</label>
                            <input type="number" name="precio_c2" id="precio_c2" class="form-control form-control-sm is-invalid" step="any" min="0" max="90" title="Solo números" placeholder="Precio compra">
                            <small class="from-text text-white" id="errorprecio_c2"></small>
                        </div>
                        <div class="form-group mt-n3">
                            <label for="precio_c3" class="col-form-label">Precio Compra May:</label>
                            <input type="number" name="precio_c3" id="precio_c3" class="form-control form-control-sm is-invalid" step="any" min="0" max="90" title="Solo números" placeholder="Precio compra">
                            <small class="from-text text-white" id="errorprecio_c3"></small>
                        </div>
                    </div>
                    <!-- body del modal -->
                    <!-- footer modal -->
                    <div class="modal-footer mt-n3 d-flex justify-content-between">
                        <input type="hidden" name="modelo" id="modelo" value="agregar">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                            <i class="fas fa-window-close"></i> Cancelar
                        </button>
                        <button type="submit" id="btn-guardar" class="btn btn-success">
                            <i class="fas fa-save"></i> Guardar
                        </button>
                    </div>
                    <!-- footer modal -->
                </form>
            </div>
        </div>
    </div>
    <!-- modal -->
    <!--tabla-->
    <div class="mt-3">
        <table id="tabla_datos" class="table table-hover table-sm table-bordered rounded border border-dark text-center dt-responsive nowrap w-100">
            <thead class="thead-dark">
                <tr>
                    <th>NOMBRE</th>
                    <th>PESO</th>
                    <th>UND</th>
                    <th>P. COMPRA MIN.</th>
                    <th>P. COMPRA MED.</th>
                    <th>P. COMPRA MAY.</th>
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
