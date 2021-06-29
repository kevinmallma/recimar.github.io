<?php
require_once '../config.php'; //importaciones
use LogicaNegocio\LN_empleado;
use Entidades\Empleado;
use Entidades\Contenedor;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

Contenedor::set('logger', function () { //historial del proyecto
    $logger = new Logger(__CONFIG__['log']['channel']); //nombre
    $file_handler = new StreamHandler(__CONFIG__['log']['path'] . date('Ymd') . '.log'); //lugar
    $logger->pushHandler($file_handler); //crear fichero
    return $logger;
});

if (isset($_POST['modelo'])) {
    if ($_POST['modelo'] == 'listar') {
        $em_LN = new LN_empleado();
        $result = $em_LN->buscar_listar($_POST['dni']);
        die(json_encode($result));
    }
    if ($_POST['modelo'] == 'agregar') {
        $em_LN = new LN_empleado();
        $em = new Empleado();
        $em->dni = $_POST['persona'];
        $em->email = $_POST['email'];
        $em->clave = password_hash($_POST['password'], PASSWORD_DEFAULT, ['cost' => 12]);
        $em_LN->empleado_agregar($em);
        $respuesta = array(
            'respuesta' => 'exito',
            'tipo' => 'Agregado',
        );
        die(json_encode($respuesta));
    }
    if ($_POST['modelo'] == 'editar') {
        $em_LN = new LN_empleado();
        $em = new Empleado();
        $em->dni = $_POST['dni_editar'];
        $em->email = $_POST['email'];
        if (!empty($_POST['password'])) {
            $em->clave = password_hash($_POST['password'], PASSWORD_DEFAULT, ['cost' => 12]);
        }
        $em_LN->empleado_editar($em);
        $respuesta = array(
            'respuesta' => 'exito',
            'tipo' => 'Modificado',
        );
        die(json_encode($respuesta));
    }
    if ($_POST['modelo'] == 'eliminar') {
        $em_LN = new LN_empleado();
        $em_LN->empleado_eliminar($_POST['dni']);
    }
} else {
    header('location: 404.html');
    die();
}
