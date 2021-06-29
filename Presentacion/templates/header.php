<?php
require_once '../config.php'; //importar
session_start();
if (!isset($_SESSION['empleado'])) {
    session_destroy();
    header('location: 404.html');
    die(); //detener todo
}
$archivo = basename($_SERVER['PHP_SELF']);  //pagina
$pagina_original = str_replace(".php", "", $archivo);
$pagina = str_replace("_", " ", $pagina_original);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <title><?= $pagina ?> | Recimar EIRL</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="google" content="notranslate">
    <!-- icono de la pagina -->
    <link rel="shortcut icon" href="imagenes/logo_reciclaje.png">
    <!-- datepicker -->
    <link rel="stylesheet" href="lib/jquery-ui/css/jquery-ui.min.css">
    <!-- Bootstrap CSS -->
    <link href="lib/bootstrap-4.6.0-dist/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <!-- datatable -->
    <link href="lib/datatables/DataTables-1.10.22/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css">
    <link href="lib/datatables/Responsive-2.2.6/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css">
    <!-- estilos -->
    <link rel="stylesheet" href="css/estilos.css">
    <!-- iconos -->
    <link href="css/all.min.css" rel="stylesheet" type="text/css">
    <!--select 2-->
    <link rel="stylesheet" href="lib/select2/css/select2-bootstrap4.min.css">
    <link rel="stylesheet" href="lib/select2/css/select2.min.css">
</head>

<body>
    <!-- menu celular -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark d-lg-none fixed-top">
        <a class="navbar-brand navbar-toggler border border-white border-right-0 border-left-0" href="B_principal.php">
            <img src="imagenes/logo.png" width="70px" class="ml-n2 logo_celular" alt="logo" loading="lazy">
            <!-- <span><i class="fa fa-user-circle-o" aria-hidden="true"></i> <?= $_SESSION['empleado'] ?></span> -->
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#toggle-menu" aria-controls="toggle-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </nav>
    <!-- menu celular -->
    <!-- menu pc-cuerpo -->
    <div class="container-fluid">
        <div class="row">