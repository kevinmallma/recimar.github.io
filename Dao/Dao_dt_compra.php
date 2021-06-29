<?php

declare(strict_types=1); //validar los parametros de entrada

namespace Dao; //ordenamiento

use PDO;
use Dao\Dao_conexion;

class Dao_dt_compra
{

    private $_db;
    public function __construct()
    { //constructor
        $this->_db = Dao_conexion::get(); //obtener la conexion
    }

    public function agregar_dt_compra(string $codigo_compra, array $compra): void
    {
        foreach ($compra as $dt) { //recooro todo hasta agregar todp el array
            $stm = $this->_db->prepare('call F_dtcompra_agregar(:codigo_compra,:id_material,:peso,:precio,:sub_total)'); //consulta
            //estabeciendo datos
            $stm->execute([
                'codigo_compra' => $codigo_compra,
                'id_material' => $dt->id_material,
                'peso' => $dt->peso,
                'precio' => $dt->precio,
                'sub_total' => $dt->sub_total
            ]);
        }
        $this->_db = Dao_conexion::desconect(); //cerrar conexion
    }

    //buscar detalle de la compra
    public function buscar_dt_compra(string $codigo): array
    {
        $stm = $this->_db->prepare('call F_dtcompra_buscar(:codigo)'); //consulta
        $stm->execute(['codigo' => $codigo]);
        return $stm->fetchAll(PDO::FETCH_CLASS, '\\Entidades\\DT_compra');
    }
}
