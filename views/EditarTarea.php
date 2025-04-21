<link rel="stylesheet" href="http://localhost:8080/ProyectoMVC/css/EditarTarea.css">
<?php 
include "menu_manager.php"; 
include "database.php"; 

$mensajeAlerta = '';
$tipoAlerta = '';

$idTarea = isset($_GET['idTarea']) ? $_GET['idTarea'] : null;
$datosTarea = null;

if ($idTarea) {
    $consulta = $conn->prepare("SELECT * FROM tarea WHERE idTarea = ?");
    $consulta->bind_param("i", $idTarea);
    if ($consulta->execute()) {
        $resultado = $consulta->get_result();
        if ($resultado->num_rows > 0) {
            $datosTarea = $resultado->fetch_assoc();
            $datosTarea['Fecha_inicio'] = date('Y-m-d', strtotime($datosTarea['Fecha_inicio']));
            $datosTarea['Fecha_fin'] = date('Y-m-d', strtotime($datosTarea['Fecha_fin']));
        } else {
            $mensajeAlerta = 'No se encontró la tarea especificada.';
            $tipoAlerta = 'error';
        }
    } else {
        $mensajeAlerta = 'Error al obtener los datos de la tarea: ' . $conn->error;
        $tipoAlerta = 'error';
    }
    $consulta->close();
}

if(isset($_POST['Enviar'])) {
    $Descripcion = $_POST['Descripcion'];
    $Fecha_inicio = $_POST['Fecha_inicio'];
    $Fecha_fin = $_POST['Fecha_fin'];
    $Recurso_idRecurso = $_POST['Recurso_idRecurso'];
    if ($Recurso_idRecurso === "NULL") {
        $Recurso_idRecurso = NULL;
    }
    
    $Estado_tarea_idEstado_tarea = $_POST['Estado_tarea_idEstado_tarea'];

    // Convertimos las fechas a objetos DateTime para poder compararlas
    $fechaInicio = new DateTime($Fecha_inicio);
    $fechaFin = new DateTime($Fecha_fin);
    $fechaHoy = new DateTime();
    $fechaHoy->setTime(0, 0, 0);

    if ($fechaInicio < $fechaHoy) {
        $mensajeAlerta = 'La fecha de inicio no puede ser anterior al día de hoy.';
        $tipoAlerta = 'error';
    } elseif ($fechaFin < $fechaInicio) {
        $mensajeAlerta = 'La fecha de fin no puede ser anterior a la fecha de inicio.';
        $tipoAlerta = 'error';
    } else {
        $consulta = $conn->prepare("UPDATE tarea SET Descripcion=?, Fecha_inicio=?, Fecha_fin=?, Recurso_idRecurso=?, Usuario_idUsuario=?, Estado_tarea_idEstado_tarea=? WHERE idTarea=?");
        $consulta->bind_param("sssiisi", $Descripcion, $Fecha_inicio, $Fecha_fin, $Recurso_idRecurso, $Usuario_idUsuario, $Estado_tarea_idEstado_tarea, $idTarea);

        if ($consulta->execute()) {
            $mensajeAlerta = 'Datos de la tarea actualizados correctamente.';
            $tipoAlerta = 'success';
        } else {
            $mensajeAlerta = "Error al actualizar los datos de la tarea: " . htmlspecialchars($conn->error);
            $tipoAlerta = 'error';
        }
        $consulta->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Tarea</title>
    <link rel="stylesheet" href="http://localhost:8080/ProyectoMVC/css/EditarTarea.css">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Tarea</title>
    <link rel="stylesheet" href="http://localhost:8080/ProyectoMVC/css/EditarTarea.css">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php
// Asegúrate de que $datosTarea se haya definido previamente, quizás mediante una consulta a la base de datos
if($datosTarea):
    // Consultas para poblar los selects
    $recursos = $conn->query("SELECT idRecurso, nombre FROM Recurso");
    $estados = $conn->query("SELECT idEstado_tarea, Nombre_Estado_tarea FROM estado_tarea");
?>
<div class="content">
    <div class="titulo">
        <h4>Editar Tarea</h4>
    </div>
    <form action="" method="POST" class="formulario">
        <!-- Descripción -->
        <div class="form-group">
            <label for="Descripcion">Descripción:</label>
            <input type="text" name="Descripcion" id="Descripcion" class="form-control" value="<?= htmlspecialchars($datosTarea['Descripcion']); ?>">
        </div>
        
        <!-- Fecha de inicio -->
        <div class="form-group">
            <label for="Fecha_inicio">Fecha de inicio:</label>
            <input type="date" name="Fecha_inicio" id="Fecha_inicio" class="form-control" value="<?= $datosTarea['Fecha_inicio']; ?>">
        </div>
        
        <!-- Fecha de fin -->
        <div class="form-group">
            <label for="Fecha_fin">Fecha fin:</label>
            <input type="date" name="Fecha_fin" id="Fecha_fin" class="form-control" value="<?= $datosTarea['Fecha_fin']; ?>">
        </div>
        
        <!-- Recurso -->
        <div class="form-group">
    <label for="Recurso_idRecurso">Recurso:</label>
    <select name="Recurso_idRecurso" id="Recurso_idRecurso" class="form-control" required>
    <!-- Opción para "Sin recurso" que se selecciona si el valor actual es NULL -->
    <option value="NULL" style="font-weight: bold; color: red;" <?= is_null($datosTarea['Recurso_idRecurso'])  ? 'selected' : ''; ?> style="font-weight: bold;">Sin recurso</option>

    <?php
    // Suponiendo que $recursos es un resultado de consulta a la base de datos que ya ha sido ejecutado anteriormente
    while ($recurso = $recursos->fetch_assoc()): 
        // Marca la opción como seleccionada si coincide con el valor almacenado para la tarea
        $selected = ($recurso['idRecurso'] == $datosTarea['Recurso_idRecurso']) ? 'selected' : '';
        echo "<option value='{$recurso['idRecurso']}' {$selected}>".htmlspecialchars($recurso['nombre'])."</option>";
    endwhile; 
    ?>
</select>

</div>
        
        
        <!-- Estado de la tarea -->
        <div class="form-group">
            <label for="Estado_tarea_idEstado_tarea">Estado de la Tarea:</label>
            <select name="Estado_tarea_idEstado_tarea" id="Estado_tarea_idEstado_tarea" class="form-control" required>
                <?php while($estado = $estados->fetch_assoc()): ?>
                <option value="<?= $estado['idEstado_tarea'] ?>" <?= $estado['idEstado_tarea'] == $datosTarea['Estado_tarea_idEstado_tarea'] ? 'selected' : ''; ?>><?= htmlspecialchars($estado['Nombre_Estado_tarea']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        
        <input type="hidden" name="idTarea" value="<?= $datosTarea['idTarea']; ?>">
        <input type="submit" name="Enviar" value="Actualizar" class="btnAgregar">
        <a href="http://localhost:8080/ProyectoMVC/views/GestionarTareas.php" class="btnGPA" style="color: white; text-decoration: none;">Regresar</a>
    </form>
</div>
<?php endif; ?>

<?php if (!empty($mensajeAlerta)): ?>
<script>
Swal.fire({
    icon: '<?= $tipoAlerta; ?>',
    title: '<?= $mensajeAlerta; ?>',
    confirmButtonText: 'Cerrar'
}).then((result) => {
    if (result.isConfirmed) {
        window.location.href = 'http://localhost:8080/ProyectoMVC/views/GestionarTareas.php';
    }
});
</script>
<?php endif; ?>

</body>
</html>