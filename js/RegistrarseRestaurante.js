const SeleccionUsuario = document.querySelector(".SeleccionUsuario");
const RegistroTurista = document.querySelector(".RegistroTurista");
const RegistroRestaurante = document.querySelector(".RegistroRestaurante");
const contenedor1 = document.querySelector(".contenedor1");
const contenedor2 = document.querySelector(".contenedor2");
const contenedor3 = document.querySelector(".contenedor3");
const contenedor4 = document.querySelector(".contenedor4");
const contenedor5 = document.querySelector(".contenedor5");
const contenedor6 = document.querySelector(".contenedor6");
const MensajeRegistro = document.querySelector(".MensajeRegistro");
const mensajeError = document.getElementById("mensajeError");

//Creamos la funcion mostrar mensajeRegustro
function mostrarMensajeRegistro() {
  MensajeRegistro.style.display = "block";
  SeleccionUsuario.style.display = "none";
  RegistroTurista.style.display = "none";
  RegistroRestaurante.style.display = "none";
  contenedor1.style.display = "none";
}

function RegisterTurista() {
  RegistroTurista.style.display = "block";
  SeleccionUsuario.style.display = "none";
  MensajeRegistro.style.display = "none";
}

function RegisterRestaurante() {
  RegistroRestaurante.style.display = "block";
  MensajeRegistro.style.display = "none";
  contenedor1.style.display = "block";
  SeleccionUsuario.style.display = "none";
}

function RegresarSeleccion() {
  SeleccionUsuario.style.display = "block";
  RegistroTurista.style.display = "none";
  RegistroRestaurante.style.display = "none";
}

function siguiente0() {
  contenedor1.style.display = "none";
  contenedor2.style.display = "block";
  return false; // Evita la redirección automática
}

function atras1() {
  contenedor1.style.display = "block";
  contenedor2.style.display = "none";
}

function siguiente1() {
  contenedor2.style.display = "none";
  contenedor3.style.display = "block";
}

function atras2() {
  contenedor2.style.display = "block";
  contenedor3.style.display = "none";
}

function siguiente2() {
  contenedor3.style.display = "none";
  contenedor4.style.display = "block";
}

function atras3() {
  contenedor3.style.display = "block";
  contenedor4.style.display = "none";
}

function siguiente3() {
  contenedor4.style.display = "none";
  contenedor5.style.display = "block";
}

function atras4() {
  contenedor5.style.display = "none";
  contenedor4.style.display = "block";
}

function siguiente4() {
  contenedor5.style.display = "none";
  contenedor6.style.display = "block";
}

function atras5() {
  contenedor5.style.display = "block";
  contenedor6.style.display = "none";
}

//Validacion que si el campo foto no está vacío, entonces debe ser una imagen
function validarFotoTur() {
  var foto = document.querySelector('input[name="fototurista"]').value;

  if (foto != "") {
    var extension = foto.substring(foto.lastIndexOf(".") + 1).toLowerCase();

    if (
      extension != "jpg" &&
      extension != "png" &&
      extension != "jpeg" &&
      extension != "gif"
    ) {
      //Ponemos un mensaje de error en el span errorFotoTur
      errorFotoTur.textContent = "El archivo seleccionado no es una imagen";

      RegisterTurista();
      return false;
    } else {
      //Validamos que el tamaño de la imagen no sea mayor a 2MB
      var tamano = document.querySelector('input[name="fototurista"]').files[0]
        .size;

      if (tamano > 2000000) {
        //Ponemos un mensaje de error en el span errorFotoTur
        errorFotoTur.textContent = "El archivo seleccionado es muy grande";

        RegisterTurista();
        return false;
      } else {
        return true;
      }
    }
  } else {
    return true;
  }
}

//Validacion que si el campo foto del restaurante no está vacío, entonces debe ser una imagen
function validarFoto() {
  var foto = document.querySelector('input[name="fotorestaurante"]').value;

  if (foto != "") {
    var extension = foto.substring(foto.lastIndexOf(".") + 1).toLowerCase();

    if (
      extension != "jpg" &&
      extension != "png" &&
      extension != "jpeg" &&
      extension != "gif"
    ) {
      //Ponemos un mensaje de error en el div mensajeErrorFoto
      mensajeErrorFoto.textContent = "El archivo seleccionado no es una imagen";

      //Le ponemos el borde rojo y de 1px al campo de foto
      document.querySelector('input[name="fotorestaurante"]').style.border =
        "1px solid red";

      RegisterRestaurante();
      return false;
    } else {
      //Validamos que el tamaño de la imagen no sea mayor a 2MB
      var tamano = document.querySelector('input[name="fotorestaurante"]')
        .files[0].size;

      if (tamano > 2000000) {
        //Ponemos un mensaje de error en el div mensajeErrorFoto
        mensajeErrorFoto.textContent = "El archivo seleccionado es muy grande";

        //Devolvemos false para que no se envíe el formulario
        RegisterRestaurante();
        return false;
      } else {
        return true;
      }
    }
  } else {
    return true;
  }
}

