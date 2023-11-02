<?php
//Inicializamos la sesion
session_start();

class turista_perfil_controller
{
    protected $hospedajeinstancia;
    protected $resenainstancia;
    protected $usuarioinstancia;

    //Creo el metodo constructor
    public function __construct()
    {
        //Llamamos al modelo de hospedaje
        require_once('models/hospedaje_model.php');

        //Creamos una instancia de la clase hospedaje_model
        $this->hospedajeinstancia = new hospedaje_model();

        //Llamamos al modelo de resena
        require_once('models/resena_model.php');

        //Creamos una instancia de la clase resena_model
        $this->resenainstancia = new resena_model();

        //Llamamos al modelo de usuario
        require_once('models/usuario_model.php');

        //Creamos una instancia de la clase usuario_model
        $this->usuarioinstancia = new usuario_model();

    }


    public function mostrarHospedajesPagina($paginaActual, $emailtur)
    {
        $cantidad_registros = 4;

        //Obtenemos el total de registros de la bd mediante la funcion obtenerHospedajesPagina
        $total_registros = $this->hospedajeinstancia->obtenerTotalHospedajes($emailtur);

        //Calculamos el total de paginas
        $total_paginas = ceil($total_registros / $cantidad_registros);

        // Aseguramos que la página actual esté en un rango válido
        if ($paginaActual < 1) {
            $paginaActual = 1;
        } elseif ($paginaActual > $total_paginas) {
            $paginaActual = $total_paginas;
        }

        $hospedajes = $this->hospedajeinstancia->obtenerHospedajesPaginados(
            $paginaActual,
            $cantidad_registros,
            $emailtur
        );

        return array(
            'hospedajes' => $hospedajes,
            'total_paginas' => $total_paginas,
            'pagina_actual' => $paginaActual
        );



    }

    public function mostrarResenasPagina($paginaActual, $emailtur)
    {
        $cantidad_registros = 4;

        //Obtenemos el total de registros de la bd mediante la funcion obtenerHospedajesPagina
        $total_registros = $this->resenainstancia->obtenerTotalResenasTurista($emailtur);

        //Calculamos el total de paginas
        $total_paginas = ceil($total_registros / $cantidad_registros);

        // Aseguramos que la página actual esté en un rango válido
        if ($paginaActual < 1) {
            $paginaActual = 1;
        } elseif ($paginaActual > $total_paginas) {
            $paginaActual = $total_paginas;
        }

        $resenas = $this->resenainstancia->obtenerResenasPaginadasTurista(
            $paginaActual,
            $cantidad_registros,
            $emailtur
        );

        return array(
            'resenas' => $resenas,
            'total_paginas' => $total_paginas,
            'pagina_actual' => $paginaActual
        );



    }

    public function verificarContrasenas($contrasena1, $contrasena2, $contrasena3, $emailtur, $baja, $admin = '')
    {

        //Limpiamos con trim y strip_tags
        $contrasena1 = trim(strip_tags($contrasena1));
        $contrasena2 = trim(strip_tags($contrasena2));
        $contrasena3 = trim(strip_tags($contrasena3));

        //Y con htmlspecialchars
        $contrasena1 = htmlspecialchars($contrasena1);
        $contrasena2 = htmlspecialchars($contrasena2);
        $contrasena3 = htmlspecialchars($contrasena3);

        //SI el admin est true significa que no se pide la contraseña actual le ponemos cualquier cosa
        if ($admin === true) {
            $contrasena3 = "noseusa";
        }

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
        $contrasenaExiste = $this->usuarioinstancia->obtenerDatosUsuario($emailtur);

        if ($baja == true) {
            //Verificamos si la contraseña es igual a la que ya estaba
            if (password_verify($contrasena1, $contrasenaExiste->contrasena)) {

                return true;
            }
        }

        if ($admin !== true) {
            //Verificamos si la contraseña es igual a la que ya estaba
            if (password_verify($contrasena3, $contrasenaExiste->contrasena)) {
                return true;
            } else {
                return false;
            }
        }
        return true;

    }

}



//Instanciamos el controlador
$turista_perfil_controller = new turista_perfil_controller();

//Instanciamos el modelo hospedaje
require_once('models/hospedaje_model.php');

//Instanciamos un objeto de la clase hospedaje_model
$hospedajeinstancia = new hospedaje_model();

//Instanciamos el modelo usuario
require_once('models/usuario_model.php');

//Instanciamos un objeto de la clase usuario_model
$usuariomodelinstancia = new usuario_model();

//LLamamos a verificacion_de_usuarios_model.php
require_once('models/verificacion_de_usuarios_model.php');

//Creamos una instancia de la clase verificacion_de_usuarios_model.php
$verificacionusuariosinstancia = new verificacionUsuarios();


