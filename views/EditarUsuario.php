<link rel="stylesheet" href="http://localhost:8080/ProyectoMVC/css/EditarUsuario.css">
<?php 
include "menu_manager.php"; 
include "database.php"; 

function mostrarError($mensaje) {
    echo "<div id='modalBackground' class='modal-background' style='display:block'></div>";
    echo "<div id='errorModal' class='error-content' style='display:block'>";
    echo "<p id='mensajeError'>$mensaje</p>";
    echo "<button class='error-btn' onclick='cerrarModal()'>Aceptar</button>";
    echo "</div>";
}

$idUsuario = $Nombre_usuario = $Rol_usuario_Tipo_usuario = $Estado_usuario_IDEstado_usuario = $persona_Cedula = "";

if(isset($_POST['Enviar'])){

    $idUsuario = $_POST['idUsuario'];
    $NuevoidUsuario = $_POST['NuevoidUsuario'];
    $Nombre_usuario = $_POST['Nombre_usuario'];
    $Contrasena = $_POST['Contrasena'];
    $Rol_usuario_Tipo_usuario = $_POST['Rol_usuario_Tipo_usuario'];
    $Estado_usuario_IDEstado_usuario = $_POST['Estado_usuario_IDEstado_usuario'];
    $persona_Cedula = $_POST['persona_Cedula'];

    if(!ctype_digit($NuevoidUsuario)) { 
        mostrarError("<strong>¡VALIDAR!</strong><br>Id Usuario debe contener únicamente números.");
    } elseif (substr($NuevoidUsuario, 0, 1) === '0') { 
        mostrarError("<strong>¡VALIDAR!</strong><br>Id Usuario no puede comenzar con cero.");
    } elseif (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ\s]+$/", $Nombre_usuario)) { 
        mostrarError("<strong>¡VALIDAR!</strong><br>Nombre Usuario solo puede contener letras.");
    } elseif (strlen($Contrasena) < 8) { 
        mostrarError("<strong>¡VALIDAR!</strong><br>La contraseña debe tener al menos 8 caracteres.");
    } elseif (!preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[-*?!@#$\/(){}=.,;:])\S{8,}$/", $Contrasena)) {
        mostrarError("<strong>¡VALIDAR!</strong><br>La contraseña debe cumplir con los siguientes requisitos:<br>
        - Debe incluir al menos un número.<br>
        - Debe contener una combinación de letras mayúsculas y minúsculas.<br>
        - Debe incluir al menos un carácter especial (- * ? ! @ # $ / () {} = . , ; :).<br>
        - Debe tener una longitud mayor o igual a 8 caracteres.<br>
        - No debe contener espacios en blanco.");
    
    } else {
        
        $consulta_existencia = "SELECT COUNT(*) as total FROM usuario WHERE idUsuario='$NuevoidUsuario' AND idUsuario != '$idUsuario'";
        $result_existencia = $conn->query($consulta_existencia);
        
        if ($result_existencia->num_rows > 0) {
            $row = $result_existencia->fetch_assoc();
            if ($row['total'] > 0) {
                mostrarError("El Id Usuario '$NuevoidUsuario' ya está registrado en la base de datos.");
            } else {
                $consulta = "UPDATE usuario SET idUsuario='$NuevoidUsuario', Nombre_usuario='$Nombre_usuario', Contrasena='$Contrasena',  Rol_usuario_Tipo_usuario='$Rol_usuario_Tipo_usuario', Estado_usuario_IDEstado_usuario='$Estado_usuario_IDEstado_usuario' WHERE idUsuario='$idUsuario'";
                $resultado = $conn->query($consulta);
            

                if($resultado){
                    echo "<script language='JavaScript'>
                    alert('Datos actualizados correctamente');
                    location.assign('http://localhost:8080/ProyectoMVC/views/Seguridad.php');
                    </script>";
                } else {
                    $error_message = "Error al actualizar los datos: " . $conn->error;
                    if (strpos($conn->error, 'foreign key constraint') !== false) {
                        $error_message = "No se puede actualizar este registro porque está asociado a un usuario.";
                    }
                    mostrarError($error_message);
                }

                mysqli_close($conn);
            }
        } else {
            mostrarError("Error al verificar la existencia del Id usuario.");
        }
    }
} else {

    $idUsuario = $_GET['idUsuario'];
    $consulta = "SELECT * FROM usuario WHERE idUsuario='$idUsuario'";
    $resultado = $conn->query($consulta);

    if ($resultado->num_rows > 0) {
        $row = $resultado->fetch_assoc();
        $idUsuario = $row['idUsuario'];
        $Nombre_usuario = $row['Nombre_usuario'];
        $Contrasena = $row['Contrasena'];
        $Rol_usuario_Tipo_usuario = $row['Rol_usuario_Tipo_usuario'];
        $Estado_usuario_IDEstado_usuario = $row['Estado_usuario_IDEstado_usuario'];
        $persona_Cedula = $row['Persona_Cedula'];
    } else {
        echo "<script language='JavaScript'>
        alert('No se encontró el registro con la ID proporcionada');
        location.assign('http://localhost:8080/ProyectoMVC/views/Seguridad.php');
        </script>";
    }

    $query_roles = "SELECT DISTINCT Rol_usuario_Tipo_usuario FROM usuario";
    $result_roles = $conn->query($query_roles);

    $query_estados = "SELECT DISTINCT Estado_usuario_IDEstado_usuario FROM usuario";
    $result_estados = $conn->query($query_estados);


    $roles_options = "";
    $estados_options = "";

    if ($result_roles->num_rows > 0) {
        while($row = $result_roles->fetch_assoc()) {
            $roles_options .= "<option value='".$row['Rol_usuario_Tipo_usuario']."'>".$row['Rol_usuario_Tipo_usuario']."</option>";
        }
    }

    if ($result_estados->num_rows > 0) {
        while($row = $result_estados->fetch_assoc()) {
            $estados_options .= "<option value='".$row['Estado_usuario_IDEstado_usuario']."'>".$row['Estado_usuario_IDEstado_usuario']."</option>";
        }
    }

    mysqli_close($conn);
}
?>


    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="formulario" onsubmit="return validarFormulario()">
        <div class="form-group">
            <label for="NuevoidUsuario">Id Usuario:</label>
            <input type="hidden" name="idUsuario" value="<?php echo $idUsuario; ?>">
            <input type="text" name="NuevoidUsuario" value="<?php echo $idUsuario; ?>" placeholder="Nuevo ID Usuario" required readonly>
        </div>

        <div class="form-group">
            <label for="Nombre_usuario">Nombre Usuario:</label>
            <input type="text" name="Nombre_usuario" value="<?php echo $Nombre_usuario; ?>" placeholder="Nombre Usuario" required>
        </div>

        <div class="form-group">
        <label for="Contrasena">Contraseña:</label>
        <input type="text" name="Contrasena" value="<?php echo $Contrasena; ?>" placeholder="Contraseña" required>
        </div>

        <div class="form-group">
            <label for="Rol_usuario_Tipo_usuario">Rol Usuario:</label>
            <select name="Rol_usuario_Tipo_usuario" required>
                <?php echo $roles_options; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="Estado_usuario_IDEstado_usuario">Estado Usuario:</label>
            <select name="Estado_usuario_IDEstado_usuario" required>
            <?php echo $estados_options; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="persona_Cedula">Cédula Persona Asociada:</label>
            <input type="text" name="persona_Cedula" value="<?php echo $persona_Cedula; ?>" placeholder="Cédula Persona Asociada" required readonly>
        </div>

        <button type="submit" name="Enviar" class="btn">Actualizar</button>
        <a href="http://localhost:8080/ProyectoMVC/views/Seguridad.php" class="btnGPA" style="color: white; text-decoration: none;">Regresar</a>
    </form>
</div>

<script>

    function validarFormulario() {
        var NuevoidUsuario = document.getElementById('NuevoidUsuario').value;
        var Nombre_usuario = document.getElementById('Nombre_usuario').value;
        var Contrasena = document.getElementById('Contrasenaa').value;


        if (NuevoidUsuario.trim() === '') {
            mostrarError("Debe ingresar nuevo Id.");
            return false;
        }

        if (Nombre_usuario.trim() === '') {
            mostrarError("Debe ingresar el nombre de usuario.");
            return false;
        }

        if (Contrasena.trim() === '') {
            mostrarError("Debe ingresar la contraseña.");
            return false;
        }

        return true;
    }

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













