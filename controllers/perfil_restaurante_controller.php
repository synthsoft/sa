<?php
//Inicializamos la sesion
session_start();

class perfil_restaurante_controller
{
    protected $usuarioinstancia;
    protected $restaurantemodelinstancia;

    //Creo el metodo constructor
    public function __construct()
    {
        //Llamamos al modelo de usuario
        require_once('models/usuario_model.php');

        //Creamos una instancia de la clase usuario_model
        $this->usuarioinstancia = new usuario_model();

        //Llamamos al modelo de restaurante
        require_once('models/restaurante_model.php');

        //Creamos una instancia de la clase restaurante_model
        $this->restaurantemodelinstancia = new restaurante_model();

    }


    public function verificarContrasenas($contrasena1, $contrasena2, $contrasena3, $emailrest)
    {

        //Limpiamos con trim y strip_tags
        $contrasena1 = trim(strip_tags($contrasena1));
        $contrasena2 = trim(strip_tags($contrasena2));
        $contrasena3 = trim(strip_tags($contrasena3));

        //Y con htmlspecialchars
        $contrasena1 = htmlspecialchars($contrasena1);
        $contrasena2 = htmlspecialchars($contrasena2);
        $contrasena3 = htmlspecialchars($contrasena3);

        //VErificamos que ninguna de las dos este vacia o no seteada
        if (
            empty($contrasena1) || empty($contrasena2) || !isset($contrasena1) ||
            !isset($contrasena2) || $contrasena1 == '' || $contrasena2 == '' || $contrasena3 == '' ||
            !isset($contrasena3) || empty($contrasena3)
        ) {

            return false;
        }

        //VErificamos que no tengan espacios de ningun tipo
        if (strpos($contrasena1, ' ') !== false || strpos($contrasena2, ' ') !== false) {

            return false;
        }

        //Verificamos si las contraseñas son iguales
        if ($contrasena1 != $contrasena2) {

            return false;
        }

        //Verificamos que la contraseña tenga al menos 8 caracteres
        if (strlen($contrasena1) < 8) {

            return false;
        }

        //Verificamos que no exista en la BD, para es obtenemos los datos del usuario
        $contrasenaExiste = $this->usuarioinstancia->obtenerDatosUsuario($emailrest);


        //Verificamos si la contraseña es igual a la que ya estaba
        if (password_verify($contrasena3, $contrasenaExiste->contrasena)) {
            return true;
        } else {
            return false;
        }


    }

    public function enviarRenovacionMembresia($emailrest, $tipomembresia, $urlcomprobantepago)
    {
        //LLamamos a la funcion para enviar la solicitud de membresia
        $resultado = $this->restaurantemodelinstancia->solicitarRenovacionMembresia($emailrest, $tipomembresia, $urlcomprobantepago);

        //Verificamos si se envio la solicitud de membresia
        if ($resultado == true) {
            return true;
        } else {

            return false;

        }

    }

}

//Instanciamos el controlador perfil_restaurante_controller
$perfil_restaurante_controller = new perfil_restaurante_controller();

//Instanciamos el modelo usuario
require_once('models/usuario_model.php');

//Instanciamos un objeto de la clase usuario_model
$usuariomodelinstancia = new usuario_model();

//Creamos una instancia de restaurante_model.php
require_once('models/restaurante_model.php');

//Creamos una instancia de restaurante_model.php
$restaurantemodelinstancia = new restaurante_model();

//Llamamos a archivos_model.php
require_once('models/archivos_model.php');

//Creamos una instancia de archivos_model.php
$archivosmodelinstancia = new archivos_model();

//LLamamos a verificacion_de_usuarios_model.php
require_once('models/verificacion_de_usuarios_model.php');

//Creamos una instancia de verificacion_de_usuarios_model.php
$verificacion_de_usuarios_model_instancia = new verificacionUsuarios();


if (!isset($_SESSION['email'])) {
    header('Location: index.php');
}


$emailrest = $_SESSION['email'];

//Verificamos si el usuario esta activo
$verificacion_de_usuarios_model_instancia->verificarEstadoLogico($emailrest);

//Verficamos que el restaurante sea un restaurante
$verificacion_de_usuarios_model_instancia->verificarRolRestaurante($emailrest);

//Obtenemos los datos del usuario
$datosuser = $usuariomodelinstancia->obtenerDatosUsuario($_SESSION['email']);

//Ponemos la condicion de que si no hay datos del usuario o devielva false el alias es "Error al cargar alias"
$alias = (!is_null($datosuser) || $datosuser !== false) ? $datosuser->alias : "Error al cargar alias";

//Datos restaurante
$datosrest = $restaurantemodelinstancia->obtenerRestaurante($emailrest);

//Si datos rest es nulo o devuelve false enviamos a error
if (is_null($datosrest) || $datosrest == false) {
    header('Location: error.html');
}

