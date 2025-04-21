<link rel="stylesheet" href="http://localhost:8080/ProyectoMVC/css/EditarPersona.css">
<?php 
include "menu_manager.php"; 
include "database.php"; 

// Función para mostrar un mensaje de error dentro del modal
function mostrarError($mensaje) {
    echo "<div id='modalBackground' class='modal-background' style='display:block'></div>";
    echo "<div id='errorModal' class='error-content' style='display:block'>";
    echo "<p id='mensajeError'>$mensaje</p>";
    echo "<button class='error-btn' onclick='cerrarModal()'>Aceptar</button>";
    echo "</div>";
}

if(isset($_POST['Enviar'])){
    // Obtener los datos del formulario
    $Cedula = $_POST['Cedula'];
    $NuevaCedula = $_POST['NuevaCedula']; // Nuevo valor de la cédula
    $Nombre_Persona = $_POST['Nombre_Persona'];
    $Apellido1_Persona = $_POST['Apellido1_Persona'];
    $Apellido2_Persona = $_POST['Apellido2_Persona'];
    $Telefono = $_POST['Telefono'];
    $Correo_Electronico = $_POST['Correo_Electronico'];
    $Estado_Persona_idEstado_Persona = $_POST['Estado_Persona_idEstado_Persona'];

    // Validar la longitud de la nueva cédula
    if(strlen($NuevaCedula) !== 9) {
        mostrarError("<strong>¡VALIDAR!</strong><br>La cédula debe tener 9 caracteres numéricos.");
    } elseif (!ctype_digit($NuevaCedula)) { // Validar que solo contenga números
        mostrarError("<strong>¡VALIDAR!</strong><br>La cédula debe contener únicamente números.");
    } elseif (substr($NuevaCedula, 0, 1) === '0') { // Validar que no comience con cero
        mostrarError("<strong>¡VALIDAR!</strong><br>La cédula no puede comenzar con cero.");
    } elseif (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ\s]+$/", $Nombre_Persona)) { // Validar que el nombre contenga solo letras y espacios
        mostrarError("<strong>¡VALIDAR!</strong><br>El espacio de nombre y apellidos solo puede contener letras.");
    } elseif (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ\s]+$/", $Apellido1_Persona)) { // Validar que el primer apellido contenga solo letras y espacios
        mostrarError("<strong>¡VALIDAR!</strong><br>El espacio de nombre y apellidos solo puede contener letras.");
    } elseif (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ\s]+$/", $Apellido2_Persona)) { // Validar que el segundo apellido contenga solo letras y espacios
        mostrarError("<strong>¡VALIDAR!</strong><br>El espacio de nombre y apellidos solo puede contener letras.");
    } elseif (!ctype_digit($Telefono)) { // Validar que solo contenga números
        mostrarError("<strong>¡VALIDAR!</strong><br>El teléfono debe contener únicamente números.");
    } elseif (strlen($Telefono) !== 8) {
        mostrarError("<strong>¡VALIDAR!</strong><br>El teléfono debe tener 8 caracteres numéricos.");
    } elseif (substr($Telefono, 0, 1) === '0') { // Validar que no comience con cero
        mostrarError("<strong>¡VALIDAR!</strong><br>El teléfono no puede comenzar con cero.");
    } elseif (!filter_var($Correo_Electronico, FILTER_VALIDATE_EMAIL)) {
        mostrarError("<strong>¡VALIDAR!</strong><br>El correo electrónico ingresado no es válido.");
    } else {
        // Obtener el dominio del correo electrónico
        $dominio = substr(strrchr($Correo_Electronico, "@"), 1);

        // Validar el dominio del correo electrónico
        if ($dominio !== 'alpha.com') {
            mostrarError("<strong>¡VALIDAR!</strong><br>El correo electrónico debe ser del dominio @alpha.com");
        } else {

            // Consulta SQL para verificar si la nueva cédula ya existe en la base de datos, excluyendo la cédula de la persona que estamos editando
            $consulta_existencia = "SELECT COUNT(*) as total FROM Persona WHERE Cedula='$NuevaCedula' AND Cedula != '$Cedula'";
            $result_existencia = $conn->query($consulta_existencia);
            
            if ($result_existencia->num_rows > 0) {
                $row = $result_existencia->fetch_assoc();
                if ($row['total'] > 0) {
                    mostrarError("La cédula '$NuevaCedula' ya está registrada en la base de datos.");
                } else {
                    // Consulta SQL para actualizar los datos en la base de datos
                    $consulta = "UPDATE Persona SET Cedula='$NuevaCedula', Nombre_Persona='$Nombre_Persona', Apellido1_Persona='$Apellido1_Persona', Apellido2_Persona='$Apellido2_Persona', Telefono='$Telefono', Correo_Electronico='$Correo_Electronico',Estado_Persona_idEstado_Persona='$Estado_Persona_idEstado_Persona' WHERE Cedula='$Cedula'";
                    $resultado = $conn->query($consulta);

                    if($resultado){
                        // Redireccionar con un mensaje de éxito
                        echo "<script language='JavaScript'>
                        alert('Datos actualizados correctamente');
                        location.assign('http://localhost:/ProyectoMVC/views/Mantenimiento.php');
                        </script>";
                    } else {
                        // Mostrar un mensaje de error en caso de fallo en la consulta
                        $error_message = "Error al actualizar los datos: " . $conn->error;
                        if (strpos($conn->error, 'foreign key constraint') !== false) {
                            $error_message = "No se puede actualizar este registro porque está asociado a un usuario.";
                        }
                        mostrarError($error_message);
                    }

                    mysqli_close($conn);
                }
            } else {
                mostrarError("Error al verificar la existencia de la cédula.");
            }
        }
    }

} else {
    // Obtener la cédula del parámetro GET y obtener los datos de la base de datos
    $Cedula = $_GET['Cedula'];
    $consulta = "SELECT * FROM Persona WHERE Cedula='$Cedula'";
    $resultado = $conn->query($consulta);

    if ($resultado->num_rows > 0) {
        // Si se encuentra el registro, obtener los datos
        $row = $resultado->fetch_assoc();
        $Cedula = $row['Cedula'];
        $Nombre_Persona = $row['Nombre_Persona'];
        $Apellido1_Persona = $row['Apellido1_Persona'];
        $Apellido2_Persona = $row['Apellido2_Persona'];
        $Telefono = $row['Telefono'];
        $Correo_Electronico = $row['Correo_Electronico'];
        $Estado_Persona_idEstado_Persona = $row['Estado_Persona_idEstado_Persona'];
    } else {
        // Mostrar un mensaje de error si no se encuentra el registro
        echo "<script language='JavaScript'>
        alert('No se encontró el registro con la cédula proporcionada');
        location.assign('http://localhost:/ProyectoMVC/views/Mantenimiento.php');
        </script>";
    }

    mysqli_close($conn);
}
?>

