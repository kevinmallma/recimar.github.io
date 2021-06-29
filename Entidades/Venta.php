<?php

namespace Entidades; //orden

class Venta {

    public $id;
    public $codigo;
    
    //cliente
    public $dni_cliente;
    public $comprador;
    
    //empleado
    public $dni_empleado;
    public $empleado;
    
    public $fecha_hora;
    public $total;
    
    //detalle
    public $dt_venta = [];

}

