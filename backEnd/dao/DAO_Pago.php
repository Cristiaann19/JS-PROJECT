<?php
require_once "conexionBD_MySQL.php";
require_once "Pago.php";

class DAO_Pago {

    //Agregar un nuevo pago
    public function agregarNuevoPago($pago) {
        $conexion = conexionPHP();
        $sql = "INSERT INTO PAGO (idReserva, montoPago, metodo, fechaPago) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conexion, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "idss",
            $pago->getIdReserva(),
            $pago->getMontoPago(),
            $pago->getMetodo(),
            $pago->getFechaPago()
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

    //Buscar un pago por ID
    public function buscarPorId($idPago) {
        $conexion = conexionPHP();
        $sql = "SELECT * FROM PAGO WHERE idPago = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "i", $idPago);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);

        if ($fila = mysqli_fetch_assoc($resultado)) {
            return new Pago(
                $fila['idPago'],
                $fila['idReserva'],
                $fila['montoPago'],
                $fila['metodo'],
                $fila['fechaPago']
            );
        }

        return null;
    }

    //Actualizar un pago
    public function actualizarPago($pago) {
        $conexion = conexionPHP();
        $sql = "UPDATE PAGO SET idReserva = ?, montoPago = ?, metodo = ?, fechaPago = ? WHERE idPago = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param(
            $stmt,
            "idssi",
            $pago->getIdReserva(),
            $pago->getMontoPago(),
            $pago->getMetodo(),
            $pago->getFechaPago(),
            $pago->getIdPago()
        );
        return mysqli_stmt_execute($stmt);
    }

    // Eliminar un pago
    public function eliminarPago($idPago) {
        $conexion = conexionPHP();
        $sql = "DELETE FROM PAGO WHERE idPago = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "i", $idPago);
        return mysqli_stmt_execute($stmt);
    }
}
?>
