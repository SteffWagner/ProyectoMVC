<?php
// Incluir el archivo de conexión a la base de datos
include "database.php";

// Obtener el ID del proyecto desde la solicitud GET
$projectId = $_GET['projectId'];

// Consulta SQL para obtener las tareas asociadas al proyecto seleccionado
$sql = "SELECT tarea.idTarea, tarea.Descripcion AS NombreTarea, presupuesto.idPresupuesto, presupuesto.Monto, presupuesto.DescripcionP
        FROM tarea
        LEFT JOIN presupuesto ON tarea.idTarea = presupuesto.Tarea_idTarea
        WHERE tarea.Proyecto_idproyecto = $projectId";

$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    echo "<table>";
    echo "<thead><tr><th>Tarea</th><th>Monto</th></tr></thead>";
    echo "<tbody>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["NombreTarea"] . "</td>";
        echo "<td>$" . $row["Monto"] . "</td>";
        // echo "<td>" . $row["DescripcionP"] . "</td>";
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
} else {
    echo "<p>No se encontraron tareas para este proyecto.</p>";
}

// Cerrar conexión
$conn->close();
?>
