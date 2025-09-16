<?php
require_once "conexionBD_MySQL.php";
require_once "Recepcionista.php";

class DAO_Recepcionista {

    //Agregar un nuevo recepcionista
    public function agregarNuevoRecepcionista($recepcionista) {
        $conexion = conexionPHP();
        
        //Primero insertamos en la tabla EMPLEADO (ya que Recepcionista hereda de Empleado)
        $sqlEmpleado = "INSERT INTO EMPLEADO (nombreEmpleado, apellidoPaternoE, apellidoMaternoE, telefono, salario, cargo, estadoEmpleado) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmtEmp = mysqli_prepare($conexion, $sqlEmpleado);
        mysqli_stmt_bind_param(
            $stmtEmp,
            "ssssdss",
            $recepcionista->getNombreE(),
            $recepcionista->getApellidoPaternoE(),
            $recepcionista->getApellidoMaternoE(),
            $recepcionista->getTelefono(),
            $recepcionista->getSalario(),
            $recepcionista->getCargo(),
            $recepcionista->getEstadoEmpleado()
        );
        mysqli_stmt_execute($stmtEmp);

        $idEmpleado = mysqli_insert_id($conexion);

        $sqlRecep = "INSERT INTO RECEPCIONISTA (idEmpleado, turno) VALUES (?, ?)";
        $stmtRecep = mysqli_prepare($conexion, $sqlRecep);
        mysqli_stmt_bind_param(
            $stmtRecep,
            "is",
            $idEmpleado,
            $recepcionista->getTurno()
        );

        return mysqli_stmt_execute($stmtRecep);
    }

    //Listar todos los recepcionistas
    public function listarRecepcionistas() {
        $conexion = conexionPHP();
        $sql = "SELECT r.idRecepcionista, e.idEmpleado, e.nombreEmpleado, e.apellidoPaternoE, e.apellidoMaternoE, e.telefono, e.salario, e.cargo, e.estadoEmpleado, r.turno
                FROM RECEPCIONISTA r
                JOIN EMPLEADO e ON r.idEmpleado = e.idEmpleado";
        $resultado = mysqli_query($conexion, $sql);
        $recepcionistas = [];

        while ($fila = mysqli_fetch_assoc($resultado)) {
            $recepcionista = new Recepcionista(
                $fila['nombreEmpleado'],
                $fila['apellidoPaternoE'],
                $fila['apellidoMaternoE'],
                $fila['telefono'],
                $fila['salario'],
                $fila['cargo'],
                $fila['estadoEmpleado'],
                $fila['turno']
            );
            $recepcionistas[] = $recepcionista;
        }

        return $recepcionistas;
    }

    //Buscar un recepcionista por ID de empleado
    public function buscarPorIdEmpleado($idEmpleado) {
        $conexion = conexionPHP();
        $sql = "SELECT r.idRecepcionista, e.idEmpleado, e.nombreEmpleado, e.apellidoPaternoE, e.apellidoMaternoE, e.telefono, e.salario, e.cargo, e.estadoEmpleado, r.turno
                FROM RECEPCIONISTA r
                JOIN EMPLEADO e ON r.idEmpleado = e.idEmpleado
                WHERE e.idEmpleado = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "i", $idEmpleado);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);

        if ($fila = mysqli_fetch_assoc($resultado)) {
            return new Recepcionista(
                $fila['nombreEmpleado'],
                $fila['apellidoPaternoE'],
                $fila['apellidoMaternoE'],
                $fila['telefono'],
                $fila['salario'],
                $fila['cargo'],
                $fila['estadoEmpleado'],
                $fila['turno']
            );
        }

        return null;
    }

    //Actualizar un recepcionista (empleado + turno)
    public function actualizarRecepcionista($recepcionista, $idEmpleado) {
        $conexion = conexionPHP();

        //Actualizar datos de EMPLEADO
        $sqlEmp = "UPDATE EMPLEADO SET nombreEmpleado = ?, apellidoPaternoE = ?, apellidoMaternoE = ?, telefono = ?, salario = ?, cargo = ?, estadoEmpleado = ? WHERE idEmpleado = ?";
        $stmtEmp = mysqli_prepare($conexion, $sqlEmp);
        mysqli_stmt_bind_param(
            $stmtEmp,
            "ssssds si",
            $recepcionista->getNombreE(),
            $recepcionista->getApellidoPaternoE(),
            $recepcionista->getApellidoMaternoE(),
            $recepcionista->getTelefono(),
            $recepcionista->getSalario(),
            $recepcionista->getCargo(),
            $recepcionista->getEstadoEmpleado(),
            $idEmpleado
        );
        mysqli_stmt_execute($stmtEmp);

        //Actualizar turno en RECEPCIONISTA
        $sqlRecep = "UPDATE RECEPCIONISTA SET turno = ? WHERE idEmpleado = ?";
        $stmtRecep = mysqli_prepare($conexion, $sqlRecep);
        mysqli_stmt_bind_param(
            $stmtRecep,
            "si",
            $recepcionista->getTurno(),
            $idEmpleado
        );

        return mysqli_stmt_execute($stmtRecep);
    }

    //Eliminar un recepcionista (primero de RECEPCIONISTA, luego EMPLEADO)
    public function eliminarRecepcionista($idEmpleado) {
        $conexion = conexionPHP();

        $sqlRecep = "DELETE FROM RECEPCIONISTA WHERE idEmpleado = ?";
        $stmtRecep = mysqli_prepare($conexion, $sqlRecep);
        mysqli_stmt_bind_param($stmtRecep, "i", $idEmpleado);
        mysqli_stmt_execute($stmtRecep);

        $sqlEmp = "DELETE FROM EMPLEADO WHERE idEmpleado = ?";
        $stmtEmp = mysqli_prepare($conexion, $sqlEmp);
        mysqli_stmt_bind_param($stmtEmp, "i", $idEmpleado);

        return mysqli_stmt_execute($stmtEmp);
    }
}
?>
