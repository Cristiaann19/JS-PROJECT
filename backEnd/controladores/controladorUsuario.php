<?php
require_once(__DIR__ . '/../../backEnd/dao/DAO_Usuario.php');

header('Content-Type: application/json');

$nombreUsuario = $_POST['usuario'] ?? null;
$contrasenia   = $_POST['contrasena'] ?? null;

if (!$nombreUsuario || !$contrasenia) {
    echo json_encode([
        "error" => true,
        "valido" => false,
        "estado" => null,
        "usuario" => null
    ]);
    exit;
}

try {
    $daoUsuario = new DAO_Usuario();
    $resultado = $daoUsuario->verificarUsuario($nombreUsuario, $contrasenia);

    echo json_encode($resultado);

} catch (Exception $e) {
    echo json_encode([
        "error" => true,
        "valido" => false,
        "estado" => null,
        "usuario" => null
    ]);
}
?>