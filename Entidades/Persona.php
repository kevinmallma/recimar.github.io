<?php

namespace Entidades;

abstract class Persona
{ //solo se puede usar por la herencia
    public $id; 
    public $dni;
    public $nombres;
    public $paterno;
    public $materno;
    public $celular;
}
