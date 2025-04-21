<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Presupuesto</title>
    <link rel="stylesheet" href="http://localhost:8080/ProyectoMVC/css/AgregarPresupuesto.css">
</head>
<body>
<?php
include "menu_manager.php";
include "database.php";

function mostrarError($mensaje) {
    echo "<script>alert('$mensaje'); window.location.href = 'http://localhost:8080/ProyectoMVC/Views/GestionarPresupuestos.php';</script>";
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
        $consulta_insertar = $conn->prepare("INSERT INTO Presupuesto (Monto, DescripcionP, Tarea_idTarea, Proyecto_idproyecto) 
            VALUES (?, ?, ?, ?)");
        $consulta_insertar->bind_param("ssii", $Monto, $DescripcionP, $Tarea_idTarea, $Proyecto_idProyecto);
        $consulta_insertar->execute();

        if ($consulta_insertar->affected_rows > 0) {
            mostrarError("El nuevo presupuesto se ha agregado correctamente");
        } else {
            mostrarError("Error al agregar el presupuesto");
        }
    } catch (PDOException $e) {
        if ($e->getCode() == '45000') {
            echo "<script>alert('El Monto a agregar excede el Monto_Activo del proyecto');</script>";
        } else {
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

    <form id="formularioPresupuesto" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="formulario" onsubmit="return validarFormulario()">
        <!-- Campo oculto para almacenar el valor de idproyecto -->
        <input type="hidden" name="Proyecto_idproyecto" id="Proyecto_idproyecto" value="">

        <!-- Campo del formulario para seleccionar la Tarea -->
        <div class="form-group">
            <label for="Tarea_idTarea">Tarea:</label>
            <select name="Tarea_idTarea" id="Tarea_idTarea" class="form-control" required>
                <option value="">Seleccionar Tarea</option>
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

        <!-- Otros campos del formulario -->
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
        <a href="GestionarPresupuestos.php" class="btnGPA">Regresar</a>
    </form>
</div>

<!-- JavaScript para validar el formulario -->
<script>
    function validarFormulario() {
        var monto = document.getElementById('Monto').value;
        var descripcion = document.getElementById('DescripcionP').value;
        
        if (monto.trim() === '' || isNaN(monto) || monto <= 0) {
            alert("Ingrese un monto válido.");
            return false;
        }

        if (descripcion.trim() === '') {
            alert("Ingrese una descripción.");
            return false;
        }

        return true;
    }

    document.addEventListener("DOMContentLoaded", function() {
        const urlParams = new URLSearchParams(window.location.search);
        const idProyecto = urlParams.get('idproyecto');
        const formulario = document.getElementById('formularioPresupuesto');
        
        if (idProyecto && formulario) {
            // Crear un campo oculto en el formulario para almacenar idproyecto
            const inputIdProyecto = document.createElement('input');
            inputIdProyecto.type = 'hidden';
            inputIdProyecto.name = 'Proyecto_idproyecto';
            inputIdProyecto.value = idProyecto;
            
            // Agregar el campo oculto al formulario
            formulario.appendChild(inputIdProyecto);
        }
    });
</script>

</body>
</html>
