<?php 
session_start();
ob_start(); // Iniciar el buffer de salida
include "menu_manager.php"; 
include "database.php"; 


if(isset($_POST['Enviar'])) {
    $Nombre = $_POST['Recurso_nombre'];
    $cantidadRecurso = $_POST['Cantidad_Recurso'];
    $Tipo_Recurso_idTipo_Recurso = $_POST['idTipo_Recurso'];

    // Determina la disponibilidad basada en la cantidad
    // Usa 'Disponible' y 'No Disponible' como valores
    $Disponibilidad_ID_Disponibilidad = ($cantidadRecurso > 0) ? 'Disponible' : 'No Disponible';

    // Prepara y ejecuta la consulta
    $consulta = $conn->prepare("INSERT INTO recurso (Nombre, Cantidad_Recurso, Disponibilidad_ID_Disponibilidad, Tipo_Recurso_idTipo_Recurso) VALUES (?, ?, ?, ?)");

    if ($consulta && $consulta->bind_param("siss", $Nombre, $cantidadRecurso, $Disponibilidad_ID_Disponibilidad, $Tipo_Recurso_idTipo_Recurso)) {
        if ($consulta->execute()) {
            $_SESSION['mensajeAlerta'] = 'Nuevo recurso creado correctamente.';
            $_SESSION['tipoAlerta'] = 'success';
        } else {
            $_SESSION['mensajeAlerta'] = "Error al crear el nuevo recurso: " . htmlspecialchars($conn->error);
            $_SESSION['tipoAlerta'] = 'error';
        }
    } else {
        $_SESSION['mensajeAlerta'] = "Error al enlazar parámetros: " . htmlspecialchars($conn->error);
        $_SESSION['tipoAlerta'] = 'error';
    }

    $consulta->close();
    // Redirecciona a la página de gestión de recursos
   header("Location:http://localhost:8080/ProyectoMVC/views/GestionarRecursos.php");
exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Recurso</title>
    <link rel="stylesheet" href="http://localhost:8080/ProyectoMVC/css/CrearRecurso.css">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="content">
    <div class="titulo">
        <h4>Crear Nuevo Recurso</h4>
    </div>
    <form action="" method="POST" class="formulario">
    <div class="form-group">
    <label for="Recurso_nombre">Nombre del Recurso:</label>
<input type="text" name="Recurso_nombre" id="Recurso_nombre" class="form-control" pattern="[A-Za-z ]+" title="Solo se permite letras" required autocomplete="on" list="recursosList">
<datalist id="recursosList">
    <?php
    // Realizar la consulta para obtener los nombres de los recursos disponibles
    $consulta_recursos = "SELECT nombre FROM Recurso";
    $resultado_recursos = $conn->query($consulta_recursos);
    // Verificar si hay resultados de la consulta
    if ($resultado_recursos->num_rows > 0) {
        //  generar las opciones para autocompletar
        while ($row = $resultado_recursos->fetch_assoc()) {
            echo "<option value='" . $row['nombre'] . "'>";
        }
    }
    ?>
</datalist>

    </select>
</div>
<div class="form-group">
    <label for="Cantidad_Recurso">Cantidad:</label>
    <input type="number" name="Cantidad_Recurso" id="Cantidad_Recurso" class="form-control" value="<?= htmlspecialchars($datosRecurso['Cantidad_Recurso'] ?? ''); ?>" min="1" step="1" required>
</div>

<input type="hidden" name="Disponibilidad_ID_Disponibilidad" id="Disponibilidad_ID_Disponibilidad" class="form-control" value="Disponible" ?>

<div class="form-group">
    <label for="idTipo_Recurso">Tipo de Recurso:</label>
    <select name="idTipo_Recurso" id="idTipo_Recurso" class="form-control" required>
        <option value="">Selecciona el tipo de recurso</option>
        <?php
        // Realizar la consulta para obtener los tipos de recurso disponibles
        $consulta_tipos_recurso = "SELECT idTipo_Recurso FROM tipo_recurso";
        $resultado_tipos_recurso = $conn->query($consulta_tipos_recurso);
        // Verificar si hay resultados de la consulta
        if ($resultado_tipos_recurso->num_rows > 0) {
            // generar las opciones del menú desplegable
            while ($row = $resultado_tipos_recurso->fetch_assoc()) {
                echo "<option value='" . $row['idTipo_Recurso'] . "'>". $row['idTipo_Recurso'] ."</option>";
            }
        } else {
            echo "<option value='' disabled>No hay tipos de recurso disponibles</option>";
        }
        ?>
    </select>
</div>


        <input type="submit" name="Enviar" value="Crear Recurso" class="btnAgregar">
        <a href="http://localhost:8080/ProyectoMVC/views/GestionarRecursos.php" class="btnGPA" style="color: white; text-decoration: none;">Regresar</a>
    </form>
</div>

<script>

</script>

</body>
</html>
