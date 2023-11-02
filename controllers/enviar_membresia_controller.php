<?php

//Inciar una nueva sesión o reanudar la existente
session_start();

class enviar_membresia_controller
{
    //CReamos las variables que vamos a utilizar en el controlador
    private $usuariomodelinstancia;
    private $restaurantemodelinstancia;
    private $archivosmodelinstancia;
    private $verificacionusuariosmodelinstancia;


    //Creamos el metodo constructor
    function __construct()
    {
        //Llamamos a los archivos con clases que vamos a utilizar
        require_once("models/usuario_model.php");
        require_once("models/restaurante_model.php");
        require_once("models/archivos_model.php");
        require_once("models/verificacion_de_usuarios_model.php");

        //Creamos las instancias de los modelos
        $this->usuariomodelinstancia = new usuario_model();
        $this->restaurantemodelinstancia = new restaurante_model();
        $this->archivosmodelinstancia = new archivos_model();
        $this->verificacionusuariosmodelinstancia = new verificacionUsuarios();

    }

    //Creamos el metodo para enviar la solicitud de membresia
    public function enviarSolicitudMembresia($emailrest, $tipomembresia, $urlcomprobantepago)
    {
        //LLamamos a la funcion para enviar la solicitud de membresia
        $resultado = $this->restaurantemodelinstancia->solicitarMembresia($emailrest, $tipomembresia, $urlcomprobantepago);

        //Verificamos si se envio la solicitud de membresia
        if ($resultado == true) {
            return true;

        } else {

            return false;

        }

    }

}

//Iniciamos la sesion
$emailrest = $_SESSION['email'];

//Verificamos que haya una sesion iniciada
if (!isset($_SESSION['email'])) {
    header("Location: login_index.php");
}

//Creamos la instancia del controlador
$enviarmembresiacontrollerinstancia = new enviar_membresia_controller();

//Llamamos a archivos_model.php
$archivosmodelinstancia = new archivos_model();

//Llamamos a restaurante_model.php
$restaurantemodelinstancia = new restaurante_model();

//Llamamos a usuario_model.php
$usuariomodelinstancia = new usuario_model();

//Creamos una instancia de verificacion_de_usuarios_model.php
$verificacionusuariosmodelinstancia = new verificacionUsuarios();

//Datos restaurante
$datosrest = $restaurantemodelinstancia->obtenerRestaurante($emailrest);

//Si los datos del restaurante no se consiguieron llamamos a error.html
if (is_null($datosrest) || $datosrest == false) {
    header("Location: error.html");
    exit;
}

//Verificamos si el usuario esta dado de baja
$verificacionusuariosmodelinstancia->verificarEstadoLogico($emailrest);

//VERIFICAMOS SI EL USUARIO ES RESTAURANTE
$verificacionusuariosmodelinstancia->verificarRolRestaurante($emailrest);


//Verificamos que si el usuario rest tiene una membresia vigente lo redirija a la pagina principal
if ($datosrest->estadomembresia == "vigente") {
    header("Location: restaurante_principal_index.php");
}

//Verificamos si se envio la solicitud de membresia
if (isset($_POST['enviarSolicitudbtn'])) {

    try {
        //Obtenemos los datos del formulario

        $tipomembresia = $_POST['membresia'];
        $urlcomprobantepago = $_FILES['comprobante']['tmp_name'];

        //Llamamos a la funcion subir_pdf_o_imagen_obtener_ruta del modelo archivos_model.php
        $urlcomprobantepago = $archivosmodelinstancia->subir_pdf_o_imagen_obtener_ruta("archivos/comprobantes", "comprobante");

        //Verificamos que la url del comprobante no sea false
        if ($urlcomprobantepago == false) {

            //Si da error al subir el comprobante mostramos un mensaje de error
            $errorSubirComprobante = true;

        } else {

            //Llamamos a la funcion para enviar la solicitud de membresia
            $resultado = $enviarmembresiacontrollerinstancia->enviarSolicitudMembresia($emailrest, $tipomembresia, $urlcomprobantepago);

            //Verificamos si se envio la solicitud de membresia
            if ($resultado == false) {

                //Si no se envio la solicitud de membresia mostramos un mensaje de error
                $errorSolicitud = true;
            }

        }

    } catch (Exception $e) {

        echo 'Excepción capturada: ', $e->getMessage(), "\n";
    }

}


try {

    //Declaramos laa variable haymensaje
    $haymensaje = false;

    $datosrest = $restaurantemodelinstancia->obtenerRestaurante($emailrest);

    //Si datosrest no es nulo
    if (!is_null($datosrest)) {

        //Verificamos si el restaurante tiene una membresia pendiente
        if ($datosrest->estadomembresia == "pendiente") {
            $membresiapendiente = true;
        } else {
            $membresiapendiente = false;
        }
        //Si los datos del restaurante tienen un mensaja
        if (!empty($datosrest->mensajemembresia)) {
            $haymensaje = true;
            $mensaje = $datosrest->mensajemembresia;
        }


    } else {

        $errorMostrarDatos = true;

    }
} catch (Exception $e) {

    echo 'Excepción capturada: ', $e->getMessage(), "\n";
}

//Nos fijamos si por get esta el parametro de renovacion pendiente y es true
//Esto es para mostrar en la view un mensaje distino y no dejar enviar una
//nueva solicitud de membresia
if (isset($_GET['renovacionpendiente']) && $_GET['renovacionpendiente'] == true) {
    $renovacionpendiente = true;
} else {
    $renovacionpendiente = false;
}

//Si se envio el formulario para cancelar la solicitud de membresia
if (isset($_POST['cancelarSoli'])) {
    //Llamamos a la funcion para cancelar la solicitud de membresia
    $resultado = $restaurantemodelinstancia->darBajaSolicitudMembresia($emailrest);

    //Verificamos si se cancelo la solicitud de membresia
    if ($resultado == true) {
        //Redirigimos al usuario al login
        header("Location: login_index.php");
    } else {
        //Si no se cancelo la solicitud de membresia mostramos un mensaje de error
        $errorCancelarSoli = true;
    }

}


require_once("views/pago_restaurante_view.php");


?>