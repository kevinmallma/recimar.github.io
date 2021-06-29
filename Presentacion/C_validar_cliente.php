<?php
require_once '../config.php'; //importaciones
use LogicaNegocio\LN_cliente;
use Entidades\Contenedor;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

Contenedor::set('logger', function () { //historial del proyecto
    $logger = new Logger(__CONFIG__['log']['channel']); //nombre
    $file_handler = new StreamHandler(__CONFIG__['log']['path'] . date('Ymd') . '.log'); //lugar
    $logger->pushHandler($file_handler); //crear fichero
    return $logger;
});
if (isset($_POST['dni'])) {
    $dato = 'noexiste';
    $id = (int) $_POST['id'];
    $cli_LN = new LN_cliente();
    if ($id == 0) { //agregar
        $result = $cli_LN->buscar_listar($_POST['dni']);
        if (!empty($result)) { // comprobar que no este vacio
            $dato = 'existe';
        }
    } else { //editar
        $result = $cli_LN->buscar_listar($_POST['dni']);
        foreach ($result as $cliente) {
            if ($cliente->id !== $_POST['id']) { //si inserta el mismo dni
                $cli_LN = new LN_cliente(); //intancia
                $result = $cli_LN->buscar_listar($_POST['dni']);
                if (!empty($result)) { // comprobar que no inserte otro dni igual 
                    $dato = 'existe';
                }
            }
        }
    }
    $respuesta = array(
        'respuesta' => $dato
    );
    die(json_encode($respuesta));
} else {
    header('location: 404.html');
    die();
}
