<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Desarrollos Alpha | Bienvenidos</title>
    <meta name="description" content="Desarrollos Alpha">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta property="og:title" content="Desarrollos Alpha">
    <meta property="og:type" content="Desarrollos Alpha">
    <link rel="apple-touch-icon" href="Logo1.png">
    
    <link rel="stylesheet" href="http://localhost:8080/ProyectoMVC/css/IniciarSesion.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <meta name="theme-color" content="#fafafa">
</head>

<body>
    <main>
        <style>
            body {
                background-image: url("/ProyectoMVC/img/fondo2.png");
                background-position: center center;
                background-size: cover;
                margin: 0;
                padding: 0;
                height: 100vh;
                justify-content: center;
                align-items: center;
            }
        </style>
        <br><br><br><br><br>
        <?php
        include "database.php";
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['usuario']; 
            $password = $_POST['password'];
        
            $consulta = "SELECT * FROM usuario WHERE Nombre_usuario = '$username' AND Contrasena = '$password'";
            $resultado = $conn->query($consulta);
        
            if ($resultado && $resultado->num_rows > 0) {

                $usuario = $resultado->fetch_assoc();
                $rolUsuario = $usuario['Rol_usuario_Tipo_usuario'];
                $estadoUsuario = $usuario['Estado_usuario_IDEstado_usuario'];
        
            
                if ($estadoUsuario === 'Inactivo') {
                    $error = '&#9940; Su cuenta está inactiva. Por favor, contacte al administrador.';
                } else {
            
                    session_start();
                    $_SESSION['usuario'] = $username;
                    $_SESSION['rol'] = $rolUsuario;
        
                    
                    switch ($rolUsuario) {
                        case 'Administrador':
                            header('Location: PantallaPrincipal.php');
                            exit();
                            break;
                        case 'Colaborador':
                            header('Location: PantallaPrincipal2.php');
                            exit();
                            break;
                        default:
                            
                            header('Location: PantallaPrincipal2.php');
                            exit();
                            break;
                    }
                }
            } else {
                $error = '&#9940; Nombre de usuario o contraseña incorrectos.';
            }
        }
        ?>

        <form class="TeamInicio" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <img src="/ProyectoMVC/img/LogoAlpha.png" alt="Logo Desarrollos Alpha">
            <br>
            <H3 style="text-align: center; margin-bottom: 27px;"> Iniciar Sesión </H3>
            <label class="LabelInicio" style="text-align: center;">Nombre de usuario</label> 
            <input class="InputInicio" type="text" name="usuario"><br> 

            <label class="LabelInicio" style="text-align: center;">Contraseña</label>
            <input class="InputInicio" type="password" name="password"><br>

            

            <button class="BotonInicio" type="submit">Iniciar sesión</button>
            <br>
            <br>
            <br>
        </form>

        <?php if (isset($error)) : ?>
            <p class="error-message"><?= $error ?></p>
        <?php endif; ?>
    </main>
    <footer>

    </footer>
    <script src="js/vendor/modernizr-3.11.2.min.js"></script>
    <script src="js/plugins.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous">
    </script>
</body>

</html>





