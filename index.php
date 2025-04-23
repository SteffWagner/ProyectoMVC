<!doctype html>

<html>

<head>
  <meta charset="utf-8">
  <!-- Descripcion del enlace, visible para el cliente -->
  <title>AllEst Proyectos | Bienvenidos</title>
  <meta name="description" content="AllEst Proyectosa">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta property="og:title" content="AllEst Proyectos">
  <meta property="og:type" content="Pagina Principal AllEst Proyectosa">
  <link rel="apple-touch-icon" href="Logo1.png">
  <!-- Place favicon.ico in the root directory -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
  <meta name="theme-color" content="#fafafa">
</head>

<body>
  <style>
    /*Estilos del fondo del fondo*/
    body {
      background-image: url("img/fondo1.svg");
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
    .centrado {
  text-align: center;
  margin-top: 100px;
}

.BotonVerde {
  background-color: #058BA8;
  color: white;
  border: none;
  padding: 12px 24px;
  font-size: 16px;
  cursor: pointer;
  border-radius: 9px;
  transition: 0.3s;
}

.BotonVerde:hover {
  background-color: #CB6CE6;
}

    a {
      color: white;
      text-decoration: none;
    }
  </style>
<main style="text-align: center;">
  <h1 style="font-family: 'Times New Roman', Times, serif;">
    <strong>Sistema de Gestión de Proyectos</strong>
  </h1>
  
  <a href="http://localhost:8080/ProyectoMVC/views/IniciarSesion.php">
    <button class="BotonVerde">Comenzar</button>
  </a>
</main>


  <footer>
    
  </footer>
  <script src="js/vendor/modernizr-3.11.2.min.js"></script>
  <script src="js/plugins.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous">
  </script>
</body>

</html>