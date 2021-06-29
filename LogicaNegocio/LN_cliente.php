<?php

declare(strict_types=1); //Respetar los tipos de variables
namespace LogicaNegocio; //ordenamiento
use Dao\Dao_cliente; //importaciones
use Entidades\Cliente;
use Entidades\Contenedor;
use PDOException;

class LN_cliente
{
    private $dao_cliente;
    public function __construct()
    {
        $this->dao_cliente = new Dao_cliente();
        $this->logger = Contenedor::get('logger'); //historial
    }

    //listar-buscar
    public function buscar_listar(string $dni = ''): array
    { //devuele un array
        $result = array(); //creamos el array
        try {
            $result = $this->dao_cliente->buscar_listar($dni); //datos
        } catch (PDOException $ex) {
            $this->logger->error($ex->getMessage()); //error
        }
        return $result;
    }

    //agregar cliente
    public function cliente_agregar(Cliente $cli): void
    {
        try {
            $this->dao_cliente->cliente_agregar($cli); //agregar
            $this->logger->info('cliente agregado con dni:' . $cli->dni); //historial
        } catch (PDOException $ex) {
            $this->logger->error($ex->getMessage()); //error
        }
    }

    //editar cliente
    public function cliente_editar(Cliente $cli): void
    {
        try {
            $this->dao_cliente->cliente_editar($cli); //editar
            $this->logger->info('cliente editado con dni: ' . $cli->dni); //historial
        } catch (PDOException $ex) {
            $this->logger->error($ex->getMessage()); //error
        }
    }

     //eliminar cliente
     public function cliente_eliminar(int $dni): void
     {
        try {
            $this->dao_cliente->cliente_eliminar($dni); //eliminar
            $this->logger->info('cliente eliminado con dni:' . $dni); //historial
        } catch (PDOException $ex) {
            $this->logger->error($ex->getMessage()); //error
        }
     }
}
