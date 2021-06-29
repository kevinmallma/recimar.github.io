<?php

declare(strict_types=1); //Respetar los tipos de variables
namespace Dao; //ordenamiento
use Dao\Dao_conexion; //importaciones
use Entidades\Cliente;
use PDO;

class Dao_cliente
{
    private $db;
    public function __construct()
    {
        $this->db = Dao_conexion::get(); //obtener la conexion
    }

    //listar-buscar
    public function buscar_listar(string $dni): array
    { //devuele un array
        $result = array(); //creamos el array
        $stm = $this->db->prepare('call B_cliente_bus_lis(:dni)'); //consulta
        $stm->execute(['dni' => $dni]); //parametro
        $result = $stm->fetchAll(PDO::FETCH_CLASS, '\\Entidades\\Cliente');
        $this->db = Dao_conexion::desconect(); //cerrar conexion
        return $result;
    }

    //agregar cliente
    public function cliente_agregar(Cliente $cli): void
    { //retorna solo true o false
        $stm = $this->db->prepare('call B_cliente_agregar(:dni,:nombres,:paterno,:materno,:celular)'); //consulta
        //estabeciendo datos
        $stm->execute([
            'dni' => $cli->dni,
            'nombres' => $cli->nombres,
            'paterno' => $cli->paterno,
            'materno' => $cli->materno,
            'celular' => $cli->celular
        ]);
        $this->db = Dao_conexion::desconect(); //cerrar conexion
    }

    //editar cliente
    public function cliente_editar(Cliente $cli): void
    {
        $stm = $this->db->prepare('call B_cliente_editar(:dni,:nombres,:paterno,:materno,:celular,:id)'); //consulta
        $stm->execute([
            'dni' => $cli->dni,
            'nombres' => $cli->nombres,
            'paterno' => $cli->paterno,
            'materno' => $cli->materno,
            'celular' => $cli->celular,
            'id' => $cli->id
        ]);
        $this->db = Dao_conexion::desconect(); //cerrar conexion
    }

    //eliminar cliente
    public function cliente_eliminar(int $dni): void
    {
        $stm = $this->db->prepare('call B_cliente_eliminar(:dni)'); //consulta
        $stm->execute(['dni' => $dni]);
        $this->db = Dao_conexion::desconect(); //cerrar conexion
    }
}
