<?php
// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener el nombre de usuario ingresado en el formulario
    $usuario = $_POST["user"];

    // Conectar a la base de datos
    $conexion = new mysqli("localhost", "usuario", "contraseña", "basededatos");

    // Verificar la conexión
    if ($conexion->connect_error) {
        die("Error en la conexión: " . $conexion->connect_error);
    }

    // Consulta para obtener las tareas completadas por el usuario
    $consulta = "SELECT * FROM tareas WHERE Usuario = '$usuario' AND Estado = 'Completada'";
    $resultado = $conexion->query($consulta);

    // Verificar si se encontraron tareas
    if ($resultado->num_rows > 0) {
        // Mostrar el encabezado del reporte
        echo "<h2>Reporte de tareas completadas por el usuario $usuario</h2>";
        echo "<table>";
        echo "<tr><th>ID Tarea</th><th>Descripción</th><th>Fecha de inicio</th><th>Fecha de fin</th></tr>";

        // Mostrar las tareas encontradas
        while ($fila = $resultado->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $fila["idTarea"] . "</td>";
            echo "<td>" . $fila["Descripcion"] . "</td>";
            echo "<td>" . $fila["Fecha_inicio"] . "</td>";
            echo "<td>" . $fila["Fecha_fin"] . "</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        // Si no se encontraron tareas completadas por el usuario
        echo "<p>No se encontraron tareas completadas por el usuario $usuario.</p>";
    }

    // Cerrar la conexión a la base de datos
    $conexion->close();
}
?>