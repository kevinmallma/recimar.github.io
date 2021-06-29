<?php

declare(strict_types=1); //Respetar los tipos de variables
namespace Dao; //ordenamiento
use Dao\Dao_conexion; //importaciones
use Entidades\Compra;
use PDO;

class Dao_compra
{
    private $db;
    public function __construct()
    {
        $this->db = Dao_conexion::get(); //obtener la conexion
    }

    //codigo
    public function codigo(): ?Compra
    { //retorna null
        $result = null;

        $stm = $this->db->prepare('call D_compra_codigo()'); //consulta
        $stm->execute(); //parametro

        $data = $stm->fetchObject('\\Entidades\\Compra');
        if ($data) {
            $result = $data;
        }

        return $result;
    }

    //agregar compra
    public function agregar_compra(Compra $com): void
    {
        $stm = $this->db->prepare('call E_compra_agregar(:codigo,:dni_cliente,:dni_empleado,:fecha_hora,:mensaje,:descuento,:total)'); //consulta
        //estabeciendo datos
        $stm->execute([
            'codigo' => $com->codigo,
            'dni_cliente' => $com->dni_cliente,
            'dni_empleado' => $com->dni_empleado,
            'fecha_hora' => $com->fecha_hora,
            'mensaje' => $com->mensaje,
            'descuento' => $com->descuento,
            'total' => $com->total,
        ]);
        $this->db = Dao_conexion::desconect(); //cerrar conexion
    }

    //buscar compra
    public function buscar_compra(string $codigo): ?Compra
    {
        $result = null;
        $stm = $this->db->prepare('call E_compra_buscar(:codigo)'); //consulta
        $stm->execute(['codigo' => $codigo]); //parametros
        $this->db = Dao_conexion::desconect(); //cerrar conexion
        $data = $stm->fetchObject('\\Entidades\\Compra');
        if ($data) {
            $result = $data;
        }
        return $result;
    }

    //reporte compra
    public function reporte_compra(string $fecha_ini, string $fecha_fin, string $dni): array
    {
        $result = array(); //creamos el array
        $stm = $this->db->prepare('call E_compra_reporte(:fecha_ini,:fecha_fin,:dni)'); //consulta
        $stm->execute([
            'fecha_ini' => $fecha_ini,
            'fecha_fin' => $fecha_fin,
            'dni' => $dni
        ]);
        $result = $stm->fetchAll(PDO::FETCH_CLASS, '\\Entidades\\Compra');
        $this->db = Dao_conexion::desconect(); //cerrar conexion
        return $result;
    }
}
