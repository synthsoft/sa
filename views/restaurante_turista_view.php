<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="css/post-login.css" />
  <link rel="shortcut icon" href="img/icon.svg" />
  <title>Creación de administrador</title>
</head>

<body>
  <header>
    <h1>
      <!-- Logo del producto -->
      <img class="imglogo" src="img/logo_producto.svg" alt="Logo del producto" />
    </h1>
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

    <!-- Si no hay una resena del turista al restaurante, se muestra el modal para enviar una resena-->
    <?php if (isset($verificarresenaanteriior) && $verificarresenaanteriior == false) { ?>
      <!-- Modal -->
      <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="staticBackdropLabel">Enviar una reseña</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form action="" method="POST">

                <h3>Del restaurante en general</h3>

                <label for="calificacionRG">Calificación:</label>
                <select id="calificacionRG" name="calificacionRG" required>
                  <option value="5">5 Puntos</option>
                  <option value="4">4 Puntos</option>
                  <option value="3">3 Puntos</option>
                  <option value="2">2 Puntos</option>
                  <option value="1">1 Punto</option>
                </select>

                <h3>De las instalaciones</h3>

                <label for="calificacionI">Calificación:</label>
                <select id="calificacionI" name="calificacionI" required>
                  <option value="5">5 Puntos</option>
                  <option value="4">4 Puntos</option>
                  <option value="3">3 Puntos</option>
                  <option value="2">2 Puntos</option>
                  <option value="1">1 Punto</option>
                </select>

                <h3>Del Menu Gastronomico</h3>

                <label for="calificacionMG">Calificación:</label>
                <select id="calificacionMG" name="calificacionMG" required>
                  <option value="5">5 Puntos</option>
                  <option value="4">4 Puntos</option>
                  <option value="3">3 Puntos</option>
                  <option value="2">2 Puntos</option>
                  <option value="1">1 Punto</option>
                </select>

                <h3>De la atencion del personal</h3>

                <label for="calificacionAP">Calificación:</label>
                <select id="calificacionAP" name="calificacionAP" required>
                  <option value="5">5 Puntos</option>
                  <option value="4">4 Puntos</option>
                  <option value="3">3 Puntos</option>
                  <option value="2">2 Puntos</option>
                  <option value="1">1 Punto</option>
                </select>
                <br>
                <br>

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary" name="enviarResenaTur">Enviar reseña</button>
            </div>
            </form>
          </div>
        </div>
      </div>
    <?php } ?>



    <!-- Modal -->
    <div class="modal fade" id="horarioModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
      aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">Horario:</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <ul>
              <!--Recorremos el arreglo de horarios para mostrarlos, formateando
                 la hora de apertura y cierre para HH:MM y emprolijar el formato de los dias-->
              <?php

              if (isset($horariosRestauranteError) && $horariosRestauranteError == true) { ?>
                <li>
                  <?php echo "No hay horarios disponibles para este restaurante" ?>
                </li>
              <?php } else {

                foreach ($horariosrestaurante as $horario) { ?>
                  <li>
                    <?php echo $horario['dia'] . ": " . $horario['estado'] ?>
                  </li>
                <?php }
              } ?>
            </ul>
          </div>
          <div class="modal-footer">
            <button type="button" data-bs-dismiss="modal">Ok</button>

          </div>
        </div>
      </div>
    </div>


    <section class="restaurant-info">

      <div class="restaurant-profile">
        <img src="<?php echo $fotoperfil ?>" alt="Foto de Perfil del Restaurante" width="300px">
        <br>
        <h5>Breve descripcion:</h5>
        <span>
          <?php echo $descripcionrestaurante ?>
        </span>
        <br>
        <h5>Nacionalidad:</h5>
        <span>
          <?php if (isset($nacionalidad) && $nacionalidad !== false) {
            echo $nacionalidad;
          } ?>
        </span>
        <br>
        <h5>Tipo de comida:</h5>
        <span>
          <?php echo $tipocomida ?>
        </span>
        <br>
        <h5>Telefonos:</h5>
        <span>
          <?php echo $telefono1 ?>
        </span>
        <br>
        <span>
          <?php echo $telefono2 ?>
        </span>
      </div>

      <div class="restaurant-details">
        <h2>
          <?php echo $nombrerestaurante ?>
        </h2>
        <p>Dirección:
          <?php echo $direccionrestaurante ?>
        </p>
        <a href="https://www.google.com/maps?q=<?php echo $direccionrestaurante ?>" target="_blank">Abrir en Google
          Maps</a>


        <h3>Horario:</h3>
        <!-- Button trigger modal -->
        <button type="button" data-bs-toggle="modal" data-bs-target="#horarioModal">
          Ver horarios
        </button>


        <p>Ahora:
          <?php

          //Validamos que si esta seteado errorAbiertoCerrado es porque no se pudo obtener el horario del restaurante
          if (isset($errorAbiertoCerradoError) && $errorAbiertoCerradoError == true) {
            echo "No hay horarios disponibles para este restaurante";
          } else {
            echo $abiertocerrado;
          }

          ?>
        </p>

        <h3>Menu:</h3>
        <button id="abrirPdfBtn">Ver Menú</button>

        <script>
          // Agrega un evento de clic al botón
          document.getElementById('abrirPdfBtn').addEventListener('click', function () {
            // URL de tu archivo PDF en el servidor
            var pdfUrl = '<?php echo $rutapdf ?>';

            // Abre una nueva ventana o pestaña con el PDF
            window.open(pdfUrl, '_blank');
          });
        </script>


      </div>


      <?php if (isset($verificarresenaanteriior) && $verificarresenaanteriior == false) { ?>
        <form action="" method="POST">

          <!-- Si la solicitud de asistencia fue aprobada por el restaurante
          el turista ve en el boton el mensaje Haz una resena y el tipo de boton cambia de submit a button
          asi se puede abrir el modal de resenas -->

          <?php if (isset($validacionasistencia) && $validacionasistencia == "si") { ?>

            <button type="button" id="botonResenas" name="resenas" data-bs-target="#staticBackdrop"
              data-validacion="<?php echo $validacionasistencia; ?>">Solicitar Asistencia</button>

          <?php } else { ?>

            <button type="submit" id="botonResenas" name="resenas" data-bs-target="#staticBackdrop"
              data-validacion="<?php echo $validacionasistencia; ?>">Solicitar Asistencia</button>

          <?php } ?>

        </form>
      <?php } ?>

    </section>
    <section class="resenas-section">
      <!-- Si hay una resena del turista al restaurante, se muestra en la vista primero-->

      <?php if (isset($resenaturista) && !empty($resenaturista)) { ?>
        <div class="reseñas-list">
          <h4>Tu reseña:</h4>

          <p>
            Restoran en general:
            <?php echo $resenaturista->restorangral ?>
          </p>
          <p>
            Instalaciones:
            <?php echo $resenaturista->instalaciones ?>
          </p>
          <p>
            Menu Gastronomico:
            <?php echo $resenaturista->menugastronomico ?>
          </p>
          <p>
            Atencion del personal:
            <?php echo $resenaturista->atencion ?>
          </p>

        </div>
      <?php } ?>

      <h3>Reseñas de los usuarios:</h3>
      <!-- mostramos el listado de resenas del restaurante -->

      <ul class="reseñas-list">

        <?php if (isset($resenasPaginadas) && count($resenasPaginadas) > 0) {

          foreach ($resenasPaginadas as $resena) { ?>
            <li>
              <?php if (isset($resena->fotoperfilologo)) { ?>
                <img src="<?php echo $resena->fotoperfilologo ?>" alt="" width="80px">
              <?php } ?>
              <p>
                <?php echo $resena->alias ?>
              </p>
              <p>
                Restoran en general:
                <?php echo $resena->restorangral ?>
              </p>
              <p>
                Instalaciones:
                <?php echo $resena->instalaciones ?>
              </p>
              <p>
                Menu Gastronomico:
                <?php echo $resena->menugastronomico ?>
              </p>
              <p>
                Atencion del personal:
                <?php echo $resena->atencion ?>
              </p>
            </li>

          <?php }
        } else { ?>
          <li>
            <p>No hay reseñas para este restaurante</p>
          </li>
        <?php } ?>

        <div class="pagination">
          <?php if ($paginaActualResenas > 1) { ?>
            <a
              href="?action=mostrarRestaurante&emailrest=<?php echo $emailrest; ?>&paginaResenas=<?php echo $paginaActualResenas - 1; ?>">Anterior</a>
          <?php } ?>

          <?php for ($i = 1; $i <= $total_paginas_resenas; $i++) { ?>
            <a href="?action=mostrarRestaurante&emailrest=<?php echo $emailrest; ?>&paginaResenas=<?php echo $i; ?>" <?php echo ($i === $paginaActualResenas) ? 'class="current"' : ''; ?>>
              <?php echo $i; ?>
            </a>
          <?php } ?>

          <?php if ($paginaActualResenas < $total_paginas_resenas) { ?>
            <a
              href="?action=mostrarRestaurante&emailrest=<?php echo $emailrest; ?>&paginaResenas=<?php echo $paginaActualResenas + 1; ?>">Siguiente</a>
          <?php } ?>
        </div>



      </ul>
    </section>


    <section>

      <h2>Promociones:</h2>

      <div id="carouselExampleControls" class="carousel">
        <div class="carousel-inner">



          <?php if (isset($promociones) && count($promociones) > 0) {

            $first = true; // Agrega esta variable para rastrear el primer elemento
          

            foreach ($promociones as $promocion) {

              ?>

              <!-- Modal -->
              <div class="modal fade" id="modal_<?php echo $promocion->idpromocion ?>" data-bs-backdrop="static"
                data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="staticBackdropLabel">
                        <?php echo $promocion->nombrepromocion ?>
                      </h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <h6>Descripcion:</h6>
                      <p class="card-text">
                        <?php echo $promocion->descrpromocion ?>
                      </p>

                      <h6>Fecha de inicio</h6>
                      <p class="card-text">
                        <?php echo date('d/m/Y', strtotime($promocion->fyhiniciopromo)); ?>
                      </p>

                      <h6>Valida hasta:</h6>
                      <p class="card-text">
                        <?php echo date('d/m/Y', strtotime($promocion->fyhfinpromo)); ?>
                      </p>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      <button type="button" class="btn btn-primary">Understood</button>
                    </div>
                  </div>
                </div>
              </div>


              <div class="carousel-item <?php if ($first)
                echo 'active'; ?>">
                <div class="card">
                  <div class="img-wrapper"><img src="<?php echo $promocion->imagenpromocion ?>" class="d-block w-100"
                      alt="..."> </div>
                  <div class="card-body">
                    <h5 class="card-title">
                      <?php echo $promocion->nombrepromocion ?>
                    </h5>
                    <!-- Button trigger modal -->
                    <button type="button" data-bs-toggle="modal"
                      data-bs-target="#modal_<?php echo $promocion->idpromocion ?>">
                      Ver detalles
                    </button>


                  </div>
                </div>
              </div>

              <?php $first = false;
            }
          } else { ?>
            <h3>No hay promociones disponibles</h3>
          <?php } ?>
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls"
          data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls"
          data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>
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
  <script src="js/restaurante_y_resenas.js"></script>
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