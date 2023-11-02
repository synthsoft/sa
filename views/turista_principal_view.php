<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="css/post-login.css" />
  <link rel="shortcut icon" href="img/icon.svg" />
  <title>Interfaz de Turista</title>
</head>

<body>
  <header>
    <h1>
      <!-- Logo del producto -->
      <img class="imglogo" src="img/logo_producto.svg" alt="Logo del producto" />
    </h1>
    <h2 class="tituloheader">Menú Turista</h2>
    <nav>
      <div id="mySidebar" class="sidebar">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>

        <a class="txtslide" href="turista_perfil_index.php">Perfil</a>

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
        <h2 class="titulo">Buscar restaurante</h2>
        <form action="" method="post" name="formBusqueda">
          <div>
            <input class="txtformulariobuscar" name="buscarRestoran" type="text" id="restaurant-name"
              placeholder="Buscar por nombre, tipo de comida o pais">
            <button type="submit" class="botoningreso" name="search-button">Buscar</button>
          </div>
        </form>

        <!--Este es el formulario para filtrar por tipo de restaurante-->
        <form action="" method="post" name="filtroPorTipo">
          <div class="filters">

            <label for="filter-category">Tipo de restaurante:</label>
            <select class="txtformularioselect" id="filter-category" name="tiporestaurante">
              <option value="todas">Todas</option>
              <option value="rbuffet" <?php if (isset($_SESSION['tiporestaurante']) && $_SESSION['tiporestaurante'] == "rbuffet") {
                echo 'selected';
              } ?>>Buffet</option>
              <option value="rcrapida" <?php if (isset($_SESSION['tiporestaurante']) && $_SESSION['tiporestaurante'] == "rcrapida") {
                echo 'selected';
              } ?>>Comida rápida</option>
              <option value="rcrcasual" <?php if (isset($_SESSION['tiporestaurante']) && $_SESSION['tiporestaurante'] == "rcrcasual") {
                echo 'selected';
              } ?>>Rápida y casual</option>
              <option value="rautor" <?php if (isset($_SESSION['tiporestaurante']) && $_SESSION['tiporestaurante'] == "rautor") {
                echo 'selected';
              } ?>>De autor</option>
              <option value="rgourmet" <?php if (isset($_SESSION['tiporestaurante']) && $_SESSION['tiporestaurante'] == "rgourmet") {
                echo 'selected';
              } ?>>Gourmet</option>
              <option value="rtematico" <?php if (isset($_SESSION['tiporestaurante']) && $_SESSION['tiporestaurante'] == "rtematico") {
                echo 'selected';
              } ?>>Temático</option>
              <option value="rpllevar" <?php if (isset($_SESSION['tiporestaurante']) && $_SESSION['tiporestaurante'] == "rpllevar") {
                echo 'selected';
              } ?>>Para llevar</option>

              <!-- Agregar más categorías según sea necesario -->
            </select>
            <input type="submit" name="enviarFiltro" value="Filtrar">

          </div>
        </form>
      </div>
    </section>


    <h2 class="titulobody" id="h1Restaurantes">Restaurantes</h2>
    <br>
    <!--Si hay registros-->
    <div class="contenedor">
      <section class="restaurant-list">
        <?php if (count($restaurantes) > 0 && $restaurantes !== []) { ?>
          <?php foreach ($restaurantes as $restaurante) { ?>
            <div class="restaurant">
              <img class="restaurant-image" src="<?php echo $restaurante->fotoperfilologo ?>" alt="">
              <h2>
                <a href="restaurante_turista_index.php?action=mostrarRestaurante&emailrest=<?php echo $restaurante->emailrest; ?>">
                  <?php echo $restaurante->nombrerestaurante; ?>
                </a>
              </h2>
              <p>
                <?php echo $restaurante->descrrestaurante; ?>
              </p>
              <p>
                <?php echo $restaurante->dirrestaurante; ?>
              </p>

              <!-- Tenemos que poner un poco de php acar para que se muestre el promedio de la reseña -->
              <?php
              $promediostdrestgral = $resenainstancia->obtenerResenasPorAreaRestaurante($restaurante->emailrest, "restorangral");
              $promediorestaurantegral = $promediostdrestgral->promedio;
              ?>

              <p>
                Puntaje de los turistas:
                <?php echo $promediorestaurantegral; ?>
              </p>
            </div>
          <?php } ?>


        <?php } else { ?>
          <h3>No hay restaurantes disponibles en tu zona.</h3>
        <?php } ?>

    </div>
    </section>

    <!--Si se presiono un filtro de tipo de restaurante vamos a cambiar el paginado para pasar
    por parametro el tipo de restaurante y si se pasa de pagna no se pierde el filtro-->
    <!--Si se presiono un filtro de tipo de restaurante vamos a cambiar el paginado para pasar
    por parametro el tipo de restaurante y si se pasa de pagna no se pierde el filtro-->
    <?php if (isset($_SESSION['tiporestaurante']) && $_SESSION['tiporestaurante'] != "") { ?>

      <!--SI hay mas de una pagina mostrar paginado-->
      <?php if ($total_paginas > 1) { ?>

        <div class="pagination">
          <?php if ($paginaActual > 1) { ?>
            <a href="?filtro=<?php echo $_SESSION['tiporestaurante'] ?>&pagina=<?php echo $paginaActual - 1; ?>">Anterior</a>
          <?php } ?>

          <?php for ($i = 1; $i <= $total_paginas; $i++) { ?>
            <a href="?filtro=<?php echo $_SESSION['tiporestaurante'] ?>&pagina=<?php echo $i; ?>" <?php echo ($i === $paginaActual) ? 'class="current"' : ''; ?>>
              <?php echo $i; ?>
            </a>
          <?php } ?>

          <?php if ($paginaActual < $total_paginas) { ?>
            <a href="?filtro=<?php echo $_SESSION['tiporestaurante'] ?>&pagina=<?php echo $paginaActual + 1; ?>">Siguiente</a>
          <?php } ?>
        </div>

        <!-- Cierra el if de si hay mas de una pagina -->s
      <?php } ?>

      <!--Si no se presiono un filtro de tipo de restaurante vamos a cambiar el paginado para pasar 
      que no pase ningun filtro-->
    <?php } else { ?>

      <?php if ($total_paginas > 1) { ?>
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

        <!-- Cierra el if de si hay mas de una pagina -->
      <?php } ?>

      <!-- Cierra el if de el paginado sin enviar filtro, osea el else -->
    <?php } ?>


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

  <!-- Scripts utilizados -->
  <script src="https://kit.fontawesome.com/d913c5e0eb.js" crossorigin="anonymous"></script>
  <script src="js/menuheader.js"></script>
</body>

</html>