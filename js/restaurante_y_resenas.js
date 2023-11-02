//Script para el boton de resenas en la pagina de restaurante para turistas
document.addEventListener("DOMContentLoaded", function () {
  var botonResenas = document.getElementById("botonResenas");
  var validacion = botonResenas.getAttribute("data-validacion");

  if (validacion === "si") {
    botonResenas.textContent = "Haz una Reseña";
    botonResenas.addEventListener("click", function () {
      // Redirige a la página correspondiente cuando se hace clic, enviando los parámetros necesarios para
      //procesarlos en php, si el formulario se envia y se valida que
      //al usuario se le acepto la solicitud de asistencia, se redirige a la pagina de resenas
      var modal = new bootstrap.Modal(
        document.getElementById("staticBackdrop")
      );
      modal.show();
    });

  } else if (validacion === "no") {
    botonResenas.textContent = "Solicitud Enviada";
    botonResenas.addEventListener("click", function () {
      // Puedes realizar acciones adicionales cuando se hace clic en "Solicitud Enviada"
      // Por ejemplo, mostrar un mensaje de confirmación o realizar una solicitud AJAX al servidor

    });
  } else {
    botonResenas.textContent = "Solicitar Asistencia";
    botonResenas.addEventListener("click", function () {
      // Aquí puedes agregar código para enviar una solicitud de asistencia
      // Por ejemplo, realizar una solicitud AJAX al servidor para registrar la solicitud
      // Una vez que se haya enviado la solicitud, puedes cambiar el texto a "Solicitud Enviada"
      botonResenas.textContent = "Solicitud Enviada";
      botonResenas.removeEventListener("click", this);
    });
  }
});


//Script para el carrusel de las promociones en la pagina de restaurante para turistas
//y puede ser que alguna mas
document.addEventListener("DOMContentLoaded", function () {
    var multipleCardCarousel = document.querySelector("#carouselExampleControls");
    if (window.matchMedia("(min-width: 768px)").matches) {
      var carousel = new bootstrap.Carousel(multipleCardCarousel, {
        interval: false,
      });
      var carouselWidth = $(".carousel-inner")[0].scrollWidth;
      var cardWidth = $(".carousel-item").width();
      var scrollPosition = 0;
      $("#carouselExampleControls .carousel-control-next").on("click", function () {
        if (scrollPosition < carouselWidth - cardWidth * 4) {
          scrollPosition += cardWidth;
          $("#carouselExampleControls .carousel-inner").animate(
            { scrollLeft: scrollPosition },
            600
          );
        }
      });
      $("#carouselExampleControls .carousel-control-prev").on("click", function () {
        if (scrollPosition > 0) {
          scrollPosition -= cardWidth;
          $("#carouselExampleControls .carousel-inner").animate(
            { scrollLeft: scrollPosition },
            600
          );
        }
      });
    } else {
      $(multipleCardCarousel).addClass("slide");
    }
  });
  