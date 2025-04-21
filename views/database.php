<?php
// Detalles de la conexión a la base de datos
$servername = "localhost";  // servidor de la base de datos está en la misma máquina
$username = "root";
$password = "mysql";
$database = "mydb";

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $database);

// Verificar la conexión
if ($conn->connect_error) {
    die("La conexión ha fallado: " . $conn->connect_error);
}
?>

