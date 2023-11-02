<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../css/post-login.css" />
    <link rel="shortcut icon" href="../img/icon.svg" />
    <script
      src="https://kit.fontawesome.com/d913c5e0eb.js"
      crossorigin="anonymous"
    ></script>
    <title>Interfaz de Turista</title>
  </head>
  <body>
    <header>
      <h1>
        <!-- Logo del producto -->
        <img
          class="imglogo"
          src="../img/logo_producto.svg"
          alt="Logo del producto"
        />
      </h1>
      <!-- Links del cabezal -->
      <nav class="navlinks">
        <ul>
          <li>
            <a class="txtlinks" href="#">Nosotros</a>
            <ul class="dropdown">
              <li class="dropdown-li">
                <a class="txtdrop" href="#">Vision</a>
              </li>
              <li class="dropdown-li">
                <a class="txtdrop" href="#">Objetivo</a>
              </li>
              <li class="dropdown-li">
                <a class="txtdrop" href="#">Alcance</a>
              </li>
            </ul>
          </li>
          <li><a class="txtlinks" href="">Contacto</a></li>
        </ul>
      </nav>
      <div class="divcuenta">
        <!-- Apartado Cuenta -->
        <ul>
          <li>
            <a class="txtlinks" href="views/turista_perfil.php">Volver</a>
            <ul class="dropdown">
              <li class="dropdown-li">
                <img
                  class="imginiciosesion"
                  src="../img/login.svg"
                  alt="Ingreso"
                />
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </header>
    <section class="historial"></section>
        <h2>Estadías en Hoteles</h2>
        <ul>
            <li>
                <strong>Hotel 1:</strong> Fecha de estadía: 01/05/2023 - 05/05/2023
            </li>
            <li>
                <strong>Hotel 2:</strong> Fecha de estadía: 15/06/2023 - 20/06/2023
            </li>
            <!-- Agregar más estadías según sea necesario -->
        </ul>

        <h2>Reseñas Creadas</h2>
        <ul>
            <li>
                <strong>Reseña 1:</strong> Hotel 1 - "Excelente servicio y comodidades."
            </li>
            <li>
                <strong>Reseña 2:</strong> Hotel 2 - "Buena ubicación, pero habitaciones pequeñas."
            </li>
            <!-- Agregar más reseñas según sea necesario -->
        </ul>
    </section>
    <footer>
        <div class="waves">
          <div class="wave" id="wave1"></div>
          <div class="wave" id="wave2"></div>
          <div class="wave" id="wave3"></div>
          <div class="wave" id="wave4"></div>
        </div>
        <ul class="social-icons">
          <li>
            <a href="#"><i class="fa-brands fa-github"></i></a>
          </li>
          <li>
            <a href="#"><i class="fa-brands fa-instagram"></i></a>
          </li>
        </ul>
        <p>&copy; Todos los derechos reservados | Synth Soft</p>
      </footer>
    </body>
  </html>