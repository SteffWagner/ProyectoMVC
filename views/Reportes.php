<?php include "menu_manager.php" ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Módulo de Reportes</title>
    <link rel="stylesheet" href="http://localhost:8080/ProyectoMVC/css/styles.css">
    <style>
        body {
            display: flex;
            /* justify-content: center;
            align-items: center; */
            height: 100vh;
            margin: 0;
        }


        .container ul {
            list-style-type: none; /* Quita los puntos de la lista */
            padding: 0;
        }

        .container ul li {
            margin-bottom: 10px;
        }

        h1 {
            text-align: center;
            color: #333;
            font-family: Arial, sans-serif;
            margin-bottom: 20px; /* Espacio inferior para separar del contenido */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Módulo de Reportes</h1><br><br>
        <ul>
            <li><a href="reporte_proyectos_finalizados_periodo.php">Reporte de proyectos finalizados por periodo</a></li>
            <li><a href="reporte_proyectos_por_presupuesto.php">Reporte de proyectos por presupuesto</a></li>   
            <li><a href="reporte_tareas_completadas_proyecto.php">Reporte de tareas completadas por proyecto</a></li>
            <li><a href="reporte_proyectos_abiertos_integrantes.php">Reporte de proyectos incompletos</a></li>
            <li><a href="reporte_presupuesto_tarea.php">Reporte de presupuesto asignado por tarea</a></li>
            <li><a href="reporte_presupuesto_proyecto.php">Reporte de presupuesto asignado por proyecto</a></li>
            <li><a href="reporte_usuarios_activos.php">Reporte de usuarios activos</a></li>            
            <li><a href="Reporte_cantidad_proyectos.php">Reporte de cantidad de proyectos</a></li> 
            <li><a href="Reporte_cantidad_tareas.php">Reporte de cantidad de tareas</a></li>             
            <li><a href="Reporte_cantidad_recursos.php">Reporte de cantidad de recursos</a></li> 
        </ul>
    </div>
</body>
</html>