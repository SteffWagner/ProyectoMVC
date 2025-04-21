<?php 
include "menu_manager.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Presupuesto por Tarea</title>
    <link rel="stylesheet" href="http://localhost:8080/ProyectoMVC/css/styles.css">
    <!-- Incluir html2pdf -->
    <script src="http://localhost/ProyectoMVC/views/html2pdf.js-/dist/html2pdf.bundle.min.js"></script>
    <style>
        header {
            margin-left: 265px;
        }
        body {
            font-family: Arial, sans-serif;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        /* Estilo para el formulario */
        #projectForm {
            margin-top: 20px;
        }


        /* Estilo para el elemento select */
        #projectSelect {
            width: 80%; /* Ancho completo del contenedor */
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box; /* Incluye el padding en el ancho total */
            font-size: 16px;
        }


    </style> 
</head>
<body>
    <header>
        <h1>Reporte de Presupuesto por Tarea</h1>
    </header>

    
    <div class="container">
    <!-- Formulario para seleccionar el proyecto -->
    <form id="projectForm">
        <h2 class="select-heading" for="projectSelect">Seleccionar Proyecto</h2>
        <select id="projectSelect" name="projectSelect">
            <?php
            // Incluir el archivo de conexión a la base de datos
            include "database.php";
            // Consulta SQL para obtener la lista de proyectos
            $sql = "SELECT idproyecto, Nombre FROM proyecto";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row["idproyecto"] . "'>" . $row["Nombre"] . "</option>";
                }
            }
            ?>
        </select>
        <button type="submit">Mostrar Tareas</button>
    </form>

        
        <div class="container_PDF">
        <img src="/ProyectoMVC/img/LogoAlpha.png" alt="Logo" id="company-logo" style="max-width: 200px; margin: 0 auto 20px;" />

            <div id="taskTableContainer">
                <!-- Aquí se mostrarán las tareas y presupuestos -->
            </div><br>
        
        <p>© 2024 Alpha</p>

        </div>

        <!-- Botón para generar el PDF -->
        <button id="generatePDF">Generar PDF</button>
    </div>
    <!-- Script para manejar la selección de proyecto y mostrar las tareas -->
    <script>
        // Función para enviar solicitud AJAX al seleccionar un proyecto
        document.getElementById('projectForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevenir el envío por defecto del formulario
            var projectId = document.getElementById('projectSelect').value;

            // Realizar solicitud AJAX usando fetch API
            fetch('get_tasks.php?projectId=' + projectId)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('taskTableContainer').innerHTML = data;
                })
                .catch(error => {
                    console.error('Error al obtener las tareas:', error);
                });
        });
        
    </script>
    <script>
        // Función para generar el PDF
        document.getElementById('generatePDF').addEventListener('click', function() {
            // Ocultar el formulario antes de generar el PDF
            // document.getElementById('budgetForm').style.display = 'none';

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
