<?php
require_once '../config.php'; //importaciones
use LogicaNegocio\LN_compra;
use Entidades\Compra;
use Entidades\Contenedor;
use Entidades\DT_compra;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Mike42\Escpos\CapabilityProfile;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\Printer;

Contenedor::set('logger', function () { //historial del proyecto
    $logger = new Logger(__CONFIG__['log']['channel']); //nombre
    $file_handler = new StreamHandler(__CONFIG__['log']['path'] . date('Ymd') . '.log'); //lugar
    $logger->pushHandler($file_handler); //crear fichero
    return $logger;
});

if (isset($_POST['modelo_compra'])) {
    session_start();
    if ($_POST['modelo_compra'] == 'codigo') { //Obtner codigo de la compra -- inicio
        $com = new LN_compra();
        $result = $com->codigo();
        die(json_encode($result));
    }
    if ($_POST['modelo_compra'] == 'listar') { //Listar compra -- inicio
        $json = array();
        if (isset($_SESSION['carrito_compra'])) { //si existe
            $datos = $_SESSION['carrito_compra'];
            for ($i = 0; $i < count($datos); $i++) { //llenar el array objetos
                $json[] = array(
                    'id_material' => $datos[$i]['id_material'],
                    'material' => $datos[$i]['material'],
                    'peso' => $datos[$i]['peso'],
                    'und' => $datos[$i]['und'],
                    'precio' => $datos[$i]['precio'],
                    'sub_total' => $datos[$i]['sub_total']
                );
            }
            die(json_encode($json)); //json
        } else {
            die(json_encode($json)); // vacio
        }
    }
    if ($_POST['modelo_compra'] == 'agregar') { //agregar a la tabla y al array -- inicio
        if (isset($_SESSION['carrito_compra'])) { //si existe
            $arreglo = $_SESSION['carrito_compra'];
            $encontro = false;
            $numero = 0;
            for ($i = 0; $i < count($arreglo); $i++) { //recorrer array
                if ($arreglo[$i]['id_material'] == $_POST['id_material']) { //si existe en el array para sumar
                    $encontro = true;
                    $numero = $i;
                }
            }
            if ($encontro == true) { //lo ecnontro - material existente
                $suma = (float) $arreglo[$numero]['peso'] + (float) $_POST['peso']; //sumar los pesos
                $arreglo[$numero]['peso'] = bcdiv((string) $suma, '1', 2);
                $arreglo[$numero]['sub_total'] =  bcdiv((string) tipo_und($arreglo[$numero]['und'], (float) $arreglo[$numero]['peso'], (float)$arreglo[$numero]['precio']), '1', 1) . '0';
                $_SESSION['carrito_compra'] = $arreglo; //llenar el array con los nuevos objetos
            } else { //no es el mismo material
                $peso = strpos($_POST['peso'], ".") ? bcdiv($_POST['peso'], '1', 2) : $_POST['peso'] . ".00"; //cero
                $arreglonuevo = array( //llenar el array
                    'id_material' => $_POST['id_material'],
                    'material' => $_POST['material'],
                    'peso' => $peso,
                    'und' => $_POST['und'],
                    'precio' => $_POST['check_precio'],
                    'sub_total' => bcdiv((string) tipo_und($_POST['und'], (float) $_POST['peso'], (float)$_POST['check_precio']), '1', 1) . '0'
                );
                array_push($arreglo, $arreglonuevo);
                $_SESSION['carrito_compra'] = $arreglo; //llenar el array con los nuevos objetos
            }
        } else { //primer dato agregado
            $peso = strpos($_POST['peso'], ".") ? bcdiv($_POST['peso'], '1', 2) : $_POST['peso'] . ".00"; //cero
            $arreglo[] = array( //llenar el arrat
                'id_material' => $_POST['id_material'],
                'material' => $_POST['material'],
                'peso' => $peso,
                'und' => $_POST['und'],
                'precio' => $_POST['check_precio'],
                'sub_total' => bcdiv((string) tipo_und($_POST['und'], (float) $_POST['peso'], (float)$_POST['check_precio']), '1', 1) . '0'
            );
            $_SESSION['carrito_compra'] = $arreglo; //registrar la compra en sesion para poder usar el cualquier lado
        }
    }
    if ($_POST['modelo_compra'] == 'editar_peso') { //editar peso
        $arreglo = $_SESSION['carrito_compra']; //pasar los datos
        $numero = 0;
        for ($i = 0; $i < count($arreglo); $i++) { //recorrer array
            if ($arreglo[$i]['id_material'] == $_POST['id']) { //si existe en el array
                $numero = $i;
            }
        }
        $arreglo[$numero]['peso'] = bcdiv($_POST['numero'], '1', 2);
        $arreglo[$numero]['sub_total'] =  bcdiv((string) tipo_und($arreglo[$numero]['und'], (float) $arreglo[$numero]['peso'], (float)$arreglo[$numero]['precio']), '1', 1) . '0';
        $_SESSION['carrito_compra'] = $arreglo; //modificar peso
    }
    if ($_POST['modelo_compra'] == 'editar_precio') { //editar precio
        $arreglo = $_SESSION['carrito_compra']; //pasar los datos
        $numero = 0;
        for ($i = 0; $i < count($arreglo); $i++) { //recorrer array
            if ($arreglo[$i]['id_material'] == $_POST['id']) { //si existe en el array
                $numero = $i;
            }
        }
        $arreglo[$numero]['precio'] = bcdiv($_POST['numero'], '1', 2);
        $arreglo[$numero]['sub_total'] =  bcdiv((string) tipo_und($arreglo[$numero]['und'], (float) $arreglo[$numero]['peso'], (float)$arreglo[$numero]['precio']), '1', 1) . '0';
        $_SESSION['carrito_compra'] = $arreglo; //modificar precio
    }
    if ($_POST['modelo_compra'] == 'eliminar') {
        $arreglo = $_SESSION['carrito_compra'];
        for ($i = 0; $i < count($arreglo); $i++) {
            if ($arreglo[$i]['id_material'] != $_POST['id']) { //bucar el id a eliminar
                $datosNuevos[] = array( //pasar a un nuevo array sin el id a eliminar
                    'id_material' => $arreglo[$i]['id_material'],
                    'material' => $arreglo[$i]['material'],
                    'peso' => $arreglo[$i]['peso'],
                    'und' => $arreglo[$i]['und'],
                    'precio' => $arreglo[$i]['precio'],
                    'sub_total' => $arreglo[$i]['sub_total']
                );
            }
        }
        if (isset($datosNuevos)) { //si hay datos
            $_SESSION['carrito_compra'] = $datosNuevos; //pasar lo dayos
        } else { //el ultimo a eliminar
            unset($_SESSION['carrito_compra']); //eliminar
        }
    }
    if ($_POST['modelo_compra'] == 'cancelar') {
        unset($_SESSION['carrito_compra']); //eliminar carrito
    }
    if ($_POST['modelo_compra'] == 'guardar') { //guardar compra
        $dni = empty($_POST['dni_cliente']) ? "00000000" : $_POST['dni_cliente'];
        $now = date('Y-m-d H:i:s');
        $LN_com = new LN_compra();
        $com = new Compra;
        $com->codigo = $_POST['codigo'];
        $com->dni_cliente = $dni;
        $com->dni_empleado = $_SESSION['dni_empleado'];
        $com->fecha_hora = $now;
        $com->mensaje = $_POST['mensaje'];
        $com->descuento = empty($_POST['descuento']) ? 0.00 : $_POST['descuento'];
        $com->total = $_POST['total'];
        $arreglo = $_SESSION['carrito_compra']; //pasar los datos
        for ($i = 0; $i < count($arreglo); $i++) { //pasar todos los datos
            $dt = new DT_compra(); //datos del detalle
            $dt->id_material = $arreglo[$i]['id_material'];
            $dt->peso = $arreglo[$i]['peso'];
            $dt->precio = $arreglo[$i]['precio'];
            $dt->sub_total = $arreglo[$i]['sub_total'];
            $com->dt_compra[] = $dt;
        }
        $LN_com->agregar_compra($com); //agregar compra
        //imprimir ticket
        $profile = CapabilityProfile::load("simple");
        $conector = new WindowsPrintConnector("TM-m30");
        $printer = new Printer($conector, $profile);
        $printer->setJustification(Printer::JUSTIFY_CENTER); //centrar
        try {
            $logo = EscposImage::load("imagenes/ticket.png", false);
            $printer->bitImage($logo);
        } catch (Exception $e) {/* No hacemos nada si hay error */
        }
        //datos de la empresa 
        $printer->text("E.I.R.L." . "\n" . "\n");
        $printer->text("Direccion: Prolong. ANDRES RAZURY 6TA. CUADRA" . "\n");
        $printer->text("(ESPALDA DE LA VIDENA - CEL: 922 171 426)" . "\n");
        //imprsion de la compra
        $printer->text('Codigo: ' . $_POST['codigo'] . "\n");
        $printer->text('Fecha-Hora: ' . $now . "\n");
        if (!empty($_POST['cliente'])) {
            $printer->text('Cliente: ' . $_POST['cliente'] . "\n");
        }
        //detalle de la fecha
        //$printer->text("MATERIAL CANTIDAD UND PRECIO SUB_TOTAL\n");
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text(str_pad('MATERIAL', 17) . str_pad('CANTIDAD', 9) . str_pad('UND', 5) . str_pad('PRECIO', 8) . str_pad('SUBTOTAL', 9, " ", STR_PAD_LEFT) . "\n");
        $printer->text("------------------------------------------------" . "\n");
        for ($i = 0; $i < count($arreglo); $i++) {
            if ($arreglo[$i]['und'] == 'DOC') {
                $printer->text(str_pad($arreglo[$i]['material'], 16) . str_pad($arreglo[$i]['peso'], 9, " ", STR_PAD_LEFT) . ' ' . str_pad($arreglo[$i]['und'], 3) . '  ' . str_pad($arreglo[$i]['precio'], 6, " ", STR_PAD_LEFT) . ' =' . str_pad($arreglo[$i]['sub_total'], 9, " ", STR_PAD_LEFT) . "\n");
            } else {
                $printer->text(str_pad($arreglo[$i]['material'], 16) . str_pad($arreglo[$i]['peso'], 9, " ", STR_PAD_LEFT) . ' ' . str_pad($arreglo[$i]['und'], 3) . ' x' . str_pad($arreglo[$i]['precio'], 6, " ", STR_PAD_LEFT) . ' =' . str_pad($arreglo[$i]['sub_total'], 9, " ", STR_PAD_LEFT) . "\n");
            }
        }
        $printer->text("------------------------------------------------" . "\n");
        $printer->setJustification(Printer::JUSTIFY_CENTER); //centrar
        if (!empty($_POST['descuento'])) {
            $printer->text('DESCUENTO: ' . $_POST['descuento'] . "\n");
            $printer->text("------------------------------------------------" . "\n");
        }
        $printer->setTextSize(2, 2);
        $printer->text('TOTAL: ' . $_POST['total'] . "\n" . "\n");
        $printer->setTextSize(1, 1);
        if (!empty($_POST['mensaje'])) {
            $printer->text('PDTA: ' . $_POST['mensaje'] . "\n");
        }
        $printer->text("Muchas gracias por su preferencia\n");
        $printer->text("(vuelva pronto)");
        $printer->feed(3);
        $printer->cut();
        $printer->pulse();
        $printer->close();
        unset($_SESSION['carrito_compra']); //destruir
    }
    if ($_POST['modelo_compra'] == 'buscar') { //buscar compra
        $com = new LN_compra();
        $result = $com->buscar_compra($_POST['codigo']);
        die(json_encode($result));
    }
    if ($_POST['modelo_compra'] == 'imprimir') {
        //imprimir ticket
        $profile = CapabilityProfile::load("simple");
        $conector = new WindowsPrintConnector("TM-m30");
        $printer = new Printer($conector, $profile);
        $printer->setJustification(Printer::JUSTIFY_CENTER); //centrar
        try {
            $logo = EscposImage::load("imagenes/ticket.png", false);
            $printer->bitImage($logo);
        } catch (Exception $e) {/* No hacemos nada si hay error */
        }
        //datos de la empresa 
        $printer->text("E.I.R.L." . "\n" . "\n");
        $printer->text("Direccion: Prolong. ANDRES RAZURY 6TA. CUADRA" . "\n");
        $printer->text("(ESPALDA DE LA VIDENA - CEL: 922 171 426)" . "\n");
        $com = new LN_compra();
        $result = $com->buscar_compra($_POST['codigo']);
        //imprsion de la compra
        $printer->text('Codigo: ' . $result->codigo . "\n");
        $printer->text('Fecha-Hora: ' . $result->fecha_hora . "\n");
        if (!empty($result->cliente[0]->nombres)) {
            $printer->text('Cliente: ' . $result->cliente[0]->nombres . ' ' . $result->cliente[0]->paterno . ' ' . $result->cliente[0]->materno . "\n");
        }
        //detalle de la fecha
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text(str_pad('MATERIAL', 17) . str_pad('CANTIDAD', 9) . str_pad('UND', 5) . str_pad('PRECIO', 8) . str_pad('SUBTOTAL', 9, " ", STR_PAD_LEFT) . "\n");
        $printer->text("------------------------------------------------" . "\n");
        //DETALLE DE LA COMPRA
        foreach ($result->dt_compra as $re) {
            if ($re->material[0]->und == 'DOC') {
                $printer->text(str_pad($re->material[0]->nombre, 16) . str_pad($re->peso, 9, " ", STR_PAD_LEFT) . ' ' . str_pad($re->material[0]->und, 3) . '  ' . str_pad($re->precio, 6, " ", STR_PAD_LEFT) . ' =' . str_pad($re->sub_total, 9, " ", STR_PAD_LEFT) . "\n");
            } else {
                $printer->text(str_pad($re->material[0]->nombre, 16) . str_pad($re->peso, 9, " ", STR_PAD_LEFT) . ' ' . str_pad($re->material[0]->und, 3) . ' x' . str_pad($re->precio, 6, " ", STR_PAD_LEFT) . ' =' . str_pad($re->sub_total, 9, " ", STR_PAD_LEFT) . "\n");
            }
        }
        $printer->text("------------------------------------------------" . "\n");
        $printer->setJustification(Printer::JUSTIFY_CENTER); //centrar
        if ($result->descuento != '0.00') {
            $printer->text('DESCUENTO: ' . $result->descuento . "\n");
            $printer->text("------------------------------------------------" . "\n");
        }
        $printer->setTextSize(2, 2);
        $printer->text('TOTAL: ' . $result->total . "\n" . "\n");
        $printer->setTextSize(1, 1);
        if (!empty($result->mensaje)) {
            $printer->text('PDTA: ' . $result->mensaje . "\n");
        }
        $printer->text("Muchas gracias por su preferencia\n");
        $printer->text("(vuelva pronto)");
        $printer->feed(3);
        $printer->cut();
        $printer->pulse();
        $printer->close();
    }
    if ($_POST['modelo_compra'] == 'reporte') { //reporte de compra
        $com = new LN_compra();
        $usuario = empty($_POST['usuario']) ? '%' : $_POST['usuario'];
        $result = $com->reporte_compra(fecha_formato($_POST['fecha_ini']), fecha_formato($_POST['fecha_fin']), $usuario);
        die(json_encode($result));
    }
} else {
    header('location: 404.html');
    die();
}