//Creamos una funcion ajax para verificar que el correo no esté en uso
function verificarCorreoElectronico(email) {
  console.log("Verificando correo electrónico:", email);

  return new Promise(function (resolve, reject) {
    // Bandera para verificar si la solicitud AJAX se envió
    let solicitudEnviada = false;

    console.log("Haciendo solicitud AJAX");

    $.ajax({
      url: "/PHPProducto - copia/ajaxconsultas.php",
      method: "POST",
      data: { correo: email },
      timeout: 5000, // Establece un tiempo de espera de 5 segundos (ajusta según tus necesidades)
      beforeSend: function () {
        solicitudEnviada = true;
      },
      success: function (response) {
        console.log("Respuesta del servidor:", response);

        if (response === "no disponible") {
          reject(); // El correo no está disponible
        } else {
          resolve(); // El correo está disponible
        }
      },
      error: function (xhr, status, error) {
        console.log("Error en la solicitud AJAX:", error);
        if (!solicitudEnviada) {
          console.log("La solicitud AJAX no se envió.");
        }
        reject(error); // Rechaza la promesa con el error
      },
    });
  });
}

//Creamos una funcion ajax para verificar que el alias no esté en uso
function verificarAlias(alias) {
  console.log("Verificando alias:", alias);

  return new Promise(function (resolve, reject) {
    // Bandera para verificar si la solicitud AJAX se envió
    let solicitudEnviada = false;

    console.log("Haciendo solicitud AJAX");

    $.ajax({
      url: "/PHPProducto - copia/ajaxconsultas.php",
      method: "POST",
      data: { alias: alias },
      timeout: 5000, // Establece un tiempo de espera de 5 segundos (ajusta según tus necesidades)
      beforeSend: function () {
        solicitudEnviada = true;
      },
      success: function (response) {
        console.log("Respuesta del servidor:", response);

        if (response === "no disponible") {
          reject(); // El alias no está disponible
        } else {
          resolve(); // El alias está disponible
        }
      },
      error: function (xhr, status, error) {
        console.log("Error en la solicitud AJAX:", error);
        if (!solicitudEnviada) {
          console.log("La solicitud AJAX no se envió.");
        }
        reject(error); // Rechaza la promesa con el error
      },
    });
  });
}

//Creamos la funcion validar correo existente
async function validarCorreoExistente(email) {
  try {
    await verificarCorreoElectronico(email);

    console.log("El correo no existe en la base de datos, es válido");
    // El correo no existe en la base de datos, es válido
    return true;
  } catch (error) {
    console.log("El correo ya existe en la base de datos, no es válido");
    // El correo ya existe en la base de datos, no es válido
    return false;
  }
}

//Creamos la funcion validar alias existente
async function validarAliasExistente(alias) {
  console.log("Validando alias existente");

  try {
    await verificarAlias(alias);

    console.log("El alias no existe en la base de datos, es válido");
    // El alias no existe en la base de datos, es válido
    return true;
  } catch (error) {
    console.log("El alias ya existe en la base de datos, no es válido");
    // El alias ya existe en la base de datos, no es válido
    return false;
  }
}

//Detenemos la ejecucion del formulario de registro de turista para validar los campos
//y si todo está bien, entonces se envía el formulario, es una función asíncrona
//lo que significa que puede esperar a que se resuelva una promesa antes de continuar
document
  .getElementById("formTur")
  .addEventListener("submit", async function (event) {
    event.preventDefault(); // Detiene la acción predeterminada del formulario

    const isValid = await validarTurista();

    if (isValid) {
      this.submit(); // Envía el formulario si es válido
    }
  });

