<?php 
include "menu_manager.php"; 
include "database.php"; 

// Verificar si se envió el idRecurso para eliminar y el método es GET
if (isset($_GET['idRecurso'])) {
    $idRecurso = $_GET['idRecurso'];

    // Preparar la consulta SQL para eliminar el recurso
    $consultaEliminar = "DELETE FROM recurso WHERE idRecurso = ?";
    $stmt = $conn->prepare($consultaEliminar);
    $stmt->bind_param("i", $idRecurso);

    // Ejecutar la consulta y verificar si fue exitosa
    if ($stmt->execute()) {
        $_SESSION['sweetAlert'] = ['type' => 'success', 'message' => 'Recurso eliminado correctamente.'];
    } else {
        $_SESSION['sweetAlert'] = ['type' => 'error', 'message' => 'Error al eliminar el recurso.'];
    }

    $stmt->close();
    // Redireccionar inmediatamente para evitar re-ejecución en refresh
    echo "<script>window.location = 'GestionarRecursos.php';</script>";
    exit();
}

// Realizar la consulta para obtener los recursos existentes
$consulta = "SELECT idRecurso, nombre AS nombre_recurso, Disponibilidad_ID_Disponibilidad, Tipo_Recurso_idTipo_Recurso, Cantidad_Recurso FROM recurso";
$resultado = $conn->query($consulta);
?>


    <link rel="stylesheet" href="http://localhost:8080/ProyectoMVC/css/GestionarRecursos.css">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
    body {
        background-image: url("/ProyectoMVC/img/ParallaxRecursos.svg");
        height: 100%;
        background-attachment: fixed;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
    }
    
</style>
<div class="titulo">
    <h1>Gestionar Recursos</h1>
    <input type="text" id="searchInput" placeholder="Buscar recurso...">
    <a class="crear-btn" href='CrearRecurso.php'><i class="mdi mdi-plus-box mdi-icon"></i>CREAR NUEVO RECURSO</a>
</div>

<table id="resourcesTable">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Cantidad</th>
            <th>Disponibilidad</th>
            <th>Tipo de Recurso</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
    <?php if ($resultado && $resultado->num_rows > 0): ?>
        <?php while ($fila = $resultado->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($fila["idRecurso"]) ?></td>
                <td><?= htmlspecialchars($fila["nombre_recurso"]) ?></td>
                <td><?= htmlspecialchars($fila["Cantidad_Recurso"]) ?></td>
                <td><?= htmlspecialchars($fila["Disponibilidad_ID_Disponibilidad"]) ?></td>
                <td><?= htmlspecialchars($fila["Tipo_Recurso_idTipo_Recurso"]) ?></td>
                <td>
                    <a class="editar-btn" href='EditarRecurso.php?idRecurso=<?= $fila["idRecurso"] ?>'><img src="/ProyectoMVC/img/Edit2.svg" width="27" height="27" alt="Editar"></a>
                    <!-- Botón para eliminar con SweetAlert -->
                    <button class="eliminar-btn" onclick="confirmarEliminacion(<?= $fila['idRecurso'] ?>)">
                        <img src="/ProyectoMVC/img/1.svg" width="27" height="27" alt="Icono Eliminar">
                    </button>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="6">No hay recursos disponibles</td></tr>
    <?php endif; ?>
    </tbody>
</table>

<script>
function confirmarEliminacion(idRecurso) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: '¡No podrás revertir esto!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminarlo!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Redirección para eliminar el recurso
            window.location.href = `GestionarRecursos.php?idRecurso=${idRecurso}`;
        }
    });
}
</script>

<?php
if(isset($_SESSION['sweetAlert'])): 
    $alert = $_SESSION['sweetAlert'];
    echo "<script>
        Swal.fire({
            icon: '{$alert['type']}',
            title: '{$alert['message']}',
            confirmButtonText: 'Ok'
        });
    </script>";
    unset($_SESSION['sweetAlert']);
endif;
?>

</body>
</html>

<?php $conn->close(); ?>

