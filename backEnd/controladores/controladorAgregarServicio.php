<?php
header('Content-Type: application/json');

// Manejo de errores y excepciones para depuración
ini_set('display_errors', 0); // No mostrar errores directamente en la salida
error_reporting(E_ALL);

set_exception_handler(function($e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => "Excepción no controlada: " . $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
});

require_once(__DIR__ . '/../dao/DAO_Servicio.php');
require_once(__DIR__ . '/../modelos/Servicios.php');

try {
    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input) {
        throw new Exception("No se recibieron datos.");
    }

    $nombreServicio = $input['nombreServicio'] ?? null;
    $descripcion = $input['descripcion'] ?? null;
    $precio = $input['precio'] ?? null;
    $estado = $input['estado'] ?? 'Activo'; // Valor por defecto si no se envía

    if (empty($nombreServicio) || empty($descripcion) || !isset($precio)) {
        echo json_encode([
            'success' => false,
            'message' => 'Todos los campos son obligatorios.'
        ]);
        exit;
    }

    // El modelo Servicio espera id, nombre, desc, precio, img, estado
    // Pasamos null para el id y la imagen, ya que no se manejan en la inserción inicial.
    $servicio = new Servicio(null, $nombreServicio, $descripcion, $precio, null, $estado);

    $daoServicio = new DAO_Servicio();
    $resultado = $daoServicio->agregarNuevoServicio($servicio);

    if ($resultado) {
        echo json_encode([
            'success' => true,
            'message' => 'Servicio agregado correctamente.'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'No se pudo agregar el servicio.'
        ]);
    }
} catch (Exception $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>