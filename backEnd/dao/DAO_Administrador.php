<?php
require_once "conexionBD_MySQL.php";
require_once "Administrador.php";

class DAO_Administrador {

    //Agregar un nuevo administrador
    public function agregarNuevoAdministrador($admin) {
        $conexion = conexionPHP();

        //Insertar en EMPLEADO
        $sqlEmpleado = "INSERT INTO EMPLEADO (nombreEmpleado, apellidoPaternoE, apellidoMaternoE, telefono, salario, cargo, estadoEmpleado) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmtEmp = mysqli_prepare($conexion, $sqlEmpleado);
        mysqli_stmt_bind_param(
            $stmtEmp,
            "ssssds s",
            $admin->getNombreE(),
            $admin->getApellidoPaternoE(),
            $admin->getApellidoMaternoE(),
            $admin->getTelefono(),
            $admin->getSalario(),
            $admin->getCargo(),
            $admin->getEstadoEmpleado()
        );
        mysqli_stmt_execute($stmtEmp);

        //Obtener ID del empleado recién insertado
        $idEmpleado = mysqli_insert_id($conexion);

        //Insertar en ADMINISTRADOR
        $sqlAdmin = "INSERT INTO ADMINISTRADOR (idEmpleado) VALUES (?)";
        $stmtAdmin = mysqli_prepare($conexion, $sqlAdmin);
        mysqli_stmt_bind_param($stmtAdmin, "i", $idEmpleado);

        return mysqli_stmt_execute($stmtAdmin);
    }

    //Listar todos los administradores
    public function listarAdministradores() {
        $conexion = conexionPHP();
        $sql = "SELECT a.idAdministrador, e.idEmpleado, e.nombreEmpleado, e.apellidoPaternoE, e.apellidoMaternoE, e.telefono, e.salario, e.cargo, e.estadoEmpleado
                FROM ADMINISTRADOR a
                JOIN EMPLEADO e ON a.idEmpleado = e.idEmpleado";
        $resultado = mysqli_query($conexion, $sql);
        $administradores = [];

        while ($fila = mysqli_fetch_assoc($resultado)) {
            $admin = new Administrador(
                $fila['nombreEmpleado'],
                $fila['apellidoPaternoE'],
                $fila['apellidoMaternoE'],
                $fila['telefono'],
                $fila['salario'],
                $fila['cargo'],
                $fila['estadoEmpleado']
            );
            $admin->setIdAdministrador($fila['idAdministrador']);
            $administradores[] = $admin;
        }

        return $administradores;
    }

    //Buscar administrador por ID de empleado
    public function buscarPorIdEmpleado($idEmpleado) {
        $conexion = conexionPHP();
        $sql = "SELECT a.idAdministrador, e.idEmpleado, e.nombreEmpleado, e.apellidoPaternoE, e.apellidoMaternoE, e.telefono, e.salario, e.cargo, e.estadoEmpleado
                FROM ADMINISTRADOR a
                JOIN EMPLEADO e ON a.idEmpleado = e.idEmpleado
                WHERE e.idEmpleado = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "i", $idEmpleado);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);

        if ($fila = mysqli_fetch_assoc($resultado)) {
            $admin = new Administrador(
                $fila['nombreEmpleado'],
                $fila['apellidoPaternoE'],
                $fila['apellidoMaternoE'],
                $fila['telefono'],
                $fila['salario'],
                $fila['cargo'],
                $fila['estadoEmpleado']
            );
            $admin->setIdAdministrador($fila['idAdministrador']);
            return $admin;
        }

        return null;
    }

    //Actualizar administrador por ID de empleado
    public function actualizarAdministrador($admin, $idEmpleado) {
        $conexion = conexionPHP();

        $sqlEmp = "UPDATE EMPLEADO SET nombreEmpleado = ?, apellidoPaternoE = ?, apellidoMaternoE = ?, telefono = ?, salario = ?, cargo = ?, estadoEmpleado = ? WHERE idEmpleado = ?";
        $stmtEmp = mysqli_prepare($conexion, $sqlEmp);
        mysqli_stmt_bind_param(
            $stmtEmp,
            "ssssds si",
            $admin->getNombreE(),
            $admin->getApellidoPaternoE(),
            $admin->getApellidoMaternoE(),
            $admin->getTelefono(),
            $admin->getSalario(),
            $admin->getCargo(),
            $admin->getEstadoEmpleado(),
            $idEmpleado
        );

        return mysqli_stmt_execute($stmtEmp);
    }

    //Eliminar administrador por ID de empleado
    public function eliminarAdministrador($idEmpleado) {
        $conexion = conexionPHP();

        $sqlAdmin = "DELETE FROM ADMINISTRADOR WHERE idEmpleado = ?";
        $stmtAdmin = mysqli_prepare($conexion, $sqlAdmin);
        mysqli_stmt_bind_param($stmtAdmin, "i", $idEmpleado);
        mysqli_stmt_execute($stmtAdmin);

        $sqlEmp = "DELETE FROM EMPLEADO WHERE idEmpleado = ?";
        $stmtEmp = mysqli_prepare($conexion, $sqlEmp);
        mysqli_stmt_bind_param($stmtEmp, "i", $idEmpleado);

        return mysqli_stmt_execute($stmtEmp);
    }
}
?>
