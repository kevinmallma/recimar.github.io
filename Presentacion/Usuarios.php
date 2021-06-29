<?php
include_once 'templates/header.php';
include_once 'templates/navegacion.php';
include_once 'templates/carga.php';
//importaciones
use LogicaNegocio\LN_cliente;
use Entidades\Contenedor;

Contenedor::set('logger', function () { //historial del proyecto
    $logger = new \Monolog\Logger(__CONFIG__['log']['channel']); //nombre
    $file_handler = new \Monolog\Handler\StreamHandler(__CONFIG__['log']['path'] . date('Ymd') . '.log'); //lugar
    $logger->pushHandler($file_handler); //crear

    return $logger;
});
?>
<h4 class="my-3"><i class="fas fa-user-lock"></i> <?= $pagina ?></h4>
<div class="p-2 contenido">
    <!-- Boton modal -->
    <button type="button" class="btn btn-dark btn_modal" data-toggle="tooltip" data-placement="right" title="Agregar">
        <i class="fa fa-plus-square" aria-hidden="true"></i> Agregar
    </button>
    <!-- Boton modal -->
    <!-- modal -->
    <div class="modal fade" id="modal_usuario" tabindex="-1" data-backdrop="static" data-keyboard="false" aria-labelledby="modal_usuario" aria-hidden="true">
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
                <form method="post" id="form-data" action="B_modelo-usuario.php" autocomplete="off">
                    <!-- body del modal -->
                    <div class="modal-body">
                        <div class="form-group mt-n3">
                            <?php
                            $cli = new LN_cliente(); //instancia
                            $result = $cli->buscar_listar();
                            ?>
                            <input type="hidden" name="dni_editar" id="dni_editar">
                            <label for="persona" class="col-form-label">Persona:</label>
                            <select name="persona" id="persona" data-placeholder="-- Seleccionar --" class="form-control" data-allow-clear="1" required>
                                <option value=""></option>
                                <?php foreach ($result as $r) { ?>
                                    <option value="<?= $r->dni ?>"><?= $r->nombres . " " . $r->paterno . " " . $r->materno ?></option>
                                <?php
                                }
                                ?>
                            </select>
                            <small class="from-text text-white" id="errorpersona"></small>
                        </div>
                        <div class="form-group mt-n3">
                            <label for="email" class="col-form-label">Email:</label>
                            <input type="email" name="email" id="email" class="form-control is-invalid" maxlength="60" placeholder="Email" required>
                            <small class="from-text text-white" id="erroremail"></small>
                        </div>
                        <div class="form-group mt-n3">
                            <label for="password" class="col-form-label">Contraseña:</label>
                            <input type="password" name="password" id="password" class="form-control is-invalid" autocomplete="" placeholder="Contraseña" required>
                            <small class="from-text text-white" id="errorpassword"></small>
                        </div>
                        <div class="form-group form-check form-check-inline">
                            <input type="checkbox" id="check" class="form-check-input" onclick="mostrar_clave()">
                            <label class="form-check-label" for="check">Mostrar contraseñas</label>
                        </div>
                    </div>
                    <!-- body del modal -->
                    <!-- footer modal -->
                    <div class="modal-footer mt-n3 d-flex justify-content-between">
                        <input type="hidden" name="modelo" id="modelo" value="agregar">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                            <i class="fas fa-window-close"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-success" id="btn-guardar">
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
    <table id="tabla_datos" class="table table-hover table-sm table-bordered rounded border border-dark text-center dt-responsive nowrap w-100">
        <thead class="thead-dark">
            <tr>
                <th>DNI</th>
                <th>NOMBRES</th>
                <th>APELLIDOS</th>
                <th>EMAIL</th>
                <th>ACCION</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
    <!--tabla-->
    <?php
    include_once 'templates/footer.php';
