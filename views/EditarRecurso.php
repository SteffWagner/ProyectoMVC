<?php 
session_start();
include "menu_manager.php"; 
include "database.php"; 

$idRecurso = isset($_GET['idRecurso']) ? $_GET['idRecurso'] : null;
$datosRecurso = null;


if ($idRecurso) {
    $consulta = $conn->prepare("SELECT Nombre, Cantidad_Recurso, Tipo_Recurso_idTipo_Recurso FROM recurso WHERE idRecurso = ?");
    $consulta->bind_param("i", $idRecurso);
    $consulta->execute();
    $resultado = $consulta->get_result();
    if ($fila = $resultado->fetch_assoc()) {
        $datosRecurso = $fila;
    }
    $consulta->close();
}

if(isset($_POST['Enviar'])) {
    $Nombre = $_POST['Recurso_nombre'];
    $cantidadRecurso = $_POST['Cantidad_Recurso'];
    $Tipo_Recurso_idTipo_Recurso = $_POST['Tipo_Recurso_idTipo_Recurso'];

    $consulta = $conn->prepare("UPDATE recurso SET Nombre=?, Cantidad_Recurso=?, Tipo_Recurso_idTipo_Recurso=? WHERE idRecurso=?");
    $consulta->bind_param("sisi", $Nombre, $cantidadRecurso, $Tipo_Recurso_idTipo_Recurso, $idRecurso);
    
    if ($consulta->execute()) {
        $_SESSION['mensajeAlerta'] = 'Recurso actualizado correctamente.';
        $_SESSION['tipoAlerta'] = 'success';
    } else {
        $_SESSION['mensajeAlerta'] = "Error al actualizar el recurso: " . htmlspecialchars($conn->error);
        $_SESSION['tipoAlerta'] = 'error';
    }
    $consulta->close();
    echo "<script>window.location.href = 'GestionarRecursos.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Recurso</title>
    <link rel="stylesheet" href="http://localhost:8080/ProyectoMVC/css/CrearRecurso.css">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="content">
    <div class="titulo">
        <h4>Editar Recurso</h4>
    </div>
    <form action="" method="POST" class="formulario" id="formularioRecurso">
    <div class="form-group">
    <label for="Recurso_nombre">Nombre del Recurso:</label>
    <input type="text" name="Recurso_nombre" id="Recurso_nombre" class="form-control" value="<?= htmlspecialchars($datosRecurso['Nombre'] ?? ''); ?>" pattern="[A-Za-z ]+" title="Solo se permite letras" required>
    </div>

        <div class="form-group">
            <label for="Cantidad_Recurso">Cantidad:</label>
            <input type="number" name="Cantidad_Recurso" id="Cantidad_Recurso" class="form-control" value="<?= htmlspecialchars($datosRecurso['Cantidad_Recurso'] ?? ''); ?>" min="1" step="1"  required>
        </div>
        <div class="form-group">
            <label for="Tipo_Recurso_idTipo_Recurso">Tipo de Recurso:</label>
            <select name="Tipo_Recurso_idTipo_Recurso" id="Tipo_Recurso_idTipo_Recurso" class="form-control" required>
                <option value="">Selecciona el tipo de recurso</option>
                <?php
                $consulta_tipos_recurso = "SELECT idTipo_Recurso FROM tipo_recurso";
                $resultado_tipos_recurso = $conn->query($consulta_tipos_recurso);
                while ($row = $resultado_tipos_recurso->fetch_assoc()) {
                    $selected = ($datosRecurso['Tipo_Recurso_idTipo_Recurso'] ?? '') == $row['idTipo_Recurso'] ? 'selected' : '';
                    echo "<option value='" . $row['idTipo_Recurso'] . "' $selected>" . $row['idTipo_Recurso'] . "</option>";
                }
                ?>
            </select>
        </div>
        <!-- Botón oculto utilizado para enviar el formulario desde JavaScript -->
        <input type="submit" name="Enviar" id="submitForm" style="display: none;">
        <button type="button" class="btnAgregar" onclick="confirmarActualizacion()">Actualizar Recurso</button>
        <a href="GestionarRecursos.php" class="btnGPA" style="color: white; text-decoration: none;">Regresar</a>
    </form>
</div>

<script>
function confirmarActualizacion() {
    var formulario = document.getElementById('formularioRecurso');

    // Verifica si el formulario es válido antes de mostrar el SweetAlert
    if (formulario.checkValidity()) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Confirma si deseas actualizar el recurso.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, actualizar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Si el usuario confirma, se simula un click en el botón de submit oculto para enviar el formulario
                document.getElementById('submitForm').click();
            }
        });
    } else {
        // Forza la validación del formulario, mostrando errores si los hay
        formulario.reportValidity();
    }
}
</script>




</body>
</html>