//Creamos la funcion validar turista
async function validarTurista() {
  return new Promise(async (resolve) => {
    console.log("Validando formulario de turista");

    //Validamos que las contraseñas sean iguales y tengan al menos 8 caracteres
    var contrasena1 = document.querySelector(
      'input[name="contrasenaturista1"]'
    ).value;
    var contrasena2 = document.querySelector(
      'input[name="contrasenaturista2"]'
    ).value;

    if (contrasena1 != contrasena2) {
      // Ponemos un mensaje de error en el span errorContrasena2Tur
      document.querySelector("#errorContrasena2Tur").textContent =
        "Las contraseñas no coinciden";

      // Le ponemos el borde rojo y de 1px a los campos de contraseña
      document.querySelector('input[name="contrasenaturista1"]').style.border =
        "1px solid red";
      document.querySelector('input[name="contrasenaturista2"]').style.border =
        "1px solid red";

      resolve(false);
      return;
    } else if (contrasena1.length < 8) {
      // Ponemos un mensaje de error en el span errorContrasena2Tur
      document.querySelector("#errorContrasena2Tur").textContent =
        "Las contraseñas deben tener al menos 8 caracteres";

      // Le ponemos el borde rojo y de 1px a los campos de contraseña
      document.querySelector('input[name="contrasenaturista1"]').style.border =
        "1px solid red";
      document.querySelector('input[name="contrasenaturista2"]').style.border =
        "1px solid red";

      resolve(false);
      return;
    }

    // Validamos que el correo no esté en uso en la base de datos
    var correoturista = document.querySelector(
      'input[name="emailturista"]'
    ).value;
    var exito = await validarCorreoExistente(correoturista);

    // Mostramos éxito en consola
    console.log("Exito:", exito);

    if (exito == false) {
      // Ponemos un mensaje de error en el div mensajeErrorCorreo
      errorCorreoTur.textContent = "El correo ya existe";

      // Le ponemos el borde rojo y de 1px al campo de correo
      document.querySelector('input[name="emailturista"]').style.border =
        "1px solid red";

      resolve(false);
      return;
    } else {
      //Validamos que el alias no tenga mas de dos espacio en blanco seguidos
      var alias = document.querySelector('input[name="aliasturista"]').value;

      if (/\s{2,}/.test(alias)) {
        // Ponemos un mensaje de error en el span errorAliasTur
        errorAliasTur.textContent =
          "El alias no puede tener mas de dos espacios en blanco seguidos";

        // Le ponemos el borde rojo y de 1px al campo de alias
        document.querySelector('input[name="aliasturista"]').style.border =
          "1px solid red";

        resolve(false);
        return;
      }

      //Verificamos que el alias no tenga caracteres especiales exepto por guiones bajos o numeros
      if (!/^[a-zA-Z0-9\_]+$/.test(alias)) {
        // Ponemos un mensaje de error en el span errorAliasTur
        errorAliasTur.textContent =
          "El alias solo puede tener letras, numeros y guiones bajos";

        // Le ponemos el borde rojo y de 1px al campo de alias
        document.querySelector('input[name="aliasturista"]').style.border =
          "1px solid red";

        resolve(false);
        return;
      }

      // Validamos que el alias no esté en uso en la base de datos,
      // pero si tiene espacios los reemplazamos por guiones bajos y lo ponemos en minúscula
      var aliasturista = document
        .querySelector('input[name="aliasturista"]')
        .value.replace(/\s/g, "_")
        .toLowerCase();

      var exito2 = await validarAliasExistente(aliasturista);

      if (exito2 == false) {
        console.log("El alias ya existe en la base de datos, OSEA EL IF FUNCA");
        // Ponemos un mensaje de error en el span mensajeErrorAlias
        errorAliasTur.textContent = "El alias ya existe";

        // Le ponemos el borde rojo y de 1px al campo de alias
        document.querySelector('input[name="aliasturista"]').style.border =
          "1px solid red";

        resolve(false);
        return;
      }
    }

    // Validamos que la foto sea una imagen
    var exito3 = validarFotoTur();

    if (exito3 == true) {
      resolve(true);
    } else {
      resolve(false);
    }
  });
}