<div class="content">
    <div class="titulo">
        <h4>Editar Colaborador</h4>
    </div>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="formulario" onsubmit="return validarFormulario()">
        <div class="form-group">
            <label for="NuevaCedula">Cedula:</label>
            <input type="text" name="NuevaCedula" id="NuevaCedula" class="form-control" value="<?php echo $Cedula; ?>" required>
        </div>

        <div class="form-group">
            <label for="Nombre_Persona">Nombre:</label>
            <input type="text" name="Nombre_Persona" id="Nombre_Persona" class="form-control" value="<?php echo $Nombre_Persona; ?>" required>
        </div>

        <div class="form-group">
            <label for="Apellido1_Persona">Primer Apellido:</label>
            <input type="text" name="Apellido1_Persona" id="Apellido1_Persona" class="form-control" value="<?php echo $Apellido1_Persona; ?>" required>
        </div>

        <div class="form-group">
            <label for="Apellido2_Persona">Segundo Apellido:</label>
            <input type="text" name="Apellido2_Persona" id="Apellido2_Persona" class="form-control" value="<?php echo $Apellido2_Persona; ?>" required>
        </div>

        <div class="form-group">
            <label for="Telefono">Teléfono:</label>
            <input type="text" name="Telefono" id="Telefono" class="form-control" value="<?php echo $Telefono; ?>" required>
        </div>

        <div class="form-group">
            <label for="Correo_Electronico">Correo Electrónico:</label>
            <input type="text" name="Correo_Electronico" id="Correo_Electronico" class="form-control" value="<?php echo $Correo_Electronico; ?>" required>
        </div>

        <div class="form-group">
            <label for="Estado_Persona_idEstado_Persona">Estado</label>
            <select name="Estado_Persona_idEstado_Persona" id="Estado_Persona_idEstado_Persona" class="form-control">
                <option value="1" <?php if($Estado_Persona_idEstado_Persona == 1) echo "selected"; ?>>Activo</option>
                <option value="2" <?php if($Estado_Persona_idEstado_Persona == 2) echo "selected"; ?>>Inactivo</option>
            </select>
        </div>

        <input type="hidden" name="Cedula" value="<?php echo $Cedula; ?>">

        <input type="submit" name="Enviar" value="Actualizar" class="btnAgregar">
        <a href="http://localhost:/ProyectoMVC/views/Mantenimiento.php" class="btnGPA" style="color: white; text-decoration: none;">Regresar</a>
    </form>

    <!-- Modal para mostrar mensajes de error -->
    <div id="modalBackground" class="modal-background" style="display: none;"></div>
    <div id="errorModal" class="error-content" style="display: none;">
        <!-- Mensaje de error aquí -->
    </div>
</div>

<div class="space"></div>

<script>
    // Función para verificar los campos antes de enviar el formulario
    function validarFormulario() {
        var nuevaCedula = document.getElementById('NuevaCedula').value;
        var nombre = document.getElementById('Nombre_Persona').value;
        var apellido1 = document.getElementById('Apellido1_Persona').value;
        var apellido2 = document.getElementById('Apellido2_Persona').value;
        var telefono = document.getElementById('Telefono').value;
        var correo_electronico = document.getElementById('Correo_Electronico').value;

        if (nuevaCedula.trim() === '') {
            mostrarError("Debe ingresar la nueva cédula.");
            return false;
        }

        if (nombre.trim() === '') {
            mostrarError("Debe ingresar el nombre.");
            return false;
        }

        if (apellido1.trim() === '') {
            mostrarError("Debe ingresar el primer apellido.");
            return false;
        }

        if (apellido2.trim() === '') {
            mostrarError("Debe ingresar el segundo apellido.");
            return false;
        }

        if (telefono.trim() === '') {
            mostrarError("Debe ingresar el teléfono.");
            return false;
        }

        if (correo_electronico.trim() === '') {
            mostrarError("Debe ingresar el correo electronico.");
            return false;
        }

        // Si todo está correcto, enviar el formulario
        return true;
    }

    // Función para mostrar un mensaje de error dentro del modal
    function mostrarError(mensaje) {
        var modalBackground = document.getElementById('modalBackground');
        var errorModal = document.getElementById('errorModal');
        var mensajeError = document.getElementById('mensajeError');

        modalBackground.style.display = 'block';
        errorModal.style.display = 'block';
        mensajeError.innerHTML = mensaje;
    }

    // Modal
    function cerrarModal() {
        var modalBackground = document.getElementById('modalBackground');
        var errorModal = document.getElementById('errorModal');

        modalBackground.style.display = 'none';
        errorModal.style.display = 'none';
    }
</script>

</body>
</html>














