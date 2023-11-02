<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="css/post-login.css" />
  <link rel="shortcut icon" href="img/icon.svg" />
  <title>Pago de membresia</title>
</head>

<body>
  <header>
    <h1>
      <img class="imglogo" src="img/logo_producto.svg" alt="Logo del producto" />
    </h1>
    <h2 class="tituloheader">Tramite de membresía</h2>
    <nav>
      <div id="mySidebar" class="sidebar">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <a class="txtslide" href="cerrarsesion.php">Cerrar Sesion</a>
      </div>

      <div id="main">
        <button class="openbtn" onclick="openNav()">&#9776; </button>
      </div>
    </nav>
  </header>
  <main>
    <section class="registro-restaurante">

      <div class="contenedor">
        <!--Si la membresia fue rechazada-->
        <?php if (isset($datosrest->estadomembresia) && $datosrest->estadomembresia == 'rechazada') { ?>
          <p class="mensaje-error">Su membresia ha sido rechazada. Puede volver a enviar una solicitud de membresia o si
            hay
            un mensaje
            del administrador, siga las instrucciones.</p>
        <?php } ?>

        <!--Si hay un error al mostrar los datos del restaurante-->
        <?php if (isset($errorMostrarDatos) && $errorMostrarDatos == true) { ?>
          <p class="mensaje-error">Ha ocurrido un error al mostrar los datos del restaurante. Discupe las molestias.</p>
        <?php } ?>

        <!--Si hay un error al enviar la solicitud de membresia-->
        <?php if (isset($errorSolicitd) && $errorSolicitd == true) { ?>
          <p class="mensaje-error">Ha ocurrido un error al enviar la solicitud de membresia. Discupe las molestias.</p>
        <?php } ?>
        <!--Si hay una solicitud de renovacion pendiente, y es true, mostramos un mensaje-->
        <?php if (isset($renovacionpendiente) && $renovacionpendiente == true) { ?>
          <p class="mensaje-confirmacion">Su solicitud de renovacion de membresia ha sido enviada. En breve recibira un
            mensaje de confirmacion y podrá acceder a su cuenta. Disculpe las molestias. Se puede contactar con nosotros
            en equiposaborizame@saborizame.com.uy .
          </p>
        <?php } ?>

        <!--Si hay una solicitud pendiente el formulario de pago no se muestra-->
        <?php if (isset($membresiapendiente) && $membresiapendiente == false && $renovacionpendiente !== true) { ?>

          <h3>Para completar el registro del restaurante, debe abonar una membresía:</h3>

          <form action="" method="POST" enctype="multipart/form-data" id="formulario-pago">
            <br>
            <label for="membresia">Selecciona una membresía:</label>
            <br>
            <select class="txtformularioselect" id="membresia" name="membresia" required>
              <option value="mensual">1 Mes - $9.99</option>
              <option value="anual">1 Año - $99.99</option>
              <option value="bianual">2 Años - $149.99</option>
            </select>
            <br>
            <br>
            <h3> Cuenta BHU del Brou: 892-72-176659.</h3>
            <br>
            <label for="comprobante">Suba su comprobante de pago:</label>
            <br>
            <input class="txtformulario" type="file" id="comprobante" name="comprobante" accept="image/*" required>
            <br>

            <!--Si hay un error al subir el comprobante de pago-->
            <?php if (isset($errorSubirComprobante) && $errorSubirComprobante == true) { ?>
              <p class="mensaje-error">Ha ocurrido un error al subir el comprobante de pago. Discupe las molestias.</p>
            <?php } ?>
            <br>
            <input class="botoningreso" type="submit" value="Enviar Pago" id="enviarSolicitudbtn" name="enviarSolicitudbtn">
          </form>

        <?php } ?>

        <!-- SI EL RESTAURANTE TIENE UNA MEMBRESIA PENDIENTE DE CONFIRMACION, MOSTRAMOS UN MENSAJE DE CONFIRMACION -->
        <?php if (isset($membresiapendiente) && $membresiapendiente == true) { ?>
          <br>
          <h3 class="mensaje-confirmacion" id="confirmacion-pago">Su pago está en espera de confirmación por el
            administrador
            del sitio.</h3>
          <br>
          <h2 class="titulo">Datos de la membresia solicitada:</h2>
          <br>
          <h3>Tipo de membresia:
            <br>
            <?php echo $datosrest->tipomembresia ?>
        </h3>
          <br>
          <h3>Estado de solicitud:
            <br>
            <?php echo $datosrest->estadomembresia ?>
        </h3>
          <br>
          <!--Boton para mostrar la imagen-->
          <button class="botoningreso" id='obtenerComprobantePago'>Ver comprobante de pago</button>
          <script>
            // Agrega un evento de clic al botón
            document.getElementById('obtenerComprobantePago').addEventListener('click', function () {
              // URL de tu archivo PDF en el servidor
              var pdfUrl = '<?php echo $datosrest->comprobante_pago ?>';

              //mostramos en consola la url del comprobante de pago
              console.log(pdfUrl);

              // Abre una nueva ventana o pestaña con el PDF
              window.open(pdfUrl, 'Comprobante de pago');
            });
          </script>

          <form action="" method="post" name="cancelarSoli">

            <input class="botoningreso" type="submit" value="Cancelar Solicitud" id="cancelarSoli" name="cancelarSoli">

          </form>


          <?php

          //Si se cancela la solicitud de membresia pero da error en errorCancelarSolici, mostramos un mensaje de error
          if (isset($errorCancelarSolici) && $errorCancelarSolici == true) { ?>
            <p class="mensaje-error">Ha ocurrido un error al cancelar la solicitud de membresia. Discupe las molestias.</p>

          <?php }
        } ?>

        <!-- SI EL RESTAURANTE TIENE UN MENSAJE DEL ADMINISTRADOR, MOSTRAMOS EL MENSAJE -->
        <?php if (isset($haymensaje) && $haymensaje == true) { ?>
          <br>
          <h3>Mensaje del administrador</h3>
          <!--Ponemos un textarea con el mensaje del administrador-->
          <textarea class="mensaje-admin" id="mensaje-admin" name="mensaje-admin"
            readonly><?php echo $mensaje ?></textarea>

        <?php } ?>
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
      </div>
    </section>
  </footer>
  <!-- Scripts usados para la página -->
  <script src="js/menuheader.js"></script>
</body>
</html>