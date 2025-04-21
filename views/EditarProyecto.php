<link rel="stylesheet" href="http://localhost:8080/ProyectoMVC/css/EditarProyecto.css">
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php 
include "menu_manager.php"; 
include "database.php"; 

// Obtener el ID del proyecto desde la URL
if(isset($_GET['idproyecto']) && !empty($_GET['idproyecto'])) {
    $proyecto_id = $_GET['idproyecto'];
} else {
    echo "ID de proyecto no válido.";
    exit; // Detener la ejecución si el ID del proyecto no es válido
}

// Obtener los datos del proyecto desde la base de datos
$query = "SELECT * FROM Proyecto WHERE idproyecto = $proyecto_id";
$result = $conn->query($query);

// Verificar si se encontraron resultados
if ($result->num_rows > 0) {
    // Datos del proyecto
    $row = $result->fetch_assoc();
    $Nombre = $row["Nombre"];
    $Descripcion = $row["Descripcion"];
    $Lider_proyecto = $row["Lider_proyecto"];
    $Tipo_Proyecto_Tipo_de_Proyecto = $row["Tipo_Proyecto_Tipo_de_Proyecto"];
    $Fecha_creacion = $row["Fecha_creacion"];
    $Fecha_entrega = $row["Fecha_entrega"];
    $Estado_proyecto_idEstado_proyecto = $row["Estado_proyecto_idEstado_proyecto"];
    $Monto_Presupuesto = $row["Monto_Presupuesto"];
} else {
    echo "No se encontró el proyecto.";
    exit; // Detener la ejecución si no se encuentra el proyecto
}

// Obtener la fecha actual en el formato correcto (YYYY-MM-DD)
$Fecha_actual = date('Y-m-d');

// Validar que la fecha de entrega no sea menor que la fecha de creación
if ($Fecha_entrega < $Fecha_actual) {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'La fecha de entrega del proyecto no puede ser anterior al día de hoy.'
        });
    </script>";
    exit; // Detener la ejecución si la fecha de entrega es anterior al día de hoy
}

// Validar que los campos no estén vacíos
if (empty($Nombre) || empty($Lider_proyecto) || empty($Fecha_creacion) || empty($Tipo_Proyecto_Tipo_de_Proyecto) || empty($Descripcion) || empty($Fecha_entrega) || empty($Estado_proyecto_idEstado_proyecto) || empty($Monto_Presupuesto)) {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Todos los campos son obligatorios.'
        });
    </script>";
    exit; // Detener la ejecución si algún campo está vacío
}

// Obtener los estados del proyecto desde la base de datos
$query_estados = "SELECT * FROM Estado_proyecto";
$result_estados = $conn->query($query_estados);

// Obtener los tipos de proyecto desde la base de datos
$query_tipos = "SELECT * FROM Tipo_Proyecto";
$result_tipos = $conn->query($query_tipos);
?>

<style>
    body {
        background-image: url("/ProyectoMVC/img/parallax12.svg");
        height: 100%;
        background-attachment: fixed;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
    }
</style>

<div class="content">
    <div class="titulo">
        <h4>Editar Proyecto</h4>
    </div>

    <form action="<?php echo $_SERVER['PHP_SELF'] . "?idproyecto=$proyecto_id"; ?>" method="POST" class="formulario">
        <!--Nombre-->
        <div class="form-group">
            <label for="Nombre">Nombre del proyecto:</label>
            <input type="text" name="Nombre" id="Nombre" class="form-control" value="<?php echo $Nombre; ?>" required>
        </div>
        
        <!--Descripcion-->
        <div class="form-group">
            <label for="Descripcion">Descripción del proyecto:</label>
            <textarea name="Descripcion" id="Descripcion" class="form-control" required><?php echo $Descripcion; ?></textarea>
        </div>
        
        <!--Lider del Proyecto-->
        <div class="form-group">
            <label for="Lider_proyecto">Lider del proyecto:</label>
            <input type="text" name="Lider_proyecto" id="Lider_proyecto" class="form-control" required pattern="[a-zA-ZáéíóúÁÉÍÓÚ\s]+" title="Solo se permiten letras" value="<?php echo $Lider_proyecto; ?>">
        </div>

        <!--Tipo de Proyecto-->
        <div class="form-group">
            <label for="Tipo_Proyecto_Tipo_de_Proyecto">Tipo de Proyecto:</label>
            <select id="Tipo_Proyecto_Tipo_de_Proyecto" name="Tipo_Proyecto_Tipo_de_Proyecto" class="form-control" required>
                <option value="">Seleccionar Tipo de Proyecto</option>
                <?php
                // Iterar sobre los resultados y crear opciones para el select
                while ($row_tipo = $result_tipos->fetch_assoc()) {
                    $selected = ($row_tipo['Tipo_de_Proyecto'] == $Tipo_Proyecto_Tipo_de_Proyecto) ? "selected" : "";
                    echo "<option value='" . $row_tipo['Tipo_de_Proyecto'] . "' $selected>" . $row_tipo['Tipo_de_Proyecto'] . "</option>";
                }
                ?>
            </select>
        </div>
        
        <!--Fecha de Creacion-->
        <div class="form-group">
            <label for="Fecha_creacion">Fecha Creacion:</label>
            <input type="date" id="Fecha_creacion" name="Fecha_creacion" class="form-control" value="<?php echo $Fecha_creacion; ?>" required>
        </div>
        
        <!--Fecha de Entrega-->
        <div class="form-group">
            <label for="Fecha_entrega">Fecha de Entrega:</label>
            <input type="date" id="Fecha_entrega" name="Fecha_entrega" class="form-control" value="<?php echo $Fecha_entrega; ?>" required>
        </div>
        
        <!--Estado del Proyecto-->
        <div class="form-group">
            <label for="Estado_proyecto_idEstado_proyecto">Estado del Proyecto:</label>
            <select id="Estado_proyecto_idEstado_proyecto" name="Estado_proyecto_idEstado_proyecto" class="form-control" required>
                <option value="">Seleccionar Estado del Proyecto</option>
                <?php
                // Reiniciar el puntero del resultado
                $result_estados->data_seek(0);
                // Iterar sobre los resultados y crear opciones para el select
                while ($row_estado = $result_estados->fetch_assoc()) {
                    $selected = ($row_estado['idEstado_proyecto'] == $Estado_proyecto_idEstado_proyecto) ? "selected" : "";
                    echo "<option value='" . $row_estado['idEstado_proyecto'] . "' $selected>" . $row_estado['Nombre_estado_proyecto'] . "</option>";
                }
                ?>
            </select>
        </div>
        
    <!--Monto del Presupuesto-->
