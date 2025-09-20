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
        $sql = "SELECT * FROM EMPLEADO order by cargo desc;";
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
                $fila['estadoE'],
                $fila['generoE']
            );
            $empleados[] = $empleado;
        }
        return $empleados;
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

    //Cargar los datos del empleado 
    public function obtenerPerfilPorDNI($dni) {
        $conexion = conexionPHP();

        $sqlCargo = "SELECT cargo from empleado where dni = ?";
        $stmt = $conexion->prepare($sqlCargo);
        $stmt->bind_param("s", $dni);
        $stmt->execute();
        $resultadoCargo = $stmt->get_result();
        $cargo = $resultadoCargo->fetch_assoc()['cargo'];
        
        if ($cargo === "Barbero") {
            $sql = "SELECT empleado.nombreEmpleado, empleado.apellidoPaternoE, empleado.apellidoMaternoE, 
            empleado.cargo, empleado.dni, empleado.telefono, empleado.salario, barbero.especialidad, empleado.generoE
            from empleado inner join barbero on empleado.idEmpleado = barbero.idEmpleado
            where empleado.dni = ?";
        } else if ($cargo === "Recepcionista") {
            $sql = "SELECT empleado.nombreEmpleado, empleado.apellidoPaternoE, empleado.apellidoMaternoE, 
            empleado.cargo, empleado.dni, empleado.telefono, empleado.salario, recepcionista.turno, empleado.generoE
            from empleado inner join recepcionista on empleado.idEmpleado = recepcionista.idEmpleado
            where empleado.dni = ?";
        } else if ($cargo === "Administrador") {
            $sql = "SELECT empleado.nombreEmpleado, empleado.apellidoPaternoE, empleado.apellidoMaternoE, 
            empleado.cargo, empleado.dni, empleado.telefono, empleado.salario, empleado.generoE
            from empleado where empleado.dni = ?";
        }

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $dni);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $perfil = $resultado->fetch_assoc();

        return $perfil;
    }
}
?>
