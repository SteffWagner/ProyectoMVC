<?php
// Verificar si se están enviando datos por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recuperar el ID del proyecto del formulario
    $projectId = $_POST['projectId'];

    // Aquí deberías incluir la conexión a tu base de datos
    include "database.php";

    // Realizar la consulta para obtener las tareas completadas por proyecto
    $query = "SELECT * FROM tarea WHERE Proyecto_idproyecto = $projectId AND Estado_tarea_idEstado_tarea = 5"; // Cambia 5 por el ID del estado de tarea "Completo"
    $result = $conn->query($query);

    // Verificar si se encontraron resultados
    if ($result->num_rows > 0) {
        // Creamos un array para almacenar las tareas
        $tareas = array();

        // Recorremos los resultados y los almacenamos en el array
        while ($row = $result->fetch_assoc()) {
            $tarea = array(
                'idTarea' => $row['idTarea'],
                'Descripcion' => $row['Descripcion'],
                'Fecha_inicio' => $row['Fecha_inicio'],
                'Fecha_fin' => $row['Fecha_fin'],
                // Agrega más campos de tarea según sea necesario
            );
            // Agregamos la tarea al array de tareas
            $tareas[] = $tarea;
        }

        // Devolver los resultados en formato JSON
        header('Content-Type: application/json');
        echo json_encode($tareas);
    } else {
        // Si no se encontraron resultados, devolvemos un mensaje de error
        header('HTTP/1.1 404 Not Found');
        echo json_encode(array('message' => 'No se encontraron tareas completadas para este proyecto'));
    }

    // Cerramos la conexión a la base de datos
    $conn->close();
} else {
    // Si no se están enviando datos por POST, devolvemos un error
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(array('message' => 'Error: No se recibieron datos'));
}
?>