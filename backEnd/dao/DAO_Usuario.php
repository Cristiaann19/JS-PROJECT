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

    //Listar todos los usuarios
    public function listarUsuarios() {
        $conexion = conexionPHP();
        $sql = "SELECT * FROM USUARIO";
        $resultado = mysqli_query($conexion, $sql);
        $usuarios = [];

        while ($fila = mysqli_fetch_assoc($resultado)) {
            $usuario = new Usuario(
                $fila['idUsuario'],
                $fila['idEmpleado'],
                $fila['nombreUsuario'],
                $fila['contraseña']
            );
            $usuarios[] = $usuario;
        }

        return $usuarios;
    }

    //Buscar un usuario por ID
    public function buscarPorId($idUsuario) {
        $conexion = conexionPHP();
        $sql = "SELECT * FROM USUARIO WHERE idUsuario = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "i", $idUsuario);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);

        if ($fila = mysqli_fetch_assoc($resultado)) {
            return new Usuario(
                $fila['idUsuario'],
                $fila['idEmpleado'],
                $fila['nombreUsuario'],
                $fila['contraseña']
            );
        }

        return null;
    }

    //Actualizar un usuario
    public function actualizarUsuario($usuario) {
        $conexion = conexionPHP();
        $sql = "UPDATE USUARIO SET idEmpleado = ?, nombreUsuario = ?, contraseña = ? WHERE idUsuario = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param(
            $stmt,
            "issi",
            $usuario->getIdEmpleado(),
            $usuario->getNombreUsuario(),
            $usuario->getContraseña(),
            $usuario->getIdUsuario()
        );
        return mysqli_stmt_execute($stmt);
    }

    //Eliminar un usuario
    public function eliminarUsuario($idUsuario) {
        $conexion = conexionPHP();
        $sql = "DELETE FROM USUARIO WHERE idUsuario = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "i", $idUsuario);
        return mysqli_stmt_execute($stmt);
    }

    //Buscar usuario por nombre y contraseña (login)
    public function login($nombreUsuario, $contraseña) {
        $conexion = conexionPHP();
        $sql = "SELECT * FROM USUARIO WHERE nombreUsuario = ? AND contraseña = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $nombreUsuario, $contraseña);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);

        if ($fila = mysqli_fetch_assoc($resultado)) {
            return new Usuario(
                $fila['idUsuario'],
                $fila['idEmpleado'],
                $fila['nombreUsuario'],
                $fila['contraseña']
            );
        }

        return null;
    }
}
?>
