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

    //listar reservas de un barbero 
    public function listarReservasID($idBarbero) {
        $conexion = conexionPHP();
        $sql = "SELECT cliente.nombreCliente, concat(cliente.apellidoPaterno, ' ', cliente.apellidoMaterno) as Apellidos, reserva.fechaReserva, reserva.hora, servicio.nombreServicio 
        from cliente inner join reserva on cliente.idCliente = reserva.idCliente inner join servicio on reserva.idServicio = servicio.idServicio 
        where idBarbero = ?";

        if ($stmt = $conexion->prepare($sql)) {
            $stmt->bind_param("i", $idBarbero);

            $stmt->execute();
            $resultado = $stmt->get_result();

            $reservas = [];
            while ($row = $resultado->fetch_assoc()) {
                $reservas[] = $row;
            }
            $stmt->close();
            return $reservas;
        } else {
            echo "Error en la consulta: " . $conexion->error;
            return [];
        }
    }
}
?>