if (!isset($_SESSION['email'])) {
    header('Location: login_index.php');
    exit;
}



//Validamos que el usuario sea un turista o administrador sino se redirige al login
$datosusuario = $usuariomodelinstancia->obtenerDatosUsuario($_SESSION['email']);


//Verificamos si el usuario esta dado de baja
$verificacionusuariosinstancia->verificarEstadoLogico($_SESSION['email']);

//Ponemos usuario admin false para que este seteado y no de error en la vista
$usuarioadmin = false;

//Seteamos el emailtur con el email de la sesion
$emailtur = $_SESSION['email'];

if ($datosusuario->rol == 'admin') {

    $usuarioadmin = true;

    //Recogemos el get emailtur
    if (isset($_GET['emailtur'])) {

        //SI el usuario es admin recogemos por get el emailtur
        //y modificamos la variable emailtur par que obtenga ese valor
        //Limpiamos con trim y strip_tags
        $emailtur = trim(strip_tags($_GET['emailtur']));

        //Y con htmlspecialchars
        $emailtur = htmlspecialchars($emailtur);

        //Verificamos que no este vacio o no seteado
        if (empty($emailtur) || !isset($emailtur) || $emailtur == '') {

            //Redirigimos a la pagina de administrador
            echo "<script>window.location.href='admin_index.php'</script>";
            exit;

        }

    }

}

$datosuser = $usuariomodelinstancia->obtenerDatosUsuario($emailtur);

//SI datos user es false significa que hubo un error bastante grave, enviamos a error.html
if ($datosuser === false) {
    echo "<script>window.location.href='error.html'</script>";
    exit;
}


$roluser = isset($datosuser->rol) ? $datosuser->rol : '';

$alias = isset($datosuser->alias) ? $datosuser->alias : '';

//Verificampos mediante la funcion verificarRolTurista si el usuario, ya sea obtenido
//por get o el de la sesion si es un turista es efectivamente un turista
//la funcion ya previene errores y esta validada, por eso aca no tocamos nada
//mas que llamarla

$verificacionusuariosinstancia->verificarRolTurista($emailtur);

//Declacramose esta variable que se usa en la vista
//para que si no hay un hospedaje en curso no mustre todo lo relacionado al
//mismo y solo muestre el historial

$nohayhospedajeencurso = null;

//Obtenemos los datos del hospedje actual del turista
$hospedaje = $hospedajeinstancia->obtenerDatosHospedajeEnCurso($emailtur);

//Si se vencio la fecha de fin del hospedaje, se elimina el hospedaje y pide un ingresar un nuevo hotel
if ($hospedaje == "nohayhospedaje") {

    $nohayhospedajeencurso = true;

} else {
    //Si devolvio false enviamos a error.html
    if ($hospedaje == false) {

        //Redirigimos a la pagina de error
        echo "<script>window.location.href='error.html'</script>";
        exit;

    }
}

