<?php

//Iniciamos la sesion
session_start();

//Creamos la clase turista_principal_controller
class turista_principal_controller
{

    //Creamos las variables que vamos a usar en la clase
    protected $db;
    protected $restauranteinstancia;
    //Creamos la funcion constructor
    public function __construct()
    {

        //Llamamos al archivo donde se encuentra la clase conectar
        //con el metodo que genera la conexion con la BD
        require_once("db/db.php");

        //Creamos una instancia de la clase "Conectar", y usamos el metodo "conexion"
        $this->db = Conectar::conexion();

        //Llamamos a restaurante_model.php
        require_once('models/restaurante_model.php');

        $this->restauranteinstancia = new restaurante_model();




    }


    //Creamos la funcion para mostrar los restaurantes en la vista turista_principal_view.php
    public function mostrarRestaurantesPagina($paginaActual, $nombrebarrio, $ciudad, $busqueda = '')
    {
        $cantidad_registros = 8;

        //Obtenemos el total de registros de la bd mediante la funcion obtenerTotalRestaurantesParaTurista
        $total_registros = $this->restauranteinstancia->obtenerTotalRestaurantesParaTurista($nombrebarrio, $ciudad, $busqueda);

        //Calculamos el total de paginas
        $total_paginas = ceil($total_registros / $cantidad_registros);

        // Aseguramos que la página actual esté en un rango válido
        if ($paginaActual < 1) {
            $paginaActual = 1;
        } elseif ($paginaActual > $total_paginas) {
            $paginaActual = $total_paginas;
        }

        //Obtenemos los registros a mostrar segun la pagina actual

        $restaurantes = $this->restauranteinstancia->obtenerRestaurantesPaginadosParaTurista(
            $paginaActual,
            $cantidad_registros,
            $nombrebarrio,
            $ciudad,
            $busqueda
        );

        //Devolver los resultados obtenidos y el total de paginas para crear la paginacion
        return array(
            'restaurantes' => $restaurantes,
            'total_paginas' => $total_paginas
        );



    }

    //Creamos una funcion para que los resultados de la busqueda
    //Se filtren en base a el tipo de restaurante
    public function mostrarRestaurantesPaginaPorTipo($paginaActual, $nombrebarrio, $ciudad, $tiporestaurante)
    {
        $cantidad_registros = 8;

        //Obtenemos el total de registros de la bd mediante la funcion obtenerTotalRestaurantesParaTurista
        $total_registros = $this->restauranteinstancia->obtenerTotalRestaurantesPorTipoParaTurista($nombrebarrio, $ciudad, $tiporestaurante);

        //Calculamos el total de paginas
        $total_paginas = ceil($total_registros / $cantidad_registros);

        // Aseguramos que la página actual esté en un rango válido
        if ($paginaActual < 1) {
            $paginaActual = 1;
        } elseif ($paginaActual > $total_paginas) {
            $paginaActual = $total_paginas;
        }

        //Obtenemos los registros a mostrar segun la pagina actual

        $restaurantes = $this->restauranteinstancia->obtenerRestaurantesPaginadosPorTipoParaTurista(
            $paginaActual,
            $cantidad_registros,
            $nombrebarrio,
            $ciudad,
            $tiporestaurante
        );

        //Devolver los resultados obtenidos y el total de paginas para crear la paginacion
        return array(
            'restaurantes' => $restaurantes,
            'total_paginas' => $total_paginas
        );



    }

}

//Instanciamos las clases para poder usarlas

$instanciaclase = new turista_principal_controller();

//Llamamos al archivo donde se encuengtra la clase hospejde_model.php
require_once('models/hospedaje_model.php');

//Creamos una instancia de la clase hospedaje_model
$hospedajeinstancia = new hospedaje_model();

//Llamamos al archivo con la clase hotel_model.php
require_once('models/hotel_model.php');

//Creamos una instancia de la clase hotel_model
$hotelinstancia = new hotel_model();

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

//Llamamos a usuario_model.php
require_once('models/usuario_model.php');

//Creamos una instancia de la clase usuario_model
$usuariomodel = new usuario_model();

//Llamamos a resena_model.php
require_once('models/resena_model.php');

//Creamos una instancia de la clase resena_model
$resenainstancia = new resena_model();

//Llamamos a la clase verificacion_de_usuarios_model.php
require_once('models/verificacion_de_usuarios_model.php');

//Creamos una instancia de la clase verificacion_de_usuarios_model
$verificacioninstancia = new verificacionUsuarios();

//Verificamos si el usuario esta logueado
if (!isset($_SESSION['email'])) {
    //Si el usuario no esta logueado lo enviamos a la pagina login_view.php
    header('Location: login_index.php');
    exit;
}

//Declaramos la variable emailtur
$usuariosesion = $_SESSION['email'];

//VEricamos que el usuario este activo
$verificacionEstadoLogico = $verificacioninstancia->verificarEstadoLogico($usuariosesion);

//Verificamos que el usuario sea un turista
$verificacionRolTurista = $verificacioninstancia->verificarRolTurista($usuariosesion);

