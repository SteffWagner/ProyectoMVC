<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Usuario</title>
    <link rel="stylesheet" href="http://localhost:8080/ProyectoMVC/css/NuevoUsuario.css">
</head>
<body>

<?php

include "database.php";
include "menu_manager.php";

// Configurar la zona horaria
date_default_timezone_set('America/Los_Angeles'); // Reemplaza 'America/Los_Angeles' con la zona horaria deseada

$query_estados = "SELECT * FROM estado_usuario";
$result_estados = $conn->query($query_estados);

$query_ultimoID = "SELECT MAX(idUsuario) AS ultimoID FROM usuario";
$result_ultimoID = $conn->query($query_ultimoID);
$row_ultimoID = $result_ultimoID->fetch_assoc();
$ultimoID = $row_ultimoID['ultimoID'];

// Si no hay un último ID, inicializarlo en 0
if (!$ultimoID) {
    $ultimoID = 0;
}

// Incrementar el último ID en 1 para obtener el nuevo ID
$nuevoID = intval($ultimoID) + 1;

// Guardar el nuevo ID en un archivo o en alguna otra ubicación
file_put_contents('ultimo_id_usuario.txt', strval($nuevoID));

$query_personas_disponibles = "SELECT * FROM persona WHERE Cedula NOT IN (SELECT Persona_Cedula FROM usuario)";
$result_personas_disponibles = $conn->query($query_personas_disponibles);

$error_messages = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $persona_cedula = $_POST['Persona_Cedula'];
    $id_usuario = $_POST['idUsuario'];
    $nombre_usuario = $_POST['Nombre_usuario'];
    $fecha_creacion = $_POST['Fecha_creacion'];
    $contrasena = $_POST['Contrasena'];
    $rol_usuario = $_POST['Rol_usuario_Tipo_usuario'];
    $estado_usuario = $_POST['Estado_usuario_IDEstado_usuario'];
    

    // Validación de la contraseña
    if (strlen($contrasena) < 8 || !preg_match('/[0-9]/', $contrasena) || !preg_match('/[A-Z]/', $contrasena) || !preg_match('/[a-z]/', $contrasena) || !preg_match('/[-*?!@#$\/(){}=.;,]/', $contrasena) || preg_match('/\s/', $contrasena)) {
        $error_messages['Contrasena'] = "La contraseña debe cumplir con los siguientes requisitos:
        - Debe tener al menos 8 caracteres.
        - Debe incluir al menos un número.
        - Debe contener una combinación de letras mayúsculas y minúsculas.
        - Debe incluir al menos un carácter especial (- * ? ! @ # $ / () {} = . , ; :).
        - No debe contener espacios en blanco.";
    }
    
    if (!preg_match("/^[a-zA-Z]+$/", $nombre_usuario)) {
        // Validación del nombre de usuario
        $error_messages['Nombre_usuario'] = "El nombre de usuario solo puede contener letras.";
    }

    // Validación de la fecha de creación
    $fecha_actual = date("Y-m-d");
    if ($fecha_creacion != $fecha_actual) {
        $error_messages['Fecha_creacion'] = "La fecha de creación debe ser la fecha actual.";
    }

    // Verificación de existencia de usuario
    $query_verificar_usuario = "SELECT * FROM usuario WHERE Nombre_usuario = '$nombre_usuario'";
    $result_verificar_usuario = $conn->query($query_verificar_usuario);
    if ($result_verificar_usuario->num_rows > 0) {
        $error_messages['Nombre_usuario'] = "El nombre de usuario '$nombre_usuario' ya está en uso. Por favor, elija otro.";
    }

    if (empty($error_messages)) {
        // Inserción de usuario
        $query_insertar_usuario = "INSERT INTO usuario (Persona_Cedula, idUsuario, Nombre_usuario, Fecha_creacion, Contrasena, Rol_usuario_Tipo_usuario, Estado_usuario_IDEstado_usuario) VALUES ('$persona_cedula', '$id_usuario', '$nombre_usuario', '$fecha_creacion', '$contrasena', '$rol_usuario', '$estado_usuario')";
        if ($conn->query($query_insertar_usuario) === TRUE) {
            echo "<script>alert('Usuario creado correctamente.'); window.location.href = 'Seguridad.php';</script>";
        } else {
            $error_messages['general'] = "Error al crear el usuario: " . $conn->error;
        }
    }
}
?>

