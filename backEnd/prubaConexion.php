<?php
    include 'conexionBD.php';
    $conexion = conexionPHP();

    $sql = "SELECT * FROM PERSONA";
    $resultado = mysqli_query($conexion, $sql);
    
    if (mysqli_num_rows($resultado) > 0) {
        echo "<table border='1' cellpadding='10'>";
        
        // Encabezados dinámicos
        echo "<tr>";
        while ($campo = mysqli_fetch_field($resultado)) {
            echo "<th>" . $campo->name . "</th>";
        }
        echo "</tr>";

        // Filas de datos
        while ($fila = mysqli_fetch_assoc($resultado)) {
            echo "<tr>";
            foreach ($fila as $valor) {
                echo "<td>" . $valor . "</td>";
            }
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "No hay resultados";
    }

    mysqli_close($conexion);
?>