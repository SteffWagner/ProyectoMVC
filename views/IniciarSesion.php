<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AllEst Proyectos | Iniciar Sesión</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background-image: url('/ProyectoMVC/img/fondo2.svg');
      background-position: center;
      background-size: cover;
      background-repeat: no-repeat;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: white;
    }

    .login-container {
      background-color:rgba(5, 9, 24, 0.66);
      padding: 40px;
      border-radius: 20px;
      width: 400px;
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.23);
      backdrop-filter: blur(8px);
      text-align: center;
    }

    .login-container img {
      max-width: 70%;
      margin-bottom: 20px;
    }

    .login-container h3 {
      font-size: 25px;
      margin-bottom: 30px;
      background: linear-gradient(to right,rgb(12, 57, 70),rgb(10, 162, 227),rgb(9, 81, 96));
      background-clip: text;
      -webkit-text-fill-color: transparent;
      animation: slide 5s ease-in-out infinite alternate;
    }

    @keyframes slide {
      0% {
        background-position: 0 50%;
      }

      100% {
        background-position: 100% 50%;
      }
    }

    label {
      display: block;
      margin-top: 15px;
      font-size: 15px;
    }

    input[type="text"],
    input[type="password"] {
      width: 80%;
      padding: 10px;
      border-radius: 13px;
      border: 1px solid white;
      background-color: transparent;
      color: white;
      margin: 10px auto;
      display: block;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
    }

    .btn-login {
      margin-top: 20px;
      background-color: #da1e6d;
      color: white;
      padding: 10px 24px;
      font-size: 16px;
      border-radius: 9px;
      border: none;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .btn-login:hover {
      background-color:rgb(14, 115, 187);
    }

    .error-message {
      margin-top: 20px;
      color: rgb(255, 80, 80);
    }
  </style>
</head>

<body>
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
          case 'Colaborador':
            header('Location: PantallaPrincipal2.php');
            exit();
          default:
            header('Location: PantallaPrincipal2.php');
            exit();
        }
      }
    } else {
      $error = '&#9940; Nombre de usuario o contraseña incorrectos.';
    }
  }
  ?>

  <div class="login-container">
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
      <img src="/ProyectoMVC/img/LogoAllEst.svg" alt="Logo">
      <h3>Iniciar Sesión</h3>

      <label for="usuario">Usuario</label>
      <input type="text" name="usuario" id="usuario" required>

      <label for="password">Contraseña</label>
      <input type="password" name="password" id="password" required>

      <button class="btn-login" type="submit">Iniciar sesión</button>

      <?php if (isset($error)) : ?>
        <p class="error-message"><?= $error ?></p>
      <?php endif; ?>
    </form>
  </div>
</body>

</html>





