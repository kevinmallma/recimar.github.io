<?php

declare(strict_types=1); //Respetar los tipos de variables
namespace LogicaNegocio; //ordenamiento
use Dao\Dao_material; //importaciones
use Entidades\Material;
use Entidades\Contenedor;
use PDOException;

class LN_material
{
    private $dao_material;
    public function __construct()
    {
        $this->dao_material = new Dao_material();
        $this->logger = Contenedor::get('logger'); //historial
    }

    //listar-buscar
    public function buscar_listar(string $id = '%'): array
    { //devuele un array
        $result = array(); //creamos el array
        try {
            $result = $this->dao_material->buscar_listar($id); //listar
        } catch (PDOException $ex) {
            $this->logger->error($ex->getMessage()); //error
        }
        return $result;
    }

    //agregar material
    public function material_agregar(Material $ma): void
    { //no retorna nada
        try {
            $this->dao_material->material_agregar($ma); //agregar
            $this->logger->info('material agregado con nombre:' . $ma->nombre); //historial
        } catch (PDOException $ex) {
            $this->logger->error($ex->getMessage()); //error
        }
    }

    //editar material
    public function material_editar(Material $ma): void
    { //no retona nada
        try {
            $this->dao_material->material_editar($ma); //editar
            $this->logger->info('material editado con id:' . $ma->id . '-' . $ma->nombre); //historial
        } catch (PDOException $ex) {
            $this->logger->error($ex->getMessage()); //error
        }
    }

    //eliminar material
    public function material_eliminar(int $id): void
    { //no retona nada
        try {
            $this->dao_material->material_eliminar($id); //eliminar
            $this->logger->info('material eliminado con id:' . $id); //historial
        } catch (PDOException $ex) {
            $this->logger->error($ex->getMessage()); //error
        }
    }
}
