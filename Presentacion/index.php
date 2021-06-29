<?php
if (isset($_GET['cerrar_sesion'])) {
    session_start();
    $cerrar_sesion = filter_var($_GET['cerrar_sesion'], FILTER_VALIDATE_BOOLEAN);
    if ($cerrar_sesion) {
        session_destroy();
    }
} else {
    session_start();
    if (isset($_SESSION['empleado'])) {
        header('location: Inicio.php');
        die();
    } else {
        session_destroy();
    }
}
?>
<!doctype html>
<html lang="es">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="google" content="notranslate">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!--icono de la pagina -->
    <link rel="shortcut icon" href="imagenes/Usuarios.png">
    <!-- Bootstrap CSS -->
    <link href="lib/bootstrap-4.6.0-dist/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <!-- iconos -->
    <link href="css/all.min.css" rel="stylesheet" type="text/css">
    <!-- CSS propios -->
    <link href="css/estilos.css" rel="stylesheet" type="text/css">
    <title>Recimar EIRL</title>
</head>

<body>
    <div class="container-fluid fondo">
        <div class="d-flex align-items-center justify-content-center alto">
            <form class="p-4 rounded-lg shadow form" action="A_login-admin.php" autocomplete="off" id="form-login" method="POST">
                <div class="form-group mt-n3 mb-n3">
                    <img src="imagenes/logo.png" width="250px" alt="Logo">
                </div>
                <div class="form-group mt-n2">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                </div>
                <div class="form-group mt-n2">
                    <label for="password">Contraseña:</label>
                    <input type="password" class="form-control" id="password" name="password" autocomplete="password" placeholder="Contraseña" required>
                </div>
                <input type="hidden" name="iniciar_sesion" value="iniciar_sesion">
                <button type="submit" class="btn btn-success btn-block"><i class="fas fa-sign-in-alt"></i> Iniciar Sesión</button>
            </form>
        </div>
    </div>

    <!-- jquery -->
    <script src="lib/jquery/jquery-3.5.1.min.js" type="text/javascript"></script>
    <!-- Alert2 -->
    <script src="lib/alert/sweetalert2.all.min.js" type="text/javascript"></script>
    <!-- iconos -->
    <script src="js/all.min.js" type="text/javascript"></script>
    <!-- js propio -->
    <script src="js/login-admin.js" type="text/javascript"></script>
</body>

</html>