//Validaciones de los campos del formulario de registro de restaurante parte 1
async function validarYAvanzar1() {
  var correorestaurante = document.querySelector(
    'input[name="correorestaurante"]'
  ).value;

  //guardamos el alias y le remplaceamos los espacios en blanco por guiones bajos, ademas lo ponemos en minuscula
  var aliasrestaurante = document
    .querySelector('input[name="aliasrestaurante"]')
    .value.replace(/\s/g, "_")
    .toLowerCase();

  var nombrerestaurante = document.querySelector(
    'input[name="nombrerestaurante"]'
  ).value;
  var contrasena1 = document.querySelector(
    'input[name="contrasena1restaurante"]'
  ).value;
  var contrasena2 = document.querySelector(
    'input[name="contrasena2restaurante"]'
  ).value;

  //Valida que los campos no estén vacíos
  if (correorestaurante == "") {
    //Ponemos un mensaje de error en el div mensajeErrorCorreo
    mensajeErrorCorreo.textContent = "El correo no puede estar vacío";

    //Le ponemos el borde rojo y de 1px al campo de correo
    document.querySelector('input[name="correorestaurante"]').style.border =
      "1px solid red";

    RegisterRestaurante();
    return false;
  }

  if (aliasrestaurante == "") {
    //Ponemos un mensaje de error en el div mensajeErrorAlias
    mensajeErrorAlias.textContent = "El alias no puede estar vacío";

    //Le ponemos el borde rojo y de 1px al campo de alias
    document.querySelector('input[name="aliasrestaurante"]').style.border =
      "1px solid red";

    RegisterRestaurante();
    return false;
  }

  if (nombrerestaurante == "") {
    //Ponemos un mensaje de error en el div mensajeErrorNombre
    mensajeErrorNombre.textContent = "El nombre no puede estar vacío";

    //Le ponemos el borde rojo y de 1px al campo de nombre
    document.querySelector('input[name="nombrerestaurante"]').style.border =
      "1px solid red";

    RegisterRestaurante();
    return false;
  }

  if (contrasena1 == "" || contrasena2 == "") {
    //Ponemos un mensaje de error en el div mensajeErrorContra
    mensajeErrorContra.textContent = "Las contraseñas no pueden estar vacías";

    //Le ponemos el borde rojo y de 1px a los campos de contraseña

    RegisterRestaurante();
    return false;
  }

  //valida que las contraseñas sean iguales y tenga al menos 8 caracteres
  if (contrasena1 != contrasena2) {
    mensajeErrorContra.textContent = "Las contraseña no coinciden";
    document.querySelector('input[name="contrasena1restaurante"]').value = "";
    document.querySelector('input[name="contrasena2restaurante"]').value = "";

    //Le ponemos el borde rojo y de 1px a los campos de contraseña
    document.querySelector(
      'input[name="contrasena1restaurante"]'
    ).style.border = "1px solid red";
    document.querySelector(
      'input[name="contrasena2restaurante"]'
    ).style.border = "1px solid red";

    RegisterRestaurante();
    return false;
  } else if (contrasena1.length < 8) {
    mensajeErrorContra.textContent =
      "Las contraseñas deben tener al menos 8 carateres";
    document.querySelector('input[name="contrasena1restaurante"]').value = "";
    document.querySelector('input[name="contrasena2restaurante"]').value = "";

    //Le ponemos el borde rojo y de 1px a los campos de contraseña
    document.querySelector(
      'input[name="contrasena1restaurante"]'
    ).style.border = "1px solid red";
    document.querySelector(
      'input[name="contrasena2restaurante"]'
    ).style.border = "1px solid red";
    RegisterRestaurante();
    return false;
  }

  //Validamos que el alias no tenga caracteres especiales exepto por guiones bajos
  if (!/^[a-zA-Z0-9\_]+$/.test(aliasrestaurante)) {
    mensajeErrorAlias.textContent =
      "El alias solo puede tener letras, numeros y guiones bajos";
    document.querySelector('input[name="aliasrestaurante"]').value = "";

    //Le ponemos el borde rojo y de 1px al campo de alias
    document.querySelector('input[name="aliasrestaurante"]').style.border =
      "1px solid red";
    RegisterRestaurante();
    return false;
  }

  //Validamos que el correo no tenga espacio en blanco
  if (/\s/.test(correorestaurante)) {
    mensajeErrorCorreo.textContent =
      "El correo no puede tener espacios en blanco";
    document.querySelector('input[name="correorestaurante"]').value = "";

    //Le ponemos el borde rojo y de 1px al campo de correo
    document.querySelector('input[name="correorestaurante"]').style.border =
      "1px solid red";
    RegisterRestaurante();
    return false;
  }

  //Validamos que el correo no existe en la base de datos
  var exito = await validarCorreoExistente(correorestaurante);

  if (exito == false) {
    mensajeErrorCorreo.textContent = "El correo ya existe";
    document.querySelector('input[name="correorestaurante"]').value = "";

    //Le ponemos el borde rojo y de 1px al campo de correo
    document.querySelector('input[name="correorestaurante"]').style.border =
      "1px solid red";
    RegisterRestaurante();
    return false;
  }

  //Validamos que el alias no exite en la base de datos
  var exito2 = await validarAliasExistente(aliasrestaurante);

  if (exito2 == false) {
    mensajeErrorAlias.textContent = "El alias ya existe";
    document.querySelector('input[name="aliasrestaurante"]').value = "";

    //Le ponemos el borde rojo y de 1px al campo de alias
    document.querySelector('input[name="aliasrestaurante"]').style.border =
      "1px solid red";
    RegisterRestaurante();
    return false;
  }

  //Llamamos a la funcion validar foto
  var exito3 = validarFoto();

  if (exito3 == true) {
    siguiente0();
  } else {
    //Enviamos un mensaje de error en el div mensajeErrorFoto
    mensajeErrorFoto.textContent = "El archivo seleccionado no es una imagen";

    //Le ponemos el borde rojo y de 1px al campo de foto
    document.querySelector('input[name="fotorestaurante"]').style.border =
      "1px solid red";

    return false;
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
  const inputdireccion = document.getElementById("address_input");
  autocomplete1 = new google.maps.places.Autocomplete(inputdireccion);

  const inputbarrio = document.getElementById("neighborhood_input");
  autocomplete2 = new google.maps.places.Autocomplete(inputbarrio);
}
// Obtenemos los datos del lugar seleccionado
function obtenerDatos() {
  if (autocomplete1 && autocomplete2) {
    // Ambos autocompletados están definidos
    var place1 = autocomplete1.getPlace();
    var place2 = autocomplete2.getPlace();

    if (place1 && place1.formatted_address && place1.geometry) {
      var direccion = place1.formatted_address;
      var latitud = place1.geometry.location.lat();
      var longitud = place1.geometry.location.lng();
    } else {
      // Si no se obtuvo la dirección o coordenadas, maneja el error aquí
      console.error("No se obtuvieron datos válidos de autocomplete.");
      return null;
    }

    if (
      place2 &&
      place2.address_components &&
      place2.address_components.length >= 2
    ) {
      var barrio = place2.address_components[0].long_name;
      var ciudad = place2.address_components[1].long_name;
    } else {
      // Si no se obtuvo el barrio y la ciudad, se asigna un string vacío
      var barrio = "";
      var ciudad = "";
    }

    var datos = [direccion, latitud, longitud, barrio, ciudad];
    return datos;
  } else {
    // Si alguno de los autocompletados no está definido,es decir que la api de google maps no se cargó
    console.error("autocomplete1 o autocomplete2 no están definidos.");
    mostrarMensajeRegistro();
    return null;
  }
}

function obtenerDatosYEnviar() {
  var datos = obtenerDatos(); // Llamamos a la función obtenerDatos para obtener los datos del lugar

  if (datos) {
    // Si se obtuvieron los datos correctamente
    // Asignamos los datos a campos ocultos en el formulario
    document.getElementById("barrio").value = datos[3];
    document.getElementById("direccion").value = datos[0];
    document.getElementById("latitud").value = datos[1];
    document.getElementById("longitud").value = datos[2];
    document.getElementById("ciudad").value = datos[4];

    return true;
  } else {
    // Si no se pudieron obtener los datos, podrías establecer valores predeterminados o dejar los campos en blanco
    document.getElementById("barrio").value = "";
    document.getElementById("direccion").value = "";
    document.getElementById("latitud").value = "";
    document.getElementById("longitud").value = "";
    document.getElementById("ciudad").value = "";

    //Ponemos un mesaje de error en el span errorUbicacion
    errorUbicacion.textContent =
      "Hubo un error al obtener la ubicación, intentelo denuevo seleccionando una opcion de las desplegadas";
    return false;
  }
}

//Funcion para validar que los inputs de la direccion y el barrio no estén vacíos
function validarInputsBarrioYDireccion() {
  var inputdireccion = document.querySelector(
    'input[name="inputdireccionrestaurante"]'
  ).value;
  var inputbarrio = document.querySelector(
    'input[name="inputbarriorestaurante"]'
  ).value;

  //Validamos que los campos no estén vacíos
  if (inputdireccion == "" || inputbarrio == "") {
    errorUbicacion.textContent =
    "Ingrese una direccion y un barrio validos";
    siguiente0();
    return false;
  } else {
    var exito = obtenerDatosYEnviar();

    if (exito) {
      return true;
    } else {
      return false;
    }
  }
}

//Validaciones de los campos telefonos de la parte 2 del formulario de registro de restaurante
function validarTelefonos() {
  var telefono1 = document.querySelector('input[name="telefono1"]').value;
  var telefono2 = document.querySelector('input[name="telefono2"]').value;

  //Validamos que al menos uno de los dos campos de telefono no esté vacío
  if (telefono1 == "" && telefono2 == "") {
    //Ponemos un mensaje de error en el div errorTel1
    errorTel2Rest.textContent =
      "Al menos uno de los dos campos de telefono debe estar lleno";

    siguiente0();
    return false;
  } else {
    //Validamos que los campos de telefono no tengan letras pero si pueden estar vacíos
    var expresion = /^[0-9]+$/;

    if (!expresion.test(telefono1) || !expresion.test(telefono2)) {
      //Ponemos un mensaje de error en el div errorTel1
      errorTel2Rest.textContent =
        "Los campos de telefono no pueden tener letras";

      siguiente0();
      return false;
    } else {
      //Validamos que los campos de telefono tengan entre 8 y 9 digitos
      if (
        telefono1.length < 8 ||
        telefono1.length > 9 ||
        telefono2.length < 8 ||
        telefono2.length > 9
      ) {
        //Ponemos un mensaje de error en el div errorTel2
        errorTel2Rest.textContent =
          "Los campos de telefono deben tener entre 8 y 9 digitos";

        siguiente0();
        return false;
      } else {
        //Validamos que los campos de telefono no tengan espacios en blanco
        var espacios = false;
        var cont = 0;

        while (!espacios && cont < telefono1.length) {
          if (telefono1.charAt(cont) == " ") espacios = true;
          cont++;
        }

        if (espacios) {
          //Ponemos un mensaje de error en el div errorTel1
          errorTel1Rest.textContent =
            "El telefono no puede tener espacios en blanco";

          //Le ponemos el borde rojo y de 1px al campo de telefono
          document.querySelector('input[name="telefono1"]').style.border =
            "1px solid red";

          siguiente0();
          return false;
        } else {
          return true;
        }
      }
    }
  }
}

//Validaciones de los campos del formulario de registro de restaurante parte 2
function validarYAvanzar2() {
  var exito = validarInputsBarrioYDireccion();

  if (exito == true) {
    //Validamos que la descripcion de restaurante no esté vacía
    var descripcionrestaurante = document.querySelector(
      'input[name="descrrestaurante"]'
    ).value;

    if (descripcionrestaurante == "") {
      //POnemos un mensaje de error en el div errorDescRest
      errorDescrRest.textContent = "La descripcion no puede estar vacía";

      //Le ponemos el borde rojo y de 1px al campo de descripcion
      document.querySelector('input[name="descrrestaurante"]').style.border =
        "1px solid red";

      siguiente0();
      return false;
    } else {
      //Llamamos a la funcion validar telefonos
      var exito2 = validarTelefonos();

      if (exito2 == true) {
        siguiente1();
      } else {
        return false;
      }
    }
  }
}

//Crear funcion que valide que si un dia esta marcado como abierto, entonces debe tener una hora de apertura y cierre
function validarYAvanzar3() {
  //Validamos que haya un tipo de comida escrita
  var tipocomida = document.querySelector('input[name="tipoComida"]').value;

  if (tipocomida == "") {
    //Ponemos un mensaje de error en el div errorTipoComida
    errorTipoComida.textContent =
      "El campo de tipo de comida no puede estar vacío";

    //Le ponemos el borde rojo y de 1px al campo de tipo de comida
    document.querySelector('input[name="tipoComida"]').style.border =
      "1px solid red";

    siguiente2();
    return false;
  }

  var lunes = document.querySelector('input[name="apertura[lunes]"]');
  var martes = document.querySelector('input[name="apertura[martes]"]');
  var miercoles = document.querySelector('input[name="apertura[miercoles]"]');
  var jueves = document.querySelector('input[name="apertura[jueves]"]');
  var viernes = document.querySelector('input[name="apertura[viernes]"]');
  var sabado = document.querySelector('input[name="apertura[sabado]"]');
  var domingo = document.querySelector('input[name="apertura[domingo]"]');

  var lunesapertura = document.querySelector(
    'input[name="horarios_apertura[lunes]"]'
  );
  var martesapertura = document.querySelector(
    'input[name="horarios_apertura[martes]"]'
  );
  var miercolesapertura = document.querySelector(
    'input[name="horarios_apertura[miercoles]"]'
  );
  var juevesapertura = document.querySelector(
    'input[name="horarios_apertura[jueves]"]'
  );
  var viernesapertura = document.querySelector(
    'input[name="horarios_apertura[viernes]"]'
  );
  var sabadoapertura = document.querySelector(
    'input[name="horarios_apertura[sabado]"]'
  );
  var domingoapertura = document.querySelector(
    'input[name="horarios_apertura[domingo]"]'
  );

  var lunescierre = document.querySelector(
    'input[name="horarios_cierre[lunes]"]'
  );
  var martescierre = document.querySelector(
    'input[name="horarios_cierre[martes]"]'
  );
  var miercolescierre = document.querySelector(
    'input[name="horarios_cierre[miercoles]"]'
  );
  var juevescierre = document.querySelector(
    'input[name="horarios_cierre[jueves]"]'
  );
  var viernescierre = document.querySelector(
    'input[name="horarios_cierre[viernes]"]'
  );
  var sabadocierre = document.querySelector(
    'input[name="horarios_cierre[sabado]"]'
  );
  var domingocierre = document.querySelector(
    'input[name="horarios_cierre[domingo]"]'
  );

  if (lunes.checked) {
    if (lunesapertura.value == "" || lunescierre.value == "") {
      //Ponemos un mensaje de error en el div errorHorariosL
      errorHorariosL.textContent =
        "Por favor llene los campos de horario de lunes";

      siguiente2();
      return false;
    }
  }

  if (martes.checked) {
    if (martesapertura.value == "" || martescierre.value == "") {
      //Ponemos un mensaje de error en el div errorHorariosM
      errorHorariosM.textContent =
        "Por favor llene los campos de horario de martes";

      siguiente2();
      return false;
    }
  }

  if (miercoles.checked) {
    if (miercolesapertura.value == "" || miercolescierre.value == "") {
      //Ponemos un mensaje de error en el div errorHorariosX
      errorHorariosX.textContent =
        "Por favor llene los campos de horario de miercoles";

      siguiente2();
      return false;
    }
  }

  if (jueves.checked) {
    if (juevesapertura.value == "" || juevescierre.value == "") {
      //Ponemos un mensaje de error en el div errorHorariosJ
      errorHorariosJ.textContent =
        "Por favor llene los campos de horario de jueves";

      siguiente2();
      return false;
    }
  }

  if (viernes.checked) {
    if (viernesapertura.value == "" || viernescierre.value == "") {
      //Ponemos un mensaje de error en el div errorHorariosV
      errorHorariosV.textContent =
        "Por favor llene los campos de horario de viernes";

      siguiente2();
      return false;
    }
  }

  if (sabado.checked) {
    if (sabadoapertura.value == "" || sabadocierre.value == "") {
      //Ponemos un mensaje de error en el div errorHorariosS
      errorHorariosS.textContent =
        "Por favor llene los campos de horario de sabado";

      siguiente2();
      return false;
    }
  }

  if (domingo.checked) {
    if (domingoapertura.value == "" || domingocierre.value == "") {
      //Ponemos un mensaje de error en el div errorHorariosD
      errorHorariosD.textContent =
        "Por favor llene los campos de horario de domingo";

      siguiente2();
      return false;
    }
  }

  siguiente3();
}

function validarPDF() {
  var pdfInput = document.querySelector('input[name="pdfMenu"]');
  var pdf = pdfInput.value;

  if (pdf != "") {
    var extension = pdf.substring(pdf.lastIndexOf(".") + 1).toLowerCase();

    if (extension != "pdf") {
      //Ponemos un mensaje de error en el div errorMenu
      errorMenu.textContent = "El archivo seleccionado no es un PDF";

      pdfInput.value = ""; // Limpia el campo de entrada del archivo
      pdfInput.focus(); // Enfoca de nuevo el campo de entrada del archivo
      return false;
    }
  }
  return true;
}
