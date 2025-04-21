<link rel="stylesheet" href="http://localhost:8080/ProyectoMVC/css/EliminarProyecto.css">

<?php 
include "database.php";
include "menu_manager.php";

// Función para eliminar un proyecto
function eliminarProyecto($idproyecto, $conn) {
    $consulta = "DELETE FROM Proyecto WHERE idproyecto='$idproyecto'";
    $resultado = $conn->query($consulta);

    if ($resultado) {
        return true; // La eliminación fue exitosa
    } else {
        return false; // Hubo un error al eliminar el proyecto
    }
}

// Verificar si se ha enviado una solicitud para eliminar un proyecto
if(isset($_GET['action']) && $_GET['action'] == 'eliminar' && isset($_GET['idproyecto'])) {
    $idproyecto = $_GET['idproyecto'];
    $eliminacionExitosa = eliminarProyecto($idproyecto, $conn);

    if ($eliminacionExitosa) {
        echo "<div id='modalBackground' class='modal-background'></div>";
        echo "<div id='errorModal' class='error-content'>
                <p>¡Datos eliminados correctamente!</p>
                <button class='error-btn' onclick='cerrarModal()'>Aceptar</button>
            </div>";
        echo "<script>document.getElementById('errorModal').style.display = 'block';</script>";
        echo "<script>document.getElementById('modalBackground').style.display = 'block';</script>";
    } else {
        $error_message = "<strong> ¡ERROR! </strong> <br> No se puede eliminar el registro de este Proyecto, <br> está asociado a una tarea, recurso o presupuesto en el sistema, verificar antes de eliminar el proyecto.";
        
        // Mostrar el mensaje de error dentro del modal
        echo "<div id='modalBackground' class='modal-background'></div>";
        echo "<div id='errorModal' class='error-content'>
                <p>$error_message</p>
                <button class='error-btn' onclick='cerrarModal()'>Aceptar</button>
            </div>";
        echo "<script>document.getElementById('modalBackground').style.display = 'block';</script>";
        echo "<script>document.getElementById('errorModal').style.display = 'block';</script>";
    }
}

// Consulta SQL
$consulta = "SELECT Proyecto.idproyecto,
    Proyecto.Nombre,
    Proyecto.Lider_Proyecto,
    Proyecto.Fecha_creacion,
    Proyecto.Fecha_entrega,
    Tipo_Proyecto_Tipo_de_Proyecto
    FROM Proyecto
    JOIN Estado_proyecto ON Proyecto.Estado_proyecto_idEstado_proyecto = Estado_proyecto.idEstado_proyecto
    ORDER BY Proyecto.idproyecto ASC, Tipo_Proyecto_Tipo_de_Proyecto ASC";
$resultado = $conn->query($consulta);

// Verificar si la consulta devuelve resultados
if (!$resultado) {
    echo "Error al ejecutar la consulta: " . $conn->error;
}
?>

<div class="content">
    <div class="titulo">
        <h4>Mantenimiento Proyectos</h4>
        <button name="btn_regreso" onclick="goBack()"><img src="/ProyectoMVC/img/RegresarIcono.svg" width="25" height="25" alt="Icono Hover"> Regresar</button>

        <input type="text" id="searchInput" placeholder="Buscar Proyecto...">
        <button class="agregar-btn"><a href="CrearProyecto.php" style="color: white; text-decoration: none;">Crear Proyectos</a></button> <!-- Botón Agregar Personas -->
    </div>

    <table id="table" class="table">
        <thead>
            <tr>
                <th>ID del Proyecto</th>
                <th>Nombre del Proyecto</th>
                <th>Lider del Proyecto</th>
                <th>Fecha de Creación</th>
                <th>Fecha de Entrega</th>
                <th>Tipo de Proyecto</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php 
        while($row = $resultado->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['idproyecto']; ?></td>
                <td><?php echo $row['Nombre']; ?></td>
                <td><?php echo $row['Lider_Proyecto']; ?></td>
                <td><?php echo $row['Fecha_creacion']; ?></td>
                <td><?php echo $row['Fecha_entrega']; ?></td>
                <td><?php echo $row['Tipo_Proyecto_Tipo_de_Proyecto']; ?></td>
                <td>
                    <a class="editar-btn" href='EditarProyecto.php?idproyecto=<?php echo $row['idproyecto']; ?>'><img src="/ProyectoMVC/img/2.svg" width="27" height="27" alt="Icono Normal"></a>
                    <button class="eliminar-btn" onclick="eliminarProyecto('<?php echo $row['idproyecto']; ?>')"><img src="/ProyectoMVC/img/1.svg" width="27" height="27" alt="Icono Hover"></button>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<div id='modalBackground' class='modal-background'></div>
<div id='errorModal' class='error-content'>
    <p id="errorMessage"></p>
    <button class='error-btn' onclick='cerrarModal()'>Aceptar</button>
</div>

<script>

const searchInput = document.getElementById('searchInput');
    const tasksTable = document.getElementById('table');
    const rows = tasksTable.getElementsByTagName('tr');

    searchInput.addEventListener('keyup', function() {
        const searchText = searchInput.value.toLowerCase();
        for (let i = 1; i < rows.length; i++) {
            const row = rows[i];
            const cells = row.getElementsByTagName('td');
            let found = false;
            for (let j = 0; j < cells.length; j++) {
                const cellText = cells[j].textContent.toLowerCase();
                if (cellText.includes(searchText)) {
                    found = true;
                    break;
                }
            }
            row.style.display = found ? '' : 'none';
        }
    });

    // Función para eliminar una persona y actualizar la página después de 2 segundos
    function eliminarProyecto(idproyecto) {
        if (confirm("¿Estás seguro de que quieres eliminar este proyecto?")) {
            location.href = 'EliminarProyectos.php?action=eliminar&idproyecto=' + idproyecto;
        }
    }

    // Función para cerrar el modal
    function cerrarModal() {
        const modalBackground = document.getElementById('modalBackground');
        const errorModal = document.getElementById('errorModal');
        modalBackground.style.display = 'none';
        errorModal.style.display = 'none';
    }

    // Función para regresar a la página anterior
    function goBack() {
        window.history.back();
    }
</script>

</body>
</html>

























