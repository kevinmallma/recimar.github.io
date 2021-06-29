<?php

declare(strict_types=1); //Respetar los tipos de variables
namespace Dao; //ordenamiento
use Dao\Dao_conexion; //importaciones
use Entidades\Material;
use PDO;

class Dao_material
{
    private $db;
    public function __construct()
    {
        $this->db = Dao_conexion::get(); //obtener la conexion
    }

    //listar-buscar
    public function buscar_listar(string $id): array
    { //devuele un array
        $result = array(); //creamos el array
        $stm = $this->db->prepare('call C_material_bus_lis(:id)'); //consulta
        $stm->execute(['id' => $id]); //parametro
        $result = $stm->fetchAll(PDO::FETCH_CLASS, '\\Entidades\\Material');
        $this->db = Dao_conexion::desconect(); //cerrar conexion
        return $result;
    }

    //agregar material
    public function material_agregar(Material $ma): void
    { //no retorna nada
        $stm = $this->db->prepare('call C_material_agregar(:nombre,:und,:precio_c1,:precio_c2,:precio_c3)'); //consulta
        $stm->execute([ //estabeciendo datos
            'nombre' => $ma->nombre,
            'und' => $ma->und,
            // 'precio_v1' => $ma->precio_v1,
            'precio_c1' => $ma->precio_c1,
            'precio_c2' => $ma->precio_c2,
            'precio_c3' => $ma->precio_c3
        ]);
        $this->db = Dao_conexion::desconect(); //cerrar conexion
    }

    //editar material
    public function material_editar(Material $ma): void
    { //no retona nada
        $stm = $this->db->prepare('call C_material_editar(:nombre,:und,:precio_c1,:precio_c2,:precio_c3,:id)'); //consulta
        $stm->execute([ //estabeciendo datos
            'nombre' => $ma->nombre,
            'und' => $ma->und,
            // 'precio_v1' => $ma->precio_v1,
            'precio_c1' => $ma->precio_c1,
            'precio_c2' => $ma->precio_c2,
            'precio_c3' => $ma->precio_c3,
            'id' => $ma->id
        ]);
        $this->db = Dao_conexion::desconect(); //cerrar conexion
    }

    //eliminar material
    public function material_eliminar(int $id): void
    { //no retona nada
        $stm = $this->db->prepare('call C_material_eliminar(:id)'); //consulta
        $stm->execute(['id' => $id]);
        $this->db = Dao_conexion::desconect(); //cerrar conexion
    }
}
