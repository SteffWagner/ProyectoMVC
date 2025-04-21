<?php
include "menu_manager.php";
include "database.php";

// Validar el id de la tarea
$idTarea = isset($_GET['idTarea']) ? intval($_GET['idTarea']) : 0;

// Consulta para obtener los detalles de la tarea
$consultaTarea = $conn->prepare("SELECT tarea.*, proyecto.nombre AS nombre_proyecto, recurso.nombre AS nombre_recurso, estado_tarea.Nombre_Estado_tarea 
                                FROM tarea
                                JOIN proyecto ON tarea.Proyecto_idproyecto = proyecto.idProyecto
                                LEFT JOIN recurso ON tarea.Recurso_idRecurso = recurso.idRecurso
                                JOIN estado_tarea ON tarea.Estado_tarea_idEstado_tarea = estado_tarea.idEstado_tarea
                                WHERE idTarea = ?");
$consultaTarea->bind_param("i", $idTarea);
if (!$consultaTarea->execute()) {
    // Manejar el error de consulta
    die("Error al obtener los detalles de la tarea: " . $conn->error);
}
$resultadoTarea = $consultaTarea->get_result();
$tarea = $resultadoTarea->fetch_assoc();

// Consulta para obtener los colaboradores asociados a la tarea
$consultaColaboradores = $conn->prepare("SELECT persona.Nombre_Persona, persona.Apellido1_Persona, persona.Apellido2_Persona 
                                        FROM tarea_tiene_personas
                                        JOIN persona ON tarea_tiene_personas.Persona_Cedula = persona.Cedula
                                        WHERE tarea_tiene_personas.tarea_idtarea = ?");
$consultaColaboradores->bind_param("i", $idTarea);
if (!$consultaColaboradores->execute()) {
    // Manejar el error de consulta
    die("Error al obtener los colaboradores de la tarea: " . $conn->error);
}
$resultadoColaboradores = $consultaColaboradores->get_result();
$colaboradores = [];
while ($row = $resultadoColaboradores->fetch_assoc()) {
    $colaboradores[] = $row;
}
$consultaColaboradores->close();
$consultaTarea->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle de la Tarea</title>
    <link rel="stylesheet" href="http://localhost:8080/ProyectoMVC/css/DetalleTarea.css">
</head>
<body>
<div class="container">
    <div class="detalle-tarea">
        <img src="/ProyectoMVC/img/LogoAlpha.png" alt="Logo" id="company-logo" style="max-width: 200px; margin: 0 auto 20px;" />
        <h1>Detalle de la Tarea</h1>
        <?php if ($tarea): ?>
            <h2><?= htmlspecialchars($tarea['nombre_proyecto']) ?></h2>
            <ul>
                <li><strong>Descripción:</strong> <?= htmlspecialchars($tarea['Descripcion']) ?></li>
                <li><strong>Fecha de Inicio:</strong> <?= date('d/m/Y', strtotime($tarea['Fecha_inicio'])) ?></li>
                <li><strong>Fecha de Fin:</strong> <?= date('d/m/Y', strtotime($tarea['Fecha_fin'])) ?></li>
                <li><strong>Recurso:</strong> <?= htmlspecialchars($tarea['nombre_recurso'] ?? 'Sin recurso') ?></li>
                <li><strong>Estado de la Tarea:</strong> <?= htmlspecialchars($tarea['Nombre_Estado_tarea']) ?></li>
            </ul>
        <?php else: ?>
            <p>No se encontró la tarea especificada.</p>
        <?php endif; ?>
        <h1>Colaboradores:</h1>
        <?php if (count($colaboradores) > 0): ?>
            <ul>
                <?php foreach ($colaboradores as $colaborador): ?>
                    <li><?= htmlspecialchars($colaborador['Nombre_Persona']) . " " . htmlspecialchars($colaborador['Apellido1_Persona']) . " " . htmlspecialchars($colaborador['Apellido2_Persona']) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No hay colaboradores asignados a esta tarea.</p>
        <?php endif; ?>
    </div>
    <button class="btnGP" onclick="window.location.href='GestionarTareas.php'">Volver a la lista de tareas</button>

</div>

<!-- Modal -->
<div class="modal-background" id="modalBackground" onclick="cerrarModal()"></div>
<div class="error-content" id="modalContent">
    <span class="close" onclick="cerrarModal()">&times;</span>
    <div id="detallesTarea">
        <!-- Dinámicamente cargar contenido aquí -->
    </div>
    <button class="error-btn" onclick="cerrarModal()">Cerrar</button>
</div>


<script>
    function cerrarModal() {
        document.getElementById('modalBackground').style.display = 'none';
        document.getElementById('modalContent').style.display = 'none';
    }
</script>
</body>
</html>
