<?php
// Configura la sesión para usar cookies seguras y HttpOnly
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);

//Iniciamos la sesion
session_start();

// Genera o recupera la sesión PHPSESSID
// (esto debe hacerse antes de cualquier salida al navegador)
if (!isset($_SESSION['PHPSESSID'])) {
    $_SESSION['PHPSESSID'] = session_id();
}

// Generar un token Anti-CSRF y guardarlo en la sesión
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

//Llamamos al modelo usuarios_model.php
require_once('models/usuario_model.php');
$usuariomodelinstancia = new usuario_model();

//llamamos a hospe_model.php
require_once('models/hospedaje_model.php');
$hospedajeinstancia = new hospedaje_model();

//Llamamos al modelo restaurante_model.php
require_once('models/restaurante_model.php');
$restaurantemodelinstancia = new restaurante_model();


//Verificamos si el usuario presionó el botón Login

if (isset($_SERVER['REQUEST_METHOD']) && isset($_POST['formulariologin'])) {

    // Obtener el nombre ingresado en el formulario
    $email = $_POST['correo'];
    $contrasena = $_POST['contrasena'];
    $csrf = $_POST['csrf_token'];

    //Si el token CSRF no coincide, mostramos un mensaje de error y ponemos exit para que no se ejecute el codigo
    if ($csrf !== $_SESSION['csrf_token']) {

        //Destruimos la sesion
        session_destroy();

        //Mostramos un mensaje de error
        $errorLogin = true;

        //Refrescamos la pagina
        header("Location: index.php");
        exit;

    }

    //Limpiamos con htmlspecialchars que convierte caracteres especiales en entidades HTML
    $email = htmlspecialchars($email);
    $contrasena = htmlspecialchars($contrasena);

    //Limpiamos con trim que elimina espacios en blanco u otros caracteres al inicio y final de una cadena de texto
    $email = trim($email);
    $contrasena = trim($contrasena);

    //Verificamos que el correo tenga el formato correcto
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        //Si el correo no tiene el formato correcto mostramos un mensaje de error
        $errorLogin = true;

        //Refrescamos la pagina
        echo "<script>window.location.href='login_index.php'</script>";

    }

    //Validamos que el correo no tenga mas de 250 caracteres
    if (strlen($email) > 250) {

        //Si el correo tiene mas de 250 caracteres mostramos un mensaje de error
        $errorLogin = true;

        //Refrescamos la pagina
        echo "<script>window.location.href='login_index.php'</script>";

    }

    //Validamos que la contraseña no tenga mas de 128 caracteres
    if (strlen($contrasena) > 128) {

        //Si la contraseña tiene mas de 128 caracteres mostramos un mensaje de error
        $errorLogin = true;

        //Refrescamos la pagina
        echo "<script>window.location.href='login_index.php'</script>";

    }

    //Llamamos a la funcion verificarEmail del modelo
    $existe = $usuariomodelinstancia->verificarEmail($email);

    //Si no existe el usuario en la BD mostramos un mensaje de error
    if ($existe == false) {

        $errorLogin = true;
        header("Location: index.php");
        exit;

    } else {
        //Si existe el usuario en la BD verificamos si la contraseña es correcta
        $exito = $usuariomodelinstancia->autenticarUsuario($email, $contrasena);

        if ($exito == true) {

            //Creamos una variable de sesion con el email del usuario
            $_SESSION['email'] = $email;

            //Si el usuario es turista lo enviaremos a la pagina turista_principal_index.php
            $datosusuario = $usuariomodelinstancia->obtenerDatosUsuario($email);

            //Verificamos que el usuario no sea false, si lo es mostramos un mensaje de error
            if ($datosusuario == false) {

                $errorLogin = true;
                header("Location: index.php");
                exit;
            }

            //Guardamos el tipo de usuario en una variable
            $tipousuario = $datosusuario->rol;

            //Creamos la condicion para verificar si el usuario es turista
            if ($tipousuario == "tur") {

                //Verificamos si el usuario tiene un hospedaje en curso
                $hospedajeEnCurso = $hospedajeinstancia->verificarHospedajeEnCurso($email);

                //Si el usuario tiene un hospedaje en curso lo enviaremos a la pagina turista_principal_index.php
                if ($hospedajeEnCurso == true) {
                    echo "<script>window.location.href='turista_principal_index.php'</script>";
                }
                //Si el usuario no tiene un hospedaje en curso lo enviaremos a la pagina hotel_turista_index.php
                else {
                    echo "<script>window.location.href='hotel_turista_index.php'</script>";
                }

            } else {

                if ($tipousuario == "rest") {


                    //Obtenemos los datos del usuario restaurante
                    $datosrest = $restaurantemodelinstancia->obtenerRestaurante($email);

                    //Verificamos que el usuario no sea false, si lo es mostramos un mensaje de error
                    if ($datosrest == false) {

                        //Destruimos la sesion
                        session_destroy();

                        $errorLogin = true;
                        header("Location: index.php");
                        exit;

                    }


                    //llamamos a la funcion cambiar estado membresia
                    //Que cambia el estado de la membresia a vencida
                    //Si la membresia en curso esta vencida
                    //Actua como un chequeador de los estados de las membresias
                    //Para siempre mentener los estados de acuerdo a la fecha actual
                    $resultado = $restaurantemodelinstancia->cambiarEstadoMembresia($email);

                    //Verificamos que la funcion no sea false,
                    //Si es false mostramos un mensaje de error
                    if ($resultado == false) {

                        //Destruimos la sesion
                        session_destroy();

                        $errorLogin = true;
                        header("Location: index.php");
                        exit;


                    } else {

                        //Guardamos el estado de la membresia en una variable
                        $estadomembresia = $datosrest->estadomembresia;

                        //Si la membresia esta vencida verificamos si hay una renovacion pendiente
                        //Si hay una renovacion pendiente, verificamos si la renovacion es valida
                        //Si la renovacion es valida, remplazamos la membresia por la renovacion
                        //Si la renovacion no es valida, lo enviamos a la pagina enviar_membresia_index.php

                        if ($resultado == "vencida") {


                            //Llamamos a verificarRenovacionMembresia
                            $resultado = $restaurantemodelinstancia->verificarRenovacionMembresia($email);

                            //Verificamos que no de false
                            if ($resultado !== false && $resultado == "renovacionvalida") {


                                //Llamamos a la funcion remplazarMembresiaPorRenovacion
                                $resultado = $restaurantemodelinstancia->remplazarMembresiaPorRenovacion($email);

                                //Si el resultado es true, lo enviamos a la pagina principal de restaurantes
                                if ($resultado !== false && $resultado == true) {

                                    echo "<script>window.location.href='restaurante_principal_index.php'</script>";

                                } else {

                                    //SI el resultado es false, mostramos un mensaje de error
                                    $errorLogin = true;

                                    //Destruimos la sesion
                                    session_destroy();

                                    header("Location: index.php");
                                    exit;

                                }

                            } else {

                                //Si no tiene renovacion pendiente, o una renovacion rechazada, enviamops a enviar_membresia_index.php
                                if ($resultado !== false && $resultado == "renovacionpendiente" || $resultado !== false && $resultado == "estadoinvalido") {


                                    //Lo enviamos a la pagina enviar_membresia_index.php
                                    //pero con el parametro renovacionpendiente true
                                    echo "<script>window.location.href='enviar_membresia_index.php?renovacionpendiente=true'</script>";
                                } else {

                                    //Si no tiene renovacion pendiente, enviamops a enviar_membresia_index.php
                                    if ($resultado !== false && $resultado == "notienerenovacion") {

                                        //Lo enviamos a la pagina enviar_membresia_index.php
                                        //pero con el parametro renovacionpendiente true
                                        echo "<script>window.location.href='enviar_membresia_index.php'</script>";
                                    } else {

                                        //SI el resultado es false, mostramos un mensaje de error
                                        $errorLogin = true;

                                        //Destruimos la sesion
                                        session_destroy();

                                        header("Location: index.php");
                                        exit;


                                    }

                                }
                            }

                        }

                        //Si la membresia no esta vencida, lo enviamos a la pagina principal de restaurantes
                        if ($estadomembresia == "vigente") {


                            echo "<script>window.location.href='restaurante_principal_index.php'</script>";

                        } else {

                            //Si no hay una membresia ni una renovacion pendiente, lo enviamos a la pagina enviar_membresia_index.php
                            echo "<script>window.location.href='enviar_membresia_index.php'</script>";
                        }

                    }

                } else {

                    if ($tipousuario == "admin") {
                        echo "<script>window.location.href='pagina_principal_admin_index.php'</script>";
                    }

                }
            }


        } else {

            //Si el correo no existe o la contraseña es incorrecta mostramos un mensaje de error
            $errorLogin = true;
            header("Location: index.php");
            exit;

        }
    }


}
//Llama a la vista
require_once('views/login_view.php');



?>