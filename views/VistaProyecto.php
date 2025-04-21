<link rel="stylesheet" href="http://localhost:8080/ProyectoMVC/css/VistaProyecto.css">
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php include "menu_manager.php";?>
<?php
// Verificar si se proporciona un ID válido en la URL
if(isset($_GET['idproyecto']) && is_numeric($_GET['idproyecto'])) {
    // ID del proyecto proporcionado en la URL
    $proyecto_id = $_GET['idproyecto'];

    include "database.php";

    // Consulta para obtener los datos del proyecto con el ID proporcionado
    $sql = "SELECT * FROM proyecto p 
            INNER JOIN estado_proyecto ep ON p.Estado_proyecto_idEstado_proyecto = ep.idEstado_proyecto
            WHERE idproyecto = $proyecto_id";
    $result = $conn->query($sql);

    // Verificar si se encontraron resultados
    if ($result->num_rows > 0) {
        // Datos del proyecto
        $row = $result->fetch_assoc();
        $nombre = $row["Nombre"];
        $descripcion = $row["Descripcion"];
        $lider_proyecto = $row["Lider_proyecto"];
        $tipo_proyecto = $row["Tipo_Proyecto_Tipo_de_Proyecto"];
        $fecha_creacion = $row["Fecha_creacion"];
        $fecha_entrega = $row["Fecha_entrega"];
        $estado_proyecto = $row["Nombre_estado_proyecto"]; // Se obtiene el nombre del estado
        $presupuesto = $row["Monto_Presupuesto"]; // Se obtiene el presupuesto

        // Obtener los nombres de los colaboradores del proyecto si hay
        $query_colaboradores = "SELECT CONCAT(Nombre_Persona, ' ', Apellido1_Persona, ' ', Apellido2_Persona) AS NombreCompleto
                                FROM Persona
                                INNER JOIN Proyecto_tiene_Personas ON Persona.Cedula = Proyecto_tiene_Personas.Persona_Cedula
                                WHERE Proyecto_tiene_Personas.Proyecto_idproyecto = $proyecto_id";
        $result_colaboradores = $conn->query($query_colaboradores);

        if ($result_colaboradores->num_rows > 0) {
            $colaboradores_nombres = array();
            while ($row_colaborador = $result_colaboradores->fetch_assoc()) {
                $colaboradores_nombres[] = $row_colaborador['NombreCompleto'];
            }
            // Convertir el array de nombres de colaboradores en una cadena separada por comas
            $colaboradores_string = implode(", ", $colaboradores_nombres). "<br>";
        } else {
            $colaboradores_string = "No hay colaboradores en este proyecto.";
        }
        
    } else {
        echo "No se encontró el proyecto.";
        exit; // Detener la ejecución si no se encuentra el proyecto
    }
    
    // Verificar si hay un parámetro de éxito en la URL
    $success = isset($_GET['success']) ? $_GET['success'] : false;
    $alert = isset($_GET['alert']) ? $_GET['alert'] : false;
    if ($success && $alert) {
        echo '<script>alert("Los datos del proyecto se han actualizado correctamente.");</script>';
    }

    // Cerrar la conexión
    $conn->close();
} else {
    echo "ID de proyecto no válido.";
    exit; // Detener la ejecución si el ID del proyecto no es válido
}
?>


<style>
    body {
        background-image: url("/ProyectoMVC/img/parallax6.svg");
        height: 100%;
        background-attachment: fixed;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
    }
</style>
<div class="contenidoaqui">
    <div class="container">
        <h2>Proyecto <?php echo $nombre; ?></h2>

        <div class="form-group">
            <label>ID del Proyecto:</label>
            <p><?php echo $proyecto_id; ?></p>
        </div>

        <div class="form-group">
            <label>Descripción:</label>
            <p><?php echo $descripcion; ?></p>
        </div>

        <div class="form-group">
            <label>Líder de proyecto:</label>
            <p><?php echo $lider_proyecto; ?></p>
        </div>

        <div class="form-group">
            <label>Colaboradores del Proyecto:</label>
            <p><?php echo $colaboradores_string; ?></p>
        </div>

        <div class="form-group">
            <label>Tipo de Proyecto:</label>
            <p><?php echo $tipo_proyecto; ?></p>
        </div>

        <div class="form-group">
            <label>Fecha de Creación:</label>
            <p><?php echo $fecha_creacion; ?></p>
        </div>

        <div class="form-group">
            <label>Fecha de Entrega:</label>
            <p><?php echo $fecha_entrega; ?></p>
        </div>

        <div class="form-group">
            <label>Estado del Proyecto:</label>
            <p><?php echo $estado_proyecto; ?></p>
        </div>
        
        <div class="form-group">
            <label>Presupuesto Inicial del Proyecto:</label>
            <p>₡ <?php echo $presupuesto; ?></p>
        </div>

        <form action="EditarProyecto.php" method="get">
            <!--Boton de modificar proyecto-->
            <input type="hidden" name="idproyecto" value="<?php echo $proyecto_id; ?>">
            <button type="submit" class="card-button"><img src="/ProyectoMVC/img/EditarIcono.svg" width="25" height="25" alt="Icono Hover"> Modificar</button>
            <!--Boton para regresar a GestionarProyecto-->
        </form>
        <button name="btn_regreso" onclick="goBack()"><img src="/ProyectoMVC/img/RegresarIcono.svg" width="25" height="25" alt="Icono Hover"> Regresar</button>

    </div>
</div>

<script>
    // Función para regresar a la página anterior
    function goBack() {
        window.location.href = 'GestionarProyecto.php';
    }
</script>

</body>
</html>




