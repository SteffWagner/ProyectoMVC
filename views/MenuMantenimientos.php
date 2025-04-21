<link rel="stylesheet" href="http://localhost:8080/ProyectoMVC/css/MenuMantenimientos.css">

<style>
/* Estilos adicionales para el contenedor del gráfico */
.container {
    max-width: 800px;
    margin: 50px auto;
    text-align: center;
}
h3 {
    margin-bottom: 20px;
}
</style>
</head>
<body>

<?php
// Incluir archivos y conexión a la base de datos
include "database.php";
include "Dark_MenuDashboard.php";

// Consulta para obtener la cantidad total de proyectos
$consultaProyectos = "SELECT COUNT(idproyecto) AS cantidadProyectos FROM Proyecto";
$resultadoProyectos = $conn->query($consultaProyectos);

// Verificar si la consulta de proyectos se ejecutó correctamente
if ($resultadoProyectos->num_rows > 0) {
    // Obtener el resultado de la consulta de proyectos
    $rowProyectos = $resultadoProyectos->fetch_assoc();
    $cantidadProyectos = $rowProyectos["cantidadProyectos"];
} else {
    $cantidadProyectos = 0;
}

// Consulta para obtener la cantidad total de tareas
$consultaTareas = "SELECT COUNT(idtarea) AS cantidadTareas FROM Tarea";
$resultadoTareas = $conn->query($consultaTareas);

// Verificar si la consulta de tareas se ejecutó correctamente
if ($resultadoTareas->num_rows > 0) {
    // Obtener el resultado de la consulta de tareas
    $rowTareas = $resultadoTareas->fetch_assoc();
    $cantidadTareas = $rowTareas["cantidadTareas"];
} else {
    $cantidadTareas = 0;
}

// Consulta para obtener la cantidad total de recursos
$consultaRecursos = "SELECT COUNT(idRecurso) AS cantidadRecursos FROM Recurso";
$resultadoRecursos = $conn->query($consultaRecursos);

// Verificar si la consulta de recursos se ejecutó correctamente
if ($resultadoRecursos->num_rows > 0) {
    // Obtener el resultado de la consulta de recursos
    $rowRecursos = $resultadoRecursos->fetch_assoc();
    $cantidadRecursos = $rowRecursos["cantidadRecursos"];
} else {
    $cantidadRecursos = 0;
}
?>

<div class="content">
    <div class="titulo">
        <h2>Mantenimientos</h2>
        <br>
        <button class="mantenimientoscolaborador-btn"><a href="Mantenimiento.php" style="color: white; text-decoration: none;">Mantenimiento Colaborador</a></button> <!-- Botón Matenimientos -->
        <button class="eliminarP-btn"><a href="EliminarProyectos.php" style="color: white; text-decoration: none;">Mantenimiento Proyectos</a></button> <!-- Botón - Eliminar Proyectos -->
        <button class="CPProyecto-btn"><a href="ColaboradorPorProyecto.php" style="color: white; text-decoration: none;">Colaborador por Proyecto</a></button> <!-- Botón - Personas por proyecto  -->
    </div>
    <br>

    <div class="container">
        <h3>Gráfico de Cantidad de Proyectos, Tareas y Recursos</h3>
        <br>
        <!-- Mostrar la cantidad de proyectos, tareas y recursos -->
        <p>Cantidad de Proyectos: <?php echo $cantidadProyectos; ?> / Cantidad de Tareas: <?php echo $cantidadTareas; ?> 
        / Cantidad de Recursos: <?php echo $cantidadRecursos; ?></p>
        <!-- Contenedor del gráfico -->
        <div id="chartContainer" style="height: 300px; width: 100%;"></div>
    </div>
</div>

<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Crear el gráfico con la cantidad de proyectos, tareas y recursos
    crearGrafico(<?php echo $cantidadProyectos; ?>, <?php echo $cantidadTareas; ?>, <?php echo $cantidadRecursos; ?>);
});

function crearGrafico(cantidadProyectos, cantidadTareas, cantidadRecursos) {
    // Crea el gráfico utilizando CanvasJS
    var chart = new CanvasJS.Chart("chartContainer", {
        animationEnabled: true,
        title: {
            text: "Cantidad Total de Proyectos, Tareas y Recursos"
        },
        data: [{
            type: "pie", // Cambiamos el tipo de gráfico a "pie"
            indexLabel: "{label}: {y}", // Mostramos el label y el valor
            dataPoints: [
                { label: "Total de Proyectos", y: cantidadProyectos },
                { label: "Total de Tareas", y: cantidadTareas },
                { label: "Total de Recursos", y: cantidadRecursos }
            ]
        }]
    });
    chart.render();
}
</script>

</body>
</html>

