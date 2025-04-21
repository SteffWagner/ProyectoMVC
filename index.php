<!doctype html>

<html>

<head>
  <meta charset="utf-8">
  <!-- Descripcion del enlace, visible para el cliente -->
  <title>Desarrollos Alpha | Bienvenidos</title>
  <meta name="description" content="Desarrollos Alpha">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta property="og:title" content="Desarrollos Alpha">
  <meta property="og:type" content="Pagina Principal Desarrollos Alpha">
  <link rel="apple-touch-icon" href="Logo1.png">
  <!-- Place favicon.ico in the root directory -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
  <meta name="theme-color" content="#fafafa">
</head>

<body>
  <style>
    /*Estilos del fondo del fondo*/
    body {
      background-image: url("img/fondo1.png");
      background-position: center center;
      background-size: cover;
      margin: 0;
      padding: 0;
      height: 100vh;
      /* altura al 100% de la ventana */
      display: flex;
      justify-content: center;
      align-items: center;
    }

    h1 {
      color: white;
      text-align: center;
      margin-top: 25vh;
      transform: translateY(-50%);
      font-size: 22px;
      letter-spacing: 0.02em;
      align-items: center;

    }

    /* Estilos para el botón */
    .BotonVerde {
      background-color: #1A9F63;
      color: white;
      border: none;
      border-radius: 13px;
      padding: 10px 22px;
      font-size: 15px;
      cursor: pointer;
      transition: background-color 0.3s;
      outline: none;
      margin-left: 160px;
      letter-spacing: 0.08em;
      align-items: center;
      margin-bottom:40px;
      margin-top: 20px;
    }

    .BotonVerde:hover {
      background-color: #156D43;
      /* Tono de verde más oscuro al pasar el cursor */
    }

    .BotonVerde:active {
      background-color: #1A9F63;
      /* Tono de verde aún más oscuro al hacer clic */
    }

    a {
      color: white;
      text-decoration: none;
    }
  </style>
  <main>
    <h1 style="font-family: 'Times New Roman', Times, serif;"><strong>Sistema de Gestion de proyectos para PYMES</strong></h1>
    <button class="BotonVerde"><a href="http://localhost:8080/ProyectoMVC/views/IniciarSesion.php">Comenzar</button>
  </main>

  <footer>
    
  </footer>
  <script src="js/vendor/modernizr-3.11.2.min.js"></script>
  <script src="js/plugins.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous">
  </script>
</body>

</html>