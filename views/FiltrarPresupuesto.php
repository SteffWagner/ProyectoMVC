<link rel="stylesheet" href="http://localhost:8080/ProyectoMVC/css/FiltrarPresupuesto.css">
<?php
include "menu_manager.php";// Incluye el archivo del menú y la conexión a la base de datos
include "database.php";
?>

<?php
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
        echo "<div class='success-message'>¡Datos eliminados correctamente!</div>";
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
?>

<?php   
// Verificar si se ha recibido el parámetro idproyecto en la URL
if (isset($_GET['idproyecto'])) {
    $idProyecto = $_GET['idproyecto'];

    // Consulta SQL para obtener los presupuestos asociados a este proyecto
    $sql_presupuestos = "SELECT p.idPresupuesto, p.Monto, p.DescripcionP, t.Descripcion AS TareaDescripcion
                    FROM presupuesto p
                    INNER JOIN tarea t ON p.Tarea_idTarea = t.idTarea
                    WHERE p.Proyecto_idproyecto = $idProyecto";
    $result_presupuestos = $conn->query($sql_presupuestos);

    
    // Verificar si hay presupuestos asociados a este proyecto
    if ($result_presupuestos && $result_presupuestos->num_rows > 0) {
        echo '<div class="content">';
        echo '<div class="titulo">';
        echo '<h2>Presupuestos del Proyecto</h2><br>';            
        echo '<input type="text" id="searchInput" placeholder="Buscar Presupuesto...">';        
        echo '<button class="agregar-btn" onclick="AgregarFiltrarPresupuesto()">Agregar</button>';
        echo '</div>';
        echo '<button class="agregar-btn"><a href="GestionarPresupuestos.php" style="color: white; text-decoration: none;">Regresar</a></button>';        
        echo '    <table id="table" class="table">';
        echo '        <thead>';
        echo '            <tr>';
        echo '                <th>Monto</th>';
        echo '                <th>Descripción</th>';
        echo '                <th>Tarea</th>';
        echo '                <th>Acciones</th>';
        echo '            </tr>';
        echo '        </thead>';
        echo '        <tbody>';
        
        // Iterar sobre los presupuestos y mostrar cada uno en una fila de la tabla
        while ($row_presupuesto = $result_presupuestos->fetch_assoc()) {
            echo '            <tr>';
            echo '                <td>' . $row_presupuesto['Monto'] . '</td>';
            echo '                <td>' . $row_presupuesto['DescripcionP'] . '</td>';
            echo '                <td>' . $row_presupuesto['TareaDescripcion'] . '</td>';
            echo '                <td>';
            echo '                    <a class="editar-btn" href="EditarPresupuesto.php?idPresupuesto=' . $row_presupuesto['idPresupuesto'] . '"><img src="/ProyectoMVC/img/2.svg" width="27" height="27" alt="Editar"></a>';
            echo '                    <button class="eliminar-btn" onclick="eliminarPresupuesto(' . $row_presupuesto['idPresupuesto'] . ')"><img src="/ProyectoMVC/img/1.svg" width="27" height="27" alt="Eliminar"></button>';
            echo '                </td>';
            echo '            </tr>';
        }
        

        echo '        </tbody>';
        echo '    </table>';
        echo '</div>';
    } else {
        // Mostrar la modal de error cuando no se encuentren presupuestos
        echo "<div class='modal' id='myModal'>";
        echo "      <div class='modal-content'>";
        echo "          <p>No se encontraron presupuestos para este proyecto.</p>";
        echo '          <button class="agregar-btn" onclick="AgregarFiltrarPresupuesto()">Agregar</button>';
        echo "          <a href='GestionarPresupuestos.php' onclick='cerrarModal()' style='display: block; text-align: center;'>Salir</a>";
        echo "      </div>";
        echo "</div>";
    }
} else {
    // Si no se proporciona el idproyecto en la URL, mostrar un mensaje de error
    echo '<div class="container">';
    echo '    <p>Error: No se ha especificado un proyecto válido.</p>';
    echo '</div>';
}

$conn->close(); // Cerrar la conexión a la base de datos al finalizar la página
?>
<script>
    function AgregarFiltrarPresupuesto() {
        // Obtener el valor de idproyecto de la URL actual
        const urlParams = new URLSearchParams(window.location.search);
        const idProyecto = urlParams.get('idproyecto');

        // Redirigir a AgregarPresupuesto.php con el parámetro idproyecto si está presente
        if (idProyecto) {
            window.location.href = `http://localhost:8080/ProyectoMVC/Views/AgregarFiltrarPresupuesto.php?idproyecto=${idProyecto}`;
        } else {
            // Si no se encuentra idproyecto en la URL actual, redirigir sin parámetros
            window.location.href = 'http://localhost:8080/ProyectoMVC/Views/AgregarPresupuesto.php';
        }
    }
</script>


<script>    
     function eliminarPresupuesto(ID) {
        if (confirm("¿Estás seguro de que deseas eliminar esta presupuesto?")) {
            location.href = 'Presupuesto.php?action=eliminar&idPresupuesto=' + ID;
        }
    }
    
    // Función para mostrar la modal automáticamente
    function mostrarModal() {
        var modal = document.getElementById('myModal');
        modal.style.display = 'block';
    }

    // Llamar a la función para mostrar la modal si no se encuentran presupuestos
    <?php
    if (isset($_GET['idproyecto']) && $result_presupuestos && $result_presupuestos->num_rows === 0) {
        echo "mostrarModal();";
    }
    ?>
    
    // Función para cerrar la modal
    function cerrarModal() {
    var modal = document.getElementById('myModal');
    modal.style.display = 'none';
}

</script>

</body>
</html>
