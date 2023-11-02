<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="css/post-login.css">
    <link rel="shortcut icon" href="img/icon.svg" />
    <!-- Asegúrate de proporcionar la ruta correcta a tu archivo CSS -->
    <title>Menú restaurante</title>
</head>

<body>
    <header>
        <!-- Logo del producto -->
        <h1>
            <img class="imglogo" src="img/logo_producto.svg" alt="Logo del producto" />
        </h1>
        <h2 class="tituloheader">
            <?php echo $nombrerestaurante ?>
        </h2>
        <!-- Apartado para retorno a Inicio Invitado -->
        <nav>
            <div id="mySidebar" class="sidebar">
                <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
                <a class="txtslide" href="perfil_restaurante_index.php">Mi cuenta</a>
                <a class="txtslide" href="#gestion-datos">Datos generales</a>
                <a class="txtslide" href="#horarios">Horarios</a>
                <a class="txtslide" href="#menu">Menú</a>
                <a class="txtslide" href="#promociones">Promociones</a>
                <a class="txtslide" href="#promociones-activas">Promociones activas</a>
                <a class="txtslide" href="#promociones-vencidas">Historial de promociones</a>
                <a class="txtslide" href="solicitudes_restaurante_index.php">Solicitudes de asistencia</a>
                <a class="txtslide"
                    href="resenas_index.php?action=resenasRest&emailrest=<?php echo $emailrest; ?>&emailtur=null">Reseñas</a>
                <a class="txtslide" href="cerrarsesion.php">Cerrar Sesion</a>
            </div>

            <div id="main">
                <button class="openbtn" onclick="openNav()">&#9776; </button>
            </div>
        </nav>
    </header>

    <main id="blur">
        <a href="#" id="scroll-to-top" class="btn btn-primary">Volver arriba</a>
        <!-- Modal Crear Promocion-->
        <div class="modal fade" id="crearPromocion" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Crear una nueva promocion</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="POST" enctype="multipart/form-data" name="formCrearPromo">
                            <h6>Ingrese el nombre:</h6>
                            <input type="text" name="nombreNuevaPromoInput" maxlength="128">

                            <h6>Ingrese la descripcion:</h6>
                            <textarea name="descripcionNuevaPromoInput" id="" cols="30" rows="5"
                                maxlength="255"></textarea>

                            <h6>Ingrese la fecha de inicio:</h6>
                            <input type="date" name="fechaInicioNuevaPromoInput">

                            <h6>Ingrese la fecha de fin:</h6>
                            <input type="date" name="fechaFinNuevaPromoInput">

                            <h6>Ingrese la imagen:</h6>
                            <input type="file" name="imagenNuevaPromoInput" accept=".jpg, .jpeg, .png">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" name="submitCrearPromo">Crear</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <section id="gestion-datos">
            <div class="contenedor">
                <h2>Gestión de Datos del Restaurante</h2>
                <br>


                <?php if (isset($_SESSION['exitoModificarDatos'])) { ?>
                    <section class="mensajeExitoError">
                        <?php echo $_SESSION['exitoModificarDatos']; ?>
                    </section>
                <?php }
                unset($_SESSION['exitoModificarDatos']); ?>

                <form action="" method="post">

                    <label for="telefono1">Telefono 1</label>
                    <br>
                    <input type="tel" id="telefono1" name="telefono1" value="<?php echo $telefono1; ?>"
                        onkeypress="return event.keyCode != 13;" maxlength="9">
                    <br>
                    <label for="telefono2">Telefono 2</label>
                    <br>
                    <input type="tel" id="telefono2" name="telefono2" value="<?php echo $telefono2; ?>"
                        onkeypress="return event.keyCode != 13;" maxlength="9">
                    <br>
                    <label for="direccion">Direccion</label>
                    <br>
                    <input type="text" id="direccion" name="direccion" value="<?php echo $direccion; ?>" readonly
                        onkeypress="return event.keyCode != 13;" maxlength="255">
                    <br>
                    <label for="barrio">Barrio:</label>
                    <br>
                    <input type="text" id="barrio" name="barrio" value="<?php echo $barrio; ?>" readonly
                        onkeypress="return event.keyCode != 13;" maxlength="255">
                    <br>
                    <p>Descripcion</p>
                    <br>
                    <textarea id="descripcion" name="descripcion" rows="4" cols="50"
                        onkeypress="return event.keyCode != 13;" maxlength="255"><?php echo $descripcion; ?></textarea>
                    <div id="contador-caracteres-desc">Caracteres restantes: </div>
                    <br>
                    <p>Tipo comida:</p>
                    <br>
                    <textarea id="tipoComida" name="tipoComida" rows="4" cols="50"
                        onkeypress="return event.keyCode != 13;"><?php echo $tipocomida; ?></textarea>
                    <div id="contador-caracteres-tc">Caracteres restantes: </div>
                    <br>

                    <br>
                    <p>Tipo restaurante:</p>
                    <select class="txtformulario" name="tipoRestoran" onkeypress="return event.keyCode != 13;">
                        <option value="<?php echo $tiporestauranteactual ?>">
                            <?php echo $tiporestaurante ?>(Actual)
                        </option>
                        <option value="rbuffet">Buffet</option>
                        <option value="rcrapida">Comida rápida</option>
                        <option value="rcrcasual">Rápida y casual</option>
                        <option value="rautor">De autor</option>
                        <option value="rgourmet">Gourmet</option>
                        <option value="rtematico">Temático</option>
                        <option value="rpllevar">Para llevar</option>
                    </select>

                    <p>Nacionalidad:</p>
                    <select class="txtformulario" name="nacionalidadRestoran" id="combo"
                        onkeypress="return event.keyCode != 13;">

                        <option value="<?php echo $idnacionalidadaactual; ?>">
                            <?php echo $nacionrestaurante ?>(Actual)
                        </option>

                        <?php
                        //Recorremos el array con un foreach para mostrar las nacionalidades en el select
                        foreach ($nacionalidad as $key => $value) {

                            echo '<option value="' . $value['idnacion'] . '">' . $value['nacionrestaurante'] . '</option>';

                        }
                        ?>
                    </select>

                    <br>
                    <button type="input" id="botonGestionDatos" name="botonGestionDatos">Guardar cambios</button>

                    <h6>Modificar visibilidad</h6>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault"
                            name="checkVisibilidad" <?php echo ($visibilidad == 'visible') ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="flexSwitchCheckDefault">
                            <?php echo ($visibilidad == 'visible') ? 'Visible' : 'Invisible'; ?>
                        </label>
                        <button type="submit" name="submitVisibilidad">Confirmar cambio en la visibilidad</button>
                    </div>
                </form>

            </div>
        </section>

        <!-- Desplegaremos los horarios en un form con inputs time recorriendo la variable $horarios -->
        <section id="horarios">

            <!-- SI ocurrio un error al obtener los restaurantes -->
            <?php if ($errorHorarios !== true) { ?>
                <div class="contenedor">
                    <?php if (isset($_SESSION['exitoModificarHorarios'])) { ?>
                        <section class="mensajeExitoError">
                            <?php echo $_SESSION['exitoModificarHorarios']; ?>
                        </section>
                    <?php }
                    unset($_SESSION['exitoModificarHorarios']); ?>



                    <h3>Horarios:</h3>
                    <form method="post" action="">
                        <?php foreach ($horarios as $horario) { ?>
                            <h4>Dia:
                                <?php echo $horario['dia'] ?>
                            </h4>

                            <p>Esta abierto? <input type="checkbox" name="abiertocerrado[<?php echo $horario['dia'] ?>]"
                                    id="abiertocerrado_<?php echo $horario['dia'] ?>" <?php echo ($horario['abiertocerrado'] == 'abierto') ? 'checked' : ''; ?>>
                            </p>

                            <label for="hora_inicio">Hora de Apertura:</label>
                            <input type="time" id="hora_inicio" name="hora_inicio[<?php echo $horario['dia'] ?>]"
                                value="<?php echo $horario['hapertura']; ?>">
                            <br>
                            <label for="hora_fin">Hora Cierre:</label>
                            <input type="time" id="hora_fin" name="hora_fin[<?php echo $horario['dia'] ?>]"
                                value="<?php echo $horario['hcierre']; ?>">
                            <br>
                        <?php } ?>
                        <button type="submit" name="horariosBtn">Guardar cambios</button>
                    </form>
                </div>
            <?php } else { ?>

                <p>Ocurrio un error al obtener los horarios, disculpe</p>
            <?php } ?>
        </section>


        <section id="menu">
            <div class="contenedor">
                <h2>Menú del Restaurante</h2>

                <?php if (isset($_SESSION['exitoModificarMenu'])) { ?>
                    <section class="mensajeExitoError">
                        <?php echo $_SESSION['exitoModificarMenu']; ?>
                    </section>
                <?php }
                unset($_SESSION['exitoModificarMenu']); ?>


                <p>Menú: <button id="abrirPdfBtn">Ver Menú</button></p>

                <script>
                    // Agrega un evento de clic al botón
                    document.getElementById('abrirPdfBtn').addEventListener('click', function () {
                        // URL de tu archivo PDF en el servidor
                        var pdfUrl = '<?php echo $rutapdf ?>';

                        // Abre una nueva ventana o pestaña con el PDF
                        window.open(pdfUrl, '_blank');
                    });
                </script>
                <form action="" method="POST" enctype="multipart/form-data">
                    <input type="file" name="menu_pdf" accept=".pdf" required>
                    <button type="submit" name="modificarMenu">Subir o Modificar Menú</button>
                </form>
            </div>
        </section>

        <section id="promociones">
            <div class="contenedor">
                <h2>Gestión de Promociones</h2>

                <?php if (isset($_SESSION['exitoCrearPromo'])) { ?>
                    <section class="mensajeExitoError">
                        <?php echo $_SESSION['exitoCrearPromo']; ?>
                    </section>
                <?php }
                unset($_SESSION['exitoCrearPromo']); ?>

                <!-- Button trigger modal -->
                <br>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#crearPromocion">
                    Crear promocion
                </button>


                <section id="promociones-activas">

                    <?php if (isset($_SESSION['exitoModificarPromo'])) { ?>
                        <section class="mensajeExitoError">
                            <?php echo $_SESSION['exitoModificarPromo']; ?>
                        </section>
                    <?php }
                    unset($_SESSION['exitoModificarPromo']); ?>


                    <br>
                    <form action="" name="formBuscadorPromosActivas" method="post">
                        <div class="contenedorpromociones">
                            <h2>Promociones Activas</h2>
                            <input type="text" name="buscadorPromosActivas"
                                placeholder="Buscar promocion por nombre o descripcion"
                                onkeypress="return event.keyCode != 13;">
                            <br>
                            <button type="submit" name="enviarBusquedaPromoActiva"
                                onkeypress="return event.keyCode != 13;">Buscar</button>
                            <br>

                            <!-- Si habilitarrestauranteactivas es true mostramos el boton restaurar -->
                            <?php if (isset($habilitarrestauraractivas) && $habilitarrestauraractivas = true) { ?>
                                <br>
                                <button type="submit" name="restaurarActivasBtn">Restaurar</button>
                                <br>
                            <?php } ?>
                        </div>
                    </form>

                    <?php if (isset($_SESSION['mensajeActivas'])) { ?>
                        <section class="mensajeExitoError">
                            <?php echo $_SESSION['mensajeActivas']; ?>
                        </section>
                    <?php }
                    unset($_SESSION['mensajeActivas']); ?>


                    <?php if (count($promocionesactivas) > 0 && $promocionesactivas !== []) { ?>
                        <?php foreach ($promocionesactivas as $promocion) { ?>

                            <!-- Modal -->
                            <div class="modal fade" id="modal_<?php echo $promocion->idpromocion ?>" data-bs-backdrop="static"
                                data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="staticBackdropLabel">Modificar promocion</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="" method="POST" enctype="multipart/form-data"
                                                name="formModificarPromo">
                                                <h6>Ingrese el nombre:</h6>
                                                <input type="text" name="nombreModificarPromoInput"
                                                    value="<?php echo $promocion->nombrepromocion ?>">

                                                <h6>Ingrese la descripcion:</h6>
                                                <textarea name="descripcionModificarPromoInput" id="" cols="30"
                                                    rows="5"><?php echo $promocion->descrpromocion ?></textarea>

                                                <h6>Ingrese la fecha de inicio:</h6>
                                                <input type="date" name="fechaInicioModificarPromoInput"
                                                    value="<?php echo date('Y-m-d', strtotime($promocion->fyhiniciopromo)); ?>">

                                                <h6>Ingrese la fecha de fin:</h6>
                                                <input type="date" name="fechaFinModificarPromoInput"
                                                    value="<?php echo date('Y-m-d', strtotime($promocion->fyhfinpromo)); ?>">

                                                <h6>Ingrese la imagen:</h6>
                                                <input type="file" name="imagenModificarPromoInput" accept=".jpg, .jpeg, .png">

                                                <input type="hidden" name="idPromoModificar"
                                                    value="<?php echo $promocion->idpromocion ?>">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-secondary" name="submitbajaPromo">Finalizar o
                                                dar
                                                de baja</button>
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-primary"
                                                name="submitModificarPromo">Modificar</button>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <section>
                                <div class="contenedor">
                                    <div class="promocion-activa">
                                        <img src="<?php echo $promocion->imagenpromocion ?>"
                                            alt="Promoción <?php echo $promocion->nombrepromocion; ?>" width="100">
                                        <h3>
                                            <?php echo $promocion->nombrepromocion; ?>
                                        </h3>

                                        <!-- Button trigger modal -->
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#modal_<?php echo $promocion->idpromocion ?>">Modificar
                                            Promocion</button>

                                    </div>
                                <?php } ?>
                            <?php } else { ?>
                                <p>No hay promociones activas</p>

                            <?php } ?>
                            <div class="pagination">
                                <?php if ($paginaActualActivas > 1) { ?>
                                    <a href="?paginaActualActivas=<?php echo $paginaActualActivas - 1; ?>">Anterior</a>
                                <?php } ?>

                                <?php for ($i = 1; $i <= $total_paginas_activas; $i++) { ?>
                                    <a href="?paginaActualActivas=<?php echo $i; ?>" <?php echo ($i === $paginaActualActivas) ? 'class="current"' : ''; ?>>
                                        <?php echo $i; ?>
                                    </a>
                                <?php } ?>

                                <?php if ($paginaActualActivas < $total_paginas_activas) { ?>
                                    <a href="?paginaActualActivas=<?php echo $paginaActualActivas + 1; ?>">Siguiente</a>
                                <?php } ?>
                            </div>
                        </div>
                    </section>


                    <section id="promociones-vencidas">
                        <br>
                        <h2>Historial Promociones</h2>

                        <br>
                        <form action="" name="formBuscadorPromosVencidas" method="post">
                            <br>
                            <input type="text" name="buscadorPromosVencidas"
                                placeholder="Buscar promocion por nombre o descripcion"
                                onkeypress="return event.keyCode != 13;">
                            <br>
                            <button type="submit" name="enviarBusquedaPromoVencidas"
                                onkeypress="return event.keyCode != 13;">Buscar</button>
                            <br>
                            <?php if (isset($habilitarrestaurarvencidas) && $habilitarrestaurarvencidas = true) { ?>

                                <button type="submit" name="restaurarVencidasBtn">Restaurar</button>

                            <?php } ?>

                        </form>

                        <?php if (isset($_SESSION['mensajeVencidas'])) { ?>
                            <section class="mensajeExitoError">
                                <?php echo $_SESSION['mensajeVencidas']; ?>
                            </section>
                        <?php }
                        unset($_SESSION['mensajeVencidas']); ?>



                        <?php if (count($promocionesvencidas) > 0 && $promocionesvencidas !== []) { ?>
                            <?php foreach ($promocionesvencidas as $promocionv) { ?>

                                <!-- Modal -->
                                <div class="modal fade" id="modal_<?php echo $promocionv->idpromocion ?>"
                                    data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                                    aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="staticBackdropLabel">Datos de la promocion</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">

                                                <h6>Nombre:</h6>
                                                <p>
                                                    <?php echo $promocionv->nombrepromocion; ?>
                                                </p>

                                                <h6>Descripcion:</h6>
                                                <p>
                                                    <?php echo $promocionv->descrpromocion; ?>
                                                </p>

                                                <h6>Fecha de inicio:</h6>
                                                <p>
                                                    <?php echo date('d/m/Y', strtotime($promocionv->fyhiniciopromo)); ?>
                                                </p>

                                                <h6>Fecha de fin:</h6>
                                                <p>
                                                    <?php echo date('d/m/Y', strtotime($promocionv->fyhfinpromo));
                                                    ; ?>
                                                </p>

                                            </div>
                                            <div class="modal-footer">

                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Ok</button>

                                            </div>

                                        </div>
                                    </div>
                                </div>


                                <div class="promocion-vencida">
                                    <img src="<?php echo $promocionv->imagenpromocion ?>" alt="Promoción 1" width="100">
                                    <h3>
                                        <?php echo $promocionv->nombrepromocion; ?>
                                    </h3>
                                    <p>
                                        <?php echo $promocionv->descrpromocion; ?>
                                    </p>

                                    <!--POnemos un boton que abra el modal con los datos de la promocion-->
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#modal_<?php echo $promocionv->idpromocion ?>">Ver Promocion</button>
                                </div>
                                <br>
                            <?php } ?>
                        <?php } else { ?>
                            <p>No hay promociones para mostrar en el historial</p>

                        <?php } ?>

                        <div class="pagination">
                            <?php if ($paginaActualVencidas > 1) { ?>
                                <a href="?paginaActualVencidas=<?php echo $paginaActualVencidas - 1; ?>">Anterior</a>
                            <?php } ?>

                            <?php for ($i = 1; $i <= $total_paginas_vencidas; $i++) { ?>
                                <a href="?paginaActualVencidas=<?php echo $i; ?>" <?php echo ($i === $paginaActualVencidas) ? 'class="current"' : ''; ?>>
                                    <?php echo $i; ?>
                                </a>
                            <?php } ?>

                            <?php if ($paginaActualVencidas < $total_paginas_vencidas) { ?>
                                <a href="?paginaActualVencidas=<?php echo $paginaActualVencidas + 1; ?>">Siguiente</a>
                            <?php } ?>
                        </div>

                    </section>

                </section>
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
                <a href="https://www.facebook.com/people/SynthSoft/61552117910575/?is_tour_dismissed=true"
                    target="_blank">
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
    <script src="js/restauranteprincipaljs.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
        integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF"
        crossorigin="anonymous"></script>

    <script>
        function toggle() {
            var blur = document.getElementById("blur");
            blur.classList.toggle("active");
            var popup = document.getElementById("popup");
            popup.classList.toggle("active");
        }
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>

</html>