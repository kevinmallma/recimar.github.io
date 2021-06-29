<!-- Moodal -->
<div class="modal fade" id="modal_cliente" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modal_cliente" aria-hidden="true">
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
            <form method="post" id="form-data-cliente" action="B_modelo-cliente.php" autocomplete="off">
                <!-- body del modal -->
                <div class="modal-body">
                    <div class="form-group mt-n3">
                        <input type="hidden" name="id" id="id">
                        <label for="dni" class="col-form-label">DNI:</label>
                        <input type="tel" name="dni" id="dni" class="form-control form-control-sm is-invalid" minlength="8" maxlength="8" pattern="[0-9]+" placeholder="DNI" required="" />
                        <small class="from-text text-white" id="errordni"></small>
                    </div>
                    <div class="form-group mt-n3">
                        <label for="nombre" class="col-form-label">Nombre(s):</label>
                        <input type="text" name="nombre" id="nombre" class="form-control form-control-sm is-invalid" pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ´ ]{3,30}" title="Solo letras" placeholder="Nombre(s)" required="" />
                        <small class="from-text text-white" id="errornombre"></small>
                    </div>
                    <div class="form-group mt-n3">
                        <label for="paterno" class="col-form-label">Apellido Paterno:</label>
                        <input type="text" name="paterno" id="paterno" class="form-control form-control-sm is-invalid" pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ´ ]{3,30}" title="Solo letras" placeholder="Paterno" required="" />
                        <small class="from-text text-white" id="errorpaterno"></small>
                    </div>
                    <div class="form-group mt-n3">
                        <label for="materno" class="col-form-label">Apellido Materno:</label>
                        <input type="text" name="materno" id="materno" class="form-control form-control-sm is-invalid" pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ´ ]{3,30}" title="Solo letras" placeholder="Materno" required="" />
                        <small class="from-text text-white" id="errormaterno"></small>
                    </div>
                    <div class="form-group mt-n3">
                        <label for="celular" class="col-form-label">Celular:</label>
                        <input type="tel" name="celular" id="celular" class="form-control form-control-sm is-invalid" minlength="9" maxlength="9" pattern="[0-9]+" placeholder="Celular" />
                        <small class="from-text text-white" id="errorcelular"></small>
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
<!-- Moodal -->