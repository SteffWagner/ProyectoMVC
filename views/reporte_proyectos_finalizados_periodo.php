<?php 
include "menu_manager.php";
include "database.php";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de proyectos finalizados por período</title>
    <link rel="stylesheet" href="http://localhost:8080/ProyectoMVC/css/styles.css">
    <!-- Incluir html2pdf -->
    <script src="http://localhost/ProyectoMVC/views/html2pdf.js-/dist/html2pdf.bundle.min.js"></script>

    <style>
        
        /* Estilos para el documento PDF */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
        }
        header {
            margin-left: 265px;
        }
        h3 {
            text-align: center;
            margin-bottom: 20px; /* Añade espacio entre el título y el formulario */
        }

        

        form {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        input[type="date"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button[type="submit"], #generatePDF {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #1A9F63;
            color: #fff;
            cursor: pointer;
            margin-bottom: 20px; /* Añade espacio entre los botones y el formulario */
        }
        .container_PDF {
            border: 2px solid green; /* Borde sólido de 2px de ancho y color verde */
            padding: 20px; /* Espaciado interno de 20px (opcional, para dar espacio al contenido) */
            margin: 55px;
        }
       
    </style>


</head>

<body>
<header>
        <h3>Reporte de proyectos finalizados por período</h3>
    </header>

    <div class="container">
        <form id="periodForm">
            <label for="start">Inicio del período:</label>
            <input type="date" id="start" name="start" required>

            <label for="end">Fin del período:</label>
            <input type="date" id="end" name="end" required>

            <button type="submit">Generar reporte</button>
        </form>

        <div class="container_PDF">
        <img src="/ProyectoMVC/img/LogoAlpha.png" alt="Logo" id="company-logo" style="max-width: 200px; margin: 0 auto 20px;" />

        <br>
            <div id="reportResults">
                <!-- Los resultados del reporte se mostrarán aquí -->
            </div>
        <br>
        
        <p>© 2024 Alpha</p>

        </div>

        <!-- Botón para generar el PDF -->
        <button id="generatePDF">Generar PDF</button>
    </div>

    <!-- Incluir el script JavaScript -->
    <script src="http://localhost/ProyectoMVC/views/script.js"></script>

    <script>
    document.getElementById('generatePDF').addEventListener('click', function() {
        // Ocultar el formulario antes de generar el PDF
        document.getElementById('periodForm').style.display = 'none';

        // Seleccionar solo el contenido relevante para el PDF
        var container = document.querySelector('.container_PDF');

        // Calcular la escala basada en las dimensiones de la ventana
        var screenWidth = window.innerWidth || document.documentElement.clientWidth;
        var screenHeight = window.innerHeight || document.documentElement.clientHeight;
        var scaleFactor = screenWidth / container.offsetWidth; // Calcular la escala

        // Crear un nuevo objeto html2pdf con las opciones ajustadas
        var pdf = new html2pdf(container, {
            margin: [10, 20, 0, 30], // Establecer márgenes top, right, bottom, left
            html2canvas: {
                scale: scaleFactor, // Escalar según el ancho de la ventana
                letterRendering: true, // Mejora la renderización de texto
            },
            jsPDF: {
                unit: 'mm', // Unidad de medida para el documento PDF (milímetros)
                format: 'letter', // Formato del documento (A4)
                orientation: 'portrait', // Orientación del documento (vertical)
                compressPDF: true, // Comprimir el PDF para reducir el tamaño del archivo (opcional)
                putOnlyUsedFonts: true, // Incluir solo las fuentes utilizadas en el PDF (opcional)
                precision: 16 // Precisión de los cálculos de posicionamiento (opcional)
            }
        });

        // Generar el PDF y guardarlo
        pdf.save();

        // Mostrar el formulario después de generar el PDF (opcional)
        document.getElementById('periodForm').style.display = 'block';
    });
    </script>

</body>
</html>