<div class="form-group">
    <label for="Monto_Presupuesto">Presupuesto Inicial del proyecto:</label>
    <input type="number" name="Monto_Presupuesto" id="Monto_Presupuesto" class="form-control" value="<?php echo $Monto_Presupuesto; ?>" step="0.01" required min="0">
</div>

        
        <!--Botón de enviar-->
        <div class="form-group">
            <button type="submit" class="btnGP" name="Editar">Actualizar Proyecto</button>
            <a href="VistaProyecto.php?idproyecto=<?php echo $proyecto_id; ?>" class="btnCancelar">Cancelar Edición</a>
        </div>

    </form>
</div>

<?php
// Manejo de la edición del proyecto
if (isset($_POST['Editar'])) {
    // Obtener los datos enviados por el formulario
    $Nombre = $_POST['Nombre'];
    $Descripcion = $_POST['Descripcion'];
    $Lider_proyecto = $_POST['Lider_proyecto'];
    $Tipo_Proyecto_Tipo_de_Proyecto = $_POST['Tipo_Proyecto_Tipo_de_Proyecto'];
    $Fecha_creacion = $_POST['Fecha_creacion'];
    $Fecha_entrega = $_POST['Fecha_entrega'];
    $Estado_proyecto_idEstado_proyecto = $_POST['Estado_proyecto_idEstado_proyecto'];
    $Monto_Presupuesto = $_POST['Monto_Presupuesto'];
    
    // Actualizar los datos del proyecto en la base de datos
    $query_update = "UPDATE Proyecto SET Nombre='$Nombre', Descripcion='$Descripcion', Lider_proyecto='$Lider_proyecto', Tipo_Proyecto_Tipo_de_Proyecto='$Tipo_Proyecto_Tipo_de_Proyecto', Fecha_creacion='$Fecha_creacion', Fecha_entrega='$Fecha_entrega', Estado_proyecto_idEstado_proyecto='$Estado_proyecto_idEstado_proyecto', Monto_Presupuesto='$Monto_Presupuesto' WHERE idproyecto='$proyecto_id'";
    
    if ($conn->query($query_update) === TRUE) {
        // Si la actualización es exitosa, mostrar mensaje de éxito
        echo "<script>
        Swal.fire({
            title: '¡Proyecto editado!',
            text: 'Los cambios se han guardado correctamente.',
            icon: 'success',
            confirmButtonText: 'OK'
        }).then(function() {
            window.location.href = 'VistaProyecto.php?idproyecto=$proyecto_id';
        });
    </script>";
    
    } else {
        
        // Si ocurre un error durante la actualización, mostrar mensaje de error
        echo "<script>
        Swal.fire({
            title: 'Error',
            text: 'Se produjo un error al editar el proyecto.',
            icon: 'error',
            confirmButtonText: 'OK'
        });
        </script>";
    }
}
?>
<script>      
 // Validación de la fecha de entrega para que no sea anterior a la fecha de creacion
    document.getElementById('Fecha_entrega').addEventListener('change', function() {
        var fecha_entrega = new Date(this.value);
        var fecha_creacion = new Date(document.getElementById('Fecha_creacion').value);
        if (fecha_entrega < fecha_creacion) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'La fecha de entrega del proyecto no puede ser anterior a la fecha de creación.'
            });
            this.value = '';
        }
    });
</script>

</body>

</html>

