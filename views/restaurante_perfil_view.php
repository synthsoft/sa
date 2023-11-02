<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="Content-Security-Policy" content="
  default-src 'self' https://cdn.jsdelivr.net https://code.jquery.com https://ka-f.fontawesome.com; 
  img-src 'self' data: https://www.instagram.com https://www.facebook.com https://github.com https://www.linkedin.com; 
  font-src 'self' https://ka-f.fontawesome.com; 
  script-src 'self' https://kit.fontawesome.com https://code.jquery.com https://cdn.jsdelivr.net 'unsafe-hashes' 'unsafe-inline'; 
  style-src 'self' https://cdn.jsdelivr.net https://kit.fontawesome.com 'unsafe-inline';">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="css/post-login.css" />
  <link rel="shortcut icon" href="img/icon.svg" />
  <title>Turista perfil</title>
</head>

<body>
  <header>
    <h1>
      <!-- Logo del producto -->
      <img class="imglogo" src="img/logo_producto.svg" alt="Logo del producto" />
    </h1>
    <!-- Links del cabezal -->
      <nav>
      <div id="mySidebar" class="sidebar">
                <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
                <a class="txtslide" href="restaurante_principal_index.php">Volver</a>
                <a class="txtslide" href="cerrarsesion.php">Cerrar Sesion</a>
            </div>

            <div id="main">
                <button class="openbtn" onclick="openNav()">&#9776; </button>
            </div>
      </nav>
    
  </header>

  <main>

    <section class="profile-form">
      <h2>Datos del perfil</h2>
      <form action="" method="POST" name="formCambiosUser">
        <label for="nombre">Alias:</label>
        <input type="text" id="alias" name="alias" value="<?php echo $alias ?>">

        <?php if (isset($_SESSION['aliasMensaje'])) { ?>
          <p class="error">
            <?php echo $_SESSION['aliasMensaje'];
            unset($_SESSION['aliasMensaje']); ?>
          </p>
        <?php } ?>

        <br>

        <label for="correo">Correo:</label>
        <input type="email" id="correo" name="correo" value="<?php echo $_SESSION['email'] ?>" readonly>
        <br>

        <label for="contraseña">Ingrese su Contraseña actual (si desea cambiar la contraseña):</label>
        <input type="password" id="contrasena" name="contrasenaactual">
        <br>

        <label for="contraseña">Contraseña Nueva:</label>
        <input type="password" id="contrasena" name="contrasena1">
        <br>

        <label for="contraseña">Repetir Contraseña Nueva:</label>
        <input type="password" id="contrasena" name="contrasena2">
        <?php if (isset($_SESSION['contrasenaMensaje'])) { ?>
          <p class="error">
            <?php echo $_SESSION['contrasenaMensaje'];
            unset($_SESSION['contrasenaMensaje']); ?>
          </p>
        <?php } ?>
        <br>

        <input type="submit" value="Guardar Cambios" name="submitCambiosUser">

      </form>
    </section>

    <section class="profile-form">
      <h2>Membresia y renovacion</h2>
      <h3>Membresia actual:</h3>
      <table>
        <tr>
          <th>Fecha de inicio:</th>
          <th>Fecha de termino:</th>
          <th>Tipo de membresia:</th>
          <th>Ver comprobante</th>
        </tr>
        <tr>
          <td>
            <?php echo date('d/m/Y', strtotime($iniciomembresia)) ?>
          </td>
          <td>
            <?php echo date('d/m/Y', strtotime($finmembresia)) ?>
          </td>
          <td>
            <?php echo $tipomembresia ?>
          </td>
          <td> <!--Boton para mostrar la imagen-->
            <button id='obtenerComprobantePago'>Ver comprobante de pago</button>
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
          </td>
        </tr>
      </table>
      <h3>Renovacion de membresia:</h3>


      <?php if (isset($renovacionaceptada) && $renovacionaceptada == false) { ?>
        <!--Si la membresia fue rechazada-->
        <?php if (isset($datosrest->estadorenovacion) && $datosrest->estadorenovacion == 'rechazada') { ?>
          <p class="mensaje-error">Su membresia ha sido rechazada. Puede volver a enviar una solicitud de membresia o si hay
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

        <!--Si hay una solicitud pendiente el formulario de pago no se muestra-->
        <?php if (isset($renovacionpendiente) && $renovacionpendiente == false) { ?>

          <p>Solicitud de renovacion:</p>

          <form action="" method="POST" enctype="multipart/form-data" id="formulario-pago">
            <label for="membresia">Selecciona una membresía:</label>
            <select id="membresia" name="membresia" required>
              <option value="mensual">1 Mes - $9.99</option>
              <option value="anual">1 Año - $99.99</option>
              <option value="bianual">2 Años - $149.99</option>
            </select>
            <br>
            <p> Cuenta BHU del Brou: 892-72-176659.</p>
            <br>
            <label for="comprobante">Suba su comprobante de pago:</label>
            <input type="file" id="comprobante" name="comprobante" accept="image/*" required>
            <br>
            <!--Si hay un error al subir el comprobante de pago-->
            <?php if (isset($errorSubirComprobante) && $errorSubirComprobante == true) { ?>
              <p class="mensaje-error">Ha ocurrido un error al subir el comprobante de pago. Discupe las molestias.</p>
            <?php } ?>
            <br>
            <input type="submit" value="Enviar Pago" id="enviarSolicitudbtn" name="enviarSolicitudbtn">
          </form>

        <?php } ?>

        <!-- SI EL RESTAURANTE TIENE UNA MEMBRESIA PENDIENTE DE CONFIRMACION, MOSTRAMOS UN MENSAJE DE CONFIRMACION -->
        <?php if (isset($renovacionpendiente) && $renovacionpendiente == true) { ?>
          <p class="mensaje-confirmacion" id="confirmacion-pago">Su pago está en espera de confirmación por el administrador
            del sitio.</p>

          <h2>Datos de la membresia solicitada:</h2>
          <p>Tipo de membresia:
            <?php echo $datosrest->tiporenovacion ?>
          </p>
          <p>Estado de solicitud:
            <?php echo $datosrest->estadorenovacion ?>
          </p>

          <!--Boton para mostrar la imagen-->
          <button id='obtenerComprobantePago2'>Ver comprobante de pago</button>
          <script>
            // Agrega un evento de clic al botón
            document.getElementById('obtenerComprobantePago2').addEventListener('click', function () {
              // URL de tu archivo PDF en el servidor
              var pdfUrl = '<?php echo $datosrest->comprobante_pago_renovacion ?>';

              //mostramos en consola la url del comprobante de pago
              console.log(pdfUrl);

              // Abre una nueva ventana o pestaña con el PDF
              window.open(pdfUrl, 'Comprobante de pago');
            });
          </script>

        <?php } ?>

        <!-- SI EL RESTAURANTE TIENE UN MENSAJE DEL ADMINISTRADOR, MOSTRAMOS EL MENSAJE -->
        <?php if (isset($haymensaje) && $haymensaje == true) { ?>
          <br>
          <h3>Mensaje del administrador</h3>
          <!--Ponemos un textarea con el mensaje del administrador-->
          <textarea class="mensaje-admin" id="mensaje-admin" name="mensaje-admin" readonly><?php echo $mensaje ?></textarea>

        <?php }
      } ?>

      <?php if (isset($renovacionaceptada) && $renovacionaceptada == true) { ?>
        <h3>Datos sobre la renovacion:</h3>
        <table>
          <tr>
            <th>Fecha de inicio:</th>
            <th>Fecha de termino:</th>
            <th>Tipo de membresia:</th>
            <th>Ver comprobante</th>
          </tr>
          <tr>
            <td>
              <?php echo date('d/m/Y', strtotime($iniciorenovacion)) ?>
            </td>
            <td>
              <?php echo date('d/m/Y', strtotime($finrenovacion)) ?>
            </td>
            <td>
              <?php echo $tiporenovacion ?>
            </td>
            <td> <!--Boton para mostrar la imagen-->
              <button id='obtenerComprobantePago3'>Ver comprobante de pago</button>
              <script>
                // Agrega un evento de clic al botón
                document.getElementById('obtenerComprobantePago3').addEventListener('click', function () {
                  // URL de tu archivo PDF en el servidor
                  var pdfUrl = '<?php echo $datosrest->comprobante_pago_renovacion ?>';

                  //mostramos en consola la url del comprobante de pago
                  console.log(pdfUrl);

                  // Abre una nueva ventana o pestaña con el PDF
                  window.open(pdfUrl, 'Comprobante de pago');
                });
              </script>
            </td>
          </tr>
        </table>

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
    </section>
  </footer>

  <!-- Scripts -->
  <script src="js/menuheader.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
    integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
    integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF"
    crossorigin="anonymous"></script>

  <script src="https://kit.fontawesome.com/d913c5e0eb.js" crossorigin="anonymous"></script>
</body>

</html>