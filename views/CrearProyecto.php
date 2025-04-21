<link rel="stylesheet" href="http://localhost:8080/ProyectoMVC/css/CrearProyecto.css">
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php
include "menu_manager.php";
include "database.php";

// Función para mostrar un mensaje de SweetAlert2 con redirección después de cerrar
function mostrarMensaje($tipo, $mensaje) {
    echo "<script>
    window.onload = function() {
            Swal.fire({
                icon: '{$tipo}',
                title: '{$mensaje}',
                confirmButtonText: 'Aceptar'
            }).then((result) => {
                if (result.value) {
                    window.location.href = 'http://localhost:8080/ProyectoMVC/views/CrearProyecto.php';
                }
            });
        };
        </script>";
}

// Obtener el último ID de proyecto generado desde la base de datos
$query_ultimoID = "SELECT MAX(idproyecto) AS ultimoID FROM Proyecto";
$result_ultimoID = $conn->query($query_ultimoID);
$row_ultimoID = $result_ultimoID->fetch_assoc();
$ultimoID = $row_ultimoID['ultimoID'];

// Si no hay un último ID, inicializarlo en 0
if (!$ultimoID) {
    $ultimoID = 0;
}

// Incrementar el último ID en 1 para obtener el nuevo ID
$nuevoID = intval($ultimoID) + 1;

// Variable para almacenar el nuevo ID después de guardar el proyecto
$nuevoIDGuardado = null;

// Obtener todas las personas disponibles desde la base de datos
$query_personas_disponibles = "SELECT * FROM persona WHERE Cedula NOT IN (SELECT Persona_Cedula FROM Proyecto_tiene_Personas)";
$result_personas_disponibles = $conn->query($query_personas_disponibles);

