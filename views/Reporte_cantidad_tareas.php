<?php
include "menu_manager.php";
include "database.php";

// Consulta para obtener la cantidad total de tareas
$queryCantidadTareas = "SELECT COUNT(*) AS cantidad_tareas FROM tarea";
$resultCantidadTareas = $conn->query($queryCantidadTareas);

if ($resultCantidadTareas->num_rows > 0) {
    $rowCantidadTareas = $resultCantidadTareas->fetch_assoc();
    $cantidadTareas = $rowCantidadTareas['cantidad_tareas'];
} else {
    $cantidadTareas = 0;
}

// Consulta para obtener los detalles de todas las tareas
$queryDetallesTareas = "SELECT idTarea, Descripcion FROM tarea";
$resultDetallesTareas = $conn->query($queryDetallesTareas);

$tareas = array();
if ($resultDetallesTareas->num_rows > 0) {
    while ($rowTarea = $resultDetallesTareas->fetch_assoc()) {
        $tareas[] = $rowTarea;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de cantidad de tareas</title>
    <link rel="stylesheet" href="http://localhost/ProyectoMVC/css/styles.css">
</head>
<body>
<header>
    <h1>Reporte de cantidad de tareas</h1>
</header>

<div class="container">
    <div class="container_PDF">
        <img src="/ProyectoMVC/img/LogoAlpha.png" alt="Logo" id="company-logo" style="max-width: 200px; margin: 0 auto 20px;" />

        <div id="reportResults">
        <!-- Mostrar la cantidad total de tareas -->
        <h3>Cantidad Total de Tareas: <?php echo $cantidadTareas; ?></h3>

        <!-- Mostrar los detalles de todas las tareas -->
        <h4>Detalles de las Tareas:</h4>
        <ul>
            <?php foreach ($tareas as $tarea) : ?>
                <li>Numero de Tarea: <?php echo $tarea['idTarea']; ?> - Descripción: <?php echo $tarea['Descripcion']; ?></li>
            <?php endforeach; ?>
        </ul>
        </div>
        <br><br><p>© 2024 Alpha</p>
    </div>
    <button id="generatePDF">Generar PDF</button>
</div>

<script src="http://localhost/ProyectoMVC/views/script.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>
<script>
    document.getElementById('generatePDF').addEventListener('click', function() {
        // Seleccionar solo el contenido relevante para el PDF (container_PDF)
        var container = document.querySelector('.container_PDF');

        // Crear un nuevo objeto html2pdf con las opciones ajustadas
        var pdf = new html2pdf(container, {
            margin: [10, 20, 0, 30], // Márgenes top, right, bottom, left
            html2canvas: {
                scale: 1, // Escala de captura de html2canvas (aumenta la resolución)
                letterRendering: true, // Mejora la renderización de texto
            },
            jsPDF: {
                unit: 'mm', // Unidad de medida para el documento PDF (milímetros)
                format: 'letter', // Formato del documento (carta)
                orientation: 'portrait' // Orientación del documento (vertical)
            }
        });

        // Generar el PDF y guardar
        pdf.save();
    });
</script>
</body>
</html>
