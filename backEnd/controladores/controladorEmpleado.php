<?php
require_once(__DIR__ . '/../../backEnd/dao/DAO_Empleado.php');

//Cargar datos para tabla de empleados
$daoEmpleado = new DAO_Empleado();
$empleados = $daoEmpleado->listarEmpleados();
$contador = 1;

foreach ($empleados as $emp) {
    echo "<tr>";
    echo "<td>{$contador}</td>";
    echo "<td>{$emp->getNombreE()}</td>";
    echo "<td>{$emp->getApellidoPaternoE()}</td>";
    echo "<td>{$emp->getApellidoMaternoE()}</td>";
    echo "<td>{$emp->getTelefono()}</td>";
    echo "<td>{$emp->getSalario()}</td>";
    echo "<td>{$emp->getCargo()}</td>";
    echo "<td>{$emp->getEstadoEmpleado()}</td>";
    echo "</tr>";
    $contador++;
}
?>
