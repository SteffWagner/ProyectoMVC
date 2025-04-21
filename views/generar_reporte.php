<?php
// Verificamos si se están enviando datos por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recuperamos las fechas del formulario
    $start = $_POST['start'];
    $end = $_POST['end'];

    // Aquí deberías incluir la conexión a tu base de datos
    include "database.php";

    // Realizamos la consulta para obtener los proyectos finalizados en el período indicado
    $query = "SELECT * FROM proyecto WHERE Fecha_creacion BETWEEN '$start' AND '$end'";
    $result = $conn->query($query);

    // Verificamos si se encontraron resultados
    if ($result->num_rows > 0) {
        // Creamos un array para almacenar los proyectos
        $proyectos = array();

        // Recorremos los resultados y los almacenamos en el array
        while ($row = $result->fetch_assoc()) {
            $proyecto = array(
                'idproyecto' => $row['idproyecto'],
                'Nombre' => $row['Nombre'],
                'Descripcion' => $row['Descripcion'],
                'Lider_proyecto' => $row['Lider_proyecto'],
                'Monto_Presupuesto' => $row['Monto_Presupuesto'],
                'Monto_Activo' => $row['Monto_Activo'],                
                'Fecha_creacion' => $row['Fecha_creacion'],
                'Fecha_entrega' => $row['Fecha_entrega'],
                'Tipo_Proyecto_Tipo_de_Proyecto' => $row['Tipo_Proyecto_Tipo_de_Proyecto']
            );
            // Agregamos el proyecto al array de proyectos
            $proyectos[] = $proyecto;
        }

        // Devolvemos los resultados en formato JSON
        header('Content-Type: application/json');
        echo json_encode($proyectos);
    } else {
        // Si no se encontraron resultados, devolvemos un mensaje de error
        header('HTTP/1.1 404 Not Found');
        echo json_encode(array('message' => 'No se encontraron proyectos finalizados en el período especificado'));
    }

    // Cerramos la conexión a la base de datos
    $conn->close();
} else {
    // Si no se están enviando datos por POST, devolvemos un error
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(array('message' => 'Error: No se recibieron datos'));
}
?>
