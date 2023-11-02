document.addEventListener('DOMContentLoaded', function () {
  var switchElement = document.getElementById('flexSwitchCheckDefault');
  var labelElement = document.querySelector('label[for="flexSwitchCheckDefault"]');

  switchElement.addEventListener('change', function () {
      if (switchElement.checked) {
          // El interruptor se activó
          labelElement.textContent = 'Visible';

          // Puedes realizar acciones adicionales aquí
      } else {
          // El interruptor se desactivó
          labelElement.textContent = 'Invisible';

          // Puedes realizar acciones adicionales aquí
      }
  });
});

  // Obtén los textarea y los elementos de los contadores
  var textareaDescripcion = document.getElementById("descripcion");
  var contadorDescripcion = document.getElementById("contador-caracteres-desc");

  var textareaTipoComida = document.getElementById("tipoComida");
  var contadorTipoComida = document.getElementById("contador-caracteres-tc");

  // Establece el límite máximo de caracteres para cada textarea
  var limiteCaracteres = 250; // Cambia esto al límite que desees

  // Agrega un event listener para detectar cambios en el textarea de Descripción
  textareaDescripcion.addEventListener("input", function() {

    var longitudTexto = textareaDescripcion.value.length;
    var caracteresRestantes = limiteCaracteres - longitudTexto;

    // Actualiza el contador de Descripción
    contadorDescripcion.textContent = "Caracteres restantes: " + caracteresRestantes;

    // Puedes agregar estilos o cambiar el color del contador según la longitud del texto
    if (caracteresRestantes < 0) {
      contadorDescripcion.style.color = "red"; // Cambia el color a rojo si se excede el límite
    } else {
      contadorDescripcion.style.color = "black"; // Restaura el color predeterminado si está dentro del límite
    }
  });

  // Agrega un event listener para detectar cambios en el textarea de Tipo Comida
  textareaTipoComida.addEventListener("input", function() {
    var longitudTexto = textareaTipoComida.value.length;
    var caracteresRestantes = limiteCaracteres - longitudTexto;

    // Actualiza el contador de Tipo Comida
    contadorTipoComida.textContent = "Caracteres restantes: " + caracteresRestantes;

    // Puedes agregar estilos o cambiar el color del contador según la longitud del texto
    if (caracteresRestantes < 0) {
      contadorTipoComida.style.color = "red"; // Cambia el color a rojo si se excede el límite
    } else {
      contadorTipoComida.style.color = "black"; // Restaura el color predeterminado si está dentro del límite
    }
  });

  // Detectar el desplazamiento de la página
window.onscroll = function () {
  scrollFunction();
};

function scrollFunction() {
  if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
      // Mostrar el botón cuando se desplaza hacia abajo
      document.getElementById("scroll-to-top").style.display = "block";
  } else {
      // Ocultar el botón cuando está en la parte superior
      document.getElementById("scroll-to-top").style.display = "none";
  }
}

// Desplazarse suavemente hacia arriba cuando se hace clic en el botón
document.getElementById("scroll-to-top").onclick = function () {
  scrollToTop();
};

function scrollToTop() {
  document.body.scrollTop = 0; // Para navegadores Safari
  document.documentElement.scrollTop = 0; // Para otros navegadores
}

 /* Set the width of the sidebar to 250px and the left margin of the page content to 250px */
 function openNav() {
  document.getElementById("mySidebar").style.width = "250px";
  document.getElementById("main").style.marginLeft = "250px";
}

/* Set the width of the sidebar to 0 and the left margin of the page content to 0 */
function closeNav() {
  document.getElementById("mySidebar").style.width = "0";
  document.getElementById("main").style.marginLeft = "0";
}

