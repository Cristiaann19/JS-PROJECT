<?php
    require_once "conexionBD_MySQL.php";
    require_once "Cliente.php";

    class DAO_Cliente {
        public function agregarCliente($Cliente) {
            $conexion =  conexionPHP();
            $sql = "INSERT INTO CLIENTE (nombreCliente, apellidoPaterno, apellidoMaterno, telefono, email) values (?, ?, ?, ?, ?)";
            $smt = mysqli_prepare($conexion, $sql);

            mysqli_stmt_bind_param(
            $stmt,
            "sssss",
            $cliente->getNombreCliente(),
            $cliente->getApellidoPaterno(),
            $cliente->getApellidoMaterno(),
            $cliente->getTelefono(),
            $cliente->getEmail()
            );
        return mysqli_stmt_execute($stmt);
        }

        public function listarCliente() {
            $conexion =  conexionPHP();
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
            $clientes[] = $cliente;
        }
        return $clientes;
        }

        public function obtenerClientePorId($idCliente) {
        $conexion = conexionPHP();
        $sql = "SELECT * FROM cliente WHERE idCliente = ?";
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

        public function actualizarCliente($cliente) {
        $conexion = conexionPHP();
        $sql = "UPDATE cliente 
                SET nombreCliente=?, apellidoPaterno=?, apellidoMaterno=?, telefono=?, email=? 
                WHERE idCliente=?";
        $stmt = mysqli_prepare($conexion, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "sssssi",
            $cliente->getNombre(),
            $cliente->getApellidoPaterno(),
            $cliente->getApellidoMaterno(),
            $cliente->getTelefono(),
            $cliente->getEmail(),
            $cliente->getIdCliente()
        );

        return mysqli_stmt_execute($stmt);
        }
    }
?>