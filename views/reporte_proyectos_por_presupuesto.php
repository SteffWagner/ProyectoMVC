<?php 
include "menu_manager.php";
include "database.php";
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de proyectos por presupuesto</title>
    <link rel="stylesheet" href="http://localhost:8080/ProyectoMVC/css/styles.css">
    <!-- Incluir html2pdf -->
    <script src="http://localhost/ProyectoMVC/views/html2pdf.js-/dist/html2pdf.bundle.min.js"></script>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #ddd;
        }
    </style>
</head>

<body>
    <header>
        <h1>Reporte de proyectos por presupuesto</h1>
    </header>

    <div class="container">
        <form id="budgetForm">
            <label for="min">Presupuesto mínimo:</label>
            <input type="number" id="min" name="min" required>

            <label for="max">Presupuesto máximo:</label>
            <input type="number" id="max" name="max" required>

            <button type="submit">Generar reporte</button>
        </form>

        <div class="container_PDF">
            <div id="reportResults">
                <!-- Los resultados del reporte se mostrarán aquí -->
            </div>
        </div>
        
        <!-- Botón para generar el PDF -->
        <button id="generatePDF">Generar PDF</button>
    </div>

    <script>
        document.getElementById('budgetForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Evita el envío del formulario por defecto

            // Obtiene los valores del formulario
            var min = document.getElementById('min').value;
            var max = document.getElementById('max').value;

            // Realiza una solicitud AJAX
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'generar_reporte_presupuesto.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // Convertir la respuesta JSON en una tabla HTML y mostrarla
                    var response = JSON.parse(xhr.responseText);
                    var tableHtml = '<table border="1"><tr><th>ID Proyecto</th><th>Nombre</th><th>Monto Presupuesto</th><th>Monto Activo</th></tr>';

                    // Iterar sobre cada proyecto en la respuesta y construir las filas de la tabla
                    response.forEach(function(proyecto) {
                        tableHtml += '<tr>';
                        tableHtml += '<td>' + proyecto.idproyecto + '</td>';
                        tableHtml += '<td>' + proyecto.Nombre + '</td>';
                        tableHtml += '<td>' + proyecto.Monto_Presupuesto + '</td>';
                        tableHtml += '<td>' + proyecto.Monto_Activo + '</td>';
                        tableHtml += '</tr>';
                    });

                    tableHtml += '</table>';

                    // Mostrar la tabla dentro del contenedor reportResults
                    document.getElementById('reportResults').innerHTML = tableHtml;
                }
            };
            xhr.send('min=' + min + '&max=' + max);
        });

        // Función para generar el PDF
        document.getElementById('generatePDF').addEventListener('click', function() {
            // Ocultar el formulario antes de generar el PDF
            document.getElementById('budgetForm').style.display = 'none';

            // Seleccionar solo el contenido relevante para el PDF
            var container = document.querySelector('.container_PDF');

            // Crear un nuevo objeto html2pdf con las opciones deseadas (incluyendo márgenes)
            var pdf = new html2pdf(container, {
                margin: [10, 20, 10, 20], // Establecer márgenes top, right, bottom, left
                html2canvas: {
                    scale: 3, // Escala de captura de html2canvas (aumenta la resolución)
                    letterRendering: true // Mejora la renderización de texto
                },
                filename: 'reporte_proyectos.pdf',
                jsPDF: {
                    unit: 'mm', // Unidad de medida para el documento PDF (milímetros)
                    format: 'a4', // Formato del documento (A4)
                    orientation: 'portrait', // Orientación del documento (vertical)
                    compressPDF: true, // Comprimir el PDF para reducir el tamaño del archivo (opcional)
                    putOnlyUsedFonts: true, // Incluir solo las fuentes utilizadas en el PDF (opcional)
                    precision: 16 // Precisión de los cálculos de posicionamiento (opcional)
                }
            });

            // Generar el PDF y guardarlo
            pdf.save();

            // Mostrar el formulario después de generar el PDF (opcional)
            document.getElementById('budgetForm').style.display = 'block';
        });
    </script>
</body>

</html>

