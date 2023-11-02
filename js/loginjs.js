const contenedorloguin = document.querySelector(".contenedor");


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
            resolve(); // El correo existe en la BD
          } else {
            reject(); // El correo no existe en la BD
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
  
      console.log("El correo existe en la base de datos, es válido");
      // El correo no existe en la base de datos, es válido
      return true;
    } catch (error) {
      console.log("El correo no existe en la base de datos, no es válido");
      // El correo ya existe en la base de datos, no es válido
      return false;
    }
  }

  function validarCredenciales(email, password) {
    console.log("Validando credenciales:", email, password);
  
    return new Promise(function (resolve, reject) {
      // Bandera para verificar si la solicitud AJAX se envió
      let solicitudEnviada = false;
  
      console.log("Haciendo solicitud AJAX");
  
      $.ajax({
        url: "/PHPProducto - copia/ajaxconsultas.php",
        method: "POST",
        data: { correologin: email, contrasenalogin: password },
        timeout: 5000, // Establece un tiempo de espera de 5 segundos (ajusta según tus necesidades)
        beforeSend: function () {
          solicitudEnviada = true;
        },
        success: function (response) {
          console.log("Respuesta del servidor:", response);
  
          if (response === "credenciales válidas") {
            resolve(); // Las credenciales son válidas
          } else {
            reject(); // Las credenciales no son válidas
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

  //Creamos la funcion validarCredecialesExistente
    async function validarCredecialesExistente(email, password) {
        try {
        await validarCredenciales(email, password);
    
        console.log("Las credenciales son válidas");
        // Las credenciales son válidas
        return true;
        } catch (error) {
        console.log("Las credenciales no son válidas");
        // Las credenciales no son válidas
        return false;
        }
    }

    document
  .getElementById("loginForm")
  .addEventListener("submit", async function (event) {
    event.preventDefault(); // Detiene la acción predeterminada del formulario

    const isValid = await validarFormulario();

    if (isValid) {
      this.submit(); // Envía el formulario si es válido
    }
  });

    async function validarFormulario() {
        return new Promise(async (resolve) => {
          console.log("Validando formulario de login");
        
          // Validamos que el correo exista en la base de datos
          var correo = document.querySelector(
            'input[name="correo"]'
          ).value;
          var exito = await validarCorreoExistente(correo);
      
          // Mostramos éxito en consola
          console.log("Exito:", exito);
      
          //Si el correo no existe ponemos mensaje de error en el span errorLogin
            if (exito === false) {
                document.querySelector("#errorlogin").textContent =
                "El correo electrónico no existe";
                resolve(false);
            } else {

                console.log("Se estan validando credenciales");
                //validamos las credenciales
                var password = document.querySelector(
                    'input[name="contrasena"]'
                ).value;

                var exito = await validarCredecialesExistente(correo, password);

                // Mostramos éxito en consola
                console.log("Exito:", exito);

                //Si las credenciales no son validas ponemos mensaje de error en el span errorLogin
                if (exito === false) {

                    console.log("Las credenciales no son validas");

                  document.querySelector("#errorlogin").textContent =
                    "La contraseña no es válida";
                    resolve(false);
                } else {

                    //Ponemos en el mensaje de error que se ha iniciado sesion
                    document.querySelector("#errorlogin").textContent =
                    "Se ha iniciado sesión correctamente";
                    
                  
                    resolve(true);
                }

            }
    
        });
      }