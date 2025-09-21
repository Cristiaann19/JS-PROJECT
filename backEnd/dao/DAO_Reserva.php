<?php
require_once(__DIR__ . '/../conexionBD_MySQL.php');
require_once(__DIR__ . '/../modelos/Reserva.php');

class DAO_Reserva {

    //Agregar una nueva reserva
    public function agregarNuevaReserva($reserva) {
        $conexion = conexionPHP();
        $sql = "INSERT INTO RESERVA (idCliente, idBarbero, idServicio, fechaReserva, hora, estado) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conexion, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "iiisss",
            $reserva->getIdCliente(),
            $reserva->getIdBarbero(),
            $reserva->getIdServicio(),
            $reserva->getFechaReserva(),
            $reserva->getHora(),
            $reserva->getEstado()
        );

        return mysqli_stmt_execute($stmt);
    }

    //Listar todas las reservas
    public function listarReservas() {
        $conexion = conexionPHP();
        $sql = "SELECT * FROM RESERVA";
        $resultado = mysqli_query($conexion, $sql);
        $reservas = [];

        while ($fila = mysqli_fetch_assoc($resultado)) {
            $reserva = new Reserva(
                $fila['idReserva'],
                $fila['idCliente'],
                $fila['idBarbero'],
                $fila['idServicio'],
                $fila['fechaReserva'],
                $fila['hora'],
                $fila['estado']
            );
            $reservas[] = $reserva;
        }

        return $reservas;
    }

    //Buscar una reserva por ID
    public function buscarPorId($idReserva) {
        $conexion = conexionPHP();
        $sql = "SELECT * FROM RESERVA WHERE idReserva = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "i", $idReserva);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);

        if ($fila = mysqli_fetch_assoc($resultado)) {
            return new Reserva(
                $fila['idReserva'],
                $fila['idCliente'],
                $fila['idBarbero'],
                $fila['idServicio'],
                $fila['fechaReserva'],
                $fila['hora'],
                $fila['estado']
            );
        }

        return null;
    }

    //Actualizar una reserva
    public function actualizarReserva($reserva) {
        $conexion = conexionPHP();
        $sql = "UPDATE RESERVA SET idCliente = ?, idBarbero = ?, idServicio = ?, fechaReserva = ?, hora = ?, estado = ? WHERE idReserva = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param(
            $stmt,
            "iiisssi",
            $reserva->getIdCliente(),
            $reserva->getIdBarbero(),
            $reserva->getIdServicio(),
            $reserva->getFechaReserva(),
            $reserva->getHora(),
            $reserva->getEstado(),
            $reserva->getIdReserva()
        );
        return mysqli_stmt_execute($stmt);
    }

    //Eliminar una reserva
    public function eliminarReserva($idReserva) {
        $conexion = conexionPHP();
        $sql = "DELETE FROM RESERVA WHERE idReserva = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "i", $idReserva);
        return mysqli_stmt_execute($stmt);
    }
}
?>
