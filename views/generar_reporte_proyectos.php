<?php
include "database.php";

// Consulta para obtener la cantidad total de proyectos
$query = "SELECT COUNT(*) AS cantidad_proyectos FROM proyecto";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $cantidadProyectos = $row['cantidad_proyectos'];

    // Devolver la cantidad de proyectos como respuesta JSON
    echo json_encode($cantidadProyectos);
} else {
    // Si no hay proyectos, devolver 0 como respuesta JSON
    echo json_encode(0);
}

$conn->close();
?>
