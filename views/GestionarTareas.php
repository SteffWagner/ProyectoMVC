<?php
// session_start();
ob_start();
include "menu_manager.php"; 
include "database.php";

if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['idTarea'])) {
    $idTarea = (int) $_GET['idTarea'];

    // Iniciar la transacción
    $conn->begin_transaction();
    try {
        // Primero eliminamos las personas asociadas a la tarea
        $consultaRelacion = $conn->prepare("DELETE FROM tarea_tiene_personas WHERE tarea_idtarea = ?");
        $consultaRelacion->bind_param("i", $idTarea);
        $consultaRelacion->execute();
        $consultaRelacion->close();

        // Luego eliminamos la tarea
        $consultaTarea = $conn->prepare("DELETE FROM tarea WHERE idTarea = ?");
        $consultaTarea->bind_param("i", $idTarea);
        if ($consultaTarea->execute()) {
            // Si todo va bien, hacemos commit de la transacción
            $conn->commit();
            $_SESSION['mensaje'] = 'Tarea eliminada con éxito.';
            $_SESSION['tipoMensaje'] = 'success';
        } else {
            // Si hay un error, hacemos rollback
            $conn->rollback();
            $_SESSION['mensaje'] = 'Error al eliminar la tarea: ' . $conn->error;
            $_SESSION['tipoMensaje'] = 'error';
        }
        $consultaTarea->close();
    } catch (Exception $e) {
        // Si ocurre cualquier excepción, también hacemos rollback
        $conn->rollback();
        $_SESSION['mensaje'] = 'Error al eliminar la tarea: ' . $e->getMessage();
        $_SESSION['tipoMensaje'] = 'error';
    }

    // Redirigimos al usuario a la página de gestión de tareas
    header("Location: GestionarTareas.php");
    exit();
}


$consulta = "SELECT tarea.idTarea, proyecto.nombre AS nombre_proyecto, tarea.Descripcion FROM tarea
            JOIN proyecto ON tarea.Proyecto_idproyecto = proyecto.idProyecto";
$resultado = $conn->query($consulta);
?>

<style>
    body {
        background-image: url("/ProyectoMVC/img/Parallax44.svg");
        height: 100%;
        background-attachment: fixed;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
    }
</style>


    <link rel="stylesheet" href="http://localhost:8080/ProyectoMVC/css/GestionarTareas.css">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="titulo">
    <h1>Gestionar Tareas</h1>
    <input type="text" id="searchInput" placeholder="Buscar tarea...">
    <a class="crear-btn" href='CrearTarea.php'><i class="mdi mdi-plus-box mdi-icon"></i>CREAR NUEVA TAREA</a>
</div>

<table id="tasksTable">
    <thead>
        <tr>
            <th>Tarea</th>
            <th>Nombre Proyecto</th>
            <th>Descripción de la Tarea</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
    <?php if ($resultado && $resultado->num_rows > 0): ?>
        <?php while ($fila = $resultado->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($fila["idTarea"]) ?></td>
            <td><?= htmlspecialchars($fila["nombre_proyecto"]) ?></td>
            <td><?= htmlspecialchars($fila["Descripcion"]) ?></td>
            <td>
                <a class="ver-btn" href='DetalleTarea.php?idTarea=<?= $fila["idTarea"] ?>'>
                    <img src="/ProyectoMVC/img/VerTarea.svg" width="27" height="27" alt="Ver">
                </a>
                <a class="editar-btn" href='EditarTarea.php?idTarea=<?= $fila["idTarea"] ?>'>
                    <img src="/ProyectoMVC/img/Edit2.svg" width="27" height="27" alt="Editar">
                </a>
                <button class="eliminar-btn" onclick="confirmarEliminacion('<?= $fila['idTarea'] ?>')">
                    <img src="/ProyectoMVC/img/1.svg" width="27" height="27" alt="Eliminar">
                </button>
            </td>
        </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="4">No hay tareas disponibles</td></tr>
    <?php endif; ?>
    </tbody>
</table>

<script>
function confirmarEliminacion(idTarea) {
    Swal.fire({
        icon: 'warning',
        title: '¿Estás seguro?',
        text: 'No podrás revertir esto!',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminarlo!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '?action=delete&idTarea=' + idTarea;
        }
    });
}
</script>

<?php if (isset($_SESSION['mensaje']) && isset($_SESSION['tipoMensaje'])): ?>
<script>
    Swal.fire({
        icon: '<?= $_SESSION['tipoMensaje'] ?>',
        title: '<?= $_SESSION['mensaje'] ?>',
        confirmButtonText: 'Ok'
    });
    <?php unset($_SESSION['mensaje'], $_SESSION['tipoMensaje']); ?>
</script>
<?php endif; ?>

</body>
</html>

<?php $conn->close(); ?>
