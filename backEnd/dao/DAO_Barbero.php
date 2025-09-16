<?php
require_once "conexionBD_MySQL.php";
require_once "Barbero.php";

class DAO_Barbero {

    //Agregar un nuevo barbero
    public function agregarNuevoBarbero($barbero) {
        $conexion = conexionPHP();

        //Primero insertamos en la tabla EMPLEADO
        $sqlEmpleado = "INSERT INTO EMPLEADO (nombreEmpleado, apellidoPaternoE, apellidoMaternoE, telefono, salario, cargo, estadoEmpleado) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmtEmp = mysqli_prepare($conexion, $sqlEmpleado);
        mysqli_stmt_bind_param(
            $stmtEmp,
            "ssssds s",
            $barbero->getNombreE(),
            $barbero->getApellidoPaternoE(),
            $barbero->getApellidoMaternoE(),
            $barbero->getTelefono(),
            $barbero->getSalario(),
            $barbero->getCargo(),
            $barbero->getEstadoEmpleado()
        );
        mysqli_stmt_execute($stmtEmp);

        $idEmpleado = mysqli_insert_id($conexion);

        // Insertamos en la tabla BARBERO
        $sqlBarbero = "INSERT INTO BARBERO (idEmpleado, especialidad) VALUES (?, ?)";
        $stmtBarbero = mysqli_prepare($conexion, $sqlBarbero);
        mysqli_stmt_bind_param(
            $stmtBarbero,
            "is",
            $idEmpleado,
            $barbero->getEspecialidad()
        );

        return mysqli_stmt_execute($stmtBarbero);
    }

    //Listar todos los barberos
    public function listarBarberos() {
        $conexion = conexionPHP();
        $sql = "SELECT b.idBarbero, e.idEmpleado, e.nombreEmpleado, e.apellidoPaternoE, e.apellidoMaternoE, e.telefono, e.salario, e.cargo, e.estadoEmpleado, b.especialidad
                FROM BARBERO b
                JOIN EMPLEADO e ON b.idEmpleado = e.idEmpleado";
        $resultado = mysqli_query($conexion, $sql);
        $barberos = [];

        while ($fila = mysqli_fetch_assoc($resultado)) {
            $barbero = new Barbero(
                $fila['nombreEmpleado'],
                $fila['apellidoPaternoE'],
                $fila['apellidoMaternoE'],
                $fila['telefono'],
                $fila['salario'],
                $fila['cargo'],
                $fila['estadoEmpleado'],
                $fila['especialidad']
            );
            $barberos[] = $barbero;
        }

        return $barberos;
    }

    //Buscar barbero por ID de empleado
    public function buscarPorIdEmpleado($idEmpleado) {
        $conexion = conexionPHP();
        $sql = "SELECT b.idBarbero, e.idEmpleado, e.nombreEmpleado, e.apellidoPaternoE, e.apellidoMaternoE, e.telefono, e.salario, e.cargo, e.estadoEmpleado, b.especialidad
                FROM BARBERO b
                JOIN EMPLEADO e ON b.idEmpleado = e.idEmpleado
                WHERE e.idEmpleado = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "i", $idEmpleado);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);

        if ($fila = mysqli_fetch_assoc($resultado)) {
            return new Barbero(
                $fila['nombreEmpleado'],
                $fila['apellidoPaternoE'],
                $fila['apellidoMaternoE'],
                $fila['telefono'],
                $fila['salario'],
                $fila['cargo'],
                $fila['estadoEmpleado'],
                $fila['especialidad']
            );
        }

        return null;
    }

    //Actualizar barbero por ID de empleado
    public function actualizarBarbero($barbero, $idEmpleado) {
        $conexion = conexionPHP();

        //Actualizar EMPLEADO
        $sqlEmp = "UPDATE EMPLEADO SET nombreEmpleado = ?, apellidoPaternoE = ?, apellidoMaternoE = ?, telefono = ?, salario = ?, cargo = ?, estadoEmpleado = ? WHERE idEmpleado = ?";
        $stmtEmp = mysqli_prepare($conexion, $sqlEmp);
        mysqli_stmt_bind_param(
            $stmtEmp,
            "ssssds si",
            $barbero->getNombreE(),
            $barbero->getApellidoPaternoE(),
            $barbero->getApellidoMaternoE(),
            $barbero->getTelefono(),
            $barbero->getSalario(),
            $barbero->getCargo(),
            $barbero->getEstadoEmpleado(),
            $idEmpleado
        );
        mysqli_stmt_execute($stmtEmp);

        $sqlBarbero = "UPDATE BARBERO SET especialidad = ? WHERE idEmpleado = ?";
        $stmtBarbero = mysqli_prepare($conexion, $sqlBarbero);
        mysqli_stmt_bind_param(
            $stmtBarbero,
            "si",
            $barbero->getEspecialidad(),
            $idEmpleado
        );

        return mysqli_stmt_execute($stmtBarbero);
    }

    //Eliminar barbero por ID de empleado
    public function eliminarBarbero($idEmpleado) {
        $conexion = conexionPHP();

        $sqlBarbero = "DELETE FROM BARBERO WHERE idEmpleado = ?";
        $stmtBarbero = mysqli_prepare($conexion, $sqlBarbero);
        mysqli_stmt_bind_param($stmtBarbero, "i", $idEmpleado);
        mysqli_stmt_execute($stmtBarbero);

        $sqlEmp = "DELETE FROM EMPLEADO WHERE idEmpleado = ?";
        $stmtEmp = mysqli_prepare($conexion, $sqlEmp);
        mysqli_stmt_bind_param($stmtEmp, "i", $idEmpleado);

        return mysqli_stmt_execute($stmtEmp);
    }
}
?>
