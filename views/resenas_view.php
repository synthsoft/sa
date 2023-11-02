<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="css/post-login.css">
    <!-- Asegúrate de proporcionar la ruta correcta a tu archivo CSS -->
    <title>Reseñas de Restaurantes</title>
</head>

<body>
    <header>

        <h1>
            <img class="imglogo" src="img/logo_producto.svg" alt="Logo del producto" />

        </h1>

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

        <section>
            <section class="search-filter">
                <div class="search-bar">
                    <form action="" method="post" id="formBuscarResena">
                        <input name="buscarResena" type="text" id="restaurant-name"
                            placeholder="Buscar por correo o alias">
                        <button type="submit" id="search-button" name="botonBuscarResena">Buscar</button>

                        <?php if (isset($_GET['busqueda']) && $_GET['busqueda'] != "" || isset($_SESSION['busqueda'])) { ?>
                        <button type="submit" name="restaurarBusqueda">Restaurar</button>
                    <?php } ?>

                    </form>
                </div>

                <form action="" method="post" name="formOrdenFiltro">
                    <div>
                        <label for="ordenar">Orden</label>
                        
                        <select id="ordenar" name="ordenSelect">
                            <option value="">Elegir</option>
                           <!--Obtenemos la opcion que se selecciono y la mostramos como seleccionada --> 
                            
                            <option value="altasbajas" <?php if (isset($_SESSION['orden']) && $_SESSION['orden'] == "altasbajas") { echo 'selected';} ?>>Mayor puntaje</option>
                            <option value="bajasaltas" <?php if (isset($_SESSION['orden']) && $_SESSION['orden'] == "bajasaltas") { echo 'selected';} ?>>Menor puntaje</option>
                            <option value="recientes" <?php if (isset($_SESSION['orden']) && $_SESSION['orden'] == "recientes") { echo 'selected';} ?>>Mas recientes</option>
                            <option value="antiguas" <?php if (isset($_SESSION['orden']) && $_SESSION['orden'] == "antiguas") { echo 'selected';} ?>>Mas antiguas</option> 

                        </select>
                    </div>
                    <button type="submit" name="enviarFiltroOrden">Ordenar</button>

                    <?php if (isset($_GET['orden']) && $_GET['orden'] != "" || isset($_SESSION['orden'])) { ?>
                        <button type="submit" name="restaurar">Restaurar</button>
                    <?php } ?>


                </form>
            </section>
        </section>

        <section class="reviews-list">

           <!-- SI HAY RESEÑAS Y NO HUBO UN ERROR AL OBTENERLAS LAS MOSTRAMOS -->
            <?php if (count($resenas) > 0 && $resenas !== []) { ?>
                <h1>Reseñas para tu restaurante:</h1>
                <ul>
                    <!--Recorremos el arreglo de resenas y mostramos los datos-->
                    <?php foreach ($resenas as $resena) { ?>
                        <li>
                            <h3>
                                (
                                <?php echo $resena->emailtur ?>)
                                <?php echo $resena->alias ?>
                            </h3>
                            <p>
                                <?php echo date('d/m/Y H:i', strtotime($resena->fyhpuntaje)) ?>
                            </p>
                            <p class="rating">Restoran En General:
                                <?php echo $resena->restorangral ?>
                            </p>
                            <p class="rating">Instalaciones:
                                <?php echo $resena->instalaciones ?>
                            </p>
                            <p class="rating">Menu Gastronomico:
                                <?php echo $resena->menugastronomico ?>
                            </p>
                            <p class="rating">Atencion del personal:
                                <?php echo $resena->atencion ?>
                            </p>
                        </li>
                        <br>
                    <?php } ?>
                </ul>
            <?php } else {

                //SI NO HAY RESEÑAS U OBTUVO UN ERROR AL OBTENERLAS MOSTRAMOS UN MENSAJE
                echo "<h2> No hay reseñas disponibles </h2> ";

            } ?>

            <?php if (isset($_SESSION['orden']) && $_SESSION['orden'] != "") { ?>
                <div class="pagination">
                    <?php if ($paginaActual > 1) { ?>
                        <a
                            href="?action=resenasRest&emailrest=<?php echo $emailrest; ?>&pagina=<?php echo $paginaActual - 1; ?>&orden=<?php echo $_SESSION['orden']; ?>">Anterior</a>
                    <?php } ?>

                    <?php for ($i = 1; $i <= $total_paginas; $i++) { ?>
                        <a href="?action=resenasRest&emailrest=<?php echo $emailrest; ?>&orden=<?php echo $_SESSION['orden']; ?>&pagina=<?php echo $i; ?>"
                            <?php echo ($i === $paginaActual) ? 'class="current"' : ''; ?>>
                            <?php echo $i; ?>
                        </a>
                    <?php } ?>

                    <?php if ($paginaActual < $total_paginas) { ?>
                        <a
                            href="?action=resenasRest&emailrest=<?php echo $emailrest; ?>&pagina=<?php echo $paginaActual + 1; ?>&orden=<?php echo $_SESSION['orden']; ?>">Siguiente</a>
                    <?php } ?>
                </div>
            <?php } else {

                if (isset($_SESSION['busqueda']) && $_SESSION['busqueda'] != "") { ?>
                    <div class="pagination">
                        <?php if ($paginaActual > 1) { ?>
                            <a
                                href="?action=resenasRest&emailrest=<?php echo $emailrest; ?>&pagina=<?php echo $paginaActual - 1; ?>&busqueda=<?php echo $_SESSION['busqueda'] ?>">Anterior</a>
                        <?php } ?>

                        <?php for ($i = 1; $i <= $total_paginas; $i++) { ?>
                            <a href="?action=resenasRest&emailrest=<?php echo $emailrest; ?>&busqueda=<?php echo $_SESSION['busqueda'] ?>&pagina=<?php echo $i; ?>"
                                <?php echo ($i === $paginaActual) ? 'class="current"' : ''; ?>>
                                <?php echo $i; ?>
                            </a>
                        <?php } ?>

                        <?php if ($paginaActual < $total_paginas) { ?>
                            <a
                                href="?action=resenasRest&emailrest=<?php echo $emailrest; ?>&pagina=<?php echo $paginaActual + 1; ?>&busqueda=<?php echo $_SESSION['busqueda'] ?>">Siguiente</a>

                        <?php } ?>
                    </div>
                <?php } else { ?>

                    <div class="pagination">
                        <?php if ($paginaActual > 1) { ?>
                            <a
                                href="?action=resenasRest&emailrest=<?php echo $emailrest; ?>&pagina=<?php echo $paginaActual - 1; ?>">Anterior</a>
                        <?php } ?>

                        <?php for ($i = 1; $i <= $total_paginas; $i++) { ?>
                            <a href="?action=resenasRest&emailrest=<?php echo $emailrest; ?>&pagina=<?php echo $i; ?>"
                                <?php echo ($i === $paginaActual) ? 'class="current"' : ''; ?>>
                                <?php echo $i; ?>
                            </a>
                        <?php } ?>

                        <?php if ($paginaActual < $total_paginas) { ?>
                            <a
                                href="?action=resenasRest&emailrest=<?php echo $emailrest; ?>&pagina=<?php echo $paginaActual + 1; ?> ?>">Siguiente</a>

                        <?php } ?>
                    </div>


                <?php }
            } ?>

        </section>

        <section>

            <h1>Datos sobre las reseñas:</h1>

            <div>

                <h3> Promedio sobre todas las reseñas:
                    <?php echo $promediototal ?>
                </h3>


            </div>

            <div>
                <h3>Promedio sobre el restaurante en general:
                    <?php echo $promediorestaurantegral ?>
                </h3>

            </div>

            <div>

                <h3> Promedio sobre las instalaciones:
                    <?php echo $promedioinstalaciones ?>
                </h3>

            </div>

            <div>
                <h3>Promedio sobre el menu Gastronomico:
                    <?php echo $promediomenugastronomico ?>
                </h3>


            </div>

            <div>

                <h3> Promedio sobre la atencion del personal
                    <?php echo $promedioatencion ?>
                </h3>

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

    <script src="js/restaurante_y_resenas.js"></script>
    <script src="js/menuheader.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
        integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF"
        crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>

</html>