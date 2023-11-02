<?php

//Iniciamos la sesion
session_start();


//Si no hay una sesion iniciada enviamos al usuario a la pagina de inicio de sesion
if (!isset($_SESSION['email'])) {

    //Redirigimos a la pagina de inicio de sesion
    header("Location: login_index.php");
    exit;

}

//Si hay una sesion iniciada, obtenemos el email del usuario que inicio sesion
$usuariosesion = $_SESSION['email'];


//llamamos a la clase hotel_model.php
require_once('models/hotel_model.php');

//Creamos una instancia de la clase hotel_model.php
$hotelinstancia = new hotel_model();

//llamamos a la clase hospedaje_model.php
require_once('models/hospedaje_model.php');

//Creamos una instancia de la clase hospedaje_model.php
$hospedajeinstancia = new hospedaje_model();

//Creamos una instancia de barrion_model.php
require_once('models/barrio_model.php');

//Creamos una instancia de la clase barrio_model.php
$barriomodelinstancia = new barrio_model();

//Creamos una instancia de la clase usuario_model.php
require_once('models/usuario_model.php');

//Creamos una instancia de la clase usuario_model.php
$usuariomodelinstancia = new usuario_model();

//LLamamos a verificacion_de_usuarios_model.php
require_once('models/verificacion_de_usuarios_model.php');

//Creamos una instancia de la clase verificacion_de_usuarios_model.php
$verificacionusuariosinstancia = new verificacionUsuarios();


//PASAMOS A LA PARTE DE CONTROLLER DESPUES DE LA DEFINICION DE VARIABLES DE CLASES Y ANTES DE LAS FUNCIONES

//Verificamos que el estado logico del usuario sea activo
$verificacionEstadoLogico = $verificacionusuariosinstancia->verificarEstadoLogico($usuariosesion);

//Verificamos que el rol del usuario sea turista
$verificacionRolTurista = $verificacionusuariosinstancia->verificarRolTurista($usuariosesion);

//Definimos emailtur como el email del usuario que inicio sesion

$emailtur = $usuariosesion;

//Verificamos si hay una estadía en curso y si hay redirigimos a la pagina de inicio de turista
$verificacionHospedaje = $hospedajeinstancia->verificarHospedajeEnCurso($emailtur);


//Si hay una estadía en curso
if ($verificacionHospedaje == true) {

    //Redirigimos a la pagina de inicio de turista
    header('Location: turista_principal_index.php');
    exit;

}


//Despues de esas verificaciones de sesion y rol, procedemos a verificar si se presiono el boton de envio de formulario
//Si se presiona el boton envioHotelTurista del formulario de hotel_turista_view.php
if (isset($_POST['envioHotelTurista'])) {

    //Obtenemos las variables del formulario de hotel_turista_view.php
    $nombreHotel = $_POST['hotel'];
    $fechaInicio = $_POST['fechaInicio'];
    $fechaFin = $_POST['fechaFin'];
    $barrio = $_POST['barrio'];
    $ciudad = $_POST['ciudad'];
    $direccion = $_POST['direccion'];


    //Verificamos que $ciudad,$barrio, $nombreHotel y direccion no esten vacios o sean null o undefined
    if (empty($ciudad) || empty($barrio) || empty($nombreHotel) || empty($direccion) || $ciudad == null || $barrio == null || $nombreHotel == null || $direccion == null) {

        //Guardamos true en la sesion de errorRegistroHotel
        $_SESSION['errorRegistroHotel'] = true;

        //Refrescamos la pagina
        header("Refresh:0");

        exit;
    }

    //Verificmos que la fecha de inicio no sea mayor a la fecha de fin, que la fecha de inicio 
    //no sea mayor a la fecha actual y que la fecha de fin no sea menor a la fecha actual
    if ($fechaInicio > $fechaFin || $fechaInicio > date("Y-m-d") || $fechaFin < date("Y-m-d")) {

        //Guardamos true en la sesion de errorRegistroHotel
        $_SESSION['errorRegistroHotel'] = true;

        //Refrescamos la pagina
        header("Refresh:0");

        exit;
    }

    //Formateamos fechaInicio para que sea compatible con la base de datos, agregandole la hora y minutos actuales
    // asi si se se cancela la estadia en el mismo dia por algun error de ingreso de datos, se pueda registrar 
    //un nuevo hospedaje con la misma fecha de inicio.
    $fechaInicio = $fechaInicio . " " . date("H:i:s");

    //Formateamos fechaFin para que sea compatible con la base de datos, agregandole hora y minutos genericos
    $fechaFin = $fechaFin . " 15:00:00";

    //Verficamos si el barrio existe en la base de datos
    $verificacionBarrio = $barriomodelinstancia->verificarBarrio($ciudad, $barrio);


    //Si el barrio no existe en la base de datos
    if ($verificacionBarrio === "noexiste") {

        //Registramos el barrio en la base de datos
        $registroBarrio = $barriomodelinstancia->insertarBarrio($ciudad, $barrio);

        //si registroBarrio es false, significa que no se registro el barrio en la base de datos
        if ($registroBarrio == false) {

            //Guardamos true en la sesion de errorRegistroHotel
            $_SESSION['errorRegistroHotel'] = true;

            //Refrescamos la pagina
            header("Refresh:0");

            exit;
        }

    } else {
        if ($verificacionBarrio === false) {

            //Guardamos true en la sesion de errorRegistroHotel
            $_SESSION['errorRegistroHotel'] = true;

            //Refrescamos la pagina
            header("Refresh:0");

            exit;
        }
    }

    //Verificamos si el hotel ya existe en la base de datos
    $verificacionHotel = $hotelinstancia->VerificarHotelBD($nombreHotel, $direccion);

    //Si el hotel no existe en la base de datos
    if ($verificacionHotel === "noexiste") {

        //Registramos el hotel en la base de datos
        $registroHotel = $hotelinstancia->RegistrarHotelBD($nombreHotel, $direccion);

        //si el hotel no se registro correctamente en la base de datos ponemos true en la variable de error
        if ($registroHotel == false) {

            //Guardamos true en la sesion de errorRegistroHotel
            $_SESSION['errorRegistroHotel'] = true;

            //Refrescamos la pagina
            header("Refresh:0");

            exit;

        }

    } else {

        //Si da false significa que hubo un error en la consulta
        if ($verificacionHotel == false) {

            //Guardamos true en la sesion de errorRegistroHotel
            $_SESSION['errorRegistroHotel'] = true;

            //Refrescamos la pagina
            header("Refresh:0");

            exit;
        }
    }

    //Agregamos un nuevo hospedaje en la base de datos
    $registroHospedaje = $hospedajeinstancia->nuevoHospedaje($emailtur, $fechaInicio, $fechaFin, $direccion, $ciudad, $barrio);

    //Si el hospedaje se registro correctamente
    if ($registroHospedaje == true) {


            //Redirigimos a la pagina de inicio de turista
            echo "<script>window.location.href='turista_principal_index.php'</script>";
            exit;
    } else {

        //Guardamos true en la sesion de errorRegistroHotel
        $_SESSION['errorRegistroHotel'] = true;

        //Refrescamos la pagina
        header("Refresh:0");
        exit;
    }

}

//Llamos a la vista hotel_turista_view.php
require_once('views/hotel_turista_view.php');


?>