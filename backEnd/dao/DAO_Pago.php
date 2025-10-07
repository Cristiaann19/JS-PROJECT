<?php
require_once(__DIR__ . '/../conexionBD_MySQL.php');
require_once(__DIR__ . '/../modelos/Pago.php');

class DAO_Pago {
    //Agregar un nuevo pago
    public function agregarNuevoPago($pago) {
        $conexion = conexionPHP();
        $sql = "INSERT INTO PAGO (idReserva, montoPago, metodo, fechaPago, estado) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conexion, $sql);

        $idReserva = $pago->getIdReserva();
        $montoPago = $pago->getMontoPago();
        $metodo = $pago->getMetodo();
        $fechaPago = $pago->getFechaPago();
        $estado = "Pendiente";

        mysqli_stmt_bind_param(
            $stmt,
            "idss",
            $idReserva,
            $montoPago,
            $metodo,
            $fechaPago,
            $estado
        );

        return mysqli_stmt_execute($stmt);
    }

    //Listar todos los pagos
    public function listarPagos() {
        $conexion = conexionPHP();
        $sql = "SELECT * FROM PAGO";
        $resultado = mysqli_query($conexion, $sql);
        $pagos = [];

        while ($fila = mysqli_fetch_assoc($resultado)) {
            $pago = new Pago(
                $fila['idPago'],
                $fila['idReserva'],
                $fila['montoPago'],
                $fila['metodo'],
                $fila['fechaPago']
            );
            $pagos[] = $pago;
        }

        return $pagos;
    }

    //Procesar pago 
    public function procesarPago($idPago, $metodoPago) {
        $conexion = conexionPHP();
        $sql = "UPDATE PAGO SET estado = 'Confirmado', metodo = ? WHERE idPago = ?";
        $stmt = mysqli_prepare($conexion, $sql);

        if (!$stmt) {
            return false;
        }

        mysqli_stmt_bind_param($stmt, "si", $metodoPago, $idPago);
        $resultado = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $resultado;
    }
}
?>
