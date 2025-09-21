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

    //Buscar un cliente por ID
    public function buscarPorId($idCliente) {
        $conexion = conexionPHP();
        $sql = "SELECT * FROM CLIENTE WHERE idCliente = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "i", $idCliente);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);

        if ($fila = mysqli_fetch_assoc($resultado)) {
            $cliente = new Cliente(
                $fila['nombreCliente'],
                $fila['apellidoPaterno'],
                $fila['apellidoMaterno'],
                $fila['telefono'],
                $fila['email']
            );
            $cliente->setIdCliente($fila['idCliente']);
            return $cliente;
        }

        return null;
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

    //Eliminar un cliente por ID
    public function eliminarCliente($idCliente) {
        $conexion = conexionPHP();
        $sql = "DELETE FROM CLIENTE WHERE idCliente = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "i", $idCliente);
        return mysqli_stmt_execute($stmt);
    }
}
?>
