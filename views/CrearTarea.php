<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php 
include "menu_manager.php"; 
include "database.php"; 

// Variables para mensajes de alerta
$mensajeAlerta = '';
$tipoAlerta = '';

// Cargar proyectos, recursos, estados, y usuarios
$consultaProyectos = $conn->query("SELECT idProyecto, nombre FROM Proyecto");
$proyectos = $consultaProyectos->fetch_all(MYSQLI_ASSOC);
$consultaRecursos = $conn->query("SELECT idRecurso, nombre FROM Recurso");
$recursos = $consultaRecursos->fetch_all(MYSQLI_ASSOC);
$consultaEstados = $conn->query("SELECT idEstado_tarea, Nombre_Estado_tarea FROM Estado_tarea");
$estadosTarea = $consultaEstados->fetch_all(MYSQLI_ASSOC);

$idProyecto = isset($_POST['Proyecto_idproyecto']) ? $_POST['Proyecto_idproyecto'] : null;
if ($idProyecto) {
    $stmt = $conn->prepare("SELECT persona.Cedula, CONCAT(persona.Nombre_Persona, ' ', persona.Apellido1_Persona, ' ', persona.Apellido2_Persona) AS NombreCompleto
                            FROM persona 
                            JOIN Proyecto_tiene_Personas ON persona.Cedula = Proyecto_tiene_Personas.Persona_Cedula 
                            WHERE Proyecto_tiene_Personas.Proyecto_idProyecto = ?");
    $stmt->bind_param('i', $idProyecto);
    $stmt->execute();
    $result = $stmt->get_result();
    $personas = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $personas = [];
}

if(isset($_POST['Enviar'])) {
    $Descripcion = $_POST['Descripcion'];
    $Fecha_inicio = $_POST['Fecha_inicio'];
    $Fecha_fin = $_POST['Fecha_fin'];
    $Recurso_idRecurso = $_POST['Recurso_idRecurso'] === 'NULL' ? NULL : $_POST['Recurso_idRecurso'];
    $Estado_tarea_idEstado_tarea = $_POST['Estado_tarea_idEstado_tarea'];
    $agregarPersonas = $_POST['checkAgregarPersonas'] === 'si';

    $conn->begin_transaction();
    try {
        $stmtTarea = $conn->prepare("INSERT INTO tarea (Descripcion, Fecha_inicio, Fecha_fin, Proyecto_idproyecto, Recurso_idRecurso, Estado_tarea_idEstado_tarea) VALUES (?, ?, ?, ?, ?, ?)");
        $stmtTarea->bind_param("sssiii", $Descripcion, $Fecha_inicio, $Fecha_fin, $idProyecto, $Recurso_idRecurso, $Estado_tarea_idEstado_tarea);
        $stmtTarea->execute();
        $idTarea = $conn->insert_id;

        if ($agregarPersonas && !empty($_POST['Persona_Cedula'])) {
            foreach ($_POST['Persona_Cedula'] as $cedula) {
                $stmtPersona = $conn->prepare("INSERT INTO tarea_tiene_personas (tarea_idtarea, Persona_Cedula) VALUES (?, ?)");
                $stmtPersona->bind_param("is", $idTarea, $cedula);
                $stmtPersona->execute();
            }
        }
        $conn->commit();
        $mensajeAlerta = 'Nueva tarea creada correctamente.';
        $tipoAlerta = 'success';
    } catch (mysqli_sql_exception $e) {
        $conn->rollback();
        $mensajeAlerta = "Error al crear la nueva tarea: " . htmlspecialchars($e->getMessage());
        $tipoAlerta = 'error';
    }
}

if (!empty($mensajeAlerta)) {
    echo "<script>
            Swal.fire({
                icon: '" . htmlspecialchars($tipoAlerta) . "',
                title: '" . htmlspecialchars($mensajeAlerta) . "',
                confirmButtonText: 'Cerrar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location = 'http://localhost:8080/ProyectoMVC/views/GestionarTareas.php';
                }
            });
          </script>";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Tarea</title>
    <link rel="stylesheet" href="http://localhost:8080/ProyectoMVC/css/CrearTarea.css">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
</head>
<style>
    body {
        background-image: url("/ProyectoMVC/img/parallax.svg");
        height: 100%;
        background-attachment: fixed;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
    }

    .confirmButtonText {
        background-color: #2ecc71;
            color: #fff;
        }

    .checkbox-container {
        margin-bottom: 10px;
    }

    .persona-container {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }

    .btn-remove {
        background-color: #ff6666;
        color: white;
        border: none;
        padding: 2px 7px 2px 6px;
        border-radius: 5px;
        cursor: pointer;
        margin-left: 5px;
    }
        .btnGP {
        background-color: #178542;
        color: white;
        border: none;
        padding: 2px 7px 2px 6px;
        border-radius: 5px;
        cursor: pointer;
        margin-left: 5px;
    }
</style>

<body>
<div class="content">
    <div class="titulo">
        <h4>Crear Nueva Tarea</h4>
    </div>
    <form action="" method="POST" class="formulario">
    <div class="form-group">
    <label for="Proyecto_idproyecto">Elija un proyecto para la tarea:</label>
    <select name="Proyecto_idproyecto" id="Proyecto_idproyecto" class="form-control" required onchange="this.form.submit();">
        <option value="" disabled <?= $idProyecto ? 'hidden' : ''; ?>></option>
        <?php foreach ($proyectos as $proyecto): ?>
            <option value="<?= $proyecto['idProyecto'] ?>" <?= ($idProyecto == $proyecto['idProyecto']) ? 'selected' : ''; ?>>
                <?= htmlspecialchars($proyecto['nombre']) ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>


   <!-- Checkbox para habilitar/deshabilitar la opción de agregar personas -->
<div class="checkbox-container">
    <label><strong>¿Agregar colaboradores a la tarea?</strong></label>
    <input type="radio" id="checkAgregarPersonasSi" name="checkAgregarPersonas" value="si" required> Si
    <input type="radio" id="checkAgregarPersonasNo" name="checkAgregarPersonas" value="no" required> No
</div>
<div id="nuevasPersonas" class="form-group" style="display: none;">
    <label for="Persona_Cedula">Asignar colaboradores al proyecto:</label>
    <div class="persona-container">
        <select name="Persona_Cedula[]" class="form-control persona-select">
            <option value="">Seleccionar colaborador</option>
            <?php foreach ($personas as $persona): ?>
                <option value="<?= $persona['Cedula'] ?>"><?= $persona['NombreCompleto'] ?></option>
            <?php endforeach; ?>
        </select>
        <button type="button" class="btn-remove" onclick="removerCampo(this)">Remover Persona</button>
    </div>
    <button type="button" class="btnGP" onclick="agregarCampo()">Agregar Persona</button>
</div>

<script>
// JavaScript para manejar la visibilidad y validación del formulario
document.addEventListener('DOMContentLoaded', function() {
    const agregarSi = document.getElementById('checkAgregarPersonasSi');
    const agregarNo = document.getElementById('checkAgregarPersonasNo');
    const nuevasPersonas = document.getElementById('nuevasPersonas');

    agregarSi.addEventListener('change', function() {
        if (this.checked) {
            nuevasPersonas.style.display = 'block';
        }
    });

    agregarNo.addEventListener('change', function() {
        if (this.checked) {
            nuevasPersonas.style.display = 'none';
        }
    });

    // Añadir validación para asegurarse de que no se envía un formulario sin seleccionar una persona
    document.querySelector('form').addEventListener('submit', function(e) {
        if (agregarSi.checked && document.querySelector('.persona-select').value === "") {
            e.preventDefault(); // Evitar que el formulario se envíe
            alert('Debe seleccionar al menos un colaborador si opta por agregar.');
        }
    });
});
</script>


        <!-- Descripción y Fechas -->
        <div class="form-group">
            <label for="Descripcion">Descripción:</label>
            <input type="text" name="Descripcion" id="Descripcion" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="Fecha_inicio">Fecha de inicio:</label>
            <input type="date" name="Fecha_inicio" id="Fecha_inicio" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="Fecha_fin">Fecha fin:</label>
            <input type="date" name="Fecha_fin" id="Fecha_fin" class="form-control" required>
        </div>
        <!--  Recursos, Usuario y Estado de Tarea -->
       
        <div class="form-group">
            <label for="Recurso_idRecurso">Recurso:</label>
            <select name="Recurso_idRecurso" id="Recurso_idRecurso" class="form-control" required>
                <option value="" disabled selected>Seleccionar</option>
                <option value="NULL">Sin recurso</option>
                <?php foreach ($recursos as $recurso): ?>
                    <option value="<?= $recurso['idRecurso'] ?>"><?= htmlspecialchars($recurso['nombre']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
          
            </select>
        </div>
       
        <div class="form-group">
            <label for="Estado_tarea_idEstado_tarea">Estado de la Tarea:</label>
            <select name="Estado_tarea_idEstado_tarea" id="Estado_tarea_idEstado_tarea" class="form-control" required>
                <option value="" disabled selected>Seleccionar</option>
                <?php foreach ($estadosTarea as $estado): ?>
                    <option value="<?= $estado['idEstado_tarea'] ?>"><?= htmlspecialchars($estado['Nombre_Estado_tarea']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <input type="submit" name="Enviar" value="Crear Tarea" class="btnAgregar">
        <a href="http://localhost:8080/ProyectoMVC/views/GestionarTareas.php" class="btnGPA" style="color: white; text-decoration: none;">Regresar</a>
    </form>
</div>

<script>
    // Validación de la fecha de inicio para que no sea anterior al día de hoy
    document.getElementById('Fecha_inicio').addEventListener('change', function() {
        // Usamos moment.js para crear un objeto de momento de la fecha de inicio y de hoy
        var fechaInicio = moment(this.value);
        var fechaHoy = moment().startOf('day'); // Esto establecerá la fecha de hoy a medianoche

        // Comparamos si la fecha de inicio es antes de la fecha de hoy
        if (fechaInicio.isBefore(fechaHoy)) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'La fecha de inicio no puede ser anterior al día de hoy.'
            });
            this.value = ''; // Restablecemos el valor del input
        }
    });

    // Validación de la fecha de fin para que no sea anterior a la fecha de inicio
    document.getElementById('Fecha_fin').addEventListener('change', function() {
        var fechaFin = new Date(this.value);
        var fechaInicio = new Date(document.getElementById('Fecha_inicio').value);
        if (fechaFin < fechaInicio) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'La fecha de fin no puede ser anterior a la fecha de inicio.'
            });
            this.value = '';
        }
    });
