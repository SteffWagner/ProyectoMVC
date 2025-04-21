<link rel="stylesheet" href="http://localhost:8080/ProyectoMVC/css/Presupuesto.css">
<?php 
include "database.php";
include "menu_manager.php";

// Función para eliminar un presupuesto
function eliminarPresupuesto($ID, $conn) {
    $consulta = "DELETE FROM presupuesto WHERE idPresupuesto ='$ID'";
    $resultado = $conn->query($consulta);

    if ($resultado) {
        return true; // La eliminación fue exitosa
    } else {
        return false; // Hubo un error al eliminar la persona
    }
}

// Verificar si se ha enviado una solicitud para eliminar un presupuesto
if(isset($_GET['action']) && $_GET['action'] == 'eliminar' && isset($_GET['idPresupuesto'])) {
    $ID = $_GET['idPresupuesto'];
    $eliminacionExitosa = eliminarPresupuesto($ID, $conn);

    if ($eliminacionExitosa) {
        echo "<div class='success-message'></div>";
    } else {
        $error_message = "Error al eliminar los datos: " . $conn->error;
        if (strpos($conn->error, 'foreign key constraint') !== false) {
            $error_message = "<strong> ¡ERROR! </strong> <br> No se puede eliminar el registro de este presupuesto, <br> está asociado a un usuario del sistema.";
            // Mostrar el mensaje de error dentro del modal
            echo "<div id='modalBackground' class='modal-background'></div>";
            echo "<div id='errorModal' class='error-content'>
                    <p>$error_message</p>
                    <button class='error-btn' onclick='cerrarModal()'>Aceptar</button>
                </div>";
            echo "<script>document.getElementById('modalBackground').style.display = 'block';</script>";
            echo "<script>document.getElementById('errorModal').style.display = 'block';</script>";
        } else {
            echo "<div class='error-message'>$error_message</div>";
        }
    }
}

// Consulta SQL
$consulta = "SELECT 
presupuesto.idPresupuesto,
presupuesto.Monto,
presupuesto.DescripcionP,
proyecto.Nombre,
tarea.Descripcion
FROM presupuesto
JOIN proyecto ON presupuesto.Proyecto_idproyecto = proyecto.idproyecto
JOIN tarea  ON presupuesto.Tarea_idTarea = tarea.idTarea;";

$resultado = $conn->query($consulta);

// Verificar si la consulta devuelve resultados
if (!$resultado) {
    echo "Error al ejecutar la consulta: " . $conn->error;
}
?>

<div class="content">
    <div class="titulo">
        <h4>Buscar Presupuesto</h4>
        <input type="text" id="searchInput" placeholder="Buscar Presupuesto...">
        <!-- <button class="agregar-btn"><a  href="AgregarPresupuesto.php" style="color: white; text-decoration: none;">Agregar Presupuesto</a></button> -->
    </div>
    <button class="agregar-btn"><a href="GestionarPresupuestos.php" style="color: white; text-decoration: none;">Regresar</a></button>

    <table id="table" class="table">
        <thead>
            <tr>
                <th>Presupuesto Tarea</th>
                <th>Descripcion</th>
                <th>Nombre Proyecto</th>
                <th>Nombre Tarea</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php 
        while($row = $resultado->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['Monto']; ?></td>
                <td><?php echo $row['DescripcionP']; ?></td>
                <td><?php echo $row['Nombre']; ?></td>
                <td><?php echo $row['Descripcion']; ?></td>
                <td>
                    <a class="editar-btn" href='EditarPresupuesto.php?idPresupuesto=<?php echo $row['idPresupuesto']; ?>'><img src="/ProyectoMVC/img/2.svg" width="27" height="27" alt="Icono Normal"></a>
                    <button class="eliminar-btn" onclick="eliminarPresupuesto('<?php echo $row['idPresupuesto']; ?>')"><img src="/ProyectoMVC/img/1.svg" width="27" height="27" alt="Icono Normal"></button>
                    <button class="ver-btn" onclick="verPresupuesto('<?php echo $row['idPresupuesto']; ?>')"><img src="/ProyectoMVC/img/3.svg" width="27" height="27" alt="Icono Normal"></button>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php 
// No se necesita cerrar la conexión, ya que está incluida en database.php
?>
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

    // Función para eliminar un presupuesto y actualizar la página después de 2 segundos
    function eliminarPresupuesto(ID) {
        if (confirm("¿Estás seguro de que deseas eliminar esta presupuesto?")) {
            location.href = 'Presupuesto.php?action=eliminar&idPresupuesto=' + ID;
        }
    }

    // Función para cerrar el modal
    function cerrarModal() {
        const modalBackground = document.getElementById('modalBackground');
        const errorModal = document.getElementById('errorModal');
        modalBackground.style.display = 'none';
        errorModal.style.display = 'none';
    }
    // Funcion Ver Presupuesto
    function verPresupuesto(idPresupuesto) {
        location.href = 'VistaPresupuesto.php?idPresupuesto=' + idPresupuesto;
    }
</script>

</body>
</html>