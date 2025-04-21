<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Seguridad</title>
    <link rel="stylesheet" href="http://localhost:8080/ProyectoMVC/css/Seguridad.css">
</head>
<body>

<?php 
include "database.php";
include "Dark_MenuDashboard.php";

function eliminarUsuario($idUsuario, $conn) {
    $consulta = "DELETE FROM usuario WHERE idUsuario='$idUsuario'";
    $resultado = $conn->query($consulta);

    if ($resultado) {
        return true; 
    } else {
        return false; 
    }
}

if(isset($_GET['action']) && $_GET['action'] == 'eliminar' && isset($_GET['idUsuario'])) {
    $idUsuario = $_GET['idUsuario'];
    $eliminacionExitosa = eliminarUsuario($idUsuario, $conn);

    if ($eliminacionExitosa) {
        echo "<div class='success-message'>¡Usuario eliminado correctamente!</div>";
    } else {
        // Mostrar mensaje en lugar de la salida de la excepción
        echo "<div class='error-message'>No se puede eliminar el usuario porque tiene tareas asociadas.</div>";
    }
}

$consulta = "SELECT usuario.*, CONCAT(persona.Nombre_Persona, ' ', persona.Apellido1_Persona, ' ', persona.Apellido2_Persona) AS NombreCompleto, usuario.Fecha_creacion FROM usuario INNER JOIN persona ON usuario.Persona_Cedula = persona.Cedula";
$resultado = $conn->query($consulta);

if (!$resultado) {
    echo "Error al ejecutar la consulta: " . $conn->error;
}
?>

<div class="content">
<div class="titulo">
    <h4>Gestión de Seguridad</h4>
    <input type="text" id="searchInput" placeholder="Filtrar Usuario del sistema">
    <button class="agregar-btn"><a href="NuevoUsuario.php" style="color: white; text-decoration: none;">Crear Nuevo Usuario</a></button> 
</div>

<table id="table" class="table">
    <thead>
        <tr>
            <th>Id Usuario</th>
            <th>Nombre Completo</th>
            <th>Cédula</th>
            <th>Nombre de Usuario</th>
            <th>Tipo de acceso</th>
            <th>Contraseña</th>
            <th>Estado</th>
            <th>Usuario Desde</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
    <?php 
    while($row = $resultado->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['idUsuario']; ?></td>
            <td><?php echo $row['NombreCompleto']; ?></td>
            <td><?php echo $row['Persona_Cedula']; ?></td>
            <td><?php echo $row['Nombre_usuario']; ?></td>
            <td><?php echo $row['Rol_usuario_Tipo_usuario']; ?></td>
            <td><?php echo str_repeat('*', strlen($row['Contrasena'])); ?></td>
            <td><?php echo $row['Estado_usuario_IDEstado_usuario']; ?></td>
            <td><?php echo $row['Fecha_creacion']; ?></td>
            <td>
                <a class="editar-btn" href='EditarUsuario.php?idUsuario=<?php echo $row['idUsuario']; ?>'><img src="/ProyectoMVC/img/2.svg" width="27" height="27" alt="Icono Normal"></a>
                <button class="eliminar-btn" onclick="eliminarUsuario('<?php echo $row['idUsuario']; ?>')"><img src="/ProyectoMVC/img/1.svg" width="27" height="27" alt="Icono Normal"></button>
            </td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>
</div>

<script>
    const searchInput = document.getElementById('searchInput');
    const tasksTable = document.getElementById('table');
    const rows = tasksTable.getElementsByTagName('tr');

    searchInput.addEventListener('keyup', function() {
        const searchText = searchInput.value.toLowerCase();
        for (let i = 1; i < rows.length; i++) {
            const row = rows[i];
            const cells = row.getElementsByTagName('td');
            let found = false;
            for (let j = 0; j < cells.length; j++) {
                const cellText = cells[j].textContent.toLowerCase();
                if (cellText.includes(searchText)) {
                    found = true;
                    break;
                }
            }
            row.style.display = found ? '' : 'none';
        }
    });

    function eliminarUsuario(idUsuario) {
        if (confirm("¿Estás seguro de que deseas eliminar este usuario?")) {
            location.href = 'Seguridad.php?action=eliminar&idUsuario=' + idUsuario;
        }
    }
</script>
</body>
</html>