</script>
<script>
document.getElementById('checkAgregarPersonasSi').addEventListener('change', function() {
    if(this.checked) {
        document.getElementById('nuevasPersonas').style.display = 'block';
        // Asegurarte de que los campos dentro de 'nuevasPersonas' sean requeridos cuando se muestren
        document.querySelectorAll('.persona-select').forEach(select => select.required = true);
    }
});

document.getElementById('checkAgregarPersonasNo').addEventListener('change', function() {
    if(this.checked) {
        document.getElementById('nuevasPersonas').style.display = 'none';
        // Quitar el atributo required cuando los campos no son visibles
        document.querySelectorAll('.persona-select').forEach(select => select.required = false);
    }
});

function agregarCampo() {
    if (puedeAgregarOtroCampo()) {
        var container = document.querySelector('.persona-container');
        var clone = container.cloneNode(true);
        clone.querySelector('.btn-remove').style.display = 'inline-block';
        container.parentNode.insertBefore(clone, container.nextSibling);
        actualizarSelecciones();
    } else {
        Swal.fire({
            title: 'Atención',
            text: 'No hay más colaboradores disponibles para agregar.',
            icon: 'warning',
            confirmButtonText: 'Entendido'
        });
    }
}

function removerCampo(button) {
    var container = button.parentNode;
    container.parentNode.removeChild(container);
    actualizarSelecciones();
}

function actualizarSelecciones() {
    var allSelectedValues = Array.from(document.querySelectorAll('.persona-select')).map(select => select.value).filter(Boolean);

    document.querySelectorAll('.persona-select').forEach(select => {
        Array.from(select.options).forEach(option => {
            option.disabled = allSelectedValues.includes(option.value) && select.value !== option.value;
        });
    });

    document.querySelector('.btnGP').disabled = !puedeAgregarOtroCampo();
}

function puedeAgregarOtroCampo() {
    var allOptions = Array.from(document.querySelectorAll('.persona-select option'));
    var selectedOptions = new Set(Array.from(document.querySelectorAll('.persona-select')).map(select => select.value));
    return allOptions.some(option => !selectedOptions.has(option.value) && option.value !== "");
}
</script>
<script>
document.getElementById('Proyecto_idproyecto').addEventListener('change', function() {
    var selectOption = this.querySelector('option[value=""]');
    // Ocultar la opción "Seleccionar" si se ha hecho una selección válida
    selectOption.hidden = this.value !== '';
});
</script>




</body>
</html>