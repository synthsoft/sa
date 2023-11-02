<?php
// Path: controllers/resenas_controller.php
//Creamos la clase resena_controller
class resena_controller
{
    protected $db;

    protected $resenainstancia;
    //Creamos el metodo constructor
    public function __construct()
    {
        //Llamamos al archivo donde se encuentra la clase conectar
        //con el metodo que genera la conexion con la BD
        require_once("db/db.php");

        //Creamos una instancia de la clase "Conectar", y usamos el metodo "conexion"
        $this->db = Conectar::conexion();

        //Llamamos a la clase resena_model.php
        require_once("models/resena_model.php");

        //Creamos una instancia de la clase resena_model
        $this->resenainstancia = new resena_model();

    }

    public function mostrarResenasPagina($emailrest, $paginaActual)
    {
        $cantidad_registros = 3;

        //Obtenemos el total de registros de la bd mediante la funcion obtenerTotalRestaurantesParaTurista
        $total_registros = $this->resenainstancia->obtenerTotalResenasRestaurante($emailrest);

        //Calculamos el total de paginas
        $total_paginas = ceil($total_registros / $cantidad_registros);


        // Aseguramos que la página actual esté en un rango válido
        if ($paginaActual < 1) {
            $paginaActual = 1;
        } elseif ($paginaActual > $total_paginas) {
            $paginaActual = $total_paginas;
        }

        //Obtenemos los registros a mostrar segun la pagina actual

        $resenas = $this->resenainstancia->obtenerResenasPaginadasRestaurante(
            $paginaActual,
            $cantidad_registros,
            $emailrest
        );

        //Devolver los resultados obtenidos y el total de paginas para crear la paginacion
        return array(

            'resenas' => $resenas,
            'total_paginas' => $total_paginas,
            'total_registros' => $total_registros

        );

    }

    public function mostrarResenasPaginaOrden($emailrest, $paginaActual, $orden)
    {
        $cantidad_registros = 3;

        //Obtenemos el total de registros de la bd mediante la funcion obtenerTotalRestaurantesParaTurista
        $total_registros = $this->resenainstancia->obtenerTotalResenasRestaurante($emailrest);

        //Calculamos el total de paginas
        $total_paginas = ceil($total_registros / $cantidad_registros);


        // Aseguramos que la página actual esté en un rango válido
        if ($paginaActual < 1) {
            $paginaActual = 1;
        } elseif ($paginaActual > $total_paginas) {
            $paginaActual = $total_paginas;
        }

        //Obtenemos los registros a mostrar segun la pagina actual

        $resenas = $this->resenainstancia->obtenerResenasPaginadasRestaurantePorOrdenAscDesc(
            $paginaActual,
            $cantidad_registros,
            $emailrest,
            $orden
        );

        //Devolver los resultados obtenidos y el total de paginas para crear la paginacion
        return array(

            'resenas' => $resenas,
            'total_paginas' => $total_paginas,
            'total_registros' => $total_registros

        );

    }

    public function mostrarResenasPaginaOrdenMejorPeor($emailrest, $paginaActual, $orden)
    {
        $cantidad_registros = 3;

        //Obtenemos el total de registros de la bd mediante la funcion obtenerTotalRestaurantesParaTurista
        $total_registros = $this->resenainstancia->obtenerTotalResenasRestaurante($emailrest);

        //Calculamos el total de paginas
        $total_paginas = ceil($total_registros / $cantidad_registros);


        // Aseguramos que la página actual esté en un rango válido
        if ($paginaActual < 1) {
            $paginaActual = 1;
        } elseif ($paginaActual > $total_paginas) {
            $paginaActual = $total_paginas;
        }

        //Obtenemos los registros a mostrar segun la pagina actual

        $resenas = $this->resenainstancia->obtenerResenasPaginadasRestaurantePorMejorPeor(
            $paginaActual,
            $cantidad_registros,
            $emailrest,
            $orden
        );

        //Devolver los resultados obtenidos y el total de paginas para crear la paginacion
        return array(

            'resenas' => $resenas,
            'total_paginas' => $total_paginas,
            'total_registros' => $total_registros

        );

    }

