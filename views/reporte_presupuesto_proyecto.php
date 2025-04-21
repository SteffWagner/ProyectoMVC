<?php
include "menu_manager.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de presupuesto asignado por proyecto</title>
    <link rel="stylesheet" href="http://localhost:8080/ProyectoMVC/css/styles.css">
    <!-- <link rel="stylesheet" href="http://localhost/ProyectoMVC/css/GestionarPresupuesto.css"> -->
    <!-- Incluir html2pdf -->
    <script src="http://localhost/ProyectoMVC/views/html2pdf.js-/dist/html2pdf.bundle.min.js"></script>
    <style>
        /* Estilos para ocultar elementos que no se imprimirán */
        @media print {
            body * {
                visibility: hidden;
            }
            #printableArea, #printableArea * {
                visibility: visible;
            }
            #printableArea {
                position: absolute;
                left: 0;
                top: 0;
            }
        } 

        table {
            border-collapse: collapse; /* Colapsar bordes para evitar espacios entre celdas */
            width: 100%; /* Ancho total de la tabla */
        }
        th, td {
            border: 1px solid ; /* Borde de 1px sólido y color gris claro */
            padding: 8px; /* Espaciado interno dentro de las celdas */
            text-align: center; 
        }
        .container_PDF{
            display: flex;
            flex-direction: column;
            align-items: center; /* Centra los elementos horizontalmente */
            margin: 55px;
            border: 2px solid green; /* Borde sólido de 2px de ancho y color verde */
        }
    </style>
</head>
<body>
    <header>
        <h1>Reporte de Presupuesto Proyecto</h1>
    </header>
    <div class="container">
    <div class="container_PDF">
    
        <?php
        // Incluir el archivo de conexión a la base de datos
        include "database.php";

        // Obtener los valores mínimos y máximos del formulario
        $min = isset($_POST['min']) ? intval($_POST['min']) : 0;
        $max = isset($_POST['max']) ? intval($_POST['max']) : PHP_INT_MAX;

        // Consulta SQL para obtener proyectos dentro del rango de presupuesto
        $sql = "SELECT Nombre, Monto_Presupuesto, Monto_Activo, Fecha_entrega FROM proyecto WHERE Monto_Presupuesto >= $min AND Monto_Presupuesto <= $max";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            echo '<div id="printableArea">';
            echo '<h2>Resultados del reporte</h2>';
            echo '<table>';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Proyecto</th>';
            echo '<th>Presupuesto Inicial</th>';
            echo '<th>Presupuesto Activo</th>';
            echo '<th>Fecha de Entrega</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            // Array para almacenar datos de proyectos en formato JSON
            $proyectos = array();

            while ($row = $result->fetch_assoc()) {
                // Agregar cada proyecto como un objeto al array
                $proyectos[] = $row;

                // Mostrar cada proyecto en la tabla dentro de #printableArea
                echo '<tr>';
                echo '<td>' . $row["Nombre"] . '</td>';
                echo '<td>$' . $row["Monto_Presupuesto"] . '</td>';
                echo '<td>$' . $row["Monto_Activo"] . '</td>';
                echo '<td>' . $row["Fecha_entrega"] . '</td>';
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
            echo '</div>'; // Cierre de #printableArea
        } else {
            echo "<p>No se encontraron proyectos dentro del rango de presupuesto especificado.</p>";
        }

        // Convertir el array de proyectos a formato JSON para procesamiento en JavaScript
        $proyectos_json = json_encode($proyectos);
        ?>

        
        <br><br>
        <p>© 2024 Alpha</p>
    </div>
    <!-- Botón para generar el PDF -->
    <br><button id="generatePDF">Generar PDF</button>
    </div>
    <script>
        // Función para generar el PDF
        document.getElementById('generatePDF').addEventListener('click', function() {
            // Ocultar el contenedor antes de generar el PDF
            document.querySelector('.container_PDF').style.display = 'none';

            // Obtener los proyectos en formato JSON desde PHP
            var proyectosJSON = <?php echo $proyectos_json; ?>;

            // Mostrar los proyectos en una tabla dentro de #printableArea
            mostrarProyectos(proyectosJSON);

            // Crear un nuevo objeto html2pdf con las opciones deseadas para generar el PDF
            var pdf = new html2pdf(document.getElementById('printableArea'), {
                margin: [10, 20, 10, 20], // Establecer márgenes top, right, bottom, left
                html2canvas: {
                    scale: 3, // Escala de captura de html2canvas (aumenta la resolución)
                    letterRendering: true, // Mejora la renderización de texto
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

            // Generar el PDF y guardar
            pdf.save();

            // Mostrar el contenedor después de generar el PDF (opcional)
            document.querySelector('.container_PDF').style.display = 'flex';
        });

        function mostrarProyectos(data) {
        var html = '<img src="/ProyectoMVC/img/LogoAlpha.png" alt="Logo" id="company-logo" style="max-width: 200px; margin: 0 auto 20px;" />';
        html += '<h2>Resultados del reporte</h2>';
        html += '<table>';
        html += '<thead>';
        html += '<tr>';
        html += '<th>Proyecto</th>';
        html += '<th>Presupuesto Inicial</th>';
        html += '<th>Presupuesto Activo</th>';
        html += '<th>Fecha de Entrega</th>';
        html += '</tr>';
        html += '</thead>';
        html += '<tbody>';
        
        // Iterar sobre cada proyecto en el arreglo de datos JSON
        data.forEach(function(proyecto) {
            html += '<tr>';
            html += '<td>' + proyecto.Nombre + '</td>';
            html += '<td>$' + proyecto.Monto_Presupuesto + '</td>';
            html += '<td>$' + proyecto.Monto_Activo + '</td>';
            html += '<td>' + proyecto.Fecha_entrega + '</td>';
            html += '</tr>';
        });

        html += '</tbody>';
        html += '</table><br>';

        // Mostrar el HTML generado dentro del contenedor #printableArea
        document.getElementById('printableArea').innerHTML = html;
    }

    </script>
</body>
</html>
