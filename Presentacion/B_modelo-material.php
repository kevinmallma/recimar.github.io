<?php
require_once '../config.php'; //importaciones
use LogicaNegocio\LN_material;
use Entidades\Material;
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
    if ($_POST['modelo'] == 'listar') { //listar
        $ma_LN = new LN_material();
        $result = $ma_LN->buscar_listar($_POST['id']);
        die(json_encode($result));
    }
    if ($_POST['modelo'] == 'agregar') { //agregar
        $ma_LN = new LN_material();
        $ma = new Material();
        $ma->nombre = validar($_POST['nombre']);
        $ma->und = $_POST['und'];
        // $ma->precio_v1 = $_POST['precio_v1'] == '' ? 0.00 : $_POST['precio_v1'];
        $ma->precio_c1 = $_POST['precio_c1'] == '' ? 0.00 : $_POST['precio_c1'];
        $ma->precio_c2 = $_POST['precio_c2'] == '' ? 0.00 : $_POST['precio_c2'];
        $ma->precio_c3 = $_POST['precio_c3'] == '' ? 0.00 : $_POST['precio_c3'];
        $ma_LN->material_agregar($ma);
        $respuesta = array(
            'respuesta' => 'exito',
            'tipo' => 'Agregado',
        );
        die(json_encode($respuesta));
    }
    if ($_POST['modelo'] == 'editar') { //editar
        $ma_LN = new LN_material();
        $ma = new Material();
        $ma->nombre = validar($_POST['nombre']);
        $ma->und = $_POST['und'];
        // $ma->precio_v1 = $_POST['precio_v1'] == '' ? 0.00 : $_POST['precio_v1'];
        $ma->precio_c1 = $_POST['precio_c1'] == '' ? 0.00 : $_POST['precio_c1'];
        $ma->precio_c2 = $_POST['precio_c2'] == '' ? 0.00 : $_POST['precio_c2'];
        $ma->precio_c3 = $_POST['precio_c3'] == '' ? 0.00 : $_POST['precio_c3'];
        $ma->id = $_POST['id'];
        $ma_LN->material_editar($ma);
        $respuesta = array(
            'respuesta' => 'exito',
            'tipo' => 'Modificado',
        );
        die(json_encode($respuesta));
    }
    if ($_POST['modelo'] == 'eliminar') { //editar
        $ma_LN = new LN_material();
        $ma_LN->material_eliminar((int) $_POST['id']);
    }
} else {
    header('location: 404.html');
    die();
}
