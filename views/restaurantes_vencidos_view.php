<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="css/post-login.css">
  <link rel="shortcut icon" href="img/icon.svg" />
  <title>Restaurantes con costos vencidos</title>
</head>

<body>
  <header>
    <!-- Logo del producto -->
    <h1>
      <img class="imglogo" src="img/logo_producto.svg" alt="Logo del producto" />
    </h1>
    <h2 class="tituloheader">Restaurantes con costos vencidos</h2>
    <nav>
      <div id="mySidebar" class="sidebar">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <a class="txtslide" href="admin_index.php">Volver</a>
        <a class="txtslide" href="admin_perfil_index.php">Mi cuenta</a>
        <a class="txtslide" href="registro_admin_index.php">Crear administrador</a>
        <a class="txtslide" href="pago_admin_index.php">Pagos</a>
        <a class="txtslide" href="cerrarsesion.php">Cerrar Sesion</a>
      </div>

      <div id="main">
        <button class="openbtn" onclick="openNav()">&#9776; </button>
      </div>
    </nav>
  </header>
  
  <main>
    <section>
      <div class="contenedorbuscar">
        <form action="" method="get" id="formBuscarPersona">
          <input class="txtformulario" name="buscarRestoran" type="text" id="restaurant-name"
            placeholder="Buscar por correo o alias">
          <button class="botoningreso" type="submit" id="search-button" name="search-button">Buscar</button>

          <?php if (isset($seBuscoAlgo) && $seBuscoAlgo === true) { ?>
            <button type="submit" name="restaurarSolicitud">Restaurar</button>
          <?php } ?>
        </form>
      </div>
    </section>


    <section>
      <div class="contenedor">
        <ul class="vencidos-list">
          <!-- Lista de restaurantes con costos vencidos -->
          <?php if (count($restaurantes) > 0 && $restaurantes !== []) { ?>

            <?php foreach ($restaurantes as $restaurante) { ?>

              <form action="" method="post" id="formAceptarRechazarSoli">
                <li>
                  <h4>
                    <?php echo $restaurante->nombrerestaurante ?>(
                    <?php echo $restaurante->emailrest ?>)
                  </h4>
                  <p>Fecha en la que inició la membresia vencida:
                    <?php echo $restaurante->iniciomembresia ?>
                  </p>
                  <p>Fecha en la que venció la membresia:
                    <?php echo $restaurante->finmembresia ?>
                  </p>
                  <p>Telefono 1:
                    <?php echo $restaurante->telefono1 ?>
                  </p>
                  <p>Telefono 2:
                    <?php echo $restaurante->telefono2 ?>
                  </p>
                  <input type="hidden" value="<?php echo $restaurante->emailrest ?>" name="emailRest">
                  <button type="submit" class="baja-rest" name="bajaRestaurante">Dar baja lógica </button>

                </li>
              </form>
            <?php } ?>

          <?php } else { ?>
            <h3>No se encontraron solicitudes para su restaurante</h3>
          <?php } ?>

        </ul>

        <div class="pagination">
          <?php if ($paginaActual > 1) { ?>
            <a href="?pagina=<?php echo $paginaActual - 1; ?>">Anterior</a>
          <?php } ?>

          <?php for ($i = 1; $i <= $total_paginas; $i++) { ?>
            <a href="?pagina=<?php echo $i; ?>" <?php echo ($i === $paginaActual) ? 'class="current"' : ''; ?>>
              <?php echo $i; ?>
            </a>
          <?php } ?>

          <?php if ($paginaActual < $total_paginas) { ?>
            <a href="?pagina=<?php echo $paginaActual + 1; ?>">Siguiente</a>
          <?php } ?>
        </div>
      </div>
    </section>

  </main>

  <!-- Pie de página -->
  <footer>
    <div class="waves">
      <div class="wave" id="wave1"></div>
      <div class="wave" id="wave2"></div>
      <div class="wave" id="wave3"></div>
      <div class="wave" id="wave4"></div>
    </div>
    <!-- Seccion 1 del footer -->
    <section>
      <div class="divcontacto">
        <h2>¡Contactanos!</h2>
        <p class="textofooter">
          Si deseas entablar un contacto con nosotros,
          a continuacion le estaremos dejando las formas
          posibles que tiene de poder comunicarse con nosotros.
        </p>
      </div>
    </section>
    <!-- Seccion 2 del footer -->
    <section>
      <!-- Seccion encargada de mostrar las diferentes redes sociales que tengo -->
      <!-- Div que contiene los apartados de las redes sociales -->
      <div class="divcontacto">
        <h2>Redes</h2>
        <!-- Apartado para Instagram -->
        <a href="https://www.instagram.com/synthsoftuy/" target="_blank">
          <img src="img/instagram.svg" alt="Instragram" />Instagram
        </a>
        <!-- Apartado para Facebook -->
        <a href="https://www.facebook.com/people/SynthSoft/61552117910575/?is_tour_dismissed=true" target="_blank">
          <img src="img/facebook.svg" alt="Facebook" />Facebook
        </a>
        <!-- Apartado para GitHub -->
        <a href="https://github.com/synthsoft" target="_blank">
          <img src="img/github.svg" alt="GitHub" />GitHub
        </a>
        <!-- Apartado para Linkedin -->
        <a href="https://www.linkedin.com/in/synth-soft-9b7974293/" target="_blank">
          <img src="img/linkedin.svg" alt="Linkedin" />Linkedin
        </a>
      </div>
    </section>
    <!-- Seccion 3 del footer -->
    <section>
      <!-- Seccion encargada de mostrar los contactos directos que tengo -->
      <div class="divcontacto">
        <!-- Div que contiene los apartados de los contactos directos -->
        <h2>Contacto Directo</h2>
        <!-- Apartado de correo electronico -->
        <a href="" class="textogrande">
          <img src="img/mail.svg" alt="Correo Electronico" />
          SynthSoft11@gmail.com
        </a>
        <!-- Apartado de pagina de la empresa -->
        <a href="" class="textogrande">
          <img src="img/empresa.svg" alt="Pagina de la empresa" />
          Página de la empresa
        </a>
        <!-- Apartado del contacto telefónico -->
        <a href="" class="textogrande">
          <img src="img/phone.svg" alt="Teléfono" />
          +598 093 982 427
        </a>
    </section>
  </footer>
  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
    integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
    </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
    integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous">
    </script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="js/menuheader.js"></script>
</body>

</html>