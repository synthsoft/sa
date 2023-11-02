<?php

class pagina_admin_controller
{
    //Atributos
    private $usuariomodelinstancia;
    private $administradormodelinstancia;
    private $restaurantemodelinstancia;
    private $turistamodelinstancia;

    //Metodos

    //Constructor
    function __construct()
    {

        //Llamamos a la clase usuario
        require_once('models/usuario_model.php');
        $this->usuariomodelinstancia = new usuario_model();

        //Llamamos a la clase administrador
        require_once('models/administrador_model.php');
        $this->administradormodelinstancia = new administrador_model();

        //Llamamos a la clase restaurante
        require_once('models/restaurante_model.php');
        $this->restaurantemodelinstancia = new restaurante_model();

        //Llamamos a la clase turista
        require_once('models/turista_model.php');
        $this->turistamodelinstancia = new turista_model();

    }

    public function mostrarRestaurantesPagina($paginaActual, $busqueda = '')
    {
        $cantidad_registros = 8;

        //Obtenemos el total de registros de la bd mediante la funcion obtenerTotalRestaurantesParaAdmin
        $total_registros = $this->restaurantemodelinstancia->obtenerTotalRestaurantesParaAdmin($busqueda);

        //Calculamos el total de paginas
        $total_paginas = ceil($total_registros / $cantidad_registros);


        // Aseguramos que la página actual esté en un rango válido
        if ($paginaActual < 1) {
            $paginaActual = 1;
        } elseif ($paginaActual > $total_paginas) {
            $paginaActual = $total_paginas;
        }

        //Obtenemos los registros a mostrar segun la pagina actual

        $restaurantes = $this->restaurantemodelinstancia->obtenerRestaurantesPaginadosParaAdmin(
            $paginaActual,
            $cantidad_registros,
            $busqueda
        );


        //Devolver los resultados obtenidos y el total de paginas para crear la paginacion
        return array(

            'restaurantes' => $restaurantes,
            'total_paginas' => $total_paginas,
            'total_registros' => $total_registros

        );

    }

    public function mostrarTuristasPagina($paginaActual, $busqueda = '')
    {
        $cantidad_registros = 8;

        //Obtenemos el total de registros de la bd mediante la funcion obtenerTotalTuristasParaAdmin
        $total_registros = $this->turistamodelinstancia->obtenerTotalTuristasParaAdmin($busqueda);


        //Calculamos el total de paginas
        $total_paginas = ceil($total_registros / $cantidad_registros);


        // Aseguramos que la página actual esté en un rango válido
        if ($paginaActual < 1) {
            $paginaActual = 1;
        } elseif ($paginaActual > $total_paginas) {
            $paginaActual = $total_paginas;
        }

        //Obtenemos los registros a mostrar segun la pagina actual

        $turistas = $this->turistamodelinstancia->obtenerTuristasPaginadosParaAdmin(
            $paginaActual,
            $cantidad_registros,
            $busqueda
        );




        //Devolver los resultados obtenidos y el total de paginas para crear la paginacion
        return array(

            'turistas' => $turistas,
            'total_paginas' => $total_paginas,
            'total_registros' => $total_registros

        );

    }

}

//Verificamos si hay una sesion iniciada
session_start();

//CReamos una instancia de la clase pagina_admin_controller
//Que ademas en el constructor llama a las clases usuario, administrador, restaurante y turista
$paginaadmincontrollerinstancia = new pagina_admin_controller();

$administradormodelinstancia = new administrador_model();

$restaurantemodelinstancia = new restaurante_model();

$turistamodelinstancia = new turista_model();

//Llamamos a verificacion_de_usuarios_model para verificar si el usuario esta logueado
require_once('models/verificacion_de_usuarios_model.php');
//Creamos una instancia de la clase verificacion_de_usuarios_model
$verificaciondeusuariosmodelinstancia = new verificacionUsuarios();

//Verificamos si el usuario esta logueado

if (!isset($_SESSION['email'])) {
     //Si no hay sesion iniciada, redireccionamos al login
    header('Location: login_index.php');
    exit;
}

//Verificamos si el usuario esta logicamente activo
$verificaciondeusuariosmodelinstancia->verificarEstadoLogico($_SESSION['email']);

//Verificamos si el usuario es administrador
$verificaciondeusuariosmodelinstancia->verificarRolAdministrador($_SESSION['email']);


//-------OBTEMNEMOS LOS DATOS PARA LA PAGINACION RESTAURANTES-------

//Obtenemos la pagina actual
if (isset($_GET['paginaRestaurantes'])) {
    $paginaActualRest = $_GET['paginaRestaurantes'];
} else {
    $paginaActualRest = 1;
}

//LLamamos a la funcion mostrarRestaurantesPagina, la cual nos devuelve un arreglo con los resultados obtenidos y el total de paginas
$resultados = $paginaadmincontrollerinstancia->mostrarRestaurantesPagina($paginaActualRest);

//Obtenemos los resultados
$restaurantes = $resultados['restaurantes'];

//Obtenemos el total de paginas
$total_paginas_restaurante = $resultados['total_paginas'];

//Obtenemos el total de registros
$total_registros_restaurante = $resultados['total_registros'];


//-------OBTEMNEMOS LOS DATOS PARA LA PAGINACION TURISTAS-------

//Obtenemos la pagina actual
if (isset($_GET['paginaTuristas'])) {
    $paginaActualTur = $_GET['paginaTuristas'];
} else {
    $paginaActualTur = 1;
}

//LLamamos a la funcion mostrarTuristasPagina, la cual nos devuelve un arreglo con los resultados obtenidos y el total de paginas
$resultados = $paginaadmincontrollerinstancia->mostrarTuristasPagina($paginaActualTur);

//Obtenemos los resultados
$turistas = $resultados['turistas'];

//Obtenemos el total de paginas
$total_paginas_turista = $resultados['total_paginas'];

//Obtenemos el total de registros
$total_registros_turista = $resultados['total_registros'];


//Si el adminitrador busca un restaurante cambiames los resultados
if (isset($_POST['btnBuscarrest'])) {
    $busqueda = $_POST['busquedarestaurantes'];
    $resultados = $paginaadmincontrollerinstancia->mostrarRestaurantesPagina($paginaActualRest, $busqueda);

    //Obtenemos los resultados
    $restaurantes = $resultados['restaurantes'];

    //Obtenemos el total de paginas
    $total_paginas_restaurante = $resultados['total_paginas'];

    //Obtenemos el total de registros
    $total_registros_restaurante = $resultados['total_registros'];
}


//Si el adminitrador busca un turista cambiames los resultados
if (isset($_POST['btnBuscartur'])) {
    $busqueda = $_POST['busquedatur'];
    $resultados = $paginaadmincontrollerinstancia->mostrarTuristasPagina($paginaActualTur, $busqueda);

    //Obtenemos los resultados
    $turistas = $resultados['turistas'];

    //Obtenemos el total de paginas
    $total_paginas_restaurante = $resultados['total_paginas'];

    //Obtenemos el total de registros
    $total_registros_restaurante = $resultados['total_registros'];
}


//llamamaos a la vista de la pagina principal del administrador
require_once('views/administrador_view.php');

?>