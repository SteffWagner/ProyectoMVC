<link rel="stylesheet" href="http://localhost:8080/ProyectoMVC/css/GestionarPresupuesto.css">
<?php
include "menu_manager.php";
include "database.php"; 
?>


<style>
    body {
        background-image: url("/ProyectoMVC/img/ParallaxPresupuestos.svg");
        height: 100%;
        background-attachment: fixed;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
    }
    
</style>

    <div class="search-container">
    <button type="submit"><a href="Presupuesto.php">Ver Presupuestos</a></button>
    <input type="text" id="searchInput" placeholder="Buscar Proyecto" onkeyup="buscarProyecto()">
    <!-- <input type="text" id="searchInput" placeholder="Buscar Proyecto" onkeyup="buscarProyecto()"> -->
    <button type="submit"><a href="AgregarPresupuesto.php">Agregar Presupuesto</a></button>    
    </div>

    <?php
    $sql = "SELECT * FROM proyecto";
    $result = $conn->query($sql);

    echo' <div class="contenidoaqui">';
    // Verificar si hay resultados  window.location.href = 'Presupuesto.php';
    if ($result->num_rows > 0) {
        echo' <div class="container">';
        // Iterar sobre los resultados y mostrarlos en tarjetas HTML
        while($row = $result->fetch_assoc()) {
            // Obtener el Proyecto_idproyecto actual
            $proyecto_id = $row["idproyecto"];

            // Consultar los datos de presupuesto asociados al Proyecto_idproyecto actual
            $sql_presupuesto = "SELECT Monto, DescripcionP FROM presupuesto WHERE Proyecto_idproyecto = $proyecto_id";
            $result_presupuesto = $conn->query($sql_presupuesto);

            echo '<div class="project">';
            echo '    <div class="card">';
            echo '        <div class="card-body">';
            echo '            <h3 class="card-title text-center">' . $row["Nombre"] . '</h3>';
            echo '            <h3 class="v-card-subtitle"> Presupuesto Inicial</h3>'; 
            echo '            <h7 class="text">₡ ' . $row["Monto_Presupuesto"] . '</h7><br>';     
            echo '            <h3 class="v-card-subtitle"> Presupuesto Activo</h3>'; 
            echo '            <h7 class="textA">₡ ' . $row["Monto_Activo"] . '</h7> <br>';
            // Enlace para abrir detalles de FiltrarPresupuesto asociados al proyecto
            echo '            <a href="FiltrarPresupuesto.php?idproyecto=' . $row["idproyecto"] . '" class="card-link">Ver Presupuestos</a>';
            echo '        </div>';
            echo '    </div>';
            echo '</div>';
            
        }
        echo '</div>';
    } else {
        echo "No se encontraron proyectos.";
    }
    echo '</div>';
    $conn->close();
    ?>


        
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
                var lider = proyectos[i].getElementsByClassName('v-card-subtitle')[0];                
                var monto = proyectos[i].getElementsByClassName('text')[0];
                var montoA = proyectos[i].getElementsByClassName('textA')[0];
                // Comprobar si el título o el líder del proyecto coinciden con el término de búsqueda
                if (titulo.innerHTML.toUpperCase().indexOf(filter) > -1 ||
                    monto.innerHTML.toUpperCase().indexOf(filter) > -1  || 
                    montoA.innerHTML.toUpperCase().indexOf(filter) > -1 ||
                    lider.innerHTML.toUpperCase().indexOf(filter) > -1) {
                    proyectos[i].style.display = '';
                } else {
                    proyectos[i].style.display = 'none';
                }
            }
        }
    </script>

</body>
</html>