$iniciomembresia = $datosrest->iniciomembresia;

$finmembresia = $datosrest->finmembresia;

$tipomembresia = $datosrest->tipomembresia;

$estadomembresia = $datosrest->estadomembresia;

$iniciorenovacion = $datosrest->iniciorenovacion;

$finrenovacion = $datosrest->finrenovacion;

$tiporenovacion = $datosrest->tiporenovacion;

$estadorenovacion = $datosrest->estadorenovacion;



//Si el fin de la membresia es menor a la fecha actual enviamos al login y cerramos la sesion
if ($finmembresia < date("Y-m-d")) {

    //redirieccionamos al index
    echo "<script>window.location.href='login_index.php'</script>";

    //Cerramos la sesion
    session_destroy();

}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitCambiosUser'])) {


    $contrasena1 = $_POST['contrasena1'];
    $contrasena2 = $_POST['contrasena2'];
    $contrasena3 = $_POST['contrasenaactual'];
    $aliasform = $_POST['alias'];

    //Comparamos el alias que ya estaba con el nuevo si se cambio algo llamamos primero a la funcion verificarAlias
    if ($aliasform != $alias) {


        //Verificamos si el alias existe en la BD
        $aliasExiste = $usuariomodelinstancia->verificarAlias($aliasform);
        //Si el alias existe en la BD, significa que ya esta en uso
        if ($aliasExiste) {
            $alias = $aliasform;
            $aliasMensaje = 'El alias ya esta en uso';
            //Guardamos el mensaje en una variable de sesion
            $_SESSION['aliasMensaje'] = $aliasMensaje;

        } else {
            //Llamamos a la funcion actualizarAliasUsuario
            $resultado = $usuariomodelinstancia->actualizarAliasUsuario($emailrest, $aliasform);
            echo $resultado;

            //Ponemos mensaje de exito
            $aliasMensaje = 'El alias se actualizo correctamente';
            //Guardamos el mensaje en una variable de sesion
            $_SESSION['aliasMensaje'] = $aliasMensaje;




        }
    }

    //Si al menos un campo de contraseña esta seteado o con algo escrito
    //Llamamos a la funcion verificarContrasenas

    if ($contrasena1 != '' || $contrasena2 != '' || !empty($contrasena1) || !empty($contrasena2)) {


        $resultadoContra = $perfil_restaurante_controller->verificarContrasenas($contrasena1, $contrasena2, $contrasena3, $emailrest);

        if (isset($resultadoContra) && $resultadoContra !== false) {

            //LLamamos a la funcion actualizarContrasenaUsuario
            $resultado = $usuariomodelinstancia->actualizarContrasenaUsuario($emailrest, $contrasena1);

            //Si la contraseña se actualizo correctamente
            if ($resultado) {
                //Ponemos mensaje de exito
                $contrasenaMensaje = 'La contraseña se actualizo correctamente';
                //Lo guardamos en una variable de sesion al mensaje
                $_SESSION['contrasenaMensaje'] = $contrasenaMensaje;


            } else {
                //Ponemos mensaje de error
                $contrasenaMensaje = 'La contraseña no se pudo actualizar';
                //Lo guardamos en una variable de sesion
                $_SESSION['contrasenaMensaje'] = $contrasenaMensaje;



            }

        } else {
            $contrasenaMensaje = 'Las contraseñas no coinciden, no tienen el formato adecuado o son iguales a las que ya estaba';
            //Lo guardamos en una variable de sesion
            $_SESSION['contrasenaMensaje'] = $contrasenaMensaje;
        }

    }
    //refrescamos la pagina
    header('Location: perfil_restaurante_index.php');
    exit;

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
            $errorSubirComprobante = true;
        } else {

            //Llamamos a la funcion para enviar la solicitud de membresia
            $resultado = $perfil_restaurante_controller->enviarRenovacionMembresia($emailrest, $tipomembresia, $urlcomprobantepago);

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
        if ($datosrest->estadorenovacion == "pendiente") {
            $renovacionpendiente = true;
        } else {
            $renovacionpendiente = false;
        }
        //Si los datos del restaurante tienen un mensaja
        if (!empty($datosrest->mensajemembresia)) {
            $haymensaje = true;
            $mensaje = $datosrest->mensajemembresia;
        }


    } else {

        $errorMostrarDatos = true;

    }


    //Si datosrest no es nulo
    if (!is_null($datosrest)) {

        //Verificamos si el restaurante tiene una membresia pendiente
        if ($datosrest->estadorenovacion == "valida") {
            $renovacionaceptada = true;
            $haymensaje = false;
        } else {
            $renovacionaceptada = false;
        }


    } else {

        $errorMostrarDatos = true;

    }

} catch (Exception $e) {

    echo 'Excepción capturada: ', $e->getMessage(), "\n";
}



require_once('views/restaurante_perfil_view.php');













?>