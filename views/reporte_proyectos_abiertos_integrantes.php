<?php
include "menu_manager.php";
include "database.php"; 

function obtener_proyectos_abiertos_con_integrantes($conn) {
    $sql = "SELECT 
                proyecto.Nombre AS nombre_proyecto, 
                proyecto.Descripcion AS descripcion_proyecto, 
                GROUP_CONCAT(persona.Nombre_Persona) AS integrantes
            FROM 
                proyecto
            JOIN 
                estado_proyecto ON proyecto.Estado_proyecto_idEstado_proyecto = estado_proyecto.idEstado_proyecto
            LEFT JOIN 
                proyecto_tiene_personas ON proyecto.idproyecto = proyecto_tiene_personas.Proyecto_idproyecto
            LEFT JOIN 
                persona ON proyecto_tiene_personas.Persona_Cedula = persona.Cedula
            WHERE 
                estado_proyecto.Nombre_estado_proyecto = 'Incompleto'
            GROUP BY 
                proyecto.idproyecto";

    $result = $conn->query($sql);
    $proyectos = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $proyecto = array(
                'nombre' => $row['nombre_proyecto'],
                'descripcion' => $row['descripcion_proyecto'],
                'integrantes' => $row['integrantes']
            );
            $proyectos[] = $proyecto;
        }
    }

    return $proyectos;
}

$proyectos = obtener_proyectos_abiertos_con_integrantes($conn);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Proyectos Abiertos con Integrantes</title>
    <link rel="stylesheet" href="http://localhost/ProyectoMVC/css/styles.css">
    <!-- Incluir html2pdf -->
    <script src="http://localhost/ProyectoMVC/views/html2pdf.js-/dist/html2pdf.bundle.min.js"></script>
</head>
<body>
    <header>
        <h1>Reporte de Proyectos Abiertos con Integrantes</h1>
    </header>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        table th {
            background-color: #f2f2f2;
        }
    </style>
    <div class="container" >
    
        <div class="container_PDF" id="printableArea">
        <img src="/ProyectoMVC/img/LogoAlpha.png" alt="Logo" id="company-logo" style="max-width: 200px; margin: 0 auto 20px;" />

        <table>
            <thead>
                <tr>
                    <th>Proyecto</th>
                    <th>Descripción</th>
                    <th>Integrantes</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($proyectos as $proyecto) : ?>
                    <tr>
                        <td><?php echo $proyecto['nombre']; ?></td>
                        <td><?php echo $proyecto['descripcion']; ?></td>
                        <td><?php echo $proyecto['integrantes']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    
        <p>© 2024 Alpha</p>

        </div>
        <!-- Botón para generar el PDF -->
        <button id="generatePDF">Generar PDF</button>
        
    </div>

    <script>
        document.getElementById('generatePDF').addEventListener('click', function() {
            // Seleccionar el contenedor que contiene los resultados
            var container = document.getElementById('printableArea');

            // Crear un nuevo objeto html2pdf con las opciones deseadas
            var pdf = new html2pdf(container, {
                margin: [10, 20, 10, 20], // Establecer márgenes top, right, bottom, left
                html2canvas: {
                    scale: 3, // Escala de captura de html2canvas (aumenta la resolución)
                    letterRendering: true // Mejora la renderización de texto
                }});
            
            // Opciones adicionales (opcional)
            var options = {
                margin: 10, // Márgenes del documento en píxeles
                filename: 'reporte_proyectos_abiertos.pdf', // Nombre del archivo PDF
                image: { type: 'jpeg', quality: 0.98 }, // Tipo y calidad de la imagen del PDF
                html2canvas: { scale: 2 }, // Escala del lienzo HTML2Canvas
                jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' } // Unidad, formato y orientación del documento JS PDF
            };
            
            // Generar el PDF y guardarlo
            pdf.from().save();
        });
    </script>
</body>
</html>
