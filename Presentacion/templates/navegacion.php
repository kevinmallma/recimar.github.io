<?php
$tipo = '';
if (isset($_GET['tipo'])) { //vereficar en que padre se localiza
    $tipo = filter_var($_GET['tipo'], FILTER_SANITIZE_STRING);
}
?>
<!--barra de menu-->
<aside class="col-xl-2 col-lg-3 d-lg-block bg-dark sidebar collapse" id="toggle-menu">
    <div class="sidebar-sticky bajar-menu">
        <!-- Logo de la imagen -->
        <h1 class="text-center d-lg-block d-none">
            <img src="imagenes/logo.png" width="200px" class="ml-n2 logo_celular" alt="logo" loading="lazy">
            <div class="border border-success border-right-0 border-left-0 login">
                <p class="mt-2 text-light p-login">
                    <i class="fas fa-user-tie"></i>
                    <?= $_SESSION['empleado'] ?>
                </p>
            </div>
        </h1>
        <!-- Logo de la imagen -->
        <!-- menu -->
        <div class="accordion" id="menu">
            <!-- principal -->
            <div class="card bg-dark mb-n3">
                <div class="card-header" id="primero">
                    <h2 class="mt-n2 mx-n3">
                        <button onclick="location.href = 'Inicio.php'" class="btn btn-block text-light py-2 <?= $pagina == 'Inicio' ? 'activo-menu' : 'barras-seleccion' ?>" type="button">
                            <div class="d-flex justify-content-between">
                                <div><i class="fas fa-home"></i> Inicio</div>
                            </div>
                        </button>
                    </h2>
                </div>
            </div>
            <!-- principal -->
            <!-- Configuracion -->
            <div class="card bg-dark mb-n3">
                <div class="card-header mt-n1" id="segundo">
                    <h2 class="mt-n2 mx-n3">
                        <button class="btn btn-block text-light py-2 <?= $tipo == 'configuracion' ? 'activo-menu' : 'collapsed barras-seleccion' ?>" type="button" data-toggle="collapse" data-target="#configuracion" aria-expanded="true" aria-controls="configuracion">
                            <div class="d-flex justify-content-between">
                                <div><i class="fa fa-cog" aria-hidden="true"></i> Configuración</div>
                                <div><i class="fa fa-chevron-down" aria-hidden="true"></i></div>
                            </div>
                        </button>
                    </h2>
                </div>
                <div id="configuracion" class="collapse mx-1 mt-n3 mb-3 <?= $tipo == 'configuracion' ? 'show' : '' ?>" aria-labelledby="segundo" data-parent="#menu">
                    <div class="list-group">
                        <a href="Usuarios.php?tipo=configuracion" class="list-group-item py-2 <?= $pagina == 'Usuarios' ? 'text-dark activo-li' : 'text-light bg-dark' ?>">
                            <i class="fas fa-user-lock" aria-hidden="true"></i> Usuarios
                        </a>
                        <a href="Clientes.php?tipo=configuracion" class="list-group-item py-2 <?= $pagina == 'Clientes' ? 'text-dark activo-li' : 'text-light bg-dark' ?>">
                            <i class="fas fa-users" aria-hidden="true"></i></i> Clientes
                        </a>
                        <a href="Materiales.php?tipo=configuracion" class="list-group-item py-2 <?= $pagina == 'Materiales' ? 'text-dark activo-li' : 'text-light bg-dark' ?>">
                            <i class="fas fa-recycle" aria-hidden="true"></i> Materiales
                        </a>
                    </div>
                </div>
            </div>
            <!-- Configuracion -->
            <!-- compra -->
            <div class="card bg-dark mb-n3">
                <div class="card-header mt-n1" id="tercero">
                    <h2 class="mt-n2 mx-n3">
                        <button class="btn btn-block text-light py-2 <?= $tipo == 'compra' ? 'activo-menu' : 'collapsed barras-seleccion' ?>" type="button" data-toggle="collapse" data-target="#compra" aria-expanded="false" aria-controls="compra">
                            <div class="d-flex justify-content-between">
                                <div><i class="fas fa-shopping-cart" aria-hidden="true"></i> Compra</div>
                                <div><i class="fa fa-chevron-down" aria-hidden="true"></i></div>
                            </div>
                        </button>
                    </h2>
                </div>
                <div id="compra" class="collapse mx-1 mt-n3 mb-3 <?= $tipo == 'compra' ? 'show' : '' ?>" aria-labelledby="tercero" data-parent="#menu">
                    <div class="list-group">
                        <a href="Nueva_Compra.php?tipo=compra" class="list-group-item py-2 <?= $pagina == 'Nueva Compra' ? 'text-dark activo-li' : 'text-light bg-dark' ?>">
                            <i class="fas fa-cart-plus" aria-hidden="true"></i> Nueva
                        </a>
                        <a href="Buscar_Compra.php?tipo=compra" class="list-group-item py-2 <?= $pagina == 'Buscar Compra' ? 'text-dark activo-li' : 'text-light bg-dark' ?>">
                            <i class="fas fa-search" aria-hidden="true"></i> Buscar
                        </a>
                        <a href="Reporte_Compra.php?tipo=compra" class="list-group-item py-2 <?= $pagina == 'Reporte Compra' ? 'text-dark activo-li' : 'text-light bg-dark' ?>">
                            <i class="far fa-file-alt" aria-hidden="true"></i> Reporte
                        </a>
                    </div>
                </div>
            </div>
            <!-- compra -->
            <!-- Graficos -->
            <div class="card bg-dark mb-n3">
                <div class="card-header mt-n1" id="cuarto">
                    <h2 class="mt-n2 mx-n3">
                        <button class="btn btn-block text-light py-2 <?= $tipo == 'grafico' ? 'activo-menu' : 'collapsed barras-seleccion' ?>" type="button" data-toggle="collapse" data-target="#grafico" aria-expanded="false" aria-controls="cosultas">
                            <div class="d-flex justify-content-between">
                                <div><i class="fas fas fa-signal" aria-hidden="true"></i> Gráfico</div>
                                <div><i class="fa fa-chevron-down" aria-hidden="true"></i></div>
                            </div>
                        </button>
                    </h2>
                </div>
                <div id="grafico" class="collapse mx-1 mt-n3 mb-3 <?= $tipo == 'grafico' ? 'show' : '' ?>" aria-labelledby="cuarto" data-parent="#menu">
                    <div class="list-group">
                        <a href="Gra_compra.php?tipo=grafico" class="list-group-item py-2 <?= $pagina == 'Gra compra' ? 'text-dark activo-li' : 'text-light bg-dark' ?>">
                            <i class="far fa-circle" aria-hidden="true"></i> Compra
                        </a>
                        <a href="Gra_material.php?tipo=grafico" class="list-group-item py-2 <?= $pagina == 'Gra material' ? 'text-dark activo-li' : 'text-light bg-dark' ?>">
                            <i class="far fa-circle" aria-hidden="true"></i> Material
                        </a>
                    </div>
                </div>
            </div>
            <!-- Graficos -->
            <!-- venta -->
            <!-- <div class="card bg-dark mb-n3">
                <div class="card-header mt-n1" id="Cuarto">
                    <h2 class="mt-n2 mx-n3">
                        <button class="btn btn-block text-light collapsed barras-seleccion py-2" type="button" data-toggle="collapse" data-target="#venta" aria-expanded="false" aria-controls="venta">
                            <div class="d-flex justify-content-between">
                                <i class="fa fa-money" aria-hidden="true"> Venta</i>
                                <i class="fa fa-chevron-down" aria-hidden="true"></i>
                            </div>
                        </button>
                    </h2>
                </div>
                <div id="venta" class="collapse mx-1 mt-n3 mb-3" aria-labelledby="cuarto" data-parent="#menu">
                    <ul class="list-group">
                        <li class="list-group-item bg-dark py-1">
                            <a type="button" class="text-light" href="#">
                                <i class="fa fa-circle-o" aria-hidden="true"> Nuevo</i>
                            </a>
                        </li>
                        <li class="list-group-item bg-dark py-1">
                            <a type="button" class="text-light" href="#">
                                <i class="fa fa-circle-o" aria-hidden="true"> Editar</i>
                            </a>
                        </li>
                        <li class="list-group-item bg-dark py-1">
                            <a type="button" class="text-light" href="#">
                                <i class="fa fa-circle-o" aria-hidden="true"> Buscar</i>
                            </a>
                        </li>
                        <li class="list-group-item bg-dark py-1">
                            <a type="button" class="text-light" href="#">
                                <i class="fa fa-circle-o" aria-hidden="true"> Reporte</i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div> -->
            <!-- venta -->
            <!-- salir -->
            <div class="card bg-dark mb-n3">
                <div class="card-header mt-n1" id="salir">
                    <h2 class="mt-n2 mx-n3">
                        <button class="btn btn-block text-light collapsed barras-salir py-2" type="button">
                            <div class="d-flex justify-content-between">
                                <div><i class="fas fa-door-open"></i> Cerrar Sesión</div>
                            </div>
                        </button>
                    </h2>
                </div>
            </div>
            <!-- salir -->
        </div>
        <!-- menu -->
    </div>
</aside>
<!--barra de menu-->
<!-- contenido -->
<main class="col-xl-10 col-lg-9 ml-md-auto bajar-cuerpo">