<?php
include "menu_manager.php";
include "database.php";

// Consulta para obtener la cantidad total de recursos
$queryCantidadRecursos = "SELECT SUM(Cantidad_Recurso) AS cantidad_recursos FROM recurso";
$resultCantidadRecursos = $conn->query($queryCantidadRecursos);

if ($resultCantidadRecursos->num_rows > 0) {
    $rowCantidadRecursos = $resultCantidadRecursos->fetch_assoc();
    $cantidadRecursos = $rowCantidadRecursos['cantidad_recursos'];
} else {
    $cantidadRecursos = 0;
}

// Consulta para obtener los detalles de todos los recursos
$queryDetallesRecursos = "SELECT idRecurso, Nombre, Cantidad_Recurso FROM recurso";
$resultDetallesRecursos = $conn->query($queryDetallesRecursos);

$recursos = array();
if ($resultDetallesRecursos->num_rows > 0) {
    while ($rowRecurso = $resultDetallesRecursos->fetch_assoc()) {
        $recursos[] = $rowRecurso;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de cantidad de recursos</title>
    <link rel="stylesheet" href="http://localhost/ProyectoMVC/css/styles.css">
</head>
<body>
<header>
    <h1>Reporte de cantidad de recursos</h1>
</header>

<div class="container">
    <div class="container_PDF">
        <img src="/ProyectoMVC/img/LogoAlpha.png" alt="Logo" id="company-logo" style="max-width: 200px; margin: 0 auto 20px;" />

        <div id="reportResults">
            <!-- Mostrar la cantidad total de recursos -->
            <h3>Cantidad Total de Recursos: <?php echo $cantidadRecursos; ?></h3><br>

            <!-- Mostrar los detalles de todos los recursos -->
            <h4>Detalles de los Recursos:</h4>
            <ul>
                <?php foreach ($recursos as $recurso) : ?>
                    <li>Nombre del recurso: <?php echo $recurso['Nombre']; ?> - Cantidad: <?php echo $recurso['Cantidad_Recurso']; ?></li>
                <?php endforeach; ?>
            </ul>
    
            <br><br><p>© 2024 Alpha</p>

        </div>
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
