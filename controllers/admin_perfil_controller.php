<?php
//Inicializamos la sesion
session_start();

class admin_perfil_controller
{

    protected $usuarioinstancia;

    //Creo el metodo constructor
    public function __construct()
    {
        //Llamamos al modelo de usuario
        require_once('models/usuario_model.php');

        //Creamos una instancia de la clase usuario_model
        $this->usuarioinstancia = new usuario_model();

    }


    public function verificarContrasenas($contrasena1, $contrasena2, $contrasena3, $emailadmin, $baja)
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
        $contrasenaExiste = $this->usuarioinstancia->obtenerDatosUsuario($emailadmin);

        if ($baja == true) {
            //Verificamos si la contraseña es igual a la que ya estaba
            if (password_verify($contrasena1, $contrasenaExiste->contrasena)) {

                return true;
            }
        }

        //Verificamos si la contraseña es igual a la que ya estaba
        if (password_verify($contrasena3, $contrasenaExiste->contrasena)) {
            return true;
        } else {
            return false;
        }


    }

}

//Instanciamos el controlador que a su vez instancia el modelo usuario
$admin_perfil_controller = new admin_perfil_controller();

//Instanciamos un objeto de la clase usuario_model
$usuariomodelinstancia = new usuario_model();

//Llamamos al archivo con el modelo verificacion_de_usuarios_model.php
require_once('models/verificacion_de_usuarios_model.php');

//Instanciamos un objeto de la clase verificacion_de_usuarios_model
$verificacionusuarios = new verificacionUsuarios();


//Verificamos que el usuario haya iniciado sesion
if (!isset($_SESSION['email'])) {
    header('Location: login_index.php');
    exit;
}

//Verificamos que el usuario este logicamente activo
$verificacionusuarios->verificarEstadoLogico($_SESSION['email']);

//Verificamos que el usuario sea administrador
$verificacionusuarios->verificarRolAdministrador($_SESSION['email']);

//Obtenemos el email del usuario y lo guardamos en una variable
$emailadmin = $_SESSION['email'];

//Obtenemos los datos del usuario para mostrarlos en el formulario
$datosuser = $usuariomodelinstancia->obtenerDatosUsuario($_SESSION['email']);

//SI no se pudo obtener los datos del usuario, lo dejamos en null a $alias con una condicion ternaria
$alias = $datosuser->alias != null ? $datosuser->alias : null;

//SI se presiono el boton de actualizar datos del usuario, lo gestionamos
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitCambiosUser'])) {

    $contrasena1 = $_POST['contrasena1'];
    $contrasena2 = $_POST['contrasena2'];
    $contrasenaactual = $_POST['contrasenaactual'];
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
            $usuariomodelinstancia->actualizarAliasUsuario($emailadmin, $aliasform);
            //Ponemos mensaje de exito
            $aliasMensaje = 'El alias se actualizo correctamente';
            //Guardamos el mensaje en una variable de sesion
            $_SESSION['aliasMensaje'] = $aliasMensaje;

        }
    }

    //Si al menos un campo de contraseña esta seteado o con algo escrito
    //Llamamos a la funcion verificarContrasenas

    if ($contrasena1 != '' || $contrasena2 != '' || !empty($contrasena1) || !empty($contrasena2)) {

        $resultadoContra = $admin_perfil_controller->verificarContrasenas($contrasena1, $contrasena2, $contrasenaactual, $emailadmin, false);

        if (isset($resultadoContra) && $resultadoContra !== false) {

            //LLamamos a la funcion actualizarContrasenaUsuario
            $resultado = $usuariomodelinstancia->actualizarContrasenaUsuario($emailadmin, $contrasena1);

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
    header('Location: admin_perfil_index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['seEnvioEliminar'])) {


    //Obtenemos las contrasenas
    $contrasena1 = $_POST['contrasenaeliminar1'];
    $contrasena2 = $_POST['contrasenaeliminar2'];
    $contrasenaactual = "noseusaenestecaso";

    $resultadoContra = $admin_perfil_controller->verificarContrasenas($contrasena1, $contrasena2, $contrasenaactual, $emailadmin, true);

    if (isset($resultadoContra) && $resultadoContra !== false) {

        //LLamamos a la funcion bajaLogicaUsuario
        $resultado = $usuariomodelinstancia->bajaLogicaUsuario($emailadmin);

        //Si se dio de baja correctamente
        if ($resultado) {

            //Redirigimos al login y destruimos la sesion
            session_destroy();
            header('Location: login_index.php');
            exit;

        } else {
            //Ponemos mensaje de error
            $contrasenaMensaje = 'La cuenta no se pudo eliminar';
            //Lo guardamos en una variable de sesion
            $_SESSION['contrasenaMensajeBaja'] = $contrasenaMensaje;

        }

    } else {
        //Llamamos al modal bootstrap data-bs-target="#eliminarCuenta"

        $contrasenaMensaje = 'Las contraseñas no coinciden, no tienen el formato adecuado o son iguales a las que ya estaba';
        //Lo guardamos en una variable de sesion
        $_SESSION['contrasenaMensajeBaja'] = $contrasenaMensaje;

        $_SESSION['errorBaja'] = "No se pudo dar la baja, verifique que las contraseñas sean correctas";

    }


}

require_once('views/admin_perfil_view.php');


?>