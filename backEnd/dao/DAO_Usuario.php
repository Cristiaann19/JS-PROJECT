<?php
require_once(__DIR__ . '/../conexionBD_MySQL.php');
require_once(__DIR__ . '/../modelos/Usuario.php');

class DAO_Usuario {
    //Agregar un nuevo usuario
    public function agregarNuevoUsuario($usuario) {
        $conexion = conexionPHP();

        $sql = "INSERT INTO USUARIO (idEmpleado, nombreUsuario, contraseña) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conexion, $sql);
        if (!$stmt) {
            throw new Exception("Error al preparar la consulta USUARIO: " . mysqli_error($conexion));
        }

        $idEmpleado = $usuario->getIdEmpleado();
        $nombreUsuario = $usuario->getNombreUsuario();
        $contrasenia = $usuario->getContraseña();

        mysqli_stmt_bind_param(
            $stmt,
            "iss",
            $idEmpleado,
            $nombreUsuario,
            $contrasenia
        );

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error al ejecutar la consulta USUARIO: " . mysqli_error($conexion));
        }

        return mysqli_insert_id($conexion);
    }

    //para verificar el usuario
    public function verificarUsuario($nombreUsuario, $contrasenia) {
        $conexion = conexionPHP();

        $sql = "SELECT usuario.nombreUsuario, usuario.contraseña, empleado.estadoE, empleado.cargo
                FROM Usuario
                INNER JOIN Empleado ON usuario.idEmpleado = empleado.idEmpleado
                WHERE usuario.nombreUsuario = ? AND usuario.contraseña = ?";

        $stmt = mysqli_prepare($conexion, $sql);
        if (!$stmt) {
            throw new Exception("Error al preparar la consulta de verificación: " . mysqli_error($conexion));
        }

        mysqli_stmt_bind_param($stmt, "ss", $nombreUsuario, $contrasenia);

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error al ejecutar la consulta de verificación: " . mysqli_error($conexion));
        }

        $resultado = mysqli_stmt_get_result($stmt);

        if ($fila = mysqli_fetch_assoc($resultado)) {
            return [
                "valido"  => true,
                "estado"  => $fila['estadoE'],
                "usuario" => $fila['nombreUsuario'],
                "cargo"   => $fila['cargo']
            ];
        } else {
            return [
                "valido"  => false,
                "estado"  => null,
                "usuario" => null,
                "cargo"   => null
            ];
        }
    }

}
?>
