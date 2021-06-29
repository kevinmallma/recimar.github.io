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
if (isset($_POST['iniciar_sesion'])) { //si existe
    $email = $_POST['email'];
    $password = $_POST['password'];
    $em_LN = new LN_empleado(); //instancia
    $result = $em_LN->iniciar_sesion($email);
    if (!empty($result)) { //preguntar si esta vacio - email correcto
        if (password_verify($password, $result->clave)) { //comparar las contraseñas cifradas
            session_start();
            $_SESSION['dni_empleado'] = $result->dni;
            $_SESSION['empleado'] = $result->persona;
            $respuesta = array(
                'respuesta' => 'exito',
                'email' => $email,
            );
        } else { //error en la contraseña
            $respuesta = array(
                'respuesta' => 'error',
            );
        }
    } else { //error en el usuario
        $respuesta = array(
            'respuesta' => 'error',
        );
    }
    die(json_encode($respuesta));
} else { // no existe iniciar sesion
    header('location: 404.html');
}
