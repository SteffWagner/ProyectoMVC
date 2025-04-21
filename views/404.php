<!doctype html>
<html lang="en">

<head?>
  <meta charset="utf-8">
  <title>Pagina No Encontrada!</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>

  <body>
    <main>
      <style>
        body {
          background-image: url("/ProyectoMVC/img/Fondo404.png");
          background-repeat: no-repeat;
          background-position: center;
          background-size: cover;
          height: 70vh;
          align-items: center;
          text-align: center;
        }

        h1 {
          color: black;
          animation: neon 0.4s ease-in-out infinite alternate;
          margin-top: 330px;
          margin-right: 50px;
          text-align: center;
        }

        h3 {
          color: black;
          animation: neon 0.4s ease-in-out infinite alternate;
          margin-right: 50px;
          text-align: center;
        }

        @keyframes neon {
          from {
            text-shadow: 0 0 5px white, 0 0 10px white, 0 0 15px white, 0 0 19px #a98ad1, 0 0 34px #a98ad1, 0 0 40px #a98ad1, 0 0 50px #a98ad1;
          }

          to {
            text-shadow: 0 0 5px white, 0 0 10px white, 0 0 15px white, 0 0 19px #a98ad1, 0 0 34px #a98ad1, 0 0 40px #a98ad1, 0 0 50px #a98ad1;
          }
        }

        .boton-con-imagen {
          display: inline-block;
          position: relative;
          overflow: hidden;
          padding: 0;
          border: none;
          background: transparent;
          align-items: center;
          text-align: center;
          margin-left: 360px;
      
        }

        .boton-con-imagen img {
          display: block;
          width: 20%;
          height: 20%;
          object-fit: cover;
          border-radius: 30%;
        }

        .boton-con-imagen:hover {
          transform: scale(0.9);
          /* Reducción al 90% */
          transition: transform 0.3s ease-out;
          /* Animación de 0.3 segundos */
        }
      </style>
      <h1>PAGINA NO ENCONTRADA ERROR 404 !! </h1>
      <h3>Lo sentimos, esta pagina no existe !</h3>
        <button class="boton-con-imagen" onclick="window.location.href = 'http://localhost:8080/ProyectoMVC/';">
          <img src="/ProyectoMVC/img/Logo1.png" alt="Regresa">
        </button>
    </main>
  </body>
</html>
<!-- IE needs 512+ bytes: https://docs.microsoft.com/archive/blogs/ieinternals/friendly-http-error-pages -->