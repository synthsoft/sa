<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="css/post-login.css" />
  <link rel="shortcut icon" href="img/icon.svg" />
  <title>Interfaz de pagos del Administrador</title>
</head>

<body>
  <header>
    <h1>
      <!-- Logo del producto -->
      <img class="imglogo" src="img/logo_producto.svg" alt="Logo del producto" />
    </h1>
    <h2 class="tituloheader">Pagos de membresias pendientes</h2>
    <nav>
      <div id="mySidebar" class="sidebar">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <a class="txtslide" href="admin_index.php">Volver</a>
        <a class="txtslide" href="admin_perfil_index.php">Mi cuenta</a>
        <a class="txtslide" href="registro_admin_index.php">Crear administrador</a>
        <a class="txtslide" href="restaurantes_vencidos_index.php">Restaurantes con costos vencidos</a>
        <a class="txtslide" href="cerrarsesion.php">Cerrar Sesion</a>
      </div>

      <div id="main">
        <button class="openbtn" onclick="openNav()">&#9776; </button>
      </div>
    </nav>
  </header>

  <main>
    <section>
      <!-- Tarjetas de restaurantes paginadas -->
      <div class="contenedor">
        <?php if (count($solicitudes) > 0 && $solicitudes !== []) { ?>

          <h3 class="titulo">Solicitudes de restaurantes</h3>
          <br>
          <?php foreach ($solicitudes as $solicitud) { ?>
            <div class="contenedordatos">

              <h4>Nombre del Restaurante:
                <br>
                <br>
                <?php echo $solicitud->nombrerestaurante ?>
              </h4>

              <!--Remplazamos los caracteres @ y . por un string vacio para que no se generen errores en el id del modal-->
              <?php $idModal = str_replace(['@', '.'], '', $solicitud->emailrest); ?>
              <br>
              <!-- Button trigger modal -->
              <button type="button" class="botoningreso" data-bs-toggle="modal" data-bs-target="#<?php echo $idModal ?>">
                Ver informacion del Restaurante
              </button>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="<?php echo $idModal ?>" data-bs-backdrop="static" data-bs-keyboard="false"
              tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">
                      <?php echo $solicitud->nombrerestaurante ?>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">

                    <h3 class="titulomodal">Datos de membresia:</h3>
                    <p>Tipo de membresia:
                      <br>
                      <?php
                      if ($solicitud->estadomembresia == "pendiente") {
                        echo $solicitud->tipomembresia;
                      } else {

                        if ($solicitud->estadorenovacion == "pendiente") {
                          echo $solicitud->tiporenovacion;

                        }
                      }
                      ?>
                    </p>

                    <p>Tipo de solicitud:
                      <br>
                      <?php
                      if ($solicitud->estadomembresia == "pendiente") {
                        echo "Solicitud de membresia";
                      } else {

                        if ($solicitud->estadorenovacion == "pendiente") {
                          echo "Solicitud de renovacion";

                        }
                      }
                      ?>

                    </p>

                    <h3 class="titulomodal">Datos Generales:</h3>
                    <p>Email:
                      <br>
                      <?php echo $solicitud->emailrest ?>
                    </p>
                    <p>Alias:
                      <br>
                      <?php echo $solicitud->alias ?>
                    </p>

                    <p>
                      <?php if (isset($solicitud->telefono1)) {
                        echo "Telefono 1: " . "<br>" . $solicitud->telefono1;
                      } ?>
                    </p>

                    <p>
                      <?php if (isset($solicitud->telefono2)) {
                        echo "Telefono 2: " . "<br>" . $solicitud->telefono2;
                      } ?>
                    </p>

                    <h3 class="titulomodal">Ubicacion:</h3>
                    <p>Direccion:
                      <br>
                      <?php echo $solicitud->dirrestaurante ?>
                    </p>
                    <p>Barrio:
                      <br>
                      <?php echo $solicitud->nombrebarrio ?>
                    </p>

                    <h3 class="titulomodal">Menu:
                      <br>
                      <button class="botoningreso" id=menuPdf>Ver menú</button>
                    </h3>

                    <script>
                      // Agrega un evento de clic al botón
                      document.getElementById('menuPdf').addEventListener('click', function () {
                        // URL de tu archivo PDF en el servidor
                        var pdfUrl = '<?php echo $solicitud->rutapdf ?>';

                        // Abre una nueva ventana o pestaña con el PDF
                        window.open(pdfUrl, 'Menu');
                      });
                    </script>

                    <!--Boton para mostrar la imagen-->
                    <button class="botoningreso" id='obtenerComprobantePago<?php echo $idModal; ?>'>Ver comprobante de
                      pago</button>
                    <script>
                      // Agrega un evento de clic al botón
                      document.getElementById('obtenerComprobantePago<?php echo $idModal; ?>').addEventListener('click', function () {
                        // URL de tu archivo PDF en el servidor
                        var pdfUrl = '<?php echo $solicitud->comprobante_pago ?>';

                        //mostramos en consola la url del comprobante de pago
                        console.log(pdfUrl);

                        // Abre una nueva ventana o pestaña con el PDF
                        window.open(pdfUrl, 'Comprobante de pago');
                      });
                    </script>

                    <br>

                    <form action="" method="post" name="formSolicitudesAdmin">
                      <h4 class="titulomodal">Mensaje en caso de rechazo:</h4>
                      <input class="txtformulario" type="textarea" id="textoRechazo" name="textoRechazo">
                      <input type="hidden" name="correoRestaurante" value="<?php echo $solicitud->emailrest ?>">
                      <input type="hidden" name="tipoMembresia" value="<?php echo $solicitud->tipomembresia ?>">
                      <input type="hidden" name="tipoRenovacion" value="<?php echo $solicitud->tiporenovacion ?>">
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="botoncancelar" data-bs-dismiss="modal">Cancelar</button>

                    <?php
                    if ($solicitud->estadomembresia == "pendiente") {
                      ?> <button class="botonrechazo" type="submit" id="botonRechazarMembresia"
                        name="botonRechazarMembresia">Rechazar</button>
                      <?php
                    } else {

                      if ($solicitud->estadorenovacion == "pendiente") {
                        ?> <button class="botonrechazo" type="submit" id="botonRechazarRenovacion"
                          name="botonRechazarRenovacion">Rechazar</button>
                        <?php

                      }
                    }
                    ?>

                    <?php
                    if ($solicitud->estadomembresia == "pendiente") {
                      ?> <button type="submit" class="botoningreso" name="botonAceptarMembresia">Aceptar</button>
                      <?php
                    } else {

                      if ($solicitud->estadorenovacion == "pendiente") {
                        ?> <button type="submit" class="botoningreso" name="botonAceptarRenovacion">Aceptar</button>
                        <?php

                      }
                    }
                    ?>
                  </div>
                  </form>
                </div>
              </div>
            </div>
          <?php } ?>
        </div>
      <?php } else { ?>
        <h3>No hay solicitudes de restaurantes registradas</h3>
      <?php } ?>

      <div class="pagination">
        <?php
        // Supongamos que tienes las siguientes variables definidas:
        // $total_paginas: el número total de páginas de resultados
        // $paginaActual: la página actual en la que te encuentras
        
        if ($total_paginas > 1) {
          // Mostrar el paginado solo si hay más de una página
          ?>
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
          <?php
        }
        ?>
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

  <script src="js/menuheader.js"></script>

  <!-- Scripts de Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
    integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
    integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF"
    crossorigin="anonymous"></script>
  <script src="https://kit.fontawesome.com/d913c5e0eb.js" crossorigin="anonymous"></script>
</body>



</html>