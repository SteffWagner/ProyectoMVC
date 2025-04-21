<link rel="stylesheet" href="http://localhost:8080/ProyectoMVC/css/GestionarProyecto.css">
<?php 
include "menu_manager.php";
include "database.php";
?>

<?php
    $sql = "SELECT * FROM proyecto";
    $result = $conn->query($sql);
?>

<style>
            body {
                background-image: url("/ProyectoMVC/img/parallax10.svg");
                height: 100%;
    background-attachment: fixed;
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
            }
        </style>


<!-- Container con buscador para los proyectos -->
<div class="search-container" > 
    <input type="text" id="searchInput" placeholder="Buscar Proyecto" onkeyup="buscarProyecto()">
</div>

<div class="contenidoaqui">
<?php
    // Verificar si hay resultados
    if ($result->num_rows > 0) {
        // Inicia el contenedor
        echo '<div class="container">';
        // Iterar sobre los resultados y mostrarlos en tarjetas HTML
        while($row = $result->fetch_assoc()) {
            echo '    <div class="project">';
            echo '        <div class="card">';
            echo '            <div class="card-body">';
            echo '                <h3 class="card-title text-center">' . $row["Nombre"] . '</h3>';
            echo '                <h8 class="v-card-subtitle">Líder de Proyecto: </h8>';
            echo '                <p class="card-text">' . $row["Lider_proyecto"] . '</p>';
            echo '                <a href="VistaProyecto.php?idproyecto=' . $row["idproyecto"] . '" class="card-link">Abrir Proyecto</a>'; // Enlace al editor de datos
            echo '            </div>';
            echo '        </div>';
            echo '    </div>';
        }
        // Cierra el contenedor     
        echo '</>';
    } else {
        echo "<br><br><br><br> No se encontraron proyectos, ingresa en <strong>'Crear Proyecto.'</strong>";
    }
    $conn->close();
?>
<div>

<script>
    // Función para realizar la búsqueda
    function buscarProyecto() {
        // Obtener el valor ingresado en el campo de búsqueda
        var input = document.getElementById('searchInput');
        var filter = input.value.toUpperCase();
        
        // Obtener todos los proyectos
        var proyectos = document.getElementsByClassName('project');

        // Iterar sobre cada proyecto
        for (var i = 0; i < proyectos.length; i++) {
            var titulo = proyectos[i].getElementsByClassName('card-title')[0];
            var lider = proyectos[i].getElementsByClassName('card-text')[0];

            // Comprobar si el título o el líder del proyecto coinciden con el término de búsqueda
            if (titulo.innerHTML.toUpperCase().indexOf(filter) > -1 || lider.innerHTML.toUpperCase().indexOf(filter) > -1) {
                proyectos[i].style.display = '';
            } else {
                proyectos[i].style.display = 'none';
            }
        }
    }
</script>

</body>
</html>


