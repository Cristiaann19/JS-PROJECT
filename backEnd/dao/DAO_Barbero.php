<?php
require_once(__DIR__ . '/../conexionBD_MySQL.php');
require_once(__DIR__ . '/../modelos/Barbero.php');

class DAO_Barbero {
    //Agregar un nuevo barbero
    public function agregarNuevoBarbero($barbero) {
        $conexion = conexionPHP();

        $sqlEmpleado = "INSERT INTO EMPLEADO 
            (nombreEmpleado, dni, apellidoPaternoE, apellidoMaternoE, telefono, salario, cargo, estadoE, generoE) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmtEmp = mysqli_prepare($conexion, $sqlEmpleado);
        if (!$stmtEmp) {
            throw new Exception("Error al preparar la consulta EMPLEADO: " . mysqli_error($conexion));
        }

        $nombre = $barbero->getNombreE();
        $dni = $barbero->getDNIempleado();
        $apellidoP = $barbero->getApellidoPaternoE();
        $apellidoM = $barbero->getApellidoMaternoE();
        $telefono = $barbero->getTelefono();
        $salario = $barbero->getSalario();
        $cargo = $barbero->getCargo();
        $estado = $barbero->getEstadoEmpleado();
        $genero = $barbero->getGenero();

        mysqli_stmt_bind_param(
            $stmtEmp,
            "sssssdsss",
            $nombre,
            $dni,
            $apellidoP,
            $apellidoM,
            $telefono,
            $salario,
            $cargo,
            $estado,
            $genero
        );

        mysqli_stmt_execute($stmtEmp);

        $idEmpleado = mysqli_insert_id($conexion);

        $sqlBarbero = "INSERT INTO BARBERO (idEmpleado, especialidad) VALUES (?, ?)";
        $stmtBarbero = mysqli_prepare($conexion, $sqlBarbero);
        if (!$stmtBarbero) {
            throw new Exception("Error al preparar la consulta BARBERO: " . mysqli_error($conexion));
        }

        $especialidad = $barbero->getEspecialidad();

        mysqli_stmt_bind_param(
            $stmtBarbero,
            "is",
            $idEmpleado,
            $especialidad
        );

        mysqli_stmt_execute($stmtBarbero);

        return $idEmpleado; 
    }
}
?>
