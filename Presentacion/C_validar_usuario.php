<?php
require_once '../config.php'; //importaciones
use LogicaNegocio\LN_empleado;
use Entidades\Contenedor;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

Contenedor::set('logger', function () { //historial del proyecto
    $logger = new Logger(__CONFIG__['log']['channel']); //nombre
    $file_handler = new StreamHandler(__CONFIG__['log']['path'] . date('Ymd') . '.log'); //lugar
    $logger->pushHandler($file_handler); //crear fichero
    return $logger;
});

if (isset($_POST['tipo'])) {
    $dato = 'noexiste';
    $em_LN= new ln_empleado(); //intancia
    if ($_POST['tipo'] == 'dni') { //tipo dni
        $result = $em_LN->buscar_listar($_POST['valor']);
        if (!empty($result)) : // comprobar que no este vacio
            $dato = 'existe';
        endif;
    } else if ($_POST['tipo'] == 'email') { //tipo email
        if (empty($_POST['valor'])) { //agregar email
            $result = $em_LN->buscar_listar();
            foreach ($result as $email) {
                if ($email->email == $_POST['email']) { //vereficar que no exista el email 
                    $dato = 'existe';
                }
            }
        } else { //editar email
            $result = $em_LN->buscar_listar($_POST['valor']);
            foreach ($result as $email) {
                if ($email->email !== $_POST['email']) { //comparo si inserta el mismo email
                    $em_LN = new ln_empleado(); //intancia
                    $result = $em_LN->buscar_listar();
                    foreach ($result as $email) { //vereficar que no exista el email 
                        if ($email->email == $_POST['email']) {
                            $dato = 'existe';
                        }
                    }
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
