function mostrarSeccion(mensaje) {
  var seccion = document.getElementById("miSeccion");
  if (seccion) {
    // Cambia el estilo para mostrar la sección
    seccion.style.display = "block";

    // Actualiza el contenido del span con el mensaje
    var mensajeErrorFechas = document.getElementById("mensajeErrorFechas");
    if (mensajeErrorFechas) {
      mensajeErrorFechas.textContent = mensaje;
    }
  }
}

let autocomplete1;
let autocomplete2;
// Inicializamos la API de Google Maps
function initMap() {
  initAutocomplete();
}
// Inicializamos el autocompletado
function initAutocomplete() {
  const inputdireccion = document.getElementById("places_input1");
  autocomplete1 = new google.maps.places.Autocomplete(inputdireccion);

  const inputbarrio = document.getElementById("places_input2");
  autocomplete2 = new google.maps.places.Autocomplete(inputbarrio);
}
// Obtenemos los datos del lugar seleccionado
function obtenerDatos() {
  var nombreHotel = autocomplete1.getPlace().name;
  //Obtenemos la direccion, latitud y longitud de la direccion
  var direccion = autocomplete1.getPlace().formatted_address;
  //Obtenemos el barrio y la ciudad
  var barrio = autocomplete2.getPlace().address_components[0].long_name;
  var ciudad = autocomplete2.getPlace().address_components[1].long_name;

  var datos = [direccion, barrio, ciudad, nombreHotel];
  return datos;
}

function obtenerDatosYEnviar() {
  var datos = obtenerDatos(); // Llamamos a la función obtenerDatos para obtener los datos del lugar
  if (datos) {
    // Si se obtuvieron los datos correctamente
    // Asignamos los datos a campos ocultos en el formulario
    document.getElementById("barrio").value = datos[1];
    document.getElementById("direccion").value = datos[0];
    document.getElementById("ciudad").value = datos[2];
    document.getElementById("nombreHotel").value = datos[3];

    return true;
  }
}

// Validar fechas
function validarFechas() {
  var fechaDesde = document.getElementById("fechaInicio").value;
  var fechaHasta = document.getElementById("fechaFin").value;

  console.log("Fecha de inicio:", fechaDesde);
  console.log("Fecha de finalización:", fechaHasta);

  //Validamos que la fecha de inicio sea menor a la fecha de fin
  if (fechaDesde > fechaHasta) {
    mostrarSeccion("La fecha de inicio debe ser menor a la de finalización");
    return false;
  } else {
    //Validamos que la fecha de finalizacion sea mayor a la fecha actual
    var fechaActual = new Date();

    var año = fechaActual.getFullYear(); // Obtiene el año (YYYY)
    var mes = (fechaActual.getMonth() + 1).toString().padStart(2, "0"); // Obtiene el mes (MM)
    var dia = fechaActual.getDate().toString().padStart(2, "0"); // Obtiene el día (DD)

    var fechaFormateada = año + "-" + mes + "-" + dia;

    if (fechaHasta < fechaFormateada) {
      mostrarSeccion("La fecha de finalización debe ser mayor a la actual");
      return false;
    } else {
      return true;
    }
  }
}

function validarInputsBarrioYDireccion() {
  var exito = obtenerDatosYEnviar();

  if (exito) {
    var direccion = document.querySelector('input[name="direccion"]').value;
    var barrio = document.querySelector('input[name="barrio"]').value;
    var ciudad = document.querySelector('input[name="ciudad"]').value;

    //Verificamos que los campos no estén vacíos
    if (direccion == "" || barrio == "" || ciudad == "") {
      alert(
        "Hubo un error al obtener las direcciones y hotel, intentelo denuevo"
      );
      return false;
    } else {
      return true;
    }
  }
}

// Validar el formulario
function validarFormulario() {
  return (
    obtenerDatosYEnviar() && validarFechas() && validarInputsBarrioYDireccion()
  );
}

// Evento de envío del formulario
const formulario = document.querySelector("envioHotelTurista");
if (formulario) {
  formulario.addEventListener("submit", function (event) {
    if (!validarFormulario()) {
      event.preventDefault(); // Evitar el envío del formulario si no pasa la validación
    }
  });
}
