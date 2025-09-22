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

        $sql = "SELECT usuario.nombreUsuario, usuario.contraseña, empleado.estadoE
                FROM Usuario
                INNER JOIN Empleado ON usuario.idEmpleado = empleado.idEmpleado
                WHERE usuario.nombreUsuario = ?";

        $stmt = mysqli_prepare($conexion, $sql);
        if (!$stmt) {
            throw new Exception("Error al preparar la consulta de verificación: " . mysqli_error($conexion));
        }

        mysqli_stmt_bind_param($stmt, "s", $nombreUsuario);

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error al ejecutar la consulta de verificación: " . mysqli_error($conexion));
        }

        $resultado = mysqli_stmt_get_result($stmt);

        if ($fila = mysqli_fetch_assoc($resultado)) {
            if ($fila['contraseña'] === $contrasenia) {
                return [
                    "valido"  => true,
                    "estado"  => $fila['estadoE'],
                    "usuario" => $fila['nombreUsuario']
                ];
            } else {
                return [
                    "valido"  => false,
                    "estado"  => $fila['estadoE'],
                    "usuario" => $fila['nombreUsuario']
                ];
            }
        } else {
            return [
                "valido"  => false,
                "estado"  => null,
                "usuario" => null
            ];
        }
    }
}
?>
