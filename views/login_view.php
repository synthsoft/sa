<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <!-- Agrega una política de seguridad de contenido (CSP) -->
  <meta http-equiv="Content-Security-Policy" content="
  default-src 'self' https://cdn.jsdelivr.net https://code.jquery.com https://ka-f.fontawesome.com; 
  img-src 'self' data: https://www.instagram.com https://www.facebook.com https://github.com https://www.linkedin.com; 
  font-src 'self' https://ka-f.fontawesome.com; 
  script-src 'self' https://kit.fontawesome.com https://code.jquery.com 'unsafe-hashes' 'unsafe-inline'; 
  style-src 'self' https://cdn.jsdelivr.net https://kit.fontawesome.com 'unsafe-inline';">


  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />
  <link rel="stylesheet" href="css/Ingreso.css" />
  <link rel="shortcut icon" href="img/icon.svg" />
  <title>Inicio de Sesión</title>
</head>

<body>
  <header>
    <h1></h1>
  </header>

  <main>
    <section>
      <div class="contenedor">

        <!--SI $errorLogin esta seteado, es porque ocurrio un error al iniciar sesion-->
        <!--Llamamos a PHP para mostrar el error-->
        <?php if (isset($errorLogin) && $errorLogin === true) { ?>
          <div class="alert alert-danger" role="alert">
            Ocurrion un error al iniciar sesion, por favor intente nuevamente.
          </div>
        <?php } ?>

        <form action="" method="post" onsubmit="return validarFormulario()" id="loginForm">

          <h2 class="titulologin">Inicio de Sesion</h2>
          <div class="contenedordatos">

            <p>Correo Electronico</p>
            <label for="email"></label>
            <br>
            <input class="txtformulario" type="email" name="correo" placeholder="Ingrese su correo electrónico" required
              autocomplete="email" maxlength="250" id="email" />

            <p>Contraseña</p>
            <label for="current-password"></label>
            <input class="txtformulario" type="password" name="contrasena" placeholder="Ingrese su contraseña" required
              autocomplete="current-password" maxlength="128" id="current-password" />
            <br>
            <span id="errorlogin" class="msjerror"></span>

            <input type="hidden" name="formulariologin" value="formulariologin" />
          </div>
          <div class="divbotonlogin">
            <button class="botoningreso" type="submit" name="botoningreso">
              Ingresar
            </button>
            <h5>
              ¿No tienes una cuenta aún? <br>
              <a class="register" href="registro_index.php ">¡Registrate!</a>
            </h5>
          </div>
          <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
        </form>
      </div>
      <div class="contenedorinfo">
        <img class="imginfo" src="img/logo_producto.svg" alt="Imagen de restaurante" />
        <h2>¿Quienes somos?</h2>
        <p>
          Somos una página la cual se encarga de brindar y ofrecer a los
          usuarios, una herramienta para facilitar la busqueda de un
          restaurante a la hora de estar alojado en un hotel.
        </p>
        <br />
        <p>
          Basamos nuestros parametros de busqueda y recomendaciones en reseñas
          y atributos de estos mismos restaurantes para facilitarte una buena
          elección.
        </p>
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
  <script src="js/loginjs.js"></script>
  <script src="https://kit.fontawesome.com/d913c5e0eb.js" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>

</html>