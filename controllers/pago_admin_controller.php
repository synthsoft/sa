<?php

class pago_admin_controller
{
    protected $restaurantemodelo;
    public function __construct()
    {
        //Llamamos al modelo de restaurantes
        require_once('models/restaurante_model.php');

        //Creamos una instancia de la clase restaurante_model
        $this->restaurantemodelo = new restaurante_model();


    }

    public function mostrarSolicitudesPagina($paginaActual)
    {
        $cantidad_registros = 3;

        //Obtenemos el total de registros de la bd mediante la funcion obtenerTotalRestaurantesParaTurista
        $total_registros = $this->restaurantemodelo->obtenerTotalSolicitudesRestaurantesParaAdmin();

        //Calculamos el total de paginas
        $total_paginas = ceil($total_registros / $cantidad_registros);


        // Aseguramos que la página actual esté en un rango válido
        if ($paginaActual < 1) {
            $paginaActual = 1;
        } elseif ($paginaActual > $total_paginas) {
            $paginaActual = $total_paginas;
        }

        //Obtenemos los registros a mostrar segun la pagina actual

        $solicitudes = $this->restaurantemodelo->obtenerSolicitudesRestaurantesPaginadosParaAdmin(
            $paginaActual,
            $cantidad_registros
        );

        //Devolver los resultados obtenidos y el total de paginas para crear la paginacion
        return array(

            'solicitudes' => $solicitudes,
            'total_paginas' => $total_paginas,
            'total_registros' => $total_registros

        );

    }

    function imprimirTipoSolicitud($solicitud) {
        if ($solicitud->estadomembresia == "pendiente") {
            echo "Solicitud de membresía";
        } else {
            if ($solicitud->estadorenovacion == "pendiente") {
                echo "Solicitud de renovación";
            }
        }
    }

}

//Verificamos si hay una sesion iniciada
session_start();

if (isset($_SESSION['email'])) {

    //Creamos una instancia de la clase pago_admin_controller
    $instanciaclase = new pago_admin_controller();

    //Creamos una instancia de administrador_model
    require_once('models/administrador_model.php');
    $instanciaadministradormodelo = new administrador_model();

    //llamamos a usuario_model.php
    require_once('models/usuario_model.php');
    $usuariomodelinstancia = new usuario_model();

    $datosuser = $usuariomodelinstancia->obtenerDatosUsuario($_SESSION['email']);

    //Verificamos si el usuario esta dado de baja
    $estadologico = $datosuser->estadologico;

    //Si el usuario esta dado de baja lo redirigimos a la pagina de inicio de sesion
    if ($estadologico == "inactivo") {
        session_abort();
        header("Location: login_index.php");
    }

    $roluser = $datosuser->rol;

    //VERIFICAMOS SI EL ROL DEL USUARIO ES ADMIN
    if ($roluser != 'admin') {


        //redirieccionamos al index
        echo "<script>window.location.href='index.php'</script>";

    }

    //Obtenemos la pagina actual
    //Obtenemos la pagina actual
    if (isset($_GET['pagina'])) {
        $paginaActual = $_GET['pagina'];
    } else {
        $paginaActual = 1;
    }

    //Obtenemos los datos de los restaurantes para mostrarlos en la vista
    $resultados = $instanciaclase->mostrarSolicitudesPagina($paginaActual);

    //Obtenemos las solicitudes
    $solicitudes = $resultados['solicitudes'];

    //Obtenemos el total de paginas
    $total_paginas = $resultados['total_paginas'];

    //Obtenemos el total de registros
    $total_registros = $resultados['total_registros'];


    if(isset($_POST['botonRechazarMembresia'])){

        $emailrest = $_POST['correoRestaurante'];
        $mensaje = $_POST['textoRechazo'];

        //Llamamos al modelo para rechazar la solicitud
       $resultado = $instanciaadministradormodelo->rechazarMembresia($emailrest, $mensaje);

         //Refrescamos la pagina
            header('Location: pago_admin_index.php');
    
    }

    if(isset($_POST['botonRechazarRenovacion'])){

        $emailrest = $_POST['correoRestaurante'];
        $mensaje = $_POST['textoRechazo'];

        //Llamamos al modelo para rechazar la solicitud
       $resultado = $instanciaadministradormodelo->rechazarRenovacion($emailrest, $mensaje);

       //Refrescamos la pagina
         header('Location: pago_admin_index.php');
    
    }

    if(isset($_POST['botonAceptarRenovacion'])){

        $emailrest = $_POST['correoRestaurante'];
        $tiporenovacion = $_POST['tipoRenovacion'];

        //Llamamos al modelo para aceptar la solicitud
       $resultado = $instanciaadministradormodelo->aceptarRenovacion($emailrest, $tiporenovacion);

       //Refrescamos la pagina
         header('Location: pago_admin_index.php');
    
    }

    if(isset($_POST['botonAceptarMembresia'])){

        $emailrest = $_POST['correoRestaurante'];
        $tipomembresia = $_POST['tipoMembresia'];

        //Llamamos al modelo para aceptar la solicitud
       $resultado = $instanciaadministradormodelo->aceptarMembresia($emailrest, $tipomembresia);

       //Refrescamos la pagina
         header('Location: pago_admin_index.php');
    
    }

    //Llamamos a la vista
    require_once('views/administrador_pagos_view.php');




} else {

    //Si no hay sesion iniciada, redirigimos al login
    header('Location: login_index.php');

}




//Llamo a la vista
require_once('views/administrador_pagos_view.php');


?>