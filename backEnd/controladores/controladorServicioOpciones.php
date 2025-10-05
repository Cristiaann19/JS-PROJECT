<?php
require_once(__DIR__ . '/../dao/DAO_Servicio.php');
require_once(__DIR__ . '/../modelos/Servicios.php');

header('Content-Type: application/json; charset=utf-8');

$daoServicio = new DAO_Servicio();
$servicios = $daoServicio->listarServicios();

$opciones = [];
foreach ($servicios as $servicio) {
    $opciones[] = [
        'id' => $servicio->getIdServicio(),
        'nombre' => $servicio->getNombreServicio()
    ];
}

echo json_encode($opciones);
?>
