<link rel="stylesheet" href="http://localhost:8080/ProyectoMVC/css/AgregarPersonas.css">
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

// Verificar si se envió el formulario
if(isset($_POST['Enviar'])){
    $Cedula = $_POST['Cedula'];
    $Nombre_Persona = $_POST['Nombre_Persona'];
    $Apellido1_Persona = $_POST['Apellido1_Persona'];
    $Apellido2_Persona = $_POST['Apellido2_Persona'];
    $Telefono = $_POST['Telefono'];
    $Correo_Electronico = $_POST['Correo_Electronico'];
    $Estado_Persona_idEstado_Persona = $_POST['Estado_Persona_idEstado_Persona'];

    // Validar la longitud de la cédula
    if(strlen($Cedula) !== 9) {
        mostrarError("<strong>¡VALIDAR!</strong><br>La cédula debe tener 9 caracteres numéricos.");
    } elseif (!ctype_digit($Cedula)) { // Validar que solo contenga números
        mostrarError("<strong>¡VALIDAR!</strong><br>La cédula debe contener únicamente números.");
    } elseif (substr($Cedula, 0, 1) === '0') { // Validar que no comience con cero
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
            // Consulta SQL para verificar si la cédula ya existe en la base de datos
            $consulta_existencia = "SELECT COUNT(*) as total FROM Persona WHERE Cedula='$Cedula'";
            $result_existencia = $conn->query($consulta_existencia);
            
            if ($result_existencia->num_rows > 0) {
                $row = $result_existencia->fetch_assoc();
                if ($row['total'] > 0) {
                    mostrarError("La cédula ya está registrada en la base de datos.");
                } else {
                    // Consulta SQL para insertar los datos en la base de datos
                    $consulta = "INSERT INTO Persona (Cedula, Nombre_Persona, Apellido1_Persona, Apellido2_Persona, Telefono, Correo_Electronico, Estado_Persona_idEstado_Persona) 
                                VALUES ('$Cedula', '$Nombre_Persona', '$Apellido1_Persona', '$Apellido2_Persona', '$Telefono', '$Correo_Electronico', '$Estado_Persona_idEstado_Persona')";
                    
                    // Ejecutar la consulta
                    if ($conn->query($consulta) === TRUE) {
                        // Éxito al insertar en la base de datos
                        echo "<script language='JavaScript'>
                                alert('Datos se han registrado correctamente');
                                location.assign('http://localhost:8080/ProyectoMVC/views/Mantenimiento.php');
                            </script>";
                    } else {
                        // Error al ejecutar la consulta
                        echo "Error: " . $consulta . "<br>" . $conn->error;
                    }
                }
            } else {
                mostrarError("Error al verificar la existencia de la cédula.");
            }
        }
    }
}

// Obtener los estados de la base de datos
$consulta_estados = "SELECT * FROM Estado_Persona";
$result_estados = $conn->query($consulta_estados);

// Obtener los valores del formulario si se envió
$cedula = isset($_POST['Cedula']) ? $_POST['Cedula'] : '';
$nombre = isset($_POST['Nombre_Persona']) ? $_POST['Nombre_Persona'] : '';
$apellido1 = isset($_POST['Apellido1_Persona']) ? $_POST['Apellido1_Persona'] : '';
$apellido2 = isset($_POST['Apellido2_Persona']) ? $_POST['Apellido2_Persona'] : '';
$telefono = isset($_POST['Telefono']) ? $_POST['Telefono'] : '';
$correo_electronico = isset($_POST['Correo_Electronico']) ? $_POST['Correo_Electronico'] : '';
$estado = isset($_POST['Estado_Persona_idEstado_Persona']) ? $_POST['Estado_Persona_idEstado_Persona'] : '';
?>

<div class="content">
    <div class="titulo">
        <h4><strong> Agregar Colaborador</strong></h4>
    </div>

    <form action="<?=$_SERVER['PHP_SELF']?>" method="POST" class="formulario" onsubmit="return validarFormulario()">
        <div class="form-group">
            <label for="Cedula">Cedula:</label>
            <input type="text" name="Cedula" id="Cedula" class="form-control" value="<?=$cedula?>" required>
        </div>
        <div class="form-group">
            <label for="Nombre_Persona">Nombre:</label>
            <input type="text" name="Nombre_Persona" id="Nombre_Persona" class="form-control" value="<?=$nombre?>" required>
        </div>
        <div class="form-group">
            <label for="Apellido1_Persona">Primer Apellido:</label>
            <input type="text" name="Apellido1_Persona" id="Apellido1_Persona" class="form-control" value="<?=$apellido1?>" required>
        </div>
        <div class="form-group">
            <label for="Apellido2_Persona">Segundo Apellido:</label>
            <input type="text" name="Apellido2_Persona" id="Apellido2_Persona" class="form-control" value="<?=$apellido2?>" required>
        </div>

        <div class="form-group">
            <label for="Telefono">Teléfono:</label>
            <input type="text" name="Telefono" id="Telefono" class="form-control" value="<?=$telefono?>" required>
        </div>

        <div class="form-group">
            <label for="Correo_Electronico">Correo Electrónico:</label>
            <input type="text" name="Correo_Electronico" id="Correo_Electronico" class="form-control" value="<?=$correo_electronico?>" required>
        </div>

        <div class="form-group">
            <label for="Estado_Persona_idEstado_Persona">Estado</label>
            <select name="Estado_Persona_idEstado_Persona" id="Estado_Persona_idEstado_Persona" class="form-control" required>
                <option value="">Seleccionar Estado</option>
                <?php
                // Verificar si la consulta devuelve resultados
                if ($result_estados->num_rows > 0) {
                    // Iterar sobre los resultados y crear opciones para el select
                    while($row_estado = $result_estados->fetch_assoc()) {
                        $selected = ($row_estado['idEstado_Persona'] == $estado) ? 'selected' : '';
                        echo "<option value='" . $row_estado['idEstado_Persona'] . "' $selected>" . $row_estado['Descripcion_Estado_Persona'] . "</option>";
                    }
                } else {
                    echo "<option value=''>No hay estados disponibles</option>";
                }
                ?>
            </select>
        </div>

        <input type="submit" name="Enviar" value="AGREGAR" class="btnAgregar">

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
        var nombre = document.getElementById('Nombre_Persona').value;
        var apellido1 = document.getElementById('Apellido1_Persona').value;
        var apellido2 = document.getElementById('Apellido2_Persona').value;
        var telefono = document.getElementById('Telefono').value;
        var correo_electronico = document.getElementById('Correo_Electronico').value;

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