    public function mostrarResenasPaginaBusqueda($emailrest, $paginaActual, $busqueda)
    {
        $cantidad_registros = 3;

        //Obtenemos el total de registros de la bd mediante la funcion obtenerTotalRestaurantesParaTurista
        $total_registros = $this->resenainstancia->obtenerTotalResenasRestauranteBusqueda($emailrest, $busqueda);
        //Calculamos el total de paginas
        $total_paginas = ceil($total_registros / $cantidad_registros);


        // Aseguramos que la página actual esté en un rango válido
        if ($paginaActual < 1) {
            $paginaActual = 1;
        } elseif ($paginaActual > $total_paginas) {
            $paginaActual = $total_paginas;
        }

        //Obtenemos los registros a mostrar segun la pagina actual

        $resenas = $this->resenainstancia->obtenerResenasPaginadasRestauranteBusqueda(
            $paginaActual,
            $cantidad_registros,
            $emailrest,
            $busqueda
        );

        //Devolver los resultados obtenidos y el total de paginas para crear la paginacion
        return array(

            'resenas' => $resenas,
            'total_paginas' => $total_paginas,
            'total_registros' => $total_registros

        );

    }

}



//Verificamos si hay una sesion iniciada
session_start();


if (!isset($_SESSION['email'])) {
    //Redirigimos al login
    header('Location: login_index.php');
}

//Llamamos a verificacion_de_usuarios_model.php
require_once("models/verificacion_de_usuarios_model.php");

//Creamos una instancia de verificacion_de_usuarios_model.php
$verificacionusuariosmodelinstancia = new verificacionUsuarios();

//Llamamos a usuario_model.php
require_once("models/usuario_model.php");

//Creamos una instancia de usuarios_model
$usuariomodelinstancia = new usuario_model();

//Verificamos si el usuario esta activo
$verificacionusuariosmodelinstancia->verificarEstadoLogico($_SESSION['email']);

//Verificamos si el usuario es restaurante
$verificacionusuariosmodelinstancia->verificarRolRestaurante($_SESSION['email']);

//Creamos una instancia de la clase resena_controller
$instanciaclase = new resena_controller();

//Creamos resenainstancia
$resenainstancia = new resena_model();


//Obtenemos el email del restaurante que se envio por GET al rediriigir desde el controlador turista_principal_controller
$emailrest = $_GET['emailrest'];


//-------OBTENEMOS LOS DATOS PARA LA PAGINACION DE LAS RESENAS DEL RESTAURANTE----------------

//Obtenemos la pagina actual
if (isset($_GET['pagina'])) {
    $paginaActual = $_GET['pagina'];
} else {
    $paginaActual = 1;
}

//LLamamos a la funcion mostrarResenasPagina, la cual nos devuelve un arreglo con los resultados obtenidos y el total de paginas
$resultados = $instanciaclase->mostrarResenasPagina($emailrest, $paginaActual);

//Obtenemos los resultados
$resenas = $resultados['resenas'];

//Obtenemos el total de paginas
$total_paginas = $resultados['total_paginas'];

//Obtenemos el total de registros
$total_registros = $resultados['total_registros'];



