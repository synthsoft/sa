<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="css/post-login.css" />
  <link rel="shortcut icon" href="img/icon.svg" />
  <title>Ingresar estadía de hotel</title>
</head>

<body>
  <header>
    <h1>
      <!-- Logo del producto -->
      <img class="imglogo" src="img/logo_producto.svg" alt="Logo del producto" />
    </h1>
    <h2 class="tituloheader">Ingrese los datos de su estadía en el hotel</h2>
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
      <div class="contenedor">
        <section id="miSeccion" style="display: none;">
          <!-- Contenido de la sección oculta -->
          <span id="mensajeErrorFechas"></span>
        </section>

        <section>
        <!-- SI HAY UN ERROR EN EL REGISTRO DE LA ESTADIA, SE MUESTRA UN MENSAJE DE ERROR -->
        <?php if (isset($_SESSION['errorRegistroHotel']) && $_SESSION['errorRegistroHotel'] == true) { ?>
        <section class="errorRegistroHotel">
          Disculpe, ocurrio un error al registrar su estadia. Recarge la pagina e intentelo nuevamente.
        </section>
        <?php
          }
          unset($_SESSION['errorRegistroHotel']);
        ?>
        <div class="contenedordatos">
          <form action="" method="post" enctype="multipart/form-data" onsubmit="return validarFormulario()">
            <label for="places_input1">Ingrese el hotel en el que se hospeda:</label>
            <br>
            <input class="txtformulario" type="text" id="places_input1" name="hotelTuristaDireccion" maxlength="255"
              required>
            <br>
            <label for="places_input2">Ingrese el barrio en el que se encuentra el hotel:</label>
            <br>
            <input class="txtformulario" type="text" id="places_input2" name="barrioHotel" maxlength="255" required>
            <br>
            <!--Ingreso de fecha de inicio y fin de la estadía-->
            <label for="fechaInicio">Ingrese la fecha de inicio de su estadía:</label>
            <br>
            <input class="txtformulario" type="date" id="fechaInicio" name="fechaInicio" required>
            <br>
            <br>
            <label for="fechaFin">Ingrese la fecha de fin de su estadía:</label>
            <br>
            <input class="txtformulario" type="date" id="fechaFin" name="fechaFin" required>
            <br>
            <br>
            <input type="hidden" id="barrio" name="barrio">
            <input type="hidden" id="ciudad" name="ciudad">
            <input type="hidden" id="direccion" name="direccion">
            <input type="hidden" id="nombreHotel" name="hotel">
            <input class="botoningreso" type="submit" value="Aceptar" name="envioHotelTurista">
          </form>
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
  <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDMv2gr3kcEdcvXH5tMwiOvrwcXTy8BNbg&libraries=places&callback=initMap">
    </script>
  <!-- Script de la view-->
  <script src="js/ingresohotel.js"></script>
  <script src="https://kit.fontawesome.com/d913c5e0eb.js" crossorigin="anonymous"></script>
  <script src="js/menuheader.js"></script>
</body>

</html>