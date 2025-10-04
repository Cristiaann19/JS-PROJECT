<?php
require_once(__DIR__ . '/../conexionBD_MySQL.php');
require_once(__DIR__ . '/../modelos/Cliente.php');

class DAO_Cliente {

    //Agregar un nuevo cliente
    public function agregarNuevoCliente($cliente) {
        $conexion = conexionPHP();
        $sql = "INSERT INTO CLIENTE (nombreCliente, apellidoPaterno, apellidoMaterno, telefono, email) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conexion, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "sssss",
            $cliente->getNombre(),
            $cliente->getApellidoPaterno(),
            $cliente->getApellidoMaterno(),
            $cliente->getTelefono(),
            $cliente->getEmail()
        );

        return mysqli_stmt_execute($stmt);
    }

    //Listar todos los clientes
    public function listarClientes() {
        $conexion = conexionPHP();
        $sql = "SELECT * FROM CLIENTE";
        $resultado = mysqli_query($conexion, $sql);
        $clientes = [];

        while ($fila = mysqli_fetch_assoc($resultado)) {
            $cliente = new Cliente(
                $fila['idCliente'],
                $fila['nombreCliente'],
                $fila['apellidoPaterno'],
                $fila['apellidoMaterno'],
                $fila['telefono'],
                $fila['email']
            );
            $cliente->setIdCliente($fila['idCliente']);
            $clientes[] = $cliente;
        }
        return $clientes;
    }

    //Actualizar un cliente por ID
    public function actualizarCliente($cliente, $idCliente) {
        $conexion = conexionPHP();
        $sql = "UPDATE CLIENTE SET nombreCliente = ?, apellidoPaterno = ?, apellidoMaterno = ?, telefono = ?, email = ? WHERE idCliente = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param(
            $stmt,
            "sssssi",
            $cliente->getNombre(),
            $cliente->getApellidoPaterno(),
            $cliente->getApellidoMaterno(),
            $cliente->getTelefono(),
            $cliente->getEmail(),
            $idCliente
        );

        return mysqli_stmt_execute($stmt);
    }

    //Listar reservas del cliente
    public function listarReservacionesDeCliente($idCliente){
        $conexion = conexionPHP();
        $sql = "SELECT servicio.nombreServicio, reserva.fechaReserva, reserva.hora, servicio.precio 
                FROM servicio 
                INNER JOIN reserva ON reserva.idServicio = servicio.idServicio 
                INNER JOIN cliente ON cliente.idCliente = reserva.idCliente
                WHERE cliente.idCliente = ?";

        $stmt = mysqli_prepare($conexion, $sql);
        if(!$stmt) return [];

        // "i" porque idCliente es un entero
        mysqli_stmt_bind_param($stmt, "i", $idCliente);

        mysqli_stmt_execute($stmt);

        $resultado = mysqli_stmt_get_result($stmt);
        $reservas = [];
        while($fila = mysqli_fetch_assoc($resultado)){
            $reservas[] = $fila;
        }

        mysqli_stmt_close($stmt);

        return $reservas;
    }
}
?>
