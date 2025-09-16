<?php
require_once(__DIR__ . '/../conexionBD_MySQL.php');
require_once(__DIR__ . '/../modelos/Empleado.php');

class DAO_Empleado {

    //Agregar un nuevo empleado
    public function agregarNuevoEmpleado($empleado) {
        $conexion = conexionPHP();
        $sql = "INSERT INTO EMPLEADO (nombreEmpleado, dni, apellidoPaternoE, apellidoMaternoE, telefono, salario, cargo, estadoEmpleado) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conexion, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "sssssdss",
            $empleado->getNombreE(),
            $empleado->getDni(),
            $empleado->getApellidoPaternoE(),
            $empleado->getApellidoMaternoE(),
            $empleado->getTelefono(),
            $empleado->getSalario(),
            $empleado->getCargo(),
            $empleado->getEstadoEmpleado()
        );

        return mysqli_stmt_execute($stmt);
    }

    //Listar todos los empleados
    public function listarEmpleados() {
        $conexion = conexionPHP();
        $sql = "SELECT * FROM EMPLEADO";
        $resultado = mysqli_query($conexion, $sql);
        $empleados = [];

        while ($fila = mysqli_fetch_assoc($resultado)) {
            $empleado = new Empleado(
                $fila['nombreEmpleado'],
                $fila['dni'],
                $fila['apellidoPaternoE'],
                $fila['apellidoMaternoE'],
                $fila['telefono'],
                $fila['salario'],
                $fila['cargo'],
                $fila['estadoE']
            );
            $empleados[] = $empleado;
        }

        return $empleados;
    }

    //Buscar un empleado por DNI
    public function buscarPorDni($dni) {
        $conexion = conexionPHP();
        $sql = "SELECT * FROM EMPLEADO WHERE dni = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "s", $dni);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);

        if ($fila = mysqli_fetch_assoc($resultado)) {
            return new Empleado(
                $fila['nombreEmpleado'],
                $fila['dni'],
                $fila['apellidoPaternoE'],
                $fila['apellidoMaternoE'],
                $fila['telefono'],
                $fila['salario'],
                $fila['cargo'],
                $fila['estadoEmpleado']
            );
        }

        return null;
    }

    //Actualizar un empleado por DNI
    public function actualizarEmpleado($empleado, $dni) {
        $conexion = conexionPHP();
        $sql = "UPDATE EMPLEADO SET nombreEmpleado = ?, apellidoPaternoE = ?, apellidoMaternoE = ?, telefono = ?, salario = ?, cargo = ?, estadoEmpleado = ? WHERE dni = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param(
            $stmt,
            "sssssdss",
            $empleado->getNombreE(),
            $empleado->getApellidoPaternoE(),
            $empleado->getApellidoMaternoE(),
            $empleado->getTelefono(),
            $empleado->getSalario(),
            $empleado->getCargo(),
            $empleado->getEstadoEmpleado(),
            $dni
        );

        return mysqli_stmt_execute($stmt);
    }

    //Eliminar un empleado por DNI
    public function eliminarEmpleado($dni) {
        $conexion = conexionPHP();
        $sql = "DELETE FROM EMPLEADO WHERE dni = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "s", $dni);
        return mysqli_stmt_execute($stmt);
    }
}
?>
