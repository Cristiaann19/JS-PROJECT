<?php
require_once(__DIR__ . '/../conexionBD_MySQL.php');
require_once(__DIR__ . '/../modelos/Reserva.php');
require_once(__DIR__ . '/../dao/DAO_Barbero.php');

class DAO_Reserva {

    //Agregar una nueva reserva
    public function agregarNuevaReserva($reserva) {
        $conexion = conexionPHP();
        $daoBarbero = new DAO_Barbero();
        $totalBarberos = $daoBarbero->obtenerCantidadBarberos();

        $fechaReserva = $reserva->getFechaReserva();
        $hora = $reserva->getHora();

        $barberoAsignado = null;
        for ($idBarbero = 1; $idBarbero <= $totalBarberos; $idBarbero++) {
            if ($this->hayDisponibilidad($idBarbero, $fechaReserva, $hora)) {
                $barberoAsignado = $idBarbero;
                break;
            }
        }

        if ($barberoAsignado === null) {
            return false;
        }

        $sql = "INSERT INTO reserva (idCliente, idBarbero, idServicio, fechaReserva, hora, estado) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conexion, $sql);

        $idCliente = $reserva->getIdCliente();
        $idServicio = $reserva->getIdServicio();
        $estado = $reserva->getEstado();

        mysqli_stmt_bind_param(
            $stmt,
            "iiisss",
            $idCliente,
            $barberoAsignado,
            $idServicio,
            $fechaReserva,
            $hora,
            $estado
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

    //Listar reservas de un barbero 
    public function listarReservasID($idBarbero) {
        $conexion = conexionPHP();
        $sql = "SELECT reserva.idReserva, cliente.nombreCliente, concat(cliente.apellidoPaterno, ' ', cliente.apellidoMaterno) as Apellidos, reserva.fechaReserva, reserva.hora, servicio.nombreServicio, reserva.estado 
        from cliente inner join reserva on cliente.idCliente = reserva.idCliente inner join servicio on reserva.idServicio = servicio.idServicio 
        where idBarbero = ?
        order by fechaReserva, hora asc";

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
            throw new Exception("Error en la consulta de reservas: " . $conexion->error);
            return [];
        }
    }

    //Cancelar una reserva
    public function cancelarReserva($idReserva){
        $conexion = conexionPHP();
        $sql = "UPDATE reserva set estado = 'Cancelada' where idReserva = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "i", $idReserva);
        $resultado = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $resultado;
    }

    //Cambiar estado de reserva a completada
    public function cambiarEstado($idReserva){
        $conexion = conexionPHP();
        $sql = "UPDATE reserva SET estado = 'Completada' WHERE idReserva = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "i", $idReserva);
        $resultado = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $resultado;
    }

    //Obtener ultimo id de reserva
    public function obtenerUltimoID() {
        $conexion = conexionPHP();
        $sql = "SELECT idReserva FROM reserva ORDER BY idReserva DESC LIMIT 1";
        $resultado = mysqli_query($conexion, $sql);

        if ($fila = mysqli_fetch_assoc($resultado)) {
            $ultimoID = (int)$fila['idReserva'];
            return $ultimoID;
        } else {
            return 0;
        }
    }

    //Verificar disponibilidad de reserva
    public function hayDisponibilidad($idBarbero, $fechaReserva, $hora) {
        $conexion = conexionPHP();
        $sql = "SELECT 1 FROM reserva WHERE idBarbero = ? AND fechaReserva = ? AND hora = ? LIMIT 1";
        
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "iss", $idBarbero, $fechaReserva, $hora);
        
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);
        
        return mysqli_num_rows($resultado) === 0;
    }
}   
?>
