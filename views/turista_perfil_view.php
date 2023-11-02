<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="css/post-login.css" />
  <link rel="shortcut icon" href="img/icon.svg" />
  <script src="https://kit.fontawesome.com/d913c5e0eb.js" crossorigin="anonymous"></script>
  <title>Mi perfil</title>
</head>

<body>
  <header>
    <h1>
      <!-- Logo del producto -->
      <img class="imglogo" src="img/logo_producto.svg" alt="Logo del producto" />
    </h1>
    <h2 class="tituloheader">Mi perfil</h2>
    <nav>
      <div id="mySidebar" class="sidebar">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <?php if (isset($usuarioadmin) && $usuarioadmin == true) { ?>
          <a class="txtslide" href="admin_index.php">Volver</a>
        <?php } else { ?>
          <a class="txtslide" href="turista_principal_index.php">Volver</a>
        <?php } ?>
        <a class="txtslide" href="cerrarsesion.php">Cerrar Sesion</a>
      </div>

      <div id="main">
        <button class="openbtn" onclick="openNav()">&#9776; </button>
      </div>
    </nav>
  </header>

  <main>
    <!-- Modal -->
    <div class="modal fade" id="eliminarCuenta" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
      aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">Eliminar cuenta:</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p>IMPORTANTE: La decision de eliminar la cuenta no tiene vuelta atrás, ademas si se toma
              la decision, el correo con el que estas registrado quedará inhabilitado para volver a registrarse en el
              sistema.
              Esperemos que reconsideres tu decisión.
              <br>
              <br>
              - Atte el equipo de Saborízame.
            </p>
            <form action="" method="POST" name="formEliminarUser">

              <label for="contraseña">Ingrese su Contraseña:</label>
              <input type="password" id="contrasena" name="contrasenaeliminar1">
              <br>

              <label for="contraseña">Repetir Contraseña :</label>
              <input type="password" id="contrasena" name="contrasenaeliminar2">

              <!-- Si hay un error al ingresar la contraseña -->
              <?php if (isset($_SESSION['contrasenaMensajeBaja'])) { ?>
                <p class="error">
                  <?php echo $_SESSION['contrasenaMensajeBaja'];
                  unset($_SESSION['contrasenaMensajeBaja']); ?>
                </p>
              <?php } ?>

              <input type="hidden" name="seEnvioEliminar">

              <!-- Si es administrador no es necesario que ponga la contraseña -->
              <?php if (isset($usuarioadmin) && $usuarioadmin == true) { ?>
                <p>No es necesario que ponga la contrasena ya que es administrador</p>
              <?php } ?>
          </div>
          <div class="modal-footer">
            <button class="botoncancelar" type="button" data-bs-dismiss="modal">Cancelar</button>
            <button class="botonrechazo" type="button" name="eliminarBtn" id="eliminarBtn">Eliminar</button>
          </div>
          </form>
        </div>
      </div>
    </div>


    <section>
      <div class="contenedor">
        <div class="contenedordatos">
          <?php if (isset($_SESSION['errorBaja'])) { ?>
            <p class="error">
              <?php echo $_SESSION['errorBaja'];
              unset($_SESSION['errorBaja']); ?>
            </p>
          <?php } ?>


          <form action="" method="POST" name="formCambiosUser">
            <label for="nombre">Alias:</label>
            <br>
            <input class="txtformulario" type="text" id="alias" name="alias" value="<?php echo $alias ?>">
            <br>
            <?php if (isset($_SESSION['aliasMensaje'])) { ?>
              <p class="error">
                <?php echo $_SESSION['aliasMensaje'];
                unset($_SESSION['aliasMensaje']); ?>
              </p>
            <?php } ?>
            <br>
            <label for="correo">Correo:</label>
            <br>
            <input class="txtformulario" type="email" id="correo" name="correo" value="<?php echo $emailtur ?>"
              readonly> <br>
            <br>
            <?php if (isset($usuarioadmin) && $usuarioadmin == false) { ?>
              <label for="contraseña">Ingrese su Contraseña actual (si desea cambiar la contraseña):</label>
              <br>
              <input class="txtformulario" type="password" id="contrasena" name="contrasenaactual">
              <br>
              <br>
            <?php } ?>
            <label for="contraseña">Contraseña Nueva:</label>
            <br>
            <input class="txtformulario" type="password" id="contrasena" name="contrasena1">
            <br>
            <br>
            <label for="contraseña">Repetir Contraseña Nueva:</label>
            <br>
            <input class="txtformulario" type="password" id="contrasena" name="contrasena2">
            <?php if (isset($_SESSION['contrasenaMensaje'])) { ?>
              <p class="error">
                <?php echo $_SESSION['contrasenaMensaje'];
                unset($_SESSION['contrasenaMensaje']); ?>
              </p>
            <?php } ?>
            <br>
            <br>
            <input class="botoningreso" type="submit" value="Guardar Cambios" name="submitCambiosUser">
            <br>
            <button class="botoningreso" type="button" data-bs-toggle="modal" data-bs-target="#eliminarCuenta">Dar de
              baja tu cuenta(ver
              más)</button>

          </form>
        </div>
      </div>
    </section>

    <section>
      <div class="contenedor">
        <div class="contenedordatos">
          <h2 class="titulo">Estadias</h2>
          <br>

          <!-- Si no hay un hospedaje en curso  no se muestra la estadia en curso -->
          <?php if ($nohayhospedajeencurso !== true) { ?>

            <h3>Estadía en curso</h3>
            <table>
              <tr>
                <th>Hotel</th>
                <br>
                <th>Fecha inicio</th>
                <br>
                <th>Fecha fin</th>
                <br>
              </tr>
              <tr>
                <td>
                  <?php echo $nombrehotel ?>
                </td>
                <td>
                  <?php echo date('d/m/Y', strtotime($finiciohospedaje)) ?>
                </td>
                <td>
                  <?php echo date('d/m/Y', strtotime($ffinhospedaje)) ?>
                </td>
              </tr>

            </table>
            <br>
            <form action="" method="post" name="cancelarEstadiaForm">
              <button class="botoningreso" type="submit" name="cancelaEstadiabtn">Cancelar estadia</button>
              <br>
              <br>
            </form>
          <?php } ?>

          <h2 class="titulo">Historial de Estadías</h2>
          <br>
          <table>
            <?php if (count($hospedajes) > 0 && $hospedajes !== []) { ?>
              <tr>
                <br>
                <th>Hotel</th>
                <br>
                <th>Fecha inicio</th>
                <br>
                <th>Fecha fin</th>
              </tr>

              <?php foreach ($hospedajes as $hospedaje) { ?>
                <tr>
                  <td>
                    <?php echo $hospedaje->nombrehotel ?>
                  </td>
                  <td>
                    <?php echo date('d/m/Y', strtotime($hospedaje->finiciohospedaje)) ?>
                  </td>
                  <td>
                    <?php echo date('d/m/Y', strtotime($hospedaje->ffinhospedaje)) ?>
                  </td>
                <tr>

                <?php } ?>
              <?php } else {
              echo "<h3>No hay hospedajes registrados</h3>";
            } ?>

          </table>

          <!--Su usuario admin esta seteado y es true le agregamos el parametro
      emailtur por get a los links de la paginacion-->
          <?php if (isset($usuarioadmin) && $usuarioadmin == true) { ?>
            <div class="pagination">
              <?php if ($paginaActualEstadias > 1) { ?>
                <a
                  href="?paginaEstadias=<?php echo $paginaActualEstadias - 1; ?>&emailtur=<?php echo $emailtur; ?>">Anterior</a>
              <?php } ?>

              <?php for ($i = 1; $i <= $total_paginasEstadias; $i++) { ?>
                <a href="?paginaEstadias=<?php echo $i; ?>&emailtur=<?php echo $emailtur; ?>" <?php echo ($i === $paginaActualEstadias) ? 'class="current"' : ''; ?>>
                  <?php echo $i; ?>
                </a>
              <?php } ?>

              <?php if ($paginaActualEstadias < $total_paginasEstadias) { ?>
                <a
                  href="?paginaEstadias=<?php echo $paginaActualEstadias + 1; ?>&emailtur=<?php echo $emailtur; ?>">Siguiente</a>
              <?php } ?>

            </div>
          <?php } else { ?>

            <div class="pagination">
              <?php if ($paginaActualEstadias > 1) { ?>
                <a href="?paginaEstadias=<?php echo $paginaActualEstadias - 1; ?>">Anterior</a>
              <?php } ?>

              <?php for ($i = 1; $i <= $total_paginasEstadias; $i++) { ?>
                <a href="?paginaEstadias=<?php echo $i; ?>" <?php echo ($i === $paginaActualEstadias) ? 'class="current"' : ''; ?>>
                  <?php echo $i; ?>
                </a>
              <?php } ?>

              <?php if ($paginaActualEstadias < $total_paginasEstadias) { ?>
                <a href="?paginaEstadias=<?php echo $paginaActualEstadias + 1; ?>">Siguiente</a>
              <?php } ?>

            </div>
          <?php } ?>
        </div>
      </div>

    </section>

    <section>
      <div class="contenedor">
        <br>
        <div class="contenedordatos">
          <h2 class="titulo">Reseñas realizadas</h2>
          <br>
          <table>
            <!-- Si hay reseñas las mostramos -->
            <?php if (count($resenas) > 0 && $resenas !== []) { ?>
              <tr>
                <th>Restaurante</th>
                <th>Instalaciones</th>
                <th>Menu Gastronomico</th>
                <th>Atencion</th>
                <th>Puntaje</th>
                <th>Fecha:</th>
              </tr>

              <?php foreach ($resenas as $resena) { ?>
                <tr>
                  <td>
                    <?php echo $resena->nombrerestaurante ?>
                  </td>
                  <td>
                    <?php echo $resena->restorangral ?>
                  </td>
                  <td>
                    <?php echo $resena->instalaciones ?>
                  </td>
                  <td>
                    <?php echo $resena->menugastronomico ?>
                  </td>
                  <td>
                    <?php echo $resena->atencion ?>
                  </td>
                  <td>
                    <?php echo $resena->fyhpuntaje ?>
                  </td>
                <tr>
                <?php } ?>
              <?php } else {
              echo "<h3>No hay reseñas registradas</h3>";
            } ?>
          </table>

          <!--Su usuario admin esta seteado y es true vamos a hacer lo 
      mismo que con el paginado de los hospedajes, es decir agregarle el parametro
      emailtur por get a los links de la paginacion-->
          <?php if (isset($usuarioadmin) && $usuarioadmin == true) { ?>
            <div class="pagination">
              <?php if ($paginaActualResenas > 1) { ?>
                <a
                  href="?paginaResenas=<?php echo $paginaActualResenas - 1; ?>&emailtur=<?php echo $emailtur; ?>">Anterior</a>
              <?php } ?>

              <?php for ($i = 1; $i <= $total_paginasResenas; $i++) { ?>
                <a href="?paginaResenas=<?php echo $i; ?>&emailtur=<?php echo $emailtur; ?>" <?php echo ($i === $paginaActualResenas) ? 'class="current"' : ''; ?>>
                  <?php echo $i; ?>
                </a>
              <?php } ?>

              <?php if ($paginaActualResenas < $total_paginasResenas) { ?>
                <a
                  href="?paginaResenas=<?php echo $paginaActualResenas + 1; ?>&emailtur=<?php echo $emailtur; ?>">Siguiente</a>
              <?php } ?>

            </div>
          <?php } else { ?>
            <div class="pagination">
              <?php if ($paginaActualResenas > 1) { ?>
                <a href="?paginaResenas=<?php echo $paginaActualResenas - 1; ?>">Anterior</a>
              <?php } ?>

              <?php for ($i = 1; $i <= $total_paginasResenas; $i++) { ?>
                <a href="?paginaResenas=<?php echo $i; ?>" <?php echo ($i === $paginaActualResenas) ? 'class="current"' : ''; ?>>
                  <?php echo $i; ?>
                </a>
              <?php } ?>

              <?php if ($paginaActualResenas < $total_paginasResenas) { ?>
                <a href="?paginaResenas=<?php echo $paginaActualResenas + 1; ?>">Siguiente</a>
              <?php } ?>
            </div>
          <?php } ?>
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
  <script src="js/turista.js"></script>
  <script src="js/menuheader.js"></script>

  <!-- Scripts de Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
    integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
    integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF"
    crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>

</html>