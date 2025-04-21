<link rel="stylesheet" href="http://localhost:8080/ProyectoMVC/css/Mantenimiento.css">
<?php 
include "database.php";
include "menu_manager.php";

// Función para eliminar una persona
function eliminarPersona($Cedula, $conn) {
    $consulta = "DELETE FROM Persona WHERE Cedula='$Cedula'";
    $resultado = $conn->query($consulta);

    if ($resultado) {
        return true; // La eliminación fue exitosa
    } else {
        return false; // Hubo un error al eliminar la persona
    }
}

// Verificar si se ha enviado una solicitud para eliminar una persona
if(isset($_GET['action']) && $_GET['action'] == 'eliminar' && isset($_GET['Cedula'])) {
    $Cedula = $_GET['Cedula'];
    $eliminacionExitosa = eliminarPersona($Cedula, $conn);

    if ($eliminacionExitosa) {
        echo "<div class='success-message'>¡Datos eliminados correctamente!</div>";
    } else {
        $error_message = "Error al eliminar los datos: " . $conn->error;
        if (strpos($conn->error, 'foreign key constraint') !== false) {
            $error_message = "<strong> ¡ERROR! </strong> <br> No se puede eliminar el registro de este colaborador, <br> está asociado a un 'usuario' o 'proyecto' del sistema.";
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
$consulta = "SELECT persona.Cedula,
    persona.Nombre_Persona, 
    persona.Apellido1_Persona, 
    persona.Apellido2_Persona,
    persona.Telefono,
    persona.Correo_Electronico,
    estado_persona.Descripcion_Estado_Persona
    FROM persona
    JOIN estado_persona ON persona.Estado_Persona_idEstado_Persona = estado_persona.idEstado_Persona
    ORDER BY estado_persona.Descripcion_Estado_Persona ASC, persona.Nombre_Persona ASC";
$resultado = $conn->query($consulta);

// Verificar si la consulta devuelve resultados
if (!$resultado) {
    echo "Error al ejecutar la consulta: " . $conn->error;
}
?>

<div class="content">
    <div class="titulo">
        <h4>Mantenimiento Colaborador</h4>
        <button name="btn_regreso" onclick="goBack()"><img src="/ProyectoMVC/img/RegresarIcono.svg" width="25" height="25" alt="Icono Hover"> Regresar</button>

        <input type="text" id="searchInput" placeholder="Buscar Colaborador...">
        <button class="agregar-btn"><a  href="AgregarPersonas.php" style="color: white; text-decoration: none;">Agregar Personas</a></button> <!-- Botón Agregar Personas -->
    </div>

    <table id="table" class="table">
        <thead>
            <tr>
                <th>Cedula</th>
                <th>Nombre</th>
                <th>Primer Apellido</th>
                <th>Segundo Apellido</th>
                <th>Teléfono</th>
                <th>Correo Electrónico</th>
                <th>Estado de la Persona</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php 
        while($row = $resultado->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['Cedula']; ?></td>
                <td><?php echo $row['Nombre_Persona']; ?></td>
                <td><?php echo $row['Apellido1_Persona']; ?></td>
                <td><?php echo $row['Apellido2_Persona']; ?></td>
                <td><?php echo $row['Telefono']; ?></td>
                <td><?php echo $row['Correo_Electronico']; ?></td>
                <td><?php echo $row['Descripcion_Estado_Persona']; ?></td>
                <td>
                    <a class="editar-btn" href='EditarPersona.php?Cedula=<?php echo $row['Cedula']; ?>'><img src="/ProyectoMVC/img/2.svg" width="27" height="27" alt="Icono Normal"></a>
                    <button class="eliminar-btn" onclick="eliminarPersona('<?php echo $row['Cedula']; ?>')"><img src="/ProyectoMVC/img/1.svg" width="27" height="27" alt="Icono Normal"></button>
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

    // Función para eliminar una persona y actualizar la página después de 2 segundos
    function eliminarPersona(Cedula) {
        if (confirm("¿Estás seguro de que deseas eliminar esta persona?")) {
            location.href = 'Mantenimiento.php?action=eliminar&Cedula=' + Cedula;
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
        window.location.href = 'MenuMantenimientos.php';
    }

</script>

</body>
</html>



