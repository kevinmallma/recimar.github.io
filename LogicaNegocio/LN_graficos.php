<?php

declare(strict_types=1); //Respetar los tipos de variables
namespace LogicaNegocio; //ordenamiento
use Dao\Dao_graficos; //importaciones
use Entidades\Contenedor;
use PDOException;

class LN_graficos
{
    private $dao_graficos;
    public function __construct()
    {
        $this->dao_graficos = new Dao_graficos();
        $this->logger = Contenedor::get('logger'); //historial
    }

    //total de años de compras
    public function anios_compra(): array
    {
        $result = array(); //creamos el array
        try {
            $result = $this->dao_graficos->anios_compra(); //datos
        } catch (PDOException $ex) {
            $this->logger->error($ex->getMessage()); //error
        }
        return $result;
    }

    //total por mes de cada año
    public function suma_anios_compra(string $mes, string $anio): array
    {
        $result = array(); //creamos el array
        try {
            $result = $this->dao_graficos->suma_anios_compra($mes, $anio); //datos
        } catch (PDOException $ex) {
            $this->logger->error($ex->getMessage()); //error
        }
        return $result;
    }

    //total peso del material por mes de cada año
    public function suma_anios_material(int $id, string $mes, string $anio): array
    {
        $result = array(); //creamos el array
        try {
            $result = $this->dao_graficos->suma_anios_material($id, $mes, $anio); //datos
        } catch (PDOException $ex) {
            $this->logger->error($ex->getMessage()); //error
        }
        return $result;
    }
}
