<?php

namespace Entidades; //orden

class Compra {

    public $id;
    public $codigo;
    
    //cliente
    public $dni_cliente;
    public $cliente;
    
    //empleado
    public $dni_empleado;
    public $empleado;
    
    public $fecha_hora;
    public $mensaje;
    public $descuento;
    public $total;
    
    //detalle
    public $dt_compra = [];

}
