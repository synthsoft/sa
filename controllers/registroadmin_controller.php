<?php
session_start();

class registro_admin_controller
{

    public function __construct()
    {
    }

    //Creamos la funcion para hacer las validaciones de los datos ingresados por el usuario
    public function validarEntradas($email, $contrasena1, $contrasena2, $alias)
    {

        //Limpiamos con htmlspecialchars que convierte caracteres especiales en entidades HTML
        $email = htmlspecialchars($email);
        $contrasena1 = htmlspecialchars($contrasena1);
        $contrasena2 = htmlspecialchars($contrasena2);
        $alias = htmlspecialchars($alias);

        //Limpiamos con trim que elimina espacios en blanco u otros caracteres al inicio y final de una cadena de texto
        $email = trim($email);
        $contrasena1 = trim($contrasena1);
        $contrasena2 = trim($contrasena2);
        $alias = trim($alias);

        //Verficamos si el email es válido y no tiene mas de 255 caracteres
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 255) {

            //guardamos en sesion un mensaje de error
            $_SESSION['mensajeerror'] = "El email ingresado no es válido";

            header('Location: registro_admin_index.php');
            exit;
        }

        //Verificamos que le alias no tenga mas de 50 caracteres
        if (strlen($alias) > 50) {

            //guardamos en sesion un mensaje de error
            $_SESSION['mensajeerror'] = "El alias no puede tener tantos caracteres";


            header('Location: registro_admin_index.php');
            exit;
        }

        //Verificamos que la contraseña no tenga mas de 255 caracteres
        if (strlen($contrasena1) > 255) {

            //guardamos en sesion un mensaje de error
            $_SESSION['mensajeerror'] = "La contraseña no puede tener tantos caracteres";

            header('Location: registro_admin_index.php');
            exit;
        }

        //Verificamos que la contraseña no tenga espacio en blanco
        if (strpos($contrasena1, ' ') !== false) {

            //guardamos en sesion un mensaje de error
            $_SESSION['mensajeerror'] = "La contraseña no puede tener espacios en blanco";

            header('Location: registro_admin_index.php');
            exit;
        }

        //Verificamos si las contraseñas coinciden
        if ($contrasena1 != $contrasena2) {

            //guardamos en sesion un mensaje de error
            $_SESSION['mensajeerror'] = "Las contraseñas no coinciden";

            header('Location: registro_admin_index.php');
            exit;

        }

        return true;

    }

}


//Verificamos que el usuario haya iniciado sesion
if (!isset($_SESSION['email'])) {

    //Si no hay sesion iniciada, redireccionamos al login
    header('Location: login_index.php');
    exit;
}

//Llamamos al controlador para validar los datos ingresados por el usuario
$registroadmincontrollerinstancia = new registro_admin_controller();

//Llamamos al modelo administrador_model.php
require_once('models/administrador_model.php');
$administradormodelinstancia = new administrador_model();

//Llamamos al modelo usuarios_model.php
require_once('models/usuario_model.php');
$usuariomodelinstancia = new usuario_model();

//Llamamos al modelo verificacion_de_usuarios_model.php
require_once('models/verificacion_de_usuarios_model.php');
$verificacionmodelinstancia = new verificacionUsuarios();

//Verificamos que el usuario este activo logicamente
$verificacionmodelinstancia->verificarEstadoLogico($_SESSION['email']);

//Verificamos que el usuario sea administrador
$verificacionmodelinstancia->verificarRolAdministrador($_SESSION['email']);


//Verificamos si el usuario presionó el botón Login

if (isset($_POST['crearAdmin'])) {

    // Obtener el nombre ingresado en el formulario
    $email = $_POST['email'];
    $contrasena1 = $_POST['password1'];
    $contrasena2 = $_POST['password2'];
    $alias = $_POST['alias'];

    //Llamamos a la funcion para validar los datos ingresados por el usuario
    $resultado = $registroadmincontrollerinstancia->validarEntradas($email, $contrasena1, $contrasena2, $alias);

    //Verificamos si el usuario existe en la BD
    $usuarioexiste = $usuariomodelinstancia->verificarEmail($email);

    //Si el usuario existe en la BD mostramos un mensaje de error
    if ($usuarioexiste == true) {

        //guardamos en sesion un mensaje de error
        $_SESSION['mensajeerror'] = "El usuario ya existe";

        header('Location: registro_admin_index.php');
        exit();

    } else {

        //Si el usuario no existe en la BD, lo registramos como usuario primero
        $registroexitoso = $usuariomodelinstancia->registroUsuario($email, $contrasena1, $alias, null, 'admin', "activo");

        //Si el registro fue exitoso, registramos al usuario como administrador
        if ($registroexitoso == true) {

            //Registramos al usuario como administrador
            $registroexitosoadmin = $administradormodelinstancia->registroUsuarioAdministrador($email);

            //Si el registro fue exitoso, mostramos un mensaje de exito
            if ($registroexitosoadmin == true) {

                unset($_SESSION['mensajeerror']);

                //mostramos un mensaje de que el usuario se registró exitosamente
                $_SESSION['mensajeexito'] = "El usuario se registró exitosamente como administrador";

                header('Location: registro_admin_index.php');
                exit();
            } else {

                //guardamos en sesion un mensaje de error
                $_SESSION['mensajeerror'] = "Error al registrar el usuario como administrador";
            }
        }

    }





}

require_once("views/registrar_admin_view.php");



?>