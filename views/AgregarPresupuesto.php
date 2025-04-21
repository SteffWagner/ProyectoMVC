<link rel="stylesheet" href="http://localhost:8080/ProyectoMVC/css/AgregarPresupuesto.css">

<?php
include "menu_manager.php";
include "database.php";

function mostrarError($mensaje) {
    echo "<script>alert('$mensaje'); window.location.href = 'http://localhost:8080/ProyectoMVC/Views/Presupuesto.php';</script>";
    exit; // Detener la ejecución después de mostrar la alerta
}

// Definir variables para los valores del formulario
$Monto = '';
$DescripcionP = '';
$Tarea_idTarea = '';
$Proyecto_idProyecto = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Obtener los valores del formulario
        $Monto = $_POST['Monto'];
        $DescripcionP = $conn->real_escape_string($_POST['DescripcionP']); // Escapar caracteres especiales
        $Tarea_idTarea = $_POST['Tarea_idTarea'];
        $Proyecto_idProyecto = $_POST['Proyecto_idproyecto'];

        // Ejecutar la consulta SQL para insertar un nuevo presupuesto
        $consulta_insertar = "INSERT INTO Presupuesto (Monto, DescripcionP, Tarea_idTarea, Proyecto_idproyecto) 
                            VALUES ('$Monto', '$DescripcionP', '$Tarea_idTarea', '$Proyecto_idProyecto')";

        // Intentar ejecutar la consulta y capturar errores PDO
        if ($conn->query($consulta_insertar) === TRUE) {
            // Si la inserción fue exitosa, mostrar mensaje de éxito
            mostrarError("El nuevo presupuesto se ha agregado correctamente", $_SERVER['PHP_SELF']);
        } else {
            // Capturar excepción PDO para identificar errores específicos
            throw new PDOException($conn->error, (int)$conn->errno);
        }
    } catch (PDOException $e) {
        // Manejo de errores específicos identificados por el código de error
        if ($e->getCode() == '45000') {
            // Error generado por el trigger: mostrar alerta al usuario
            echo "<script>alert('El Monto a agregar excede el Monto_Activo del proyecto');</script>";
        } else {
            // Otro tipo de error: mostrar mensaje genérico o manejar de otra manera
            echo "<script>alert('Error al agregar el presupuesto: " . $e->getMessage() . "');</script>";
        }
    }
}
?>

<!-- Estilos CSS -->
<link rel="stylesheet" href="http://localhost:8080/ProyectoMVC/css/EditarPresupuesto.css">

<!-- Formulario HTML para agregar un nuevo presupuesto -->
<div class="content">
    <div class="titulo">
        <h4>Agregar Nuevo Presupuesto</h4>
    </div>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="formulario" onsubmit="return validarFormulario()">
        <!-- Campos del formulario -->
        <div class="form-group">
            <label for="Proyecto_idproyecto">Proyecto:</label>
            <select name="Proyecto_idproyecto" id="Proyecto_idproyecto" class="form-control" required>
            <option value="">Seleccionar Proyecto</option> <!-- Opción predeterminada -->
                <?php
                // Consulta SQL para obtener la lista de proyectos
                $consulta_proyectos = "SELECT idProyecto, Nombre FROM proyecto";
                $result_proyectos = $conn->query($consulta_proyectos);
                if ($result_proyectos->num_rows > 0) {
                    while ($row_proyecto = $result_proyectos->fetch_assoc()) {
                        echo "<option value='" . $row_proyecto['idProyecto'] . "'>" . $row_proyecto['Nombre'] . "</option>";
                    }
                } else {
                    echo "<option value=''>No hay proyectos disponibles</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="Tarea_idTarea">Tarea:</label>
            <select name="Tarea_idTarea" id="Tarea_idTarea" class="form-control" required>
            <option value="">Seleccionar Tarea</option> <!-- Opción predeterminada -->
                <?php
                // Consulta SQL para obtener la lista de tareas
                $consulta_tareas = "SELECT idTarea, Descripcion FROM tarea";
                $result_tareas = $conn->query($consulta_tareas);

                if ($result_tareas->num_rows > 0) {
                    while ($row_tarea = $result_tareas->fetch_assoc()) {
                        echo "<option value='" . $row_tarea['idTarea'] . "'>" . $row_tarea['Descripcion'] . "</option>";
                    }
                } else {
                    echo "<option value=''>No hay tareas disponibles</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="Monto">Monto:</label>
            <input type="number" name="Monto" id="Monto" class="form-control" value="<?php echo $Monto; ?>" required>
        </div>

        <div class="form-group">
            <label for="DescripcionP">Descripción:</label>
            <textarea name="DescripcionP" id="DescripcionP" class="form-control" required><?php echo $DescripcionP; ?></textarea>
        </div>

        

        

        <!-- Botón para enviar el formulario -->
        <input type="submit" name="Enviar" value="Agregar Presupuesto" class="btnAgregar">
        <!-- <button class="agregar-btn" type="submit"><a href="GestionarPresupuestos.php">Regresar</a></button> -->
        <a href="GestionarPresupuestos.php" class="btnGPA" type="submit">Regresar</a>
    </form>
</div>

<!-- JavaScript para validar el formulario -->
<script>
    function validarFormulario() {
        var monto = document.getElementById('Monto').value;
        var descripcion = document.getElementById('DescripcionP').value;
        
        if (monto.trim() === '' || isNaN(monto) || monto < 0) {
            alert("Ingrese un monto válido.");
            return false;
        }

        if (descripcion.trim() === '') {
            alert("Ingrese una descripción.");
            return false;
        }

        return true;
    }
</script>

</body>
</html>