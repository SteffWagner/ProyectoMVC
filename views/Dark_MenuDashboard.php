<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alpha</title>
    <link rel="stylesheet" href="http://localhost:8080/ProyectoMVC/css/Dark_MenuDashboard.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>
<body>
    <div class="sidebar">
        <!-- Aquí va el código del menú lateral -->
        <div class="logo">
            <img src="/ProyectoMVC/img/Logo4.svg" alt="Logo">
        </div>
        <ul class="menu">
            <li>
                <a href="http://localhost:8080/ProyectoMVC/views/CrearProyecto.php">
                    <span class="imagen-normal">
                        <img src="/ProyectoMVC/img/icono2.svg" width="26" height="26" alt="Icono Normal">
                    </span>
                    <span class="imagen-hover">
                        <img src="/ProyectoMVC/img/icono1.svg" width="26" height="26" alt="Icono Hover">
                    </span>
                    <span class="texto">Crear Proyecto</span>
                </a>
            </li>

            <li>
                <a href="http://localhost:8080/ProyectoMVC/views/GestionarProyecto.php">
                    <span class="imagen-normal">
                        <img src="/ProyectoMVC/img/icono4.svg" width="26" height="26" alt="Icono Normal">
                    </span>
                    <span class="imagen-hover">
                        <img src="/ProyectoMVC/img/icono3.svg" width="26" height="26" alt="Icono Hover">
                    </span>
                    <span class="texto">Gestionar Proyecto</span>
                </a>
            </li>
            <li>
                <a href="http://localhost:8080/ProyectoMVC/views/GestionarRecursos.php">
                    <span class="imagen-normal">
                        <img src="/ProyectoMVC/img/icono8.svg" width="26" height="26" alt="Icono Normal">
                    </span>
                    <span class="imagen-hover">
                        <img src="/ProyectoMVC/img/icono7.svg" width="26" height="26" alt="Icono Hover">
                    </span>
                    <span class="texto">Gestionar Recursos</span>
                </a>
            </li>

            <li>
                <a href="http://localhost:8080/ProyectoMVC/views/GestionarTareas.php">
                    <span class="imagen-normal">
                        <img src="/ProyectoMVC/img/icono6.svg" width="26" height="26" alt="Icono Normal">
                    </span>
                    <span class="imagen-hover">
                        <img src="/ProyectoMVC/img/icono5.svg" width="26" height="26" alt="Icono Hover">
                    </span>
                    <span class="texto">Gestionar Tareas</span>
                </a>
            </li>

            <li>
                <a href="http://localhost:8080/ProyectoMVC/views/GestionarPresupuestos.php">
                    <span class="imagen-normal">
                        <img src="/ProyectoMVC/img/icono10.svg" width="26" height="26" alt="Icono Normal">
                    </span>
                    <span class="imagen-hover">
                        <img src="/ProyectoMVC/img/icono9.svg" width="26" height="26" alt="Icono Hover">
                    </span>
                    <span class="texto">Gestionar Presupuestos</span>
                </a>
            </li>

            <hr class="linea">

            <li>
                <a href="http://localhost:8080/ProyectoMVC/views/MenuMantenimientos.php">
                    <span class="imagen-normal">
                        <img src="/ProyectoMVC/img/icono12.svg" width="26" height="26" alt="Icono Normal">
                    </span>
                    <span class="imagen-hover">
                        <img src="/ProyectoMVC/img/icono11.svg" width="26" height="26" alt="Icono Hover">
                    </span>
                    <span class="texto">Mantenimiento</span>
                </a>
            </li>
            
            <li>
            <a href="http://localhost:8080/ProyectoMVC/views/Seguridad.php">
            <span class="imagen-normal">
                        <img src="/ProyectoMVC/img/icono14.svg" width="26" height="26" alt="Icono Normal">
                    </span>
                    <span class="imagen-hover">
                        <img src="/ProyectoMVC/img/icono13.svg" width="26" height="26" alt="Icono Hover">
                    </span>
                    <span class="texto">Seguridad</span>
            </a>
            </li>
        

        <li>
            <a href="http://localhost:8080/ProyectoMVC/views/Reportes.php">
            <span class="imagen-normal">
                        <img src="/ProyectoMVC/img/icono18.svg" width="26" height="26" alt="Icono Normal">
                    </span>
                    <span class="imagen-hover">
                        <img src="/ProyectoMVC/img/icono17.svg" width="26" height="26" alt="Icono Hover">
                    </span>
                    <span class="texto">Reportes</span>
            </a>
        </li>

        <hr class="linea">

        <li>
        <form action="cerrar_sesion.php" method="post" class="menu-item">
        <button type="submit" name="cerrar_sesion" class="boton-menu">
        <span class="textoCS"><img src="/ProyectoMVC/img/icono23.svg" width="45" height="45" alt="Icono Hover"></span>
        </button>
        </form>
        </a>
        </li>

        </li>
        
        </ul>
    </div>
    </div>


    <script src="Seguridad.js"></script>