//SI se envio el formulario para cambiar datos del usuario lo gestionamos aca
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitCambiosUser'])) {

    $contrasena1 = $_POST['contrasena1'];
    $contrasena2 = $_POST['contrasena2'];
    $aliasform = $_POST['alias'];
   
    //si es admin no se pide la contraseña actual y el campo queda null
    $contrasena3 = isset($_POST['contrasenaactual']) ? $_POST['contrasenaactual'] : null;

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
            $usuariomodelinstancia->actualizarAliasUsuario($emailtur, $aliasform);
            //Ponemos mensaje de exito
            $aliasMensaje = 'El alias se actualizo correctamente';
            //Guardamos el mensaje en una variable de sesion
            $_SESSION['aliasMensaje'] = $aliasMensaje;


        }
    }

    //Si al menos un campo de contraseña esta seteado o con algo escrito
    //Llamamos a la funcion verificarContrasenas

    if ($contrasena1 != '' || $contrasena2 != '' || !empty($contrasena1) || !empty($contrasena2)) {

        if (isset($usuarioadmin) && $usuarioadmin === true) {

            $resultadoContra = $turista_perfil_controller->verificarContrasenas($contrasena1, $contrasena2, $contrasena3, $emailtur, false, true);
        } else {
            $resultadoContra = $turista_perfil_controller->verificarContrasenas($contrasena1, $contrasena2, $contrasena3, $emailtur, false);
        }


        if (isset($resultadoContra) && $resultadoContra !== false) {

            //LLamamos a la funcion actualizarContrasenaUsuario
            $resultado = $usuariomodelinstancia->actualizarContrasenaUsuario($emailtur, $contrasena1);

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

    //Si es admin redirigimos a turista_perfil_index.php pero con el get emailtur
    if (isset($usuarioadmin) && $usuarioadmin === true) {

        header('Location: turista_perfil_index.php?emailtur=' . $emailtur);
        exit;
    } else {
        //Redirigimos a turista_perfil_index.php
        header('Location: turista_perfil_index.php');
        exit;
    }


}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['seEnvioEliminar'])) {


    if (isset($usuarioadmin) && $usuarioadmin == true) {

        //LLamamos a la funcion actualizarContrasenaUsuario
        $resultado = $usuariomodelinstancia->bajaLogicaUsuario($emailtur);

        //Si la contraseña se actualizo correctamente
        if ($resultado) {

            //Redirigimos a la pagina de admin
            header('Location: admin_index.php');
            exit;


        } else {
            //Ponemos mensaje de error
            $contrasenaMensaje = 'La cuenta no se pudo eliminar';
            //Lo guardamos en una variable de sesion
            $_SESSION['contrasenaMensajeBaja'] = $contrasenaMensaje;


        }

    }

    //Obtenemos las contrasenas
    $contrasena1 = $_POST['contrasenaeliminar1'];
    $contrasena2 = $_POST['contrasenaeliminar2'];


    $resultadoContra = $turista_perfil_controller->verificarContrasenas($contrasena1, $contrasena2, "noseusa", $emailtur, true);

    if (isset($resultadoContra) && $resultadoContra !== false) {

        //LLamamos a la funcion actualizarContrasenaUsuario
        $resultado = $usuariomodelinstancia->bajaLogicaUsuario($emailtur);

        //Si el usuario se dio de baja correctamente
        if ($resultado) {


            //Redirigimos al login
            header('Location: login_index.php');
            session_destroy();
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

if (isset($_POST['cancelaEstadiabtn'])) {
    //Llamaamos a la funcion bajaEstadia
    $resultado = $hospedajeinstancia->bajaEstadia($emailtur);

    //Si la estadia se cancelo correctamente redirijimos a hotel_turista_index.php
    //Sino guardamos un mendaje de error en una variable de sesion
    if ($resultado) {

        //Si es un admin, refrescamos esta misma pagina con  parametro get
        if (isset($usuarioadmin) && $usuarioadmin == true) {

            header('Location: turista_perfil_index.php?emailtur=' . $emailtur);
            exit;
        }
        //SI es turista lo enviamos a hotel_turista_index.php
        //Para que ingrese un nuevo alojamiento
        header('Location: hotel_turista_index.php');
        exit;

    } else {
        //Ponemos mensaje de error
        $estadiaMensaje = 'La estadia no se pudo cancelar';
        //Lo guardamos en una variable de sesion
        $_SESSION['estadiaMensaje'] = $estadiaMensaje;
        //redirijimos a hotel_turista_index.php
        header('Location: turista_perfil_index.php');
        exit;
    }


}


//Obtenemos los datos del hospedaje en curso del turista para mostrarlos en la pagina
$hospedajeencurso = $hospedajeinstancia->obtenerDatosHospedajeEnCurso($emailtur);

//LE PONEMOS UNA CONDICION QUE SI ESTA NULL O VACIO EL VALOR SEA FALSE
$nombrehotel = isset($hospedajeencurso->nombrehotel) ? $hospedajeencurso->nombrehotel : null;

$finiciohospedaje = isset($hospedajeencurso->finiciohospedaje) ? $hospedajeencurso->finiciohospedaje : null;

$ffinhospedaje = isset($hospedajeencurso->ffinhospedaje) ? $hospedajeencurso->ffinhospedaje : null;



//Obtenemos datos para el paginado de los hospedajes 

//Obtenemos la pagina actual para mostrar los hospedajes
if (isset($_GET['paginaEstadias'])) {
    $paginaActualEstadias = $_GET['paginaEstadias'];
} else {
    $paginaActualEstadias = 1;
}

//Esta funcion, en caso de error devuelve un array vacio, para que no salte un 
//error PDOException
$resultadosEstadias = $turista_perfil_controller->mostrarHospedajesPagina($paginaActualEstadias, $emailtur);

$hospedajes = $resultadosEstadias['hospedajes'];

$total_paginasEstadias = $resultadosEstadias['total_paginas'];


//Obtenemos datos para el paginado de las resenas

//Obtenemos la pagina actual para mostrar las resenas
if (isset($_GET['paginaResenas'])) {
    $paginaActualResenas = $_GET['paginaResenas'];
} else {
    $paginaActualResenas = 1;
}

//Esta funcion, en caso de error devuelve un array vacio, para que no salte un 
//error PDOException
$resultadosResenas = $turista_perfil_controller->mostrarResenasPagina($paginaActualResenas, $emailtur);

$resenas = $resultadosResenas['resenas'];

$total_paginasResenas = $resultadosResenas['total_paginas'];

//Llamamos a la vista turista_perfil_view.php con require once
require_once('views/turista_perfil_view.php');

?>