<?php

declare(strict_types=1); //Respetar los tipos de variables
namespace Dao; //ordenamiento
use Dao\Dao_conexion; //importaciones
use PDO;

class Dao_graficos
{
    private $db;
    public function __construct()
    {
        $this->db = Dao_conexion::get(); //obtener la conexion
    }

    //total de años de compras
    public function anios_compra(): array
    {
        $result = array(); //creamos el array
        $stm = $this->db->prepare('call E_compra_anio()'); //consulta
        $stm->execute();
        $result = $stm->fetchAll(PDO::FETCH_ASSOC);
        $this->db = Dao_conexion::desconect(); //cerrar conexion
        return $result;
    }

    //total por mes de cada año
    public function suma_anios_compra(string $mes, string $anio): array
    {
        $result = array(); //creamos el array
        $stm = $this->db->prepare('call E_compra_grafico(:mes,:anio)'); //consulta
        $stm->execute([
            'mes' => $mes,
            'anio' => $anio
        ]);
        $result = $stm->fetchAll(PDO::FETCH_ASSOC);
        $this->db = Dao_conexion::desconect(); //cerrar conexion
        return $result;
    }

    //total peso del material por mes de cada año
    public function suma_anios_material(int $id, string $mes, string $anio): array
    {
        $result = array(); //creamos el array
        $stm = $this->db->prepare('call C_materia_com_grafico(:id,:mes,:anio)'); //consulta
        $stm->execute([
            'id' => $id,
            'mes' => $mes,
            'anio' => $anio
        ]);
        $result = $stm->fetchAll(PDO::FETCH_ASSOC);
        $this->db = Dao_conexion::desconect(); //cerrar conexion
        return $result;
    }
}
