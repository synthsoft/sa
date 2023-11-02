<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="Content-Security-Policy" content="
  default-src 'self' https://ka-f.fontawesome.com; 
  img-src 'self' data: https://www.instagram.com https://www.facebook.com https://github.com https://www.linkedin.com; 
  font-src 'self' https://ka-f.fontawesome.com; 
  script-src 'self' https://kit.fontawesome.com 'unsafe-hashes' 'unsafe-inline'; 
  style-src 'self' 'unsafe-inline';">

  <link rel="stylesheet" href="css/post-login.css" />
  <link rel="shortcut icon" href="img/icon.svg" />
  <title>Interfaz de Administrador</title>
</head>

<body>
  <header>
    <h1>
      <!-- Logo del producto -->
      <img class="imglogo" src="img/logo_producto.svg" alt="Logo del producto" />
    </h1>
    <h2 class="tituloheader">Menú Administrador</h2>
    <nav>
      <div id="mySidebar" class="sidebar">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <a class="txtslide" href="admin_perfil_index.php">Mi cuenta</a>
        <a class="txtslide" href="registro_admin_index.php">Crear administrador</a>
        <a class="txtslide" href="pago_admin_index.php">Pagos</a>
        <a class="txtslide" href="restaurantes_vencidos_index.php">Restaurantes con costos vencidos</a>
        <a class="txtslide" href="cerrarsesion.php">Cerrar Sesion</a>
      </div>

      <div id="main">
        <button class="openbtn" onclick="openNav()">&#9776; </button>
      </div>
    </nav>
  </header>
  <!-- En caso de borrar el id del main, el modal no funciona -->
  <main id="blur">
    <div class="contenedor">
      <div class="contenedordatos">
        <div class="restaurantes">

          <h2>Restaurantes</h2>

          <!-- Búsqueda -->
          <form name="buscarRestaurante" action="" method="post">
            <input class="txtformulario" type="text" id="busquedarestaurantes" name="busquedarestaurantes"
              placeholder="Buscar...">
            <button type="submit" name="btnBuscarrest" class="botoningreso">Buscar</button>
          </form>

          <br>
          <hr>


          <?php if (count($restaurantes) > 0 && $restaurantes !== []) { ?>
            <ul>
              <!--Recorremos el arreglo de restaurantes y mostramos los datos-->
              <?php foreach ($restaurantes as $restaurante) { ?>
                <li>
                  <?php echo $restaurante->nombrerestaurante ?>
                  (
                  <?php echo $restaurante->alias ?>)
                  (
                  <?php echo $restaurante->emailrest ?>)
                </li>

              <?php } ?>
            </ul>
          <?php } else { ?>
            <br>
            <h3>No hay restaurantes registrados</h3>
          <?php } ?>


          <!-- Agrega más restaurantes desde la base de datos -->


          <div class="pagination">
            <?php if ($paginaActualRest > 1) { ?>
              <a href="?paginaRestaurantes=<?php echo $paginaActualRest - 1; ?>">Anterior</a>
            <?php } ?>

            <?php for ($i = 1; $i <= $total_paginas_restaurante; $i++) { ?>
              <a href="?paginaRestaurantes=<?php echo $i; ?>" <?php echo ($i === $paginaActualRest) ? 'class="current"' : ''; ?>>
                <?php echo $i; ?>
              </a>
            <?php } ?>

            <?php if ($paginaActualRest < $total_paginas_restaurante) { ?>
              <a href="?paginaRestaurantes=<?php echo $paginaActualRest + 1; ?>">Siguiente</a>
            <?php } ?>
          </div>
        </div>
        <br>
        <div class="turistas">
          <h2>Turistas</h2>

          <!-- Búsqueda -->
          <form name="formBuscarTur" action="" method="post">
            <input class="txtformulario" type="text" id="busquedatur" name="busquedatur" placeholder="Buscar...">
            <button type="submit" id="btnBuscartur" name="btnBuscartur" class="botoningreso">Buscar</button>
          </form>

          <hr>
          <?php if (count($turistas) > 0 && $turistas !== []) { ?>
            <ul>
              <!--Recorremos el arreglo de turistas y mostramos los datos-->
              <?php foreach ($turistas as $turista) { ?>
                <li>
                  <a href="turista_perfil_index.php?emailtur=<?php echo $turista->emailtur ?>">
                    <?php echo $turista->alias ?>
                    (
                    <?php echo $turista->emailtur ?>)
                  </a>
                </li>

              <?php } ?>
            </ul>
          <?php } else { ?>

            <h3>No hay turistas registrados</h3>

          <?php } ?>

          <div class="pagination">
            <?php if ($paginaActualTur > 1) { ?>
              <a href="?paginaTuristas=<?php echo $paginaActualTur - 1; ?>">Anterior</a>
            <?php } ?>

            <?php for ($i = 1; $i <= $total_paginas_turista; $i++) { ?>
              <a href="?paginaTuristas=<?php echo $i; ?>" <?php echo ($i === $paginaActualTur) ? 'class="current"' : ''; ?>>
                <?php echo $i; ?>
              </a>
            <?php } ?>

            <?php if ($paginaActualTur < $total_paginas_turista) { ?>
              <a href="?paginaTuristas=<?php echo $paginaActualTur + 1; ?>">Siguiente</a>
            <?php } ?>
          </div>

        </div>
        <div class="admin-panel">
          <button class="botoningreso" onclick="toggle()"><a href="#admin-panel" class="abutton">Administrar
              usuarios</a></button>
        </div>
      </div>
      <div id="popup">
        <br>
        <div class="usuarios">
          <button class="botoningreso" onclick="window.location.href = 'registro_admin_index.php'">Crear
            adminstrador</button>
          <button class="botoningreso" onclick="window.location.href = 'admin_perfil_index.php'">Mi cuenta</button>
          <button class="botoningreso" onclick="window.location.href = 'pago_admin_index.php'">Pagos</button>
          <button class="botoningreso" onclick="window.location.href = 'restaurantes_vencidos_index.php'">Restaurantes
            con costos vencidos</button>
        </div>
      </div>
    </div>
  </main>
  <!-- Es lo que aparece dentro del modal -->

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
  <script src="https://kit.fontawesome.com/d913c5e0eb.js" crossorigin="anonymous"></script>
  <script src="js/menuheader.js"></script>
</body>

</html>