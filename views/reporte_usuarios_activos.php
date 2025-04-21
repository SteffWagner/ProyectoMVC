<?php
include "menu_manager.php";
include "database.php";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de usuarios activos</title>
    <link rel="stylesheet" href="http://localhost:8080/ProyectoMVC/css/styles.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>
    <header>
        <h1>Reporte de usuarios activos</h1>
    </header>

    <div class="container">
    <div class="container_PDF">
    <img src="/ProyectoMVC/img/LogoAlpha.png" alt="Logo" id="company-logo" style="max-width: 200px; margin: 0 auto 20px;" />
        <table>
            <thead>
                <tr>
                    <th>ID Usuario</th>
                    <th>Nombre de Usuario</th>
                    <th>Fecha de Creación</th>
                    <th>Rol de Usuario</th>
                    <th>Estado del Usuario</th>
                    <th>Cédula de la Persona</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Consulta para obtener los usuarios activos
                $query = "SELECT idUsuario, Nombre_usuario, Fecha_creacion, Rol_usuario_Tipo_usuario, Estado_usuario_IDEstado_usuario, Persona_Cedula FROM usuario WHERE Estado_usuario_IDEstado_usuario = 'Activo'";
                $result = $conn->query($query);

                // Mostrar resultados en la tabla
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["idUsuario"] . "</td>";
                        echo "<td>" . $row["Nombre_usuario"] . "</td>";
                        echo "<td>" . $row["Fecha_creacion"] . "</td>";
                        echo "<td>" . $row["Rol_usuario_Tipo_usuario"] . "</td>";
                        echo "<td>" . $row["Estado_usuario_IDEstado_usuario"] . "</td>";
                        echo "<td>" . $row["Persona_Cedula"] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No se encontraron usuarios activos.</td></tr>";
                }
                ?>
            </tbody>
            </table><br>
            <p>© 2024 Alpha</p>
        </div>
        <!-- Botón para generar el PDF -->
        <button id="generatePDF">Generar PDF</button>
    </div>

    <!-- Incluir html2pdf -->
    <script src="http://localhost/ProyectoMVC/views/html2pdf.js-/dist/html2pdf.bundle.min.js"></script>

    <script>
        // Función para generar el PDF
        document.getElementById('generatePDF').addEventListener('click', function() {
            
            

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
