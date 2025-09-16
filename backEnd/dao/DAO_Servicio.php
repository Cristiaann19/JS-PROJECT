<?php
require_once "conexionBD_MySQL.php";
require_once "Servicio.php";

class DAO_Servicio {

    //Agregar un nuevo servicio
    public function agregarNuevoServicio($servicio) {
        $conexion = conexionPHP();
        $sql = "INSERT INTO SERVICIO (nombreServicio, descripcion, precio) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conexion, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "ssd",
            $servicio->getNombreServicio(),
            $servicio->getDescripcion(),
            $servicio->getPrecio()
        );

        return mysqli_stmt_execute($stmt);
    }

    //Listar todos los servicios
    public function listarServicios() {
        $conexion = conexionPHP();
        $sql = "SELECT * FROM SERVICIO";
        $resultado = mysqli_query($conexion, $sql);
        $servicios = [];

        while ($fila = mysqli_fetch_assoc($resultado)) {
            $servicio = new Servicio(
                $fila['idServicio'],
                $fila['nombreServicio'],
                $fila['descripcion'],
                $fila['precio']
            );
            $servicios[] = $servicio;
        }

        return $servicios;
    }

    //Buscar un servicio por ID
    public function buscarPorId($idServicio) {
        $conexion = conexionPHP();
        $sql = "SELECT * FROM SERVICIO WHERE idServicio = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "i", $idServicio);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);

        if ($fila = mysqli_fetch_assoc($resultado)) {
            return new Servicio(
                $fila['idServicio'],
                $fila['nombreServicio'],
                $fila['descripcion'],
                $fila['precio']
            );
        }

        return null;
    }

    //Actualizar un servicio
    public function actualizarServicio($servicio) {
        $conexion = conexionPHP();
        $sql = "UPDATE SERVICIO SET nombreServicio = ?, descripcion = ?, precio = ? WHERE idServicio = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param(
            $stmt,
            "ssdi",
            $servicio->getNombreServicio(),
            $servicio->getDescripcion(),
            $servicio->getPrecio(),
            $servicio->getIdServicio()
        );
        return mysqli_stmt_execute($stmt);
    }

    //Eliminar un servicio
    public function eliminarServicio($idServicio) {
        $conexion = conexionPHP();
        $sql = "DELETE FROM SERVICIO WHERE idServicio = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "i", $idServicio);
        return mysqli_stmt_execute($stmt);
    }
}
?>
