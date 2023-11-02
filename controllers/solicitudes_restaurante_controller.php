<?php

//INICIO DE SESSION
session_start();

//Creamos la clase solicitudes_restaurante_controller
class solicitudes_restaurante_controller
{

    protected $asistenciainstancia;

    public function __construct()
    {

        //LLamamos al archivo donde se encuentra la clase asistencia_model.php
        require_once("models/asistencia_model.php");

        //Creamos una instancia de la clase asistencia_model
        $this->asistenciainstancia = new asistencia_model();

    }


    public function mostrarSolicitudessPagina($emailrest, $paginaActual)
    {
        $cantidad_registros = 8;

        //Obtenemos el total de registros de la bd mediante la funcion obtenerTotalRestaurantesParaTurista
        $total_registros = $this->asistenciainstancia->obtenerTotalSolicitudesParaRestaurantes($emailrest);

        //Calculamos el total de paginas
        $total_paginas = ceil($total_registros / $cantidad_registros);


        // Aseguramos que la página actual esté en un rango válido
        if ($paginaActual < 1) {
            $paginaActual = 1;
        } elseif ($paginaActual > $total_paginas) {
            $paginaActual = $total_paginas;
        }

        //Obtenemos los registros a mostrar segun la pagina actual

        $solicitudes = $this->asistenciainstancia->obtenerSolicitudesPaginadosParaRestaurantes(
            $paginaActual,
            $cantidad_registros,
            $emailrest
        );

        //Devolver los resultados obtenidos y el total de paginas para crear la paginacion
        return array(

            'solicitudes' => $solicitudes,
            'total_paginas' => $total_paginas,
            'total_registros' => $total_registros


        );



    }

    public function mostrarSolicitudessPaginaBusqueda($emailrest, $paginaActual, $busqueda)
    {
        $cantidad_registros = 5;

        //Obtenemos el total de registros de la bd mediante la funcion obtenerTotalRestaurantesParaTurista
        $total_registros = $this->asistenciainstancia->buscarTotalSolicitudesParaRestaurantes($emailrest, $busqueda);

        //Calculamos el total de paginas
        $total_paginas = ceil($total_registros / $cantidad_registros);


        // Aseguramos que la página actual esté en un rango válido
        if ($paginaActual < 1) {
            $paginaActual = 1;
        } elseif ($paginaActual > $total_paginas) {
            $paginaActual = $total_paginas;
        }

        //Obtenemos los registros a mostrar segun la pagina actual

        $solicitudes = $this->asistenciainstancia->buscarSolicitudesPaginadasParaRestaurantes($emailrest, $busqueda, $paginaActual, $cantidad_registros);

        //Devolver los resultados obtenidos y el total de paginas para crear la paginacion
        return array(

            'solicitudes' => $solicitudes,
            'total_paginas' => $total_paginas,
            'total_registros' => $total_registros


        );



    }

}

//Creamos una instancia de la clase solicitudes_restaurante_controller
$instanciaclase = new solicitudes_restaurante_controller();

//Creamos asistenciainstancia
$asistenciainstancia = new asistencia_model();

//Llamamos a usuario_model.php
require_once("models/usuario_model.php");

//Creamos una instancia de la clase usuario_model
$usuariomodel = new usuario_model();

//Llammaos a verificacion_de_usuarios_model.php
require_once("models/verificacion_de_usuarios_model.php");

//Creamos una instancia de la clase verificacion_de_usuarios_model
$verificacionmodel = new verificacionUsuarios();


//Verificamos si hay una sesion iniciada, si no la hay redirigimos al login
if (!isset($_SESSION['email'])) {
    //Redirijimos al login
    header("Location: login_index.php");
    exit;

}

//Obtenemos el email del restaurante
$emailrest = $_SESSION['email'];

//Verificamos que el usuario este activo logicamnete, la funcion hace todo 
//Por si sola, no precisa valida nada en controller
$verificacionmodel->verificarEstadoLogico($emailrest);

//Verificamos que el usuario sea un restaurante
$verificacionmodel->verificarRolRestaurante($emailrest);


//------------------OBTENEMOS LOS DATOS PARA LA PAGINACION------------------

//Obtenemos la pagina actual
if (isset($_GET['pagina'])) {
    $paginaActual = $_GET['pagina'];
} else {
    $paginaActual = 1;
}

//Obtenemos los datos de las solicitudes para la pagina actual
$resultados = $instanciaclase->mostrarSolicitudessPagina($emailrest, $paginaActual);

$solicitudes = $resultados['solicitudes'];

$total_paginas = $resultados['total_paginas'];

$total_registros = $resultados['total_registros'];


//SI se presiono el boton de buscar entonces obtenemos los datos de la busqueda
//Y remplazamos las variables de paginacion con los resultados de la busqueda
if (isset($_GET['search-button'])) {

    $busqueda = $_GET['buscarRestoran'];

    $resultados = $instanciaclase->mostrarSolicitudessPaginaBusqueda($emailrest, $paginaActual, $busqueda);

    $solicitudes = $resultados['solicitudes'];

    $total_paginas = $resultados['total_paginas'];

    $total_registros = $resultados['total_registros'];

    $seBuscoAlgo = true;

}


//Si hay una solicitud del serividor SERVER REQUEST y el boton para aceptarla es presionado
//obtenemos las variables del formulario y nada mas por el momento
if (isset($_POST['aceptarSolicitud'])) {

    //Obtenemos las variables
    $emailtur = $_POST['emailTur'];

    $resultado = $asistenciainstancia->aceptarSolicitud($emailrest, $emailtur);

    //Validamos que el resultado sea correcto, sino recargamos pero sin mostrar nada
    if ($resultado) {

        header("Refresh:0");
        exit;

    } else {

        header("Refresh:0");
        exit;

    }

}

require_once("views/solicitudes_restaurante_view.php");

?>