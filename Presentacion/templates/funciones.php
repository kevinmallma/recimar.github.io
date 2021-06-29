<?php

//validar datos
function validar(string $cadena): string
{
    $cadena = trim($cadena); //eliminar espacios
    while (strpos($cadena, "  ") > 0) {
        $cadena = str_replace("  ", " ", $cadena);
    }
    return $cadena;
}

//validar si en kgm o docena
function tipo_und(string $und, float $peso, float $precio): float
{
    $total = 0.00;
    if ($und == 'DOC') {
        $total = $peso * ($precio / 12);
    } else {
        $total = $peso * $precio; //multiplicar
    }
    return $total;
}

//fechas formato
function fecha_formato($fecha) : string
{
    $fch = explode("/", $fecha);
    $tfecha = $fch[2] . "-" . $fch[1] . "-" . $fch[0];
    return $tfecha;
}

function validateDate($date, $format = 'Y-m-d') : bool
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}
