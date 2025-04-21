<?php
include "menu_manager.php";
include "database.php";

// Consulta para obtener la cantidad total de proyectos
$queryCantidadProyectos = "SELECT COUNT(*) AS cantidad_proyectos FROM proyecto";
$resultCantidadProyectos = $conn->query($queryCantidadProyectos);

if ($resultCantidadProyectos->num_rows > 0) {
    $rowCantidadProyectos = $resultCantidadProyectos->fetch_assoc();
    $cantidadProyectos = $rowCantidadProyectos['cantidad_proyectos'];
} else {
    $cantidadProyectos = 0;
}

// Consulta para obtener los nombres de todos los proyectos
$queryNombresProyectos = "SELECT idproyecto, Nombre FROM proyecto";
$resultNombresProyectos = $conn->query($queryNombresProyectos);

$proyectos = array();
if ($resultNombresProyectos->num_rows > 0) {
    while ($rowProyecto = $resultNombresProyectos->fetch_assoc()) {
        $proyectos[] = $rowProyecto['Nombre'];
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de cantidad de proyectos</title>
    <link rel="stylesheet" href="http://localhost/ProyectoMVC/css/styles.css">
</head>
<body>
<header>
    <h1>Reporte de cantidad de proyectos</h1>
</header>

<div class="container">
    <div class="container_PDF">
        <img src="/ProyectoMVC/img/LogoAlpha.png" alt="Logo" id="company-logo" style="max-width: 200px; margin: 0 auto 20px;" />

        <div id="reportResults">
        <!-- Mostrar la cantidad total de proyectos -->
        <h3>Cantidad Total de Proyectos: <?php echo $cantidadProyectos; ?></h3>

        <!-- Mostrar los nombres de todos los proyectos -->
        <h4>Nombre de los Proyectos:</h4>
        <ul>
            <?php foreach ($proyectos as $proyecto) : ?>
                <li><?php echo $proyecto; ?></li>
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