<div class="content">
    <!-- No incluir el título "Crear Usuario" -->
    
    <form id="crearUsuarioForm" action="<?=$_SERVER['PHP_SELF']?>" method="POST" class="formulario" onsubmit="return validarFormulario()">

        <div class="form-group">
            <label for="Persona_Cedula">Nombre de la persona:</label>
            <select id="Persona_Cedula" name="Persona_Cedula">
                <?php
            
                while ($row_persona = $result_personas_disponibles->fetch_assoc()) {
                    $nombre_completo = $row_persona['Nombre_Persona'] . ' ' . $row_persona['Apellido1_Persona'] . ' ' . $row_persona['Apellido2_Persona'];
                    echo "<option value='" . $row_persona['Cedula'] . "'>" . $nombre_completo . "</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="idUsuario">ID Usuario:</label>
            <input type="text" id="idUsuario" name="idUsuario" class="form-control" value="<?=$nuevoID?>" readonly>
        </div>

        <div class="form-group">
            <label for="Nombre_usuario">Nombre Usuario:</label>
            <input type="text" id="Nombre_usuario" name="Nombre_usuario" class="form-control" required>
            <?php if(isset($error_messages['Nombre_usuario'])) echo "<div class='error-message'>" . $error_messages['Nombre_usuario'] . "</div>"; ?>
        </div>

        <div class="form-group">
            <label for="Contrasena">Contraseña de inicio de sesión:</label>
            <input type="text" id="Contrasena" name="Contrasena" required>
            <?php if(isset($error_messages['Contrasena'])) echo "<div class='error-message'>" . $error_messages['Contrasena'] . "</div>"; ?>
        </div>


        <div class="form-group">
            <label for="Fecha_creacion">Fecha Creacion:</label>
            <input type="date" id="Fecha_creacion" name="Fecha_creacion" class="form-control" required>
            <?php if(isset($error_messages['Fecha_creacion'])) echo "<div class='error-message'>" . $error_messages['Fecha_creacion'] . "</div>"; ?>
        </div>

        <div class="form-group">
            <label for="Rol_usuario_Tipo_usuario">Tipo de acceso:</label>
            <select id="Rol_usuario_Tipo_usuario" name="Rol_usuario_Tipo_usuario" class="form-control" required>
                <option value="">Seleccionar Tipo</option>
                <?php
            
                $query_roles = "SELECT * FROM rol_usuario";
                $result_roles = $conn->query($query_roles);

                while ($row_rol = $result_roles->fetch_assoc()) {
                    echo "<option value='" . $row_rol['Tipo_usuario'] . "'>" . $row_rol['Tipo_usuario'] . "</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="Estado_usuario_IDEstado_usuario">Estado del Usuario:</label>
            <select id="Estado_usuario_IDEstado_usuario" name="Estado_usuario_IDEstado_usuario" class="form-control" required>
                <option value="">Seleccionar Estado</option>
                <option value="Activo">Activo</option>
                <option value="Inactivo">Inactivo</option>
                <?php

                while ($row_estado = $result_estados->fetch_assoc()) {
                    echo "<option value='" . $row_estado['IDEstado_usuario'] . "'>" . $row_estado['Descripcion_Estado_usuario'] . "</option>";
                }
                ?>
            </select>
        </div>

        <button type="submit" name="Enviar" class="btnGP">Crear Usuario</button>
        <a href="http://localhost:8080/ProyectoMVC/views/Seguridad.php" class="btn cancel">Cancelar</a>

        <?php if(isset($error_messages['general'])) echo "<div class='error-message'>" . $error_messages['general'] . "</div>"; ?>
    </form>
</div>

</body>
</html>