// Verificar si se envió el formulario
if(isset($_POST['Enviar'])){
    $Nombre = $_POST['nombre'];
    $Lider_proyecto = $_POST['Lider_proyecto'];
    $Personas_Cedula = isset($_POST['Persona_Cedula']) ? $_POST['Persona_Cedula'] : array();
    $Fecha_creacion = $_POST['Fecha_creacion'];
    $Tipo_Proyecto_Tipo_de_Proyecto = $_POST['Tipo_Proyecto_Tipo_de_Proyecto'];
    $Descripcion = $_POST['Descripcion'];
    $Fecha_entrega = $_POST['Fecha_entrega'];
    $Estado_proyecto_idEstado_proyecto = $_POST['Estado_proyecto_idEstado_proyecto'];
    $Monto_Presupuesto = $_POST['Monto_Presupuesto']; // Nuevo campo

    // Validar que los campos no estén vacíos
    if (empty($Nombre) || empty($Lider_proyecto) || empty($Fecha_creacion) || empty($Tipo_Proyecto_Tipo_de_Proyecto) || empty($Descripcion) || empty($Fecha_entrega) || empty($Estado_proyecto_idEstado_proyecto)) {
        mostrarMensaje('error', "<h5><strong>ERROR</strong><br>Usar el formato 0.00 con dos decimales, no se admiten numeros negativos, letras o simbolos</h5>");
    } else {

            // Si se seleccionó "Sí" pero no se agregó ninguna persona, mostrar mensaje de error
            if ($_POST['checkAgregarPersonas'] === 'si' && empty($Personas_Cedula)) {
                mostrarMensaje('error', "Debe agregar al menos una persona al proyecto.");
            } else {
                // Verificar que no se repitan los nombres de los colaboradores
                $colaboradoresUnicos = array_unique($Personas_Cedula);
                if (count($Personas_Cedula) != count($colaboradoresUnicos)) {
                    mostrarMensaje('error', "No puede seleccionar el mismo colaborador más de una vez.");
                    exit; // Detener el proceso si hay nombres de colaboradores duplicados
                }

                // Consulta SQL para insertar los datos en la tabla 'Proyecto'
                $consulta_proyecto = "INSERT INTO Proyecto (idproyecto, Nombre, Lider_Proyecto, Fecha_Creacion, Tipo_Proyecto_Tipo_de_Proyecto, Descripcion, Fecha_entrega, Estado_proyecto_idEstado_proyecto, Monto_Presupuesto) 
                            VALUES ('$nuevoID','$Nombre', '$Lider_proyecto','$Fecha_creacion', '$Tipo_Proyecto_Tipo_de_Proyecto', '$Descripcion', '$Fecha_entrega', '$Estado_proyecto_idEstado_proyecto', '$Monto_Presupuesto')";

                // Ejecutar la consulta para insertar el proyecto
                if ($conn->query($consulta_proyecto) === TRUE) {
                    // Obtener el ID del proyecto recién insertado
                    $id_proyecto_insertado = $conn->insert_id;

                    // Consulta SQL para insertar las personas asociadas al proyecto en la tabla 'Proyecto_tiene_Personas'
                    if (!empty($Personas_Cedula)) {
                        foreach ($Personas_Cedula as $persona_cedula) {
                            $consulta_personas = "INSERT INTO Proyecto_tiene_Personas (Proyecto_idproyecto, Persona_Cedula) 
                                            VALUES ('$id_proyecto_insertado', '$persona_cedula')";
                            
                            // Ejecutar la consulta para insertar las personas
                            $conn->query($consulta_personas);
                        }
                    }

                    // Éxito al insertar en la base de datos
                    mostrarMensaje('success',  "<h4>¡El proyecto se ha guardado exitosamente!<h4>");
                } else {
                    // Error al ejecutar la consulta para insertar el proyecto
                    echo "Error: " . $consulta_proyecto . "<br>" . $conn->error;
                }
            }
        }
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
</style>

<div class="content">
    <div class="titulo">
        <h4><strong>Crear Proyecto</strong></h4>
    </div>
    
    <form id="crearProyectoForm" action="<?=$_SERVER['PHP_SELF']?>" method="POST" class="formulario" onsubmit="return validarFormulario()">
        <div class="form-group">
            <label for="idproyecto">ID del Proyecto:</label>
            <input type="text" id="idproyecto" name="idproyecto" class="form-control" value="<?= isset($nuevoIDGuardado) ? $nuevoIDGuardado : $nuevoID ?>" readonly>
        </div>
        <div class="form-group">
            <label for="nombre">Nombre del Proyecto:</label>
            <input type="text" id="nombre" name="nombre" class="form-control" required>
        </div>
        <div class="form-group">
    <label for="Lider_proyecto">Lider del Proyecto:</label>
    <input type="text" id="Lider_proyecto" name="Lider_proyecto" class="form-control" required pattern="[a-zA-ZáéíóúñÁÉÍÓÚÑ\s]+" title="Solo se permiten letras">
</div>


        <!-- Checkbox para habilitar/deshabilitar la opción de agregar personas -->
        <label style="margin-left: 15px; margin-bottom:10px; font-size:15px;"><strong>¿Agregar colaboradores al proyecto?</strong></label>
        <div class="checkbox-container">
            <label style="margin-left: 20px;" for="checkAgregarPersonasSi"> Si</label>
            <input type="radio" id="checkAgregarPersonasSi" name="checkAgregarPersonas" value="si" onchange="habilitarAgregarPersonas()" required>
            <label style="margin-left: 15px;" for="checkAgregarPersonasNo"> No</label>
            <input type="radio" id="checkAgregarPersonasNo" name="checkAgregarPersonas" value="no" onchange="habilitarAgregarPersonas()" required>
        </div>

        <!-- Contenedor para agregar personas dinámicamente -->
        <div id="nuevasPersonas" class="form-group" style="display: none;">
            <label for="Persona_Cedula">Asignar colaboradores al proyecto:</label>
            <div class="persona-container">
                <select type="text" name="Persona_Cedula[]" class="form-control persona-select" required>
                    <option value="">Seleccionar colaborador</option>
                    <?php
                    mysqli_data_seek($result_personas_disponibles, 0);
                    while ($row_persona = $result_personas_disponibles->fetch_assoc()) {
                        $nombre_completo = $row_persona['Nombre_Persona'] . ' ' . $row_persona['Apellido1_Persona'] . ' ' . $row_persona['Apellido2_Persona'];
                        echo "<option value='" . $row_persona['Cedula'] . "'>" . $nombre_completo . "</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="button" class="btnGP" onclick="agregarCampo()" id="btnAgregarPersona">Agregar Persona</button>
        </div>
        
        <div class="form-group">
            <label for="Fecha_creacion">Fecha Creación:</label>
            <input type="date" id="Fecha_creacion" name="Fecha_creacion" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="Fecha_entrega">Fecha de Entrega:</label>
            <input type="date" id="Fecha_entrega" name="Fecha_entrega" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="Tipo_Proyecto_Tipo_de_Proyecto">Tipo de Proyecto:</label>
            <select id="Tipo_Proyecto_Tipo_de_Proyecto" name="Tipo_Proyecto_Tipo_de_Proyecto" class="form-control" required>
                <option value="">Seleccionar Tipo de Proyecto</option>
                <?php
                // Iterar sobre los resultados y crear opciones para el select
                while ($row_tipo = $result_tipos->fetch_assoc()) {
                    echo "<option value='" . $row_tipo['Tipo_de_Proyecto'] . "'>" . $row_tipo['Tipo_de_Proyecto'] . "</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="Estado_proyecto_idEstado_proyecto">Estado del Proyecto:</label>
            <select id="Estado_proyecto_idEstado_proyecto" name="Estado_proyecto_idEstado_proyecto" class="form-control" required>
                <option value="">Seleccionar Estado</option>
                <?php
                // Reiniciar el puntero de resultados
                mysqli_data_seek($result_estados, 0);
                // Iterar sobre los resultados y crear opciones para el select
                while ($row_estado = $result_estados->fetch_assoc()) {
                    echo "<option value='" . $row_estado['idEstado_proyecto'] . "'>" . $row_estado['Nombre_estado_proyecto'] . "</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="Descripcion">Descripción del Proyecto:</label>
            <textarea id="Descripcion" name="Descripcion" class="form-control" rows="5" required></textarea>
        </div>
        
        <div class="form-group">
            <label for="Monto_Presupuesto">Presupuesto inicial del proyecto:</label>
            <input type="number" id="Monto_Presupuesto" name="Monto_Presupuesto" class="form-control" step="0.01" required min="0">
        </div>


        
        <button type="submit" name="Enviar" class="btnGP">Guardar Proyecto</button>
    </form>
</div>

<script>
    // Función para verificar los campos antes de enviar el formulario
    function validarFormulario() {
        var nombre = document.getElementById('nombre').value;
        var lider = document.getElementById('Lider_proyecto').value;
        var fechaCreacion = document.getElementById('Fecha_creacion').value;
        var fechaEntrega = document.getElementById('Fecha_entrega').value;
        var tipo = document.getElementById('Tipo_Proyecto_Tipo_de_Proyecto').value;
        var estado = document.getElementById('Estado_proyecto_idEstado_proyecto').value;
        var descripcion = document.getElementById('Descripcion').value;
        var montoPresupuesto = document.getElementById('Monto_Presupuesto').value;

        if (nombre.trim() === '' || lider.trim() === '' || fechaCreacion.trim() === '' || fechaEntrega.trim() === ''  || tipo.trim() === '' || estado.trim() === '' || descripcion.trim() === '' || montoPresupuesto.trim() === '') {
            mostrarMensaje("error", "Todos los campos son obligatorios.");
            return false;
        }

        // Si todo está correcto, enviar el formulario
        return true;
    }

    // Función para habilitar/deshabilitar el botón de agregar personas según el estado del checkbox
    function habilitarAgregarPersonas() {
        var checkBox = document.querySelector('input[name="checkAgregarPersonas"]:checked');
        var btnAgregarPersona = document.getElementById("btnAgregarPersona");
        var nuevasPersonas = document.getElementById("nuevasPersonas");

        if (checkBox.value === 'si') {
            btnAgregarPersona.style.display = "block";
            nuevasPersonas.style.display = "block";
            // Habilitar el primer campo de selección de colaborador
            nuevasPersonas.getElementsByTagName('select')[0].required = true;
        } else {
            btnAgregarPersona.style.display = "none";
            nuevasPersonas.style.display = "none";
            // Deshabilitar el primer campo de selección de colaborador
            nuevasPersonas.getElementsByTagName('select')[0].required = false;
        }
    }

    // Función para agregar dinámicamente campos de selección de colaboradores
    function agregarCampo() {
        var nuevasPersonas = document.getElementById('nuevasPersonas');
        var nuevoCampo = document.createElement('div');
        nuevoCampo.innerHTML = `<div class="persona-container">
                                    <select type="text" name="Persona_Cedula[]" class="form-control persona-select" onchange="verificarColaboradoresRepetidos()" required>
                                        <option value="">Seleccionar colaborador</option>
                                        <?php
                                        mysqli_data_seek($result_personas_disponibles, 0);
                                        while ($row_persona = $result_personas_disponibles->fetch_assoc()) {
                                            $nombre_completo = $row_persona['Nombre_Persona'] . ' ' . $row_persona['Apellido1_Persona'] . ' ' . $row_persona['Apellido2_Persona'];
                                            echo "<option value='" . $row_persona['Cedula'] . "'>" . $nombre_completo . "</option>";
                                        }
                                        ?>
                                    </select>
                                    <button type="button" class="btn-remove" onclick="removerCampo(this)">x</button>
                                </div>`;
        nuevasPersonas.insertBefore(nuevoCampo, nuevasPersonas.lastChild); // Insertar antes del último hijo
    }

    // Función para remover el campo de selección de colaborador
    function removerCampo(btn) {
        var divPadre = btn.parentNode.parentNode;
        divPadre.removeChild(btn.parentNode);
    }

    // Función para verificar si se seleccionan colaboradores repetidos en el segundo select
    function verificarColaboradoresRepetidos() {
        var selects = document.querySelectorAll('.persona-select');
        var values = [];

        selects.forEach(function(select) {
            var selectedOption = select.options[select.selectedIndex];
            if (selectedOption.value !== '') {
                if (values.includes(selectedOption.value)) {
                    alert("No puede seleccionar el mismo colaborador más de una vez.");
                    select.selectedIndex = 0; // Reiniciar a la opción por defecto
                } else {
                    values.push(selectedOption.value);
                }
            }
        });
    }

       // Validación de la fecha de entrega para que no sea anterior a la fecha de creacion
    document.getElementById('Fecha_entrega').addEventListener('change', function() {
        var fecha_entrega = new Date(this.value);
        var fecha_creacion = new Date(document.getElementById('Fecha_creacion').value);
        if (fecha_entrega < fecha_creacion) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'La fecha de entrega del proyecto no puede ser anterior a la fecha de creacion.'
            });
            this.value = '';
        }
    });
</script>





</body>
</html>


















