<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Colaboradores por Proyecto</title>
    <link rel="stylesheet" href="http://localhost:8080/ProyectoMVC/css/EditarColaboradorPorProyecto.css">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php 
include "menu_manager.php"; 
include "database.php"; 

// Inicializar variables
$proyecto_id = "";
$colaboradores_string = "";

// Verificar si se ha enviado el formulario para agregar persona al proyecto
if(isset($_POST['Agregar'])) {
    $proyecto_id = $_POST['Proyecto_idproyecto'];
    $persona_cedula = $_POST['Persona_Cedula'];

    // Insertar la persona en el proyecto
    $query_insert = "INSERT INTO Proyecto_tiene_Personas (Proyecto_idproyecto, Persona_Cedula) VALUES (?, ?)";
    $stmt = $conn->prepare($query_insert);
    $stmt->bind_param("is", $proyecto_id, $persona_cedula);

    if($stmt->execute()) {
        echo "<script>alert('Persona agregada al proyecto exitosamente.');</script>";
    } else {
        echo "<script>alert('Error al agregar persona al proyecto.');</script>";
    }
}

// Obtener la lista de proyectos
$query_proyectos = "SELECT * FROM Proyecto";
$result_proyectos = $conn->query($query_proyectos);

// Obtener la lista de personas disponibles (que no están en ningún proyecto)
$query_personas_disponibles = "SELECT * FROM Persona WHERE Cedula NOT IN (SELECT Persona_Cedula FROM Proyecto_tiene_Personas)";
$result_personas_disponibles = $conn->query($query_personas_disponibles);

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
        <h4>Editar Personas por Proyecto</h4>
    </div>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="formulario">
        <div class="form-group">
            <label for="Proyecto">Proyecto:</label>
            <select name="Proyecto_idproyecto" id="Proyecto" class="form-control" onchange="updateProjectID()">
                <option value="">Seleccionar Proyecto</option>
                <?php
                // Mostrar opciones de proyectos
                while ($row_proyecto = $result_proyectos->fetch_assoc()) {
                    echo "<option value='" . $row_proyecto['idproyecto'] . "'>" . $row_proyecto['Nombre'] . "</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="ID_Proyecto">ID del Proyecto:</label>
            <input type="text" id="ID_Proyecto" name="ID_Proyecto" class="form-control" readonly>
        </div>

        <div class="form-group">
            <label for="Persona">Agregar Colaborador al Proyecto:</label>
            <select name="Persona_Cedula" id="Persona" class="form-control">
                <option value="">Seleccionar colaborador</option>
                <?php
                // Mostrar opciones de personas disponibles
                while ($row_persona = $result_personas_disponibles->fetch_assoc()) {
                    echo "<option value='" . $row_persona['Cedula'] . "'>" . $row_persona['Nombre_Persona'] . ' ' . $row_persona['Apellido1_Persona'] . ' ' . $row_persona['Apellido2_Persona'] . "</option>";
                }
                ?>
            </select>
        </div>
        
        <div class="form-group">
            <button type="submit" class="btnGP" name="Agregar">Agregar Persona al Proyecto</button>
            <a href="ColaboradorPorProyecto.php" class="btnCancelar">Regresar</a>
        </div>
    </form>
</div>

<script>
    // Función para actualizar el ID del proyecto cuando se cambia el select
    function updateProjectID() {
        var proyecto_id = document.getElementById("Proyecto").value;
        document.getElementById("ID_Proyecto").value = proyecto_id;
    }
</script>

</body>
</html>






















