<?php
require_once '../config.php'; //importaciones
use LogicaNegocio\LN_cliente;
use Entidades\Cliente;
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
        $cli_LN = new LN_cliente();
        $result = $cli_LN->buscar_listar($_POST['dni']);
        die(json_encode($result));
    }
    if ($_POST['modelo'] == 'agregar') { //agregar
        $cli_LN = new LN_cliente();
        $cli = new Cliente();
        $cli->dni = $_POST['dni']; //estabkecer valores
        $cli->nombres = validar($_POST['nombre']);
        $cli->paterno = validar($_POST['paterno']);
        $cli->materno = validar($_POST['materno']);
        $cli->celular = $_POST['celular'] == "" ? 'nulo' : $_POST['celular'];
        $cli_LN->cliente_agregar($cli);
        $respuesta = array(
            'respuesta' => 'exito',
            'tipo' => 'Agregado',
        );
        die(json_encode($respuesta));
    }
    if ($_POST['modelo'] == 'editar') { //editar
        $cli_LN = new LN_cliente();
        $cli = new Cliente();
        $cli->dni = $_POST['dni']; //estabkecer valores
        $cli->nombres = validar($_POST['nombre']);
        $cli->paterno = validar($_POST['paterno']);
        $cli->materno = validar($_POST['materno']);
        $cli->celular = $_POST['celular'] == "" ? 'nulo' : $_POST['celular'];
        $cli->id = $_POST['id'];
        $cli_LN->cliente_editar($cli);
        $respuesta = array(
            'respuesta' => 'exito',
            'tipo' => 'Modificado',
        );
        die(json_encode($respuesta));
    }
    if ($_POST['modelo'] == 'eliminar') { //eliminar
        $cli_LN = new LN_cliente();
        $cli_LN->cliente_eliminar((int)$_POST['dni']);
    }
} else {
    header('location: 404.html');
    die();
}
