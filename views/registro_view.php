<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="Content-Security-Policy" content="
  default-src 'self' https://cdn.jsdelivr.net https://code.jquery.com https://ka-f.fontawesome.com https://maps.googleapis.com; 
  img-src 'self' data: https://maps.gstatic.com https://www.instagram.com https://www.facebook.com https://github.com https://www.linkedin.com; 
  font-src 'self' https://ka-f.fontawesome.com; 
  script-src 'self' https://kit.fontawesome.com https://code.jquery.com https://maps.googleapis.com 'unsafe-hashes' 'unsafe-inline'; 
  style-src 'self' https://cdn.jsdelivr.net https://kit.fontawesome.com  'unsafe-inline';">

  <link rel="stylesheet" href="css/Ingreso.css" />
  <link rel="shortcut icon" href="img/icon.svg" />
  <title>Registro de usuario</title>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
  <header>
    <h1></h1>
  </header>
  <main>
    <!-- Mensaje mostrado cuando hay error al cargar la API -->
    <section class="MensajeRegistro">
      <div class="contenedorcompleto">
        <h2 class="registrocompleto">
          ¡Hubo un error al cargar componentes de la pagina, recarge la pagina y disculpe las molestias!
        </h2>
      </div>
    </section>

    <!-- Sección para elegir que tipo de usuario desea registrar -->
    <section class="SeleccionUsuario">

      <div class="contenedor">
        <h2 class="titulologin">
          Seleccione que tipo de usuario desea ser
        </h2>

        <!-- Si $errorRegistroTurista es true, y esta seteado mostramos un mensaje de error -->
        <?php if (isset($errorRegistroTurista) && $errorRegistroTurista): ?>
          <div class="errorRegistroTurista">
            <p>¡Hubo un error al registrarse, intente de nuevo!</p>
          </div>
        <?php endif; ?>

        <!-- Si $errorRegistroRestaurante es true, y esta seteado mostramos un mensaje de error -->
        <?php if (isset($errorRegistroRestaurante) && $errorRegistroRestaurante): ?>
          <div class="errorRegistroRestaurante">
            <p>¡Hubo un error al registrarse, intente de nuevo!</p>
          </div>
        <?php endif; ?>

        <div class="contenedordatos">
          <div class="tipousuario">
            <!-- Botones para acceder al menu de registro de dicho usuario seleccionado -->
            <button class="botontipousuario" onclick="RegisterTurista()">
              Turista
            </button>
            <button class="botontipousuario" onclick="RegisterRestaurante()">
              Restaurante
            </button>
          </div>
        </div>
        <a class="botoningreso" href="login_index.php">Volver</a>
      </div>
    </section>

    <!-- Sección para ingresar datos de registro del tipo de usuario de turista -->
    <section class="RegistroTurista">
      <div class="contenedordatosturista">
        <h2 class="titulologin">Registrarse como Turista</h2>
        <div class="contenedordatos">


          <!-- Ingreso de datos para registro -->
          <form action="" method="post" enctype="multipart/form-data" id="formTur">

          <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

            <label for="aliasTur">Ingrese su Alias/Nombre de usuario</label>
            <br>

            <!-- Le ponemos autocomplete al alias para una mejor experiencia de usuario -->

            <input type="text" class="txtformulario" id="aliasTur" placeholder="Nombre de usuario"
              onkeypress="return event.keyCode != 13;" required name="aliasturista" autocomplete="username"
              maxlength="250" />
            <br>
            <span id="errorAliasTur"></span>
            <br>

            <label for="contrasenaturista1">Ingrese su contraseña</label>
            <br>

            <!-- Le ponemos autocomplete="new-password" a las contrasenas para que no se autocomplete la contraseña -->
            <!-- lo que mejora la seguridad -->

            <input type="password" class="txtformulario" placeholder="Contraseña"
              onkeypress="return event.keyCode != 13;" required name="contrasenaturista1" id="contrasenaturista1"
              autocomplete="new-password" maxlength="128" />
            <br>
            <span id="errorContrasena1Tur"></span>
            <br>
            <label for="contrasenaturista2">Ingrese de nuevo su contraseña</label>
            <br>
            <input type="password" class="txtformulario" placeholder="Confirmar contraseña"
              onkeypress="return event.keyCode != 13;" required name="contrasenaturista2" id="contrasenaturista2"
              autocomplete="new-password" maxlength="128" />
            <br>
            <span id="errorContrasena2Tur"></span>
            <br>
            <label for="emailTur">Ingrese su correo</label>
            <br>
            <input type="email" class="txtformulario" id="emailTur" placeholder="Correo Electrónico"
              onkeypress="return event.keyCode != 13;" required name="emailturista" maxlength="250" />
            <br>
            <span id="errorCorreoTur"></span>
            <br>
            <label for="fototurista">Subir foto de perfil</label>
            <br>
            <input type="file" class="txtformulario" placeholder="Imagen de perfil" name="fototurista" id="fototurista"
              accept=".jpg, .jpeg, .png" />

            <span id="errorFotoTur"></span>

            <!--Creamos un input hidden que el controlador va a recibir para saber 
            que se esta registrando un turista-->

            <input type="hidden" name="formularioturista" value="formularioturista">
        </div>


        <div class="divboton">
          <!-- Botones para avanzar o retroceder -->
          <button class="botoningreso" onclick="RegresarSeleccion()">
            Volver
          </button>
          <button type="submit" class="botoningreso" name="botonRegistroTurista">
            ¡Registrate!
          </button>
        </div>
        </form>
      </div>
    </section>

    <!-- Sección para ingresar datos de registro del tipo de usuario de restaurante -->
    <section class="RegistroRestaurante">

      <!-- Parte 1 del registro de Restaurante -->
      <div class="contenedor1">
        <h2 class="titulologin">Registrarse como Restaurante (1 de 5)</h2>
        <form action="" method="post" enctype="multipart/form-data" id="formulario1restaurante">

        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

          <div class="contenedordatos">
            <!-- Ingreso de datos para registro -->

            <label for="nombreRestaurante">Ingrese el nombre del restaurante</label>
            <br>

            <input type="text" class="txtformulario" placeholder="Nombre del restaurante" name="nombrerestaurante"
              onkeypress="return event.keyCode != 13;" id="nombreRestaurante" maxlength="128" />

            <div id="mensajeErrorNombre" class="mensajeErrorNombre msjerror"></div>

            <br>

            <label for="aliasRestaurante">Ingrese su Alias/Nombre de usuario</label>

            <br>

            <input type="text" class="txtformulario" placeholder="Alias o nombre de usuario" name="aliasrestaurante"
              onkeypress="return event.keyCode != 13;" autocomplete="username" id="aliasRestaurante" maxlength="128" />

            <div id="mensajeErrorAlias" class="mensajeErrorAlias msjerror"></div>

            <br>

            <label for="contra1Restaurante">Ingrese su contraseña</label>

            <br>

            <input type="password" class="txtformulario" placeholder="Contraseña" name="contrasena1restaurante"
              onkeypress="return event.keyCode != 13;" id="contra1Restaurante" autocomplete="new-password"
              maxlength="128" />

            <div id="mensajeErrorContra1" class="mensajeErrorContra1 msjerror"></div>

            <br>

            <label for="contra2Restaurante">Ingrese de nuevo su contraseña</label>

            <br>

            <input type="password" class="txtformulario" placeholder="Confirmar contraseña"
              name="contrasena2restaurante" onkeypress="return event.keyCode != 13;" id="contra2Restaurante"
              autocomplete="new-password" maxlength="128" />

            <div id="mensajeErrorContra" class="mensajeErrorContra msjerror"></div>

            <br>

            <label for="emailRestaurante">Ingrese su correo</label>

            <br>

            <input type="email" class="txtformulario" placeholder="Correo Electrónico" name="correorestaurante"
              onkeypress="return event.keyCode != 13;" id="emailRestaurante" maxlength="250" />

            <div id="mensajeErrorCorreo" class="mensajeErrorCorreo msjerror"></div>


            <br>

            <label for="fotodelrestaurante" class="txtformulario">Subir logo del restaurante</label>

            <input type="file" class="txtformulario" placeholder="Logo del restaurante" name="fotorestaurante"
              id="fotodelrestaurante" onkeypress="return event.keyCode != 13;" accept=".jpg, .png, .jpeg" />

            <div id="mensajeErrorFoto" class="mensajeErrorFoto msjerror"></div>

          </div>

          <div class="divboton">
            <!-- Botones para avanzar o retroceder -->
            <button class="botoningreso" onclick="RegresarSeleccion()">
              Atras
            </button>
            <button class="botoningreso" type="button" value="Aceptar" name="envioparte1restaurante"
              onclick="validarYAvanzar1()">
              Siguiente
            </button>
          </div>

      </div>

      <!-- Parte 2 del registro de Restaurante -->
      <div class="contenedor2">
        <h2 class="titulologin">Registrarse como Restaurante (2 de 5)</h2>
        <div class="contenedordatos">
          <!-- Ingreso de datos para registro -->
          <label for="address_input">Ingresar dirección del restaurante</label>
          <br>
          <input type="text" id="address_input" placeholder="Ingrese la direccion del restaurante" class="txtformulario"
            name="inputdireccionrestaurante" onkeypress="return event.keyCode != 13;" maxlength="250" />
          <br>
          <br>
          <label for="neighborhood_input">Ingresar barrio del restaurante</label>
          <br>
          <input type="text" id="neighborhood_input" placeholder="Ingrese el barrio del restaurante" r
            class="txtformulario" name="inputbarriorestaurante" onkeypress="return event.keyCode != 13;"
            maxlength="250" />
          <br>
          <!-- Ponemos un span de error para mostrar el error -->
          <span id="errorUbicacion" name='errorUbicacion'></span>
          <br>
          <input type="hidden" id="direccion" name="direccion" onkeypress="return event.keyCode != 13;">
          <input type="hidden" id="barrio" name="barrio" onkeypress="return event.keyCode != 13;">
          <input type="hidden" id="ciudad" name="ciudad" onkeypress="return event.keyCode != 13;">
          <input type="hidden" id="latitud" name="latitud" onkeypress="return event.keyCode != 13;">
          <input type="hidden" id="longitud" name="longitud" onkeypress="return event.keyCode != 13;">

          <label for="descripcionRestaurante">Descripción del restaurante</label>
          <br>
          <input type="textarea" class="txtformulario" placeholder="Descripcion del restaurante y datos de relevancia"
            name="descrrestaurante" onkeypress="return event.keyCode != 13;" id="descripcionRestaurante"
            maxlength="250" />
          <br>
          <!-- Ponemos un span de error para mostrar el error -->
          <span id="errorDescrRest" class="errorDescrRest"></span>
          <br>
          <label for="telefono1">Ingrese los telefono del restaurante</label>
          <br>
          <input type="tel" class="txtformulario" placeholder="Telefono 1" name="telefono1"
            onkeypress="return event.keyCode != 13;" id="telefono1" maxlength="9" />

          <!-- Ponemos un span de error para mostrar el error -->
          <span id="errorTel1Rest" class="errorTel1Rest"></span>

          <input type="tel" class="txtformulario" placeholder="Telefono 2" name="telefono2"
            onkeypress="return event.keyCode != 13;" id="telefono2" maxlength="9" />

          <span id="errorTel2Rest" class="errorTel2Rest"></span>

          <!--llamamos a la API y la ponemos asyn para que no se bloquee la pagina -->
          <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDMv2gr3kcEdcvXH5tMwiOvrwcXTy8BNbg&libraries=places&callback=initMap">
           async defer </script>

        </div>

        <div class="divboton">
          <!-- Botones para avanzar o retroceder -->
          <button class="botoningreso" onclick="atras1()">Atras</button>
          <button class="botoningreso" type="button" value="Aceptar" onclick="validarYAvanzar2()"
            name="envioparte2restaurante">
            Siguiente
          </button>
        </div>
      </div>

      <!-- Parte 3 del registro de Restaurante -->
      <div class="contenedor3">
        <h2 class="titulologin">Registrarse como Restaurante (3 de 5)</h2>
        <h3>Premios</h3>
        <br>
        <div class="contenedordatos">
          <div class="premios">
            <!-- Ingreso de datos para registro -->
            <br>
            <label for="estrellasMichelin">Estrellas Michelin</label>
            <br>
            <select class="txtformulario" name="estrellasMichelin" onkeypress="return event.keyCode != 13;"
              id="estrellasMichelin">
              <option value="0">Ninguna</option>
              <option value="1">1 estrella</option>
              <option value="2">2 estrellas</option>
              <option value="3">3 estrellas</option>
            </select>
            <br>
            <label for="tenedorDeOro">Tenedores de Oro</label>
            <br>
            <select class="txtformulario" name="tenedorDeOro" onkeypress="return event.keyCode != 13;"
              id="tenedorDeOro">
              <option value="0">Ninguno</option>
              <option value="1">1 tenedor</option>
              <option value="2">2 tenedores</option>
              <option value="3">3 tenedores</option>
              <option value="4">4 tenedores</option>
              <option value="5">5 tenedores</option>
              <option value="6">6 tenedores</option>
              <option value="7">7 tenedores</option>
              <option value="8">8 tenedores</option>
              <option value="9">9 tenedores</option>
              <option value="10">10 tenedores</option>
            </select>
            <br>
            <label for="premioSiri">Premios Siri</label>
            <br>
            <select class="txtformulario" name="premioSiri" onkeypress="return event.keyCode != 13;" id="premioSiri">
              <option value="0">Ninguno</option>
              <option value="1">1 Siri</option>
              <option value="2">2 Siris</option>
              <option value="3">3 Siris</option>
              <option value="4">4 Siris</option>
              <option value="5">5 Siris</option>
              <option value="6">6 Siris</option>
              <option value="7">7 Siris</option>
              <option value="8">8 Siris</option>
              <option value="9">9 Siris</option>
              <option value="10">10 Siris</option>
            </select>
            <br>
          </div>
        </div>
        <div class="divboton">
          <!-- Botones para avanzar o retroceder -->
          <button class="botoningreso" onclick="atras2()">Atras</button>
          <button class="botoningreso" type="button" name="enviarparte3restaurante" onclick="siguiente2()">
            Siguiente
          </button>
        </div>
      </div>

      <!-- Parte 4 del registro de Restaurante -->
      <div class="contenedor4">
        <h2 class="titulologin">Registrarse como Restaurante (4 de 5)</h2>
        <div class="contenedordatos">
          <!-- Ingreso de datos para registro -->
          <h3>Tipo de restorán</h3>
          <br>
          <select class="txtformulario" name="tipoRestoran" onkeypress="return event.keyCode != 13;" id="tipoRestoran">
            <option value="rbuffet">Buffet</option>
            <option value="rcrapida">Comida rápida</option>
            <option value="rcrcasual">Rápida y casual</option>
            <option value="rautor">De autor</option>
            <option value="rgourmet">Gourmet</option>
            <option value="rtematico">Temático</option>
            <option value="rpllevar">Para llevar</option>
          </select>
          <br>
          <br>
          <input class="txtformulario" type="text" name="tipoComida" id="tipoComida"
            onkeypress="return event.keyCode != 13;" placeholder="Tipo de comida" maxlength="250">
          <br>
          <span id="errorTipoComida" class="errorTipoComida msjerror"></span>
          <br>
          <br>
          <select class="txtformulario" name="nacionalidadRestoran" id="combo" onkeypress="return event.keyCode != 13;">

            <!--LLamamos a PHP para mostrar las nacionalidades en el select  desde la variable
        $nacionalidades, definida en el controller-->

            <?php

            if (isset($nacionalidades) && $nacionalidades != null) {

              //Recorremos el array con un foreach para mostrar las nacionalidades en el select
              foreach ($nacionalidades as $key => $value) {

                echo '<option value="' . $value['idnacion'] . '">' . $value['nacionrestaurante'] . '</option>';

              }

            } else {
              echo '<option value="x">No hay nacionalidades, ocurrio un error</option>';
            }

            ?>

          </select>
          <br>
          <br>
          <h3>Horarios por Día:</h3>
          <div class="horarios">
            <br>
            <p>(Si el restaurante esta abierto marcar la casilla)</p>
            <br>
            <div class="contenedorhorario">
              <div class="amboshorarios">
                <label for="lunes_apertura">Lunes (Apertura):</label>
                <input type="time" id="lunes_apertura" name="horarios_apertura[lunes]">
                <label for="lunes_cierre">Lunes (Cierre):</label>
                <input type="time" id="lunes_cierre" name="horarios_cierre[lunes]">
              </div>
              <div class="checkhorario">
                <label for="apertura">¿Está abierto?</label>
                <input type="checkbox" name="apertura[lunes]" value='abierto'>

                <span id="errorHorariosL" class="errorHorariosL"></span>
              </div>
            </div>
            <br>
            <div class="contenedorhorario">
              <div class="amboshorarios">
                <label for="martes_apertura">Martes (Apertura):</label>
                <input type="time" id="martes_apertura" name="horarios_apertura[martes]">
                <label for="martes_cierre">Martes (Cierre):</label>
                <input type="time" id="martes_cierre" name="horarios_cierre[martes]">
              </div>
              <div class="checkhorario">
                <label for="apertura">¿Está abierto?</label>
                <input type="checkbox" name="apertura[martes]" value='abierto'>

                <span id="errorHorariosM" class="errorHorariosM"></span>
              </div>
            </div>
            <br>
            <div class="contenedorhorario">
              <div class="amboshorarios">
                <label for="miercoles_apertura">Miércoles (Apertura):</label>
                <input type="time" id="miercoles_apertura" name="horarios_apertura[miercoles]">
                <label for="miercoles_cierre">Miércoles (Cierre):</label>
                <input type="time" id="miercoles_cierre" name="horarios_cierre[miercoles]">
              </div>
              <div class="checkhorario">
                <label for="apertura">¿Está abierto?</label>
                <input type="checkbox" name="apertura[miercoles]" value='abierto'>

                <span id="errorHorariosX" class="errorHorariosX"></span>
              </div>
            </div>
            <br>
            <!-- Continuar con los demás días -->
            <div class="contenedorhorario">
              <div class="amboshorarios">
                <label for="jueves_apertura">Jueves (Apertura):</label>
                <input type="time" id="jueves_apertura" name="horarios_apertura[jueves]">
                <label for="jueves_cierre">Jueves (Cierre):</label>
                <input type="time" id="jueves_cierre" name="horarios_cierre[jueves]">
              </div>
              <div class="checkhorario">
                <label for="apertura">¿Está abierto?</label>
                <input type="checkbox" name="apertura[jueves]" value='abierto'>

                <span id="errorHorariosJ" class="errorHorariosJ"></span>
              </div>
            </div>
            <br>
            <div class="contenedorhorario">
              <div class="amboshorarios">
                <label for="viernes_apertura">Viernes (Apertura):</label>
                <input type="time" id="viernes_apertura" name="horarios_apertura[viernes]">
                <label for="viernes_cierre">Viernes (Cierre):</label>
                <input type="time" id="viernes_cierre" name="horarios_cierre[viernes]">
              </div>
              <div class="checkhorario">
                <label for="apertura">¿Está abierto?</label>
                <input type="checkbox" name="apertura[viernes]" value='abierto'>

                <span id="errorHorariosV" class="errorHorariosV"></span>
              </div>
            </div>
            <br>
            <div class="contenedorhorario">
              <div class="amboshorarios">
                <label for="sabado_apertura">Sábado (Apertura):</label>
                <input type="time" id="sabado_apertura" name="horarios_apertura[sabado]">
                <label for="sabado_cierre">Sábado (Cierre):</label>
                <input type="time" id="sabado_cierre" name="horarios_cierre[sabado]">
              </div>
              <div class="checkhorario">
                <label for="apertura">¿Está abierto?</label>
                <input type="checkbox" name="apertura[sabado]" value='abierto'>

                <span id="errorHorariosV" class="errorHorariosV"></span>
              </div>
            </div>
            <br>
            <div class="contenedorhorario">
              <div class="amboshorarios">
                <label for="juedomingo_apertura">Domingo (Apertura):</label>
                <input type="time" id="domingo_apertura" name="horarios_apertura[domingo]">
                <label for="domingo_cierre">Domingo (Cierre):</label>
                <input type="time" id="domingo" name="horarios_cierre[domingo]">
              </div>
              <div class="checkhorario">
                <label for="apertura">¿Está abierto?</label>
                <input type="checkbox" name="apertura[domingo]" value='abierto'>

                <span id="errorHorariosD" class="errorHorariosD"></span>
              </div>
            </div>
          </div>
          <br>
        </div>

        <div class="divboton">
          <!-- Botones para avanzar o retroceder -->
          <button class="botoningreso" onclick="atras3()">Atras</button>
          <button class="botoningreso" type="button" name="enviarparte4restaurante" onclick="validarYAvanzar3()">
            Siguiente
          </button>
        </div>
      </div>

      <!-- Parte 5 del registro de Restaurante -->
      <div class="contenedor5">
        <h2 class="titulologin">Registrarse como Restaurante (5 de 5)</h2>
        <div class="contenedordatos">
          <!-- Ingreso de datos para registro -->

          <label>Agregar menú:</label>
          <br>
          <br>
          <input type="file" class="txtformulario" required name="pdfMenu" accept=".pdf">
          <br>
          <span id="errorMenu" class="errorMenu msjerror"></span>

        </div>
        <div class="divboton">
          <!-- Botones para avanzar o retroceder -->
          <button class="botoningreso" onclick="atras4()">Atras</button>
          <button class="botoningreso" type="submit" name="registrocompletorestaurante" onclick=" return validarPDF()">
            ¡Registrame!
          </button>
        </div>
      </div>

      <!-- Cerro el formulario -->
      </form>

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

  <!-- Scripts usados para la página -->
  <script src="js/RegistrarseRestaurante.js"></script>
  <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDMv2gr3kcEdcvXH5tMwiOvrwcXTy8BNbg&libraries=places&callback=initMap">
    </script>
</body>

</html>