//Definimos emailtur en el caso de que el usuario no sea administrador
$emailtur = $usuariosesion;

//Obtenemos los datos del hospedje actual del turista
$hospedaje = $hospedajeinstancia->obtenerDatosHospedajeEnCurso($emailtur);

//Si se vencio la fecha de fin del hospedaje, se elimina el hospedaje y pide un ingresar un nuevo hotel
if ($hospedaje == "nohayhospedaje") {


        //Redireccionamos a la pagina de hotel_turista_index.php
        header('Location: hotel_turista_index.php');
        exit;

} else {
    //Si devolvio false enviamos a error.html
    if ($hospedaje == false) {

        //Redirigimos a la pagina de error.html
        echo "<script>window.location.href='error.html'</script>";
        exit;

    }

}


//Obtenemos los datos para el PAGINATION


//Obtenemos la pagina actual
if (isset($_GET['pagina'])) {
    $paginaActual = $_GET['pagina'];
} else {
    $paginaActual = 1;
}


//Obtenemos el nombre del barrio del hospedaje actual del turista
$nombrebarrio = $hospedaje->nombrebarrio;

//Obtenemos la ciudad del hospedaje actual del turista
$ciudad = $hospedaje->ciudad;

//Llamamos a la funcion mostrarRestaurantesPagina, que muestra los restaurantes en la vista turista_principal_view.php
//Que estan en el mismo barrio y ciudad que el hospedaje actual del turista
//Ya tiene prevencion de errores porque la funcion si
//devuevle false en el caso de error, devuelve un array vacio, lo que al validarlo en la view
//Devuelve un mensaje de que no se encontro nada
$resultados = $instanciaclase->mostrarRestaurantesPagina($paginaActual, $nombrebarrio, $ciudad);

//Enviamos los datos a la vista turista_principal_view.php
$restaurantes = $resultados['restaurantes'];

//Enviamos los datos a la vista turista_principal_view.php
$total_paginas = $resultados['total_paginas'];

//Ahora en el caso de que se presione un filtro vamos a mostrar los restaurantes segun el filtro
if (isset($_POST['enviarFiltro'])) {
    $tiporestaurante = $_POST['tiporestaurante'];

    //Si el tipo de restaurante es todos, restablecemos los resultados
    if ($tiporestaurante == "todas") {
        //Llamamos a la funcion mostrarRestaurantesPagina, que muestra los restaurantes en la vista turista_principal_view.php
        //Que estan en el mismo barrio y ciudad que el hospedaje actual del turista
        $resultados = $instanciaclase->mostrarRestaurantesPagina($paginaActual, $nombrebarrio, $ciudad);

        //Enviamos los datos a la vista turista_principal_view.php
        $restaurantes = $resultados['restaurantes'];

        //Enviamos los datos a la vista turista_principal_view.php
        $total_paginas = $resultados['total_paginas'];

        //ELiminamos la sesion tiporestaurante
        unset($_SESSION['tiporestaurante']);

        //Reacrgamos la pagina con con la url original
        header('Location: turista_principal_index.php');
        exit;

    } else {
        //Llamamos a mostrarRestaurantesPaginaPorTipo
        $resultados = $instanciaclase->mostrarRestaurantesPaginaPorTipo($paginaActual, $nombrebarrio, $ciudad, $tiporestaurante);

        //Enviamos los datos a la vista turista_principal_view.php
        $restaurantes = $resultados['restaurantes'];

        //Enviamos los datos a la vista turista_principal_view.php
        $total_paginas = $resultados['total_paginas'];

        //Guardamos en una sesion el tipo de restaurante
        $_SESSION['tiporestaurante'] = $tiporestaurante;

    }


}

//SI se cambio de pagina y hay un filtro activado, guardado en sesion, se va a pasar el parametro
//del tipo de restaurante para que no se pierda el filtro por GET
if (isset($_GET['filtro'])) {
    $tiporestaurante = $_GET['filtro'];

    //LLamamos a mostrarRestaurantesPaginaPorTipo
    $resultados = $instanciaclase->mostrarRestaurantesPaginaPorTipo($paginaActual, $nombrebarrio, $ciudad, $tiporestaurante);

    //Enviamos los datos a la vista turista_principal_view.php
    $restaurantes = $resultados['restaurantes'];

    $total_paginas = $resultados['total_paginas'];

}

if (isset($_POST['search-button'])) {
    $busqueda = $_POST['buscarRestoran'];

    //Llamamos a la funcion mostrarRestaurantesPagina, que muestra los restaurantes en la vista turista_principal_view.php
    //Que estan en el mismo barrio y ciudad que el hospedaje actual del turista
    $resultados = $instanciaclase->mostrarRestaurantesPagina($paginaActual, $nombrebarrio, $ciudad, $busqueda);

    //Enviamos los datos a la vista turista_principal_view.php
    $restaurantes = $resultados['restaurantes'];

    //Enviamos los datos a la vista turista_principal_view.php
    $total_paginas = $resultados['total_paginas'];


}

//Llamamos a la vista turista_principal_view.php
require_once('views/turista_principal_view.php');



?>