//Vamos al apartado de filtrar por orden
if (isset($_POST["enviarFiltroOrden"])) {

    //Obtenemos el valor de los select
    $orden = $_POST["ordenSelect"];

    //Guardamos orden en una variable de sesion

    //HAcemos un switch 
    switch ($orden) {
        case 'recientes':

            if (isset($_GET['orden'])) {

                unset($_GET['orden']);

            }

            $_SESSION['orden'] = $orden;

            //LLamamos a la funcion mostrarResenasPagina, la cual nos devuelve un arreglo con los resultados obtenidos y el total de paginas
            //Los guardamos en variables de sesion para poder usarlas en la vista

            $resultados = $instanciaclase->mostrarResenasPaginaOrden($emailrest, $paginaActual, "DESC");

            //Obtenemos los resultados
            $resenas = $resultados['resenas'];

            //Obtenemos el total de paginas
            $total_paginas = $resultados['total_paginas'];

            //Obtenemos el total de registros
            $total_registros = $resultados['total_registros'];

            break;

        case 'antiguas':

            if (isset($_GET['orden'])) {

                unset($_GET['orden']);

            }

            $_SESSION['orden'] = $orden;

            //LLamamos a la funcion mostrarResenasPagina, la cual nos devuelve un arreglo con los resultados obtenidos y el total de paginas
            $resultados = $instanciaclase->mostrarResenasPaginaOrden($emailrest, $paginaActual, "ASC");

            //Obtenemos los resultados
            $resenas = $resultados['resenas'];


            //Obtenemos el total de paginas
            $total_paginas = $resultados['total_paginas'];

            //Obtenemos el total de registros
            $total_registros = $resultados['total_registros'];

            break;

        case 'altasbajas':

            if (isset($_GET['orden'])) {

                unset($_GET['orden']);

            }

            $_SESSION['orden'] = $orden;

            //LLamamos a mostrarResenasPaginaOrdenMejorPeor
            $resultados = $instanciaclase->mostrarResenasPaginaOrdenMejorPeor($emailrest, $paginaActual, "DESC");

            //Obtenemos los resultados
            $resenas = $resultados['resenas'];

            //Obtenemos el total de paginas
            $total_paginas = $resultados['total_paginas'];

            //Obtenemos el total de registros
            $total_registros = $resultados['total_registros'];

            break;

        case 'bajasaltas':

            if (isset($_GET['orden'])) {

                unset($_GET['orden']);

            }

            $_SESSION['orden'] = $orden;

            //LLamamos a mostrarResenasPaginaOrdenMejorPeor
            $resultados = $instanciaclase->mostrarResenasPaginaOrdenMejorPeor($emailrest, $paginaActual, "ASC");

            //Obtenemos los resultados
            $resenas = $resultados['resenas'];

            //Obtenemos el total de paginas
            $total_paginas = $resultados['total_paginas'];

            //Obtenemos el total de registros
            $total_registros = $resultados['total_registros'];

            break;

    }

}


//Si llego un request por GET y esta seteado orden como reciente 
if (isset($_GET['orden']) && $_GET['orden'] == "recientes") {


    //LLamamos a la funcion mostrarResenasPagina, la cual nos devuelve un arreglo con los resultados obtenidos y el total de paginas
    //Los guardamos en variables de sesion para poder usarlas en la vista

    $resultados = $instanciaclase->mostrarResenasPaginaOrden($emailrest, $paginaActual, "DESC");

    //Obtenemos los resultados
    $resenas = $resultados['resenas'];

    //Obtenemos el total de paginas
    $total_paginas = $resultados['total_paginas'];

    //Obtenemos el total de registros
    $total_registros = $resultados['total_registros'];

}

//Si llego un request por GET y esta seteado orden como reciente 
if (isset($_GET['orden']) && $_GET['orden'] == "antiguas") {


    //LLamamos a la funcion mostrarResenasPagina, la cual nos devuelve un arreglo con los resultados obtenidos y el total de paginas
    //Los guardamos en variables de sesion para poder usarlas en la vista

    $resultados = $instanciaclase->mostrarResenasPaginaOrden($emailrest, $paginaActual, "ASC");

    //Obtenemos los resultados
    $resenas = $resultados['resenas'];

    //Obtenemos el total de paginas
    $total_paginas = $resultados['total_paginas'];

    //Obtenemos el total de registros
    $total_registros = $resultados['total_registros'];

}

