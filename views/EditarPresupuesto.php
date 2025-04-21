<link rel="stylesheet" href="http://localhost:8080/ProyectoMVC/css/EditarPresupuesto.css">

<?php
include "MenuDashboard.php";
include "database.php";

// Función para mostrar un mensaje de error dentro del modal
function mostrarError($mensaje) {
    echo "<script>alert('$mensaje');</script>";
}

function obtenerNombreProyecto($conn, $idProyecto) {
    $consulta = "SELECT Nombre FROM proyecto WHERE idProyecto = $idProyecto";
    $resultado = $conn->query($consulta);

    if ($resultado && $resultado->num_rows > 0) {
        $row = $resultado->fetch_assoc();
        return $row['Nombre'];
    } else {
        return "Proyecto no encontrado";
    }
}

// Definir las variables necesarias con valores predeterminados
$idPresupuesto = '';
$Monto = '';
$DescripcionP = '';
$Tarea_idTarea = '';
$Proyecto_idProyecto = ''; // Inicializar la variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los valores actualizados del formulario
    $idPresupuesto = $_POST['idPresupuesto'];
    $Monto = $_POST['Monto'];
    $DescripcionP = $conn->real_escape_string($_POST['DescripcionP']); // Escapar caracteres especiales
    $Tarea_idTarea = $_POST['Tarea_idTarea'];

    // Validar los datos si es necesario (por ejemplo, el monto no puede ser negativo)

    // Ejecutar la consulta SQL para actualizar los datos del presupuesto
    $consulta_actualizar = "UPDATE Presupuesto SET Monto = '$Monto', DescripcionP = '$DescripcionP', Tarea_idTarea = '$Tarea_idTarea' WHERE idPresupuesto = $idPresupuesto";

    if ($conn->query($consulta_actualizar) === TRUE) {
        // Si la actualización fue exitosa, redirigir a una página de éxito
        echo "<script>alert('Los datos del presupuesto han sido actualizados correctamente');</script>";
        echo "<script>window.location.href = 'http://localhost:8080/ProyectoMVC/views/GestionarPresupuestos.php';</script>";
        exit();
    } else {
        // Si ocurrió un error durante la actualización, mostrar un mensaje de error
        mostrarError("Error al actualizar los datos del presupuesto: " . $conn->error);
    }
} else {
    // Si no se ha enviado el formulario, obtener el idPresupuesto de la URL
    if (isset($_GET['idPresupuesto'])) {
        $idPresupuesto = $_GET['idPresupuesto'];

        // Consulta SQL para obtener los datos del presupuesto por su idPresupuesto
        $consulta = "SELECT * FROM Presupuesto WHERE idPresupuesto = $idPresupuesto";
        $resultado = $conn->query($consulta);

        if ($resultado->num_rows > 0) {
            // Si se encuentra el registro, obtener los datos
            $row = $resultado->fetch_assoc();
            $Monto = $row['Monto'];
            $DescripcionP = $row['DescripcionP'];
            $Tarea_idTarea = $row['Tarea_idTarea'];
            $Proyecto_idProyecto = $row['Proyecto_idproyecto']; // Asignar el valor a la variable
        } else {
            // Si no se encuentra el presupuesto, mostrar un mensaje de error
            mostrarError("El presupuesto con el id $idPresupuesto no fue encontrado.");
            exit(); // Salir del script
        }
    } else {
        // Si no se proporciona idPresupuesto en la URL, mostrar un mensaje de error
        mostrarError("No se proporcionó un idPresupuesto válido.");
        exit(); // Salir del script
    }
}
?>

<!-- Aquí comienza el formulario HTML para editar el presupuesto -->
<div class="content">
    <div class="titulo">
        <h4>Editar Presupuesto</h4>
    </div>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="formulario" onsubmit="return validarFormulario()">
        <!-- Campos del formulario -->
        <div class="form-group">
            <label for="Monto">Monto:</label>
            <input type="number" name="Monto" id="Monto" class="form-control" value="<?php echo $Monto; ?>" required>
        </div>

        <div class="form-group">
            <label for="DescripcionP">Descripción:</label>
            <textarea name="DescripcionP" id="DescripcionP" class="form-control" required><?php echo $DescripcionP; ?></textarea>
        </div>

        <div class="form-group">
            <label for="Tarea_idTarea">Tarea:</label>
            <select name="Tarea_idTarea" id="Tarea_idTarea" class="form-control" required>
                <?php
                // Consulta SQL para obtener la lista de tareas
                $consulta_tareas = "SELECT idTarea, Descripcion FROM tarea";
                $result_tareas = $conn->query($consulta_tareas);

                if ($result_tareas->num_rows > 0) {
                    while ($row_tarea = $result_tareas->fetch_assoc()) {
                        $selected = ($row_tarea['idTarea'] == $Tarea_idTarea) ? "selected" : "";
                        echo "<option value='" . $row_tarea['idTarea'] . "' $selected>" . $row_tarea['Descripcion'] . "</option>";
                    }
                } else {
                    echo "<option value=''>No hay tareas disponibles</option>";
                }
                ?>
            </select>
        </div>

        <!-- Campo oculto para enviar el idPresupuesto junto con el formulario -->
        <input type="hidden" name="idPresupuesto" value="<?php echo $idPresupuesto; ?>">

        <!-- Botones para enviar el formulario y regresar -->
        <input type="submit" name="Enviar" value="Actualizar" class="btnAgregar">
        <a href="http://localhost:8080/ProyectoMVC/views/GestionarPresupuestos.php" class="btnGPA" type="submit">Regresar</a>
    </form>
</div>

<!-- JavaScript para validar el formulario -->
<script>
    function validarFormulario() {
        var monto = document.getElementById('Monto').value;
        var descripcion = document.getElementById('DescripcionP').value;
        
        if (monto.trim() === '' || isNaN(monto) || monto < 0) {
            mostrarError("Ingrese un monto válido.");
            return false;
        }

        if (descripcion.trim() === '') {
            mostrarError("Ingrese una descripción.");
            return false;
        }

        return true;
    }
</script>

</body>
</html>
