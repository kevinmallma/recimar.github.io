<?php
require_once '../config.php'; //importaciones
use LogicaNegocio\LN_material;
use Entidades\Contenedor;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

Contenedor::set('logger', function () { //historial del proyecto
    $logger = new Logger(__CONFIG__['log']['channel']); //nombre
    $file_handler = new StreamHandler(__CONFIG__['log']['path'] . date('Ymd') . '.log'); //lugar
    $logger->pushHandler($file_handler); //crear fichero
    return $logger;
});

if (isset($_POST['material'])) {
    $dato = 'noexiste';
    $id = (int) $_POST['id'];
    $ma_LN = new LN_material();
    if ($id == 0) { //agregar
        $result = $ma_LN->buscar_listar();
        foreach ($result as $material) {
            if ($material->nombre == $_POST['material']) { //vereficar que no exista el material 
                $dato = 'existe';
            }
        }
    } else { //editars
        $result = $ma_LN->buscar_listar((string) $id);
        foreach ($result as $material) {
            if ($material->nombre !== $_POST['material']) { //comparo si inserta el mismo material
                $ma_LN = new LN_material(); //intancia
                $result = $ma_LN->buscar_listar();
                foreach ($result as $material) { //vereficar que no exista el usuario 
                    if ($material->nombre == $_POST['material']) {
                        $dato = 'existe';
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
