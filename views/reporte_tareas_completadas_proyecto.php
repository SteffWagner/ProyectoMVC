<?php
include "menu_manager.php";
include "database.php";

// Consulta para obtener la cantidad total de personas
$queryCantidadPersonas = "SELECT COUNT(*) AS cantidad_personas FROM persona";
$resultCantidadPersonas = $conn->query($queryCantidadPersonas);

if ($resultCantidadPersonas && $resultCantidadPersonas->num_rows > 0) {
    $rowCantidadPersonas = $resultCantidadPersonas->fetch_assoc();
    $cantidadPersonas = $rowCantidadPersonas['cantidad_personas'];
} else {
    $cantidadPersonas = 0;
}

// Consulta para obtener los nombres completos de todas las personas
$queryNombresPersonas = "SELECT Cedula, CONCAT(Nombre_Persona, ' ', Apellido1_Persona, ' ', Apellido2_Persona) AS nombre_completo FROM persona";
$resultNombresPersonas = $conn->query($queryNombresPersonas);

$personas = array();
if ($resultNombresPersonas && $resultNombresPersonas->num_rows > 0) {
    while ($rowPersona = $resultNombresPersonas->fetch_assoc()) {
        $personas[] = $rowPersona['nombre_completo'];
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de cantidad de personas</title>
    <link rel="stylesheet" href="http://localhost/ProyectoMVC/css/styles.css">
</head>
<body>
<header>
    <h1>Reporte de cantidad de personas</h1>
</header>

<div class="container">
    <div class="container_PDF">
        <img src="/ProyectoMVC/img/LogoAlpha.png" alt="Logo" id="company-logo" style="max-width: 200px; margin: 0 auto 20px;" />

        <div id="reportResults">
            <!-- Mostrar la cantidad total de personas -->
            <h3>Cantidad Total de Personas: <?php echo $cantidadPersonas; ?></h3>

            <!-- Mostrar los nombres completos de todas las personas -->
            <h4>Nombres de las Personas:</h4>
            <ul>
                <?php foreach ($personas as $persona) : ?>
                    <li><?php echo $persona; ?></li>
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
