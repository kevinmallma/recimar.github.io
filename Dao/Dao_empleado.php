<?php

declare(strict_types=1); //Respetar los tipos de variables
namespace Dao; //ordenamiento
use Dao\Dao_conexion; //importaciones
use Entidades\Empleado;
use PDO;

class Dao_empleado
{
    private $db;
    public function __construct()
    {
        $this->db = Dao_conexion::get(); //obtener la conexion
    }
    //iniciar sesion
    public function iniciar_sesion(string $usuario): ?Empleado
    { //retorna null(error)
        $result = null;
        $stm = $this->db->prepare('call A_empleado_login(:usuario)'); //consulta
        $stm->execute(['usuario' => $usuario]); //parametro
        $data = $stm->fetchObject('\\Entidades\\Empleado'); //obtenemos todo referencia la clase cliente
        $this->db = Dao_conexion::desconect(); //cerrar conexion

        if ($data) { // si hay datos
            $result = $data;
        }
        return $result;
    }

    //listar-buscar
    public function buscar_listar(string $dni): array
    { //devuele un array
        $result = array(); //creamos el array
        $stm = $this->db->prepare('call A_empleado_bus_lis(:dni)'); //consulta
        $stm->execute(['dni' => $dni]); //parametro
        $result = $stm->fetchAll(PDO::FETCH_CLASS, '\\Entidades\\Empleado');
        $this->db = Dao_conexion::desconect(); //cerrar conexion
        return $result;
    }

    //agregar usuario
    public function empleado_agregar(Empleado $em): void
    { //no decuelve nada
        $stm = $this->db->prepare('call A_empleado_agregar(:dni,:email,:clave)'); //consulta
        $stm->execute(['dni' => $em->dni, 'email' => $em->email, 'clave' => $em->clave]); //parametros
        $this->db = Dao_conexion::desconect(); //cerrar conexion
    }

    //editar empleado
    public function empleado_editar(Empleado $em): void
    {
        if (empty($em->clave)) {
            $stm = $this->db->prepare('call A_empleado_editar(:email,:dni)'); //consulta
            $stm->execute(['email' => $em->email, 'dni' => $em->dni]);
        } else {
            $stm = $this->db->prepare('call A_empleado_editar_password(:email, :password, :dni)'); //consulta
            $stm->execute(['email' => $em->email, 'password' => $em->clave, 'dni' => $em->dni]);
        }
        $this->db = Dao_conexion::desconect(); //cerrar conexion
    }

    //eliminar empleado
    public function empleado_eliminar(string $dni): void
    {
        $stm = $this->db->prepare('call A_empleado_eliminar(:dni)'); //consulta
        $stm->execute(['dni' => $dni]);
        $this->db = Dao_conexion::desconect(); //cerrar conexion
    }
}
