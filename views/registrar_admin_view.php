<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="css/post-login.css" />
  <link rel="shortcut icon" href="img/icon.svg" />
  <title>Creación de administrador</title>
</head>

<body>
  <header>
    <h1>
      <img class="imglogo" src="img/logo_producto.svg" alt="Logo del producto" />
    </h1>
    <h2 class="tituloheader">Crear administrador</h2>

    <nav>
      <div id="mySidebar" class="sidebar">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <a class="txtslide" href="admin_index.php">Volver</a>
        <a class="txtslide" href="admin_perfil_index.php">Mi cuenta</a>
        <a class="txtslide" href="pago_admin_index.php">Pagos</a>
        <a class="txtslide" href="restaurantes_vencidos_index.php">Restaurantes con costos vencidos</a>
        <a class="txtslide" href="cerrarsesion.php">Cerrar Sesion</a>
      </div>

      <div id="main">
        <button class="openbtn" onclick="openNav()">&#9776; </button>
      </div>
    </nav>

  </header>
  <section>
    <div class="contenedor">
      <h2 class="titulo">Ingrese los datos del nuevo administrador</h2>
      <?php if (isset($_SESSION['mensajeerror'])) { ?>
        <p>
          <?php echo $_SESSION['mensajeerror']; ?>
        </p>
        <?php unset($_SESSION['mensajeerror']);
      } ?>

      <?php if (isset($_SESSION['mensajeexito'])) { ?>
        <p>
          <?php echo $_SESSION['mensajeexito']; ?>
        </p>
        <?php unset($_SESSION['mensajeexito']);
      } ?>
      <br>
      <div class="contenedordatos">
        <form action="" method="POST" onsubmit="return validarFormularioCrearAdmin()">
          <label for="alias">Alias:</label>
          <br>
          <input class="txtformulario" type="text" id="alias" name="alias" required>
          <br>
          <label for="email">E-mail:</label>
          <br>
          <input class="txtformulario" type="email" id="email" name="email" required>
          <br>
          <label for="password">Contraseña:</label>
          <br>
          <input class="txtformulario" type="password" id="password" name="password1" required>
          <br>
          <br>
          <label for="password">Contraseña:</label>
          <br>
          <input class="txtformulario" type="password" id="password" name="password2" required>
          <br>
          <br>
          <input class="botoningreso" name="crearAdmin" type="submit" value="Crear Administrador">
        </form>
      </div>
    </div>
  </section>
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
  <script src="https://kit.fontawesome.com/d913c5e0eb.js" crossorigin="anonymous"></script>
  <script src="js/menuheader.js"></script>
</body>

</html>