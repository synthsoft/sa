<?php

//inicio de sesion
session_start();

class turista_restaurante_controller
{

    //Creamos resenainstancia
    private $resenainstancia;

    private $promocionesinstancia;

    //Creamos el constructor

    public function __construct()
    {
        //Llamamos a resenas_model
        require_once('models/resena_model.php');

        //Creamos una instancia de la clase resena_model
        $this->resenainstancia = new resena_model();

        //Llamamos a promociones_model.php
        require_once('models/promociones_model.php');

        //Creamos una instancia de la clase promociones_model
        $this->promocionesinstancia = new promociones_model();


    }


    public function mostrarResenasPagina($paginaActual, $emailRest)
    {
        $cantidad_registros = 3;

        //Obtenemos el total de registros de la bd mediante la funcion obtenerTotalResenasRestaurante
        $total_registros = $this->resenainstancia->obtenerTotalResenasRestaurante($emailRest);

        //Calculamos el total de paginas
        $total_paginas = ceil($total_registros / $cantidad_registros);


        // Aseguramos que la página actual esté en un rango válido
        if ($paginaActual < 1) {
            $paginaActual = 1;
        } elseif ($paginaActual > $total_paginas) {
            $paginaActual = $total_paginas;
        }

        //Obtenemos los registros a mostrar segun la pagina actual
        $resenas = $this->resenainstancia->obtenerResenasPaginadasRestaurante($paginaActual, $cantidad_registros, $emailRest);


        //Devolver los resultados obtenidos y el total de paginas para crear la paginacion
        return array(

            'resenas' => $resenas,
            'total_paginas' => $total_paginas,
            'total_registros' => $total_registros

        );

    }

    public function mostrarPromocionesPagina($paginaActual, $emailRest)
    {
        $cantidad_registros = 3;

        //Obtenemos el total de registros de la bd mediante la funcion obtenerTotalResenasRestaurante
        $total_registros = $this->promocionesinstancia->obtenerTotalPromocionesActivas($emailRest);

        //Calculamos el total de paginas
        $total_paginas = ceil($total_registros / $cantidad_registros);


        // Aseguramos que la página actual esté en un rango válido
        if ($paginaActual < 1) {
            $paginaActual = 1;
        } elseif ($paginaActual > $total_paginas) {
            $paginaActual = $total_paginas;
        }

        //Obtenemos los registros a mostrar segun la pagina actual
        $promociones = $this->promocionesinstancia->obtenerPromocionesActivasPaginadas($paginaActual, $cantidad_registros, $emailRest);


        //Devolver los resultados obtenidos y el total de paginas para crear la paginacion
        return array(

            'promociones' => $promociones,
            'total_paginas' => $total_paginas,
            'total_registros' => $total_registros

        );

    }


}


//Llamamos al archivo donde se encuengtra la clase hospejde_model.php
require_once('models/hospedaje_model.php');

//Creamos una instancia de la clase hospedaje_model
$hospedajeinstancia = new hospedaje_model();

//Llamamos al archivo con la clase hotel_model.php
require_once('models/hotel_model.php');

//Creamos una instancia de la clase hotel_model
$hotelinstancia = new hotel_model();

//Declaramos la variable emailtur
$emailtur = $_SESSION['email'];

//Llamamos a restaurante_model.php
require_once('models/restaurante_model.php');

//Creamos una instancia de la clase restaurante_model
$restauranteinstancia = new restaurante_model();

//Llamamos a la clase resena_model.php
require_once('models/resena_model.php');

//Creamos una instancia de la clase resena_model
$resenainstancia = new resena_model();

//Llamamos a la clase asistencia_model.php
require_once('models/asistencia_model.php');

//Creamos una instancia de la clase asistencia_model
$asistenciainstancia = new asistencia_model();

//Llamamos a la clase horario_model.php
require_once('models/horario_model.php');

//Creamos una instancia de la clase horario_model
$horarioinstancia = new horario_model();

//llamamos a nacionalidad_model.php
require_once('models/nacionalidad_model.php');

//Creamos una instancia de la clase nacionalidad_model
$nacionalidadinstancia = new nacionalidad_model();

//Creamos una instancia de la clase actual
$instanciaclase = new turista_restaurante_controller();

//Creamos una instancia de usuario_model
$usuariomodel = new usuario_model();

$validacionasistencia = NULL;


//Validamos que se este logueado sino se redirige al login
if (!isset($_SESSION['email'])) {

    header('Location: login_index.php');
    exit;

}

//Validamos que el usuario sea un turista o administrador sino se redirige al login
$datosusuario = $usuariomodel->obtenerDatosUsuario($_SESSION['email']);

//Verificamos que el estado del usuario sea activo
if ($datosusuario->estadologico != 'activo') {

    header('Location: login_index.php');
    exit;

}


if ($datosusuario->rol != 'tur') {

    header('Location: login_index.php');
    exit;

}

$emailtur = $_SESSION['email'];



//Si el usuario esta logueado y se le da click al boton restaurante se llama a la vista restaurante_turista_view.php
if (isset($_GET['action']) && $_GET['action'] === 'mostrarRestaurante' && isset($_GET['emailrest'])) {

    //Obtenemos los datos del hopedaje del turista
    $datosHospedaje = $hospedajeinstancia->obtenerDatosHospedajeEnCurso($emailtur);

    //Obtenemos los datos del restaurante
    $datosrestaurante = $restauranteinstancia->obtenerRestaurante($_GET['emailrest']);

    //Obtenemos los telefonos del restaurante con la  funcion obtenerTelefonosRestaurante
    if (isset($datosrestaurante->telefono1)) {
        $telefono1 = $datosrestaurante->telefono1;
    } else {
        $telefono1 = "No hay telefono";
    }

    //Obtenemos los telefonos del restaurante con la  funcion obtenerTelefonosRestaurante
    if (isset($datosrestaurante->telefono2)) {
        $telefono2 = $datosrestaurante->telefono2;
    } else {
        $telefono2 = "No hay telefono";
    }

    //Obtenemos los horarios del restaurante con la funcion obtenerHorariosRestauranteTurista
    $horariosrestaurante = $horarioinstancia->obtenerHorariosRestauranteTurista($_GET['emailrest']);

    //Verificamos que no haya devuelto un array vacio
    if (empty($horariosrestaurante)) {

        //Devolvemos true en mensaje de error para mostrarlo en la vista
        $horariosRestauranteError = true;
    }

    //Validamos si el restaurante esta abierto o cerrado mediante la funcion abiertoCerrado
    $abiertocerrado = $horarioinstancia->abiertoCerrado($_GET['emailrest']);

    //Validamos que no haya dado error la funcion abiertoCerrado
    if ($abiertocerrado == false) {

        //Devolvemos true en mensaje de error para mostrarlo en la vista
        $abiertoCerradoError = true;

    }

    //Obtenemos la ruta del pdf del menu
    $rutapdf = $datosrestaurante->rutapdf;

    //Obtenemos la fotoperfil del restaurante
    $fotoperfil = $datosrestaurante->fotoperfilologo;

    //obtenemos las nacionalidades del restaurante con la funcion obtenerNacionalidadesRestaurante
    $nacionalidadresultado = $nacionalidadinstancia->obtenerNacionalidadRestaurante($_GET['emailrest']);

    $nacionalidad = isset($nacionalidadresultado->nacionrestaurante) ? $nacionalidadresultado->nacionrestaurante : "Error al cargar la nacionalidad";

    $tipocomida = isset($datosrestaurante->tipocomida) ? $datosrestaurante->tipocomida : "Error al cargar el tipo de comida";

    $nombrerestaurante = isset($datosrestaurante->nombrerestaurante) ? $datosrestaurante->nombrerestaurante : "Error al cargar el nombre del restaurante";

    $direccionrestaurante = isset($datosrestaurante->dirrestaurante) ? $datosrestaurante->dirrestaurante : "Error al cargar la direccion del restaurante";

    $descripcionrestaurante = isset($datosrestaurante->descrrestaurante) ? $datosrestaurante->descrrestaurante : "Error al cargar la descripcion del restaurante";

    $emailrest = $_GET['emailrest'];

    //Obtenemos la pagina actual
    if (isset($_GET['paginaResenas'])) {
        $paginaActualResenas = $_GET['paginaResenas'];
    } else {
        $paginaActualResenas = 1;
    }

    //Obtenemos la pagina actual
    if (isset($_GET['paginaPromociones'])) {
        $paginaActualPromociones = $_GET['paginaPromociones'];
    } else {
        $paginaActualPromociones = 1;
    }


    //Llamamos a la funcion verficarSiHayAsistenciaPrevia
    $asistenciaprevia = $asistenciainstancia->verificarSiHayAsistenciaPrevia($emailtur, $emailrest, $datosHospedaje->finiciohospedaje);

    //Si hay una asistencia previa, llamamos a la funcion obtenerDatosAsistencia
    if ($asistenciaprevia == true) {

        $validacion = $asistenciainstancia->obtenerDatosAsistencia($emailtur, $emailrest, $datosHospedaje->finiciohospedaje);

        //Guardamos la validacion de la asistencia en una variable, para saber si el turista ya ha realizado una solicitud
        //y reflejarlo en el boton HTML
        $validacionasistencia = $validacion['validaresena'];

    }

    //Si se le da click al boton de solicitar asistencia o ver resena
    if (isset($_POST['resenas'])) {

        //si hay una asistencia previa, llamamos a la funcion obtenerDatosAsistencia
        if ($asistenciaprevia == false) {

            //Obtenemos la fecha de inicio del hospedaje

            $finiciohospedaje = $datosHospedaje->finiciohospedaje;

            //Creamos una solicitud dFe asistencia
            $nuevaasistencia = $asistenciainstancia->solicitarAsistencia($emailtur, $_GET['emailrest'], $finiciohospedaje);

            $datosasistencia = $asistenciainstancia->obtenerDatosAsistencia($emailtur, $_GET['emailrest'], $finiciohospedaje);

            $validacionasistencia = $datosasistencia['validaresena'];


        }
        //refrescamos la pagina
        header('Location: restaurante_turista_index.php?action=mostrarRestaurante&emailrest=' . $_GET['emailrest']);
        exit;
    }

    //Si enviarResenaTur es presionado llamamos a la funcion enviarResena
    if (isset($_POST['enviarResenaTur'])) {

        //Obtenemos los datos del formulario
        $emailtur = $_SESSION['email'];
        $emailrest = $_GET['emailrest'];
        $restaurantegral = $_POST['calificacionRG'];
        $instalaciones = $_POST['calificacionI'];
        $menugastronomico = $_POST['calificacionMG'];
        $atenciondelpersonal = $_POST['calificacionAP'];

        //Enviamos los datos del formulario a la funcion enviarResena
        $resenaenviada = $resenainstancia->enviarResena($emailtur, $emailrest, $restaurantegral, $instalaciones, $menugastronomico, $atenciondelpersonal);


        //Si la reseña se envio correctamente, refrescamos la pagina
        if ($resenaenviada == true) {

            header('Location: restaurante_turista_index.php?action=mostrarRestaurante&emailrest=' . $_GET['emailrest']);
            exit;

        }

    }


    $verificarresenaanteriior = $resenainstancia->verificarSiHayResenaPrevia($emailtur, $emailrest);

    //Obtenemos los datos de LA PAGINACION de las resenas
    $resultados = $instanciaclase->mostrarResenasPagina($paginaActualResenas, $emailrest);

    $resenasPaginadas = $resultados['resenas'];

    $total_paginas_resenas = $resultados['total_paginas'];

    $total_registros_resenas = $resultados['total_registros'];

    $resenaturista = $resenainstancia->obtenerResenaRestaurante($emailrest, $emailtur);

    //Obtenemos los datos de LA PAGINACION de las promociones
    $resultadospromociones = $instanciaclase->mostrarPromocionesPagina($paginaActualPromociones, $emailrest);

    $promociones = $resultadospromociones['promociones'];

    $total_paginas_promociones = $resultadospromociones['total_paginas'];

    $total_registros_promociones = $resultadospromociones['total_registros'];


    require_once('views/restaurante_turista_view.php');

}



?>