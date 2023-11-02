<?php
//INICIO DE SESSION
session_start();

//Creamos la clase solicitudes_restaurante_controller
class restaurantes_vencidos_controller
{

    protected $restauranteinstancia;

    public function __construct()
    {
        //LLamamos al archivo donde se encuentra la clase asistencia_model.php
        require_once("models/restaurante_model.php");

        //Creamos una instancia de la clase asistencia_model
        $this->restauranteinstancia = new restaurante_model();

    }


    public function mostrarVencidosPagina($paginaActual)
    {
        $cantidad_registros = 8;

        //Obtenemos el total de registros de la bd mediante la funcion obtenerTotalRestaurantesParaTurista
        $total_registros = $this->restauranteinstancia->obtenerTotalRestaurantesVencidosParaAdmin();

        //Calculamos el total de paginas
        $total_paginas = ceil($total_registros / $cantidad_registros);


        // Aseguramos que la página actual esté en un rango válido
        if ($paginaActual < 1) {
            $paginaActual = 1;
        } elseif ($paginaActual > $total_paginas) {
            $paginaActual = $total_paginas;
        }

        //Obtenemos los registros a mostrar segun la pagina actual

        $restaurantes = $this->restauranteinstancia->obtenerRestaurantesVencidosPaginadosParaAdmin($paginaActual, $cantidad_registros);

        //Devolver los resultados obtenidos y el total de paginas para crear la paginacion
        return array(

            'restaurantes' => $restaurantes,
            'total_paginas' => $total_paginas,
            'total_registros' => $total_registros


        );


    }


}

//Creamos una instancia de la clase solicitudes_restaurante_controller
$instanciaclase = new restaurantes_vencidos_controller();

//Llamamos a usuario_model.php
require_once("models/usuario_model.php");

//Creamos una instancia de la clase usuario_model
$usuariomodel = new usuario_model();

//Llamamos a verificacion_de_usuarios_model.php
require_once("models/verificacion_de_usuarios_model.php");

//Creamos una instancia de la clase verificacion_de_usuarios_model
$verificacionmodel = new verificacionUsuarios();


//Verificamos si hay una sesion iniciada
if (!isset($_SESSION['email'])) {
    header("Location: login_index.php");
    exit;
}

//Verificamos si el usuario esta logicamnete activo mediante la funcion verificarEstadoLogico
$verificacionmodel->verificarEstadoLogico($_SESSION['email']);

//Verificamos si el usuario es un administrador mediante la funcion verificarRolAdministardor
$verificacionmodel->verificarRolAdministrador($_SESSION['email']);


//Obtenemos la pagina actual
//Obtenemos la pagina actual
if (isset($_GET['pagina'])) {
    $paginaActual = $_GET['pagina'];
} else {
    $paginaActual = 1;
}


//Obtenemos los datos de las solicitudes
$resultados = $instanciaclase->mostrarVencidosPagina($paginaActual);

$restaurantes = $resultados['restaurantes'];

$total_paginas = $resultados['total_paginas'];

$total_registros = $resultados['total_registros'];

//Si hay una solicitud del serividor SERVER REQUEST y el boton para aceptarla es presionado
//obtenemos las variables del formulario y nada mas por el momento
if (isset($_POST['bajaRestaurante'])) {

    //Obtenemos las variables
    $emailRest = $_POST['emailRest'];

    $resultado = $usuariomodel->bajaLogicaUsuario($emailRest);

    if ($resultado) {

        header("Refresh:0");
        exit;

    } else {

        header("Refresh:0");
        exit;

    }

}

require_once("views/restaurantes_vencidos_view.php");



?>