<?php

declare(strict_types=1); //Respetar los tipos de variables
namespace LogicaNegocio; //ordenamiento
use Dao\Dao_compra; //importaciones
use Dao\Dao_dt_compra;
use Dao\Dao_empleado;
use Dao\Dao_cliente;
use Dao\Dao_material;
use Dao\Dao_conexion;
use Entidades\Contenedor;
use Entidades\Compra;
use PDOException;

class LN_compra
{
    private $db;
    private $dao_compra;
    private $dao_dt_compra;
    private $dao_cliente;
    private $dao_empleado;
    private $dao_material;
    private $logger; //obtener errores
    public function __construct()
    {
        $this->db = Dao_conexion::get(); //obtener la conexion
        $this->dao_compra = new Dao_compra();
        $this->dao_dt_compra = new Dao_dt_compra();
        $this->dao_cliente = new Dao_cliente();
        $this->dao_empleado = new Dao_empleado();
        $this->logger = Contenedor::get('logger'); //historial
    }

    //codigo
    public function codigo(): ?Compra
    { //retorna null
        $result = null;
        try {
            $result = $this->dao_compra->codigo();
        } catch (PDOException $ex) {
            $this->logger->error($ex->getMessage()); //error
        }
        return $result;
    }

    //agregar compra
    public function agregar_compra(Compra $com): void
    {

        try {
            $this->logger->info('Comenzó creación de la compra');
            $this->db->beginTransaction();

            $this->dao_compra->agregar_compra($com);
            $this->logger->info('Se preparó la compra');

            //detalle
            $this->dao_dt_compra->agregar_dt_compra($com->codigo, $com->dt_compra);
            $this->logger->info('Se creó el detalle de la compra');


            $this->db->commit();
        } catch (PDOException $ex) {
            $this->logger->error($ex->getMessage()); //error
        }
    }

    //buscar compra
    public function buscar_compra(string $codigo): ?Compra
    {
        $result = null;
        try {
            $data = $this->dao_compra->buscar_compra($codigo);
            if ($data) {
                $result = $data;
                //cliente
                $result->cliente = $this->dao_cliente->buscar_listar($result->dni_cliente);

                //empleado
                $result->empleado = $this->dao_empleado->buscar_listar($result->dni_empleado);

                //detalle
                $result->dt_compra = $this->obtener_dt_compra((string) $result->codigo);
            }
        } catch (PDOException $ex) {
            $this->logger->error($ex->getMessage()); //error
        }
        return $result;
    }

    private function obtener_dt_compra(string $codigo): array
    {
        $result = $this->dao_dt_compra->buscar_dt_compra($codigo);

        foreach ($result as $item) {
            $this->dao_material = new Dao_material();
            $item->material = $this->dao_material->buscar_listar($item->id_material);
        }

        return $result;
    }

    //reporte compra
    public function reporte_compra(string $fecha_ini, string $fecha_fin, string $dni = '%'): array
    {
        $result = array(); //creamos el array
        try {
            $result = $this->dao_compra->reporte_compra($fecha_ini, $fecha_fin, $dni);
            foreach ($result as $item) {
                $this->dao_empleado = new Dao_empleado();
                $empleado = $this->dao_empleado->buscar_listar($item->dni_empleado);
                $item->empleado = $empleado[0]->nombres . ' ' . $empleado[0]->paterno . ' ' . $empleado[0]->materno;
                $this->dao_cliente = new Dao_cliente();
                $cliente = $this->dao_cliente->buscar_listar($item->dni_cliente);
                if (!empty($cliente)) {
                    $item->cliente = $cliente[0]->nombres . ' ' . $cliente[0]->paterno . ' ' . $cliente[0]->materno;
                }
            }
        } catch (PDOException $ex) {
            $this->logger->error($ex->getMessage()); //error
        }
        return $result;
    }
}
