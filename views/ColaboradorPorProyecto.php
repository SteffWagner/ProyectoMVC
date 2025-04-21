<link rel="stylesheet" href="http://localhost:8080/ProyectoMVC/css/ColaboradorPorProyecto.css">
<?php 
include "database.php";
include "menu_manager.php";

// Función para eliminar un colaborador por proyecto
function eliminarColaboradorPorProyecto($ID_Proyecto, $Cedula, $conn) {
    $consulta = "DELETE FROM Proyecto_tiene_Personas WHERE Proyecto_idproyecto=? AND Persona_Cedula=?";
    
    // Preparar la consulta
    $stmt = $conn->prepare($consulta);

    // Verificar si se preparó correctamente la consulta
    if (!$stmt) {
        echo "Error al preparar la consulta: " . $conn->error;
        return false;
    }

    // Asignar los parámetros y ejecutar la consulta
    $stmt->bind_param("is", $ID_Proyecto, $Cedula);
    $stmt->execute();

    // Verificar si la eliminación fue exitosa
    if ($stmt->affected_rows > 0) {
        return true; // La eliminación fue exitosa
    } else {
        return false; // Hubo un error al eliminar el colaborador por proyecto
    }
}

// Verificar si se ha enviado una solicitud para eliminar un colaborador por proyecto
if(isset($_GET['action']) && $_GET['action'] == 'eliminar' && isset($_GET['ID_Proyecto']) && isset($_GET['Cedula'])) {
    $ID_Proyecto = $_GET['ID_Proyecto'];
    $Cedula = $_GET['Cedula'];
    $eliminacionExitosa = eliminarColaboradorPorProyecto($ID_Proyecto, $Cedula, $conn);

    if ($eliminacionExitosa) {
        echo "<div id='modalBackground' class='modal-background'></div>";
        echo "<div id='errorModal' class='error-content'>
                <p>¡Datos eliminados correctamente!</p>
                <button class='error-btn' onclick='cerrarModal()'>Aceptar</button>
            </div>";
        echo "<script>document.getElementById('errorModal').style.display = 'block';</script>";
        echo "<script>document.getElementById('modalBackground').style.display = 'block';</script>";
    } else {
        $error_message = "<strong> ¡ERROR! </strong> <br> No se pudo eliminar el registro. Verifica antes de eliminar.";
        
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

// Consulta SQL para obtener la lista de colaboradores por proyecto
$consulta = "SELECT Proyecto_tiene_Personas.Proyecto_idproyecto, 
                    Proyecto_tiene_Personas.Persona_Cedula,
                    CONCAT(Persona.Nombre_Persona, ' ', Persona.Apellido1_Persona, ' ', Persona.Apellido2_Persona) AS Nombre_Colaborador,
                    Proyecto.Nombre AS Nombre_Proyecto,
                    Proyecto.Fecha_entrega
            FROM Proyecto_tiene_Personas
            INNER JOIN Persona ON Proyecto_tiene_Personas.Persona_Cedula = Persona.Cedula
            INNER JOIN Proyecto ON Proyecto_tiene_Personas.Proyecto_idproyecto = Proyecto.idproyecto";
$resultado = $conn->query($consulta);

// Verificar si la consulta devuelve resultados
if (!$resultado) {
    echo "Error al ejecutar la consulta: " . $conn->error;
}
?>

<div class="content">
    <div class="titulo">
        <h4>Mantenimiento Colaborador por Proyecto</h4>
        <button name="btn_regreso" onclick="goBack()"><img src="/ProyectoMVC/img/RegresarIcono.svg" width="25" height="25" alt="Icono Hover"> Regresar</button>

        <input type="text" id="searchInput" placeholder="Buscar proyecto o colaborador... ">
        <a class="editar-btn" href='EditarColaboradorPorProyecto.php?idproyecto=<?php echo $proyecto_id; ?>'><img src="/ProyectoMVC/img/2.svg" width="27" height="27" alt="Icono Normal"></a>

    </div>

    <table id="table" class="table">
        <thead>
            <tr>
                <th>ID del Proyecto</th>
                <th>Nombre Proyecto</th>
                <th>Cedula</th>
                <th>Nombre Colaborador</th>
                <th>Fecha de Entrega</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php 
        while($row = $resultado->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['Proyecto_idproyecto']; ?></td>
                <td><?php echo $row['Nombre_Proyecto']; ?></td>
                <td><?php echo $row['Persona_Cedula']; ?></td>
                <td><?php echo $row['Nombre_Colaborador']; ?></td>
                <td><?php echo $row['Fecha_entrega']; ?></td>
                <td>
                    <button class="eliminar-btn" onclick="eliminarColaboradorPorProyecto('<?php echo $row['Proyecto_idproyecto']; ?>', '<?php echo $row['Persona_Cedula']; ?>')"><img src="/ProyectoMVC/img/1.svg"  width="25" height="25" alt="Eliminar"></button>
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

    // Función para eliminar un colaborador por proyecto y actualizar la página después de 2 segundos
    function eliminarColaboradorPorProyecto(ID_Proyecto, Cedula) {
        if (confirm("¿Estás seguro de que quieres eliminar a este colaborador de este proyecto?")) {
            location.href = 'ColaboradorPorProyecto.php?action=eliminar&ID_Proyecto=' + ID_Proyecto + '&Cedula=' + Cedula;
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
























