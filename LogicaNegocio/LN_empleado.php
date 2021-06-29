<?php

declare(strict_types=1); //Respetar los tipos de variables
namespace LogicaNegocio; //ordenamiento
use Dao\Dao_empleado; //importaciones
use Entidades\Empleado;
use Entidades\Contenedor;
use PDOException;

class LN_empleado
{
    private $dao_empleado;
    public function __construct()
    {
        $this->dao_empleado = new Dao_empleado();
        $this->logger = Contenedor::get('logger'); //historial
    }
    //iniciar sesion
    public function iniciar_sesion(string $usuario): ?Empleado
    { //retorna null(error)
        $result = null;
        try {
            $result = $this->dao_empleado->iniciar_sesion($usuario); //datos
            if (!empty($result)) { //si no esta vacio
                $this->logger->info('Iniciando sesion con usuario: ' . $usuario); //historial
            }
        } catch (PDOException $ex) {
            $this->logger->error($ex->getMessage()); //error
        }
        return $result;
    }
    
    //listar-buscar
    public function buscar_listar(string $dni = '%'): array
    { //devuele un array
        $result = array(); //creamos el array
        try {
            $result = $this->dao_empleado->buscar_listar($dni); //datos
        } catch (PDOException $ex) {
            $this->logger->error($ex->getMessage()); //error
        }
        return $result;
    }

    //agregar usuario
    public function empleado_agregar(Empleado $em): void
    { //no decuelve nada
        try {
            $this->dao_empleado->empleado_agregar($em);
            $this->logger->info('agregando nuevo usuario:' . $em->email); //historial
        } catch (PDOException $ex) {
            $this->logger->error($ex->getMessage()); //error
        }
    }

    //editar empleado
    public function empleado_editar(Empleado $em): void
    {
        try {
            $this->dao_empleado->empleado_editar($em);
            $this->logger->info('Empleado editado con dni: ' . $em->dni); //historial
        } catch (PDOException $ex) {
            $this->logger->error($ex->getMessage()); //error
        }
    }

    //eliminar empleado
    public function empleado_eliminar(string $dni): void
    {
        try {
            $result = $this->dao_empleado->empleado_eliminar($dni);
            $this->logger->info('Usuario eliminado con dni:' . $dni); //historial
        } catch (PDOException $ex) {
            $this->logger->error($ex->getMessage()); //error
        }
    }
}