//Si llego un request por GET y esta seteado orden como mejorpeor
if (isset($_GET['orden']) && $_GET['orden'] == "altasbajas") {


    //LLamamos a mostrarResenasPaginaOrdenMejorPeor
    $resultados = $instanciaclase->mostrarResenasPaginaOrdenMejorPeor($emailrest, $paginaActual, "DESC");

    //Obtenemos los resultados
    $resenas = $resultados['resenas'];

    //Obtenemos el total de paginas
    $total_paginas = $resultados['total_paginas'];

    //Obtenemos el total de registros
    $total_registros = $resultados['total_registros'];

}

//Si llego un request por GET y esta seteado orden como peormejor
if (isset($_GET['orden']) && $_GET['orden'] == "bajasaltas") {


    //LLamamos a mostrarResenasPaginaOrdenMejorPeor
    $resultados = $instanciaclase->mostrarResenasPaginaOrdenMejorPeor($emailrest, $paginaActual, "ASC");

    //Obtenemos los resultados
    $resenas = $resultados['resenas'];

    //Obtenemos el total de paginas
    $total_paginas = $resultados['total_paginas'];

    //Obtenemos el total de registros
    $total_registros = $resultados['total_registros'];

}

//SI se apreta restaurar orden se borra la variable de sesion orden
if (isset($_POST['restaurar'])) {

    unset($_GET['orden']);
    $_SESSION['orden'] = null;
}


if (isset($_POST['botonBuscarResena'])) {

    //Obtenemos las variables

    $busqueda = $_POST['buscarResena'];
    
    $resultado = $instanciaclase->mostrarResenasPaginaBusqueda($emailrest, $paginaActual, $busqueda);

    //Obtenemos los resultados
    $resenas = $resultado['resenas'];

    //Obtenemos el total de paginas
    $total_paginas = $resultado['total_paginas'];

    //Obtenemos el total de registros
    $total_registros = $resultado['total_registros'];

    $_SESSION['busqueda'] = $busqueda;


}

//Si llego un request por GET y esta seteado busqueda
if (isset($_GET['busqueda'])) {

    $busqueda = $_GET['busqueda'];

    //LLamamos a mostrarResenasPaginaOrdenMejorPeor
    $resultados = $instanciaclase->mostrarResenasPaginaBusqueda($emailrest, $paginaActual, $busqueda);

    //Obtenemos los resultados
    $resenas = $resultados['resenas'];

    //Obtenemos el total de paginas
    $total_paginas = $resultados['total_paginas'];

    //Obtenemos el total de registros
    $total_registros = $resultados['total_registros'];

}

if (isset($_POST['restaurarBusqueda'])) {

    unset($_GET['busqueda']);
    $_SESSION['busqueda'] = null;

    //Refrescamos la pagina
    header('Location: resenas_index.php?action=resenasRest&emailrest=' . $emailrest);
}


$promediostdrestgral = $resenainstancia->obtenerResenasPorAreaRestaurante($emailrest, "restorangral");
$promediorestaurantegral = $promediostdrestgral->promedio;

$promediostdinstalaciones = $resenainstancia->obtenerResenasPorAreaRestaurante($emailrest, "instalaciones");
$promedioinstalaciones = $promediostdinstalaciones->promedio;

$promediostdmenugastronomico = $resenainstancia->obtenerResenasPorAreaRestaurante($emailrest, "menugastronomico");
$promediomenugastronomico = $promediostdmenugastronomico->promedio;

$promediostdatencion = $resenainstancia->obtenerResenasPorAreaRestaurante($emailrest, "atencion");
$promedioatencion = $promediostdatencion->promedio;

$promediototalstd = $resenainstancia->obtenerResenasPromedio($emailrest);
$promediototal = $promediototalstd->promedio_general;


//Llamamos a la vista
require_once('views/resenas_view.php');


?>