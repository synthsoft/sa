<?php

//INICIAR SESION
session_start();

//CReamos la clase restaurante principal controller
class restaurante_principal_controller
{
    protected $restauranteinstancia;
    protected $promocionesinstancia;
    protected $nacionalidadinstancia;
    protected $horarioinstancia;

    //Construimos el metodo principal
    function __construct()
    {

        //Llamamos al modelo que contiene la conexion
        require_once('models/restaurante_model.php');

        //Creamos una variable que contenga el modelo
        $this->restauranteinstancia = new restaurante_model();

        require_once('models/promociones_model.php');

        $this->promocionesinstancia = new promociones_model();

        //Llamamos a nacionalidad_model.php
        require_once("models/nacionalidad_model.php");

        //Creamos una instancia de la clase nacionalidad_model
        $this->nacionalidadinstancia = new nacionalidad_model();

        //Llamamos a horarios_model.php
        require_once("models/horario_model.php");

        //Creamos una instancia de la clase horario_model
        $this->horarioinstancia = new horario_model();

    }

    public function mostrarPromosActivasPagina($paginaActual, $emailrest)
    {
        $cantidad_registros = 4;


        //Obtenemos el total de registros de la bd mediante la funcion obtenerTotalRestaurantesParaTurista
        $total_registros = $this->promocionesinstancia->obtenerTotalPromocionesActivas($emailrest);


        //Calculamos el total de paginas
        $total_paginas = ceil($total_registros / $cantidad_registros);

        // Aseguramos que la página actual esté en un rango válido
        if ($paginaActual < 1) {
            $paginaActual = 1;
        } elseif ($paginaActual > $total_paginas) {
            $paginaActual = $total_paginas;
        }

        //Obtenemos los registros a mostrar segun la pagina actual

        $promociones = $this->promocionesinstancia->obtenerPromocionesActivasPaginadas($paginaActual, $cantidad_registros, $emailrest);

        //Si no se obtuvieron los datos retornamos false
        if ($promociones === false) {
            return false;
        }

        //Retornamos un array con los datos a mostrar en la vista

        return array(
            'promociones' => $promociones,
            'total_registros' => $total_registros,
            'total_paginas' => $total_paginas
        );



    }

    public function mostrarPromosActivasPaginaFiltradas($paginaActual, $emailrest, $busqueda)
    {
        $cantidad_registros = 4;

        //Obtenemos el total de registros de la bd mediante la funcion obtenerTotalRestaurantesParaTurista
        $total_registros = $this->promocionesinstancia->obtenerTotalPromocionesActivasFiltradas($emailrest, $busqueda);

        //Calculamos el total de paginas
        $total_paginas = ceil($total_registros / $cantidad_registros);

        // Aseguramos que la página actual esté en un rango válido
        if ($paginaActual < 1) {
            $paginaActual = 1;
        } elseif ($paginaActual > $total_paginas) {
            $paginaActual = $total_paginas;
        }

        //Obtenemos los registros a mostrar segun la pagina actual

        $promociones = $this->promocionesinstancia->obtenerPromocionesActivasFiltradasPaginadas($paginaActual, $cantidad_registros, $emailrest, $busqueda);

        //SI promociones es false retornamos false
        if ($promociones === false) {
            return false;
        }

        //Retornamos un array con los datos a mostrar en la vista

        return array(
            'promociones' => $promociones,
            'total_registros' => $total_registros,
            'total_paginas' => $total_paginas
        );



    }

    public function mostrarPromosVencidasPagina($paginaActual, $emailrest)
    {
        $cantidad_registros = 4;

        //Obtenemos el total de registros de la bd mediante la funcion obtenerTotalRestaurantesParaTurista
        $total_registros = $this->promocionesinstancia->obtenerTotalPromocionesVencidas($emailrest);

        //Calculamos el total de paginas
        $total_paginas = ceil($total_registros / $cantidad_registros);


        // Aseguramos que la página actual esté en un rango válido
        if ($paginaActual < 1) {
            $paginaActual = 1;
        } elseif ($paginaActual > $total_paginas) {
            $paginaActual = $total_paginas;
        }

        //Obtenemos los registros a mostrar segun la pagina actual

        $promociones = $this->promocionesinstancia->obtenerPromocionesVencidasPaginadas($paginaActual, $cantidad_registros, $emailrest);

        //Si 
        //Retornamos un array con los datos a mostrar en la vista

        return array(
            'promociones' => $promociones,
            'total_registros' => $total_registros,
            'total_paginas' => $total_paginas
        );



    }

    public function mostrarPromosVencidasPaginaFiltradas($paginaActual, $emailrest, $busqueda)
    {
        $cantidad_registros = 4;

        //Obtenemos el total de registros de la bd mediante la funcion obtenerTotalRestaurantesParaTurista
        $total_registros = $this->promocionesinstancia->obtenerTotalPromocionesVencidasFiltradas($emailrest, $busqueda);

        //Calculamos el total de paginas
        $total_paginas = ceil($total_registros / $cantidad_registros);

        // Aseguramos que la página actual esté en un rango válido
        if ($paginaActual < 1) {
            $paginaActual = 1;
        } elseif ($paginaActual > $total_paginas) {
            $paginaActual = $total_paginas;
        }

        //Obtenemos los registros a mostrar segun la pagina actual

        $promociones = $this->promocionesinstancia->obtenerPromocionesVencidasFiltradasPaginadas($paginaActual, $cantidad_registros, $emailrest, $busqueda);

        //Retornamos un array con los datos a mostrar en la vista

        return array(
            'promociones' => $promociones,
            'total_registros' => $total_registros,
            'total_paginas' => $total_paginas
        );

    }

    public function tipoRestauranteActual($datosrestaurante)
    {
        if (isset($datosrestaurante->trestaurante)) {
            switch ($datosrestaurante->trestaurante) {
                case 'rbuffet':
                    return "Restoran Buffet";
                case 'rcrapida':
                    return "Restoran Comida Rapida";
                case 'rcrcasual':
                    return "Restoran Casual";
                case 'rautor':
                    return "Restoran de Autor";
                case 'rgourmet':
                    return "Restoran Gourmet";
                case 'rtematico':
                    return "Restoran Tematico";
                case 'rpllevar':
                    return "Restoran Para Llevar o Take Away";
                default:
                    return "Tipo de restaurante desconocido";
            }
        } else {
            return "No ha seleccionado un tipo de restaurante";
        }
    }

    public function validarEntradasDatosGenerales(
        $telefono1mod,
        $telefono2mod,
        $descripcionmod,
        $tcomidamod
    ) {

        //Validamos que los numeros sean de entre 8 y 9 digitos y que no contengan letras
        if (strlen($telefono1mod) < 8 || strlen($telefono1mod) > 9 || !is_numeric($telefono1mod)) {

            return false;
        }

        //Validamos que los numeros sean de entre 8 y 9 digitos y que no contengan letras
        if (strlen($telefono2mod) < 8 || strlen($telefono2mod) > 9 || !is_numeric($telefono2mod)) {

            return false;
        }

        //Validamos que no hayan mas de 250 caracteres en la descripcion
        if (strlen($descripcionmod) > 250) {

            return false;
        }

        //Validamos que no hayan mas de 250 caracteres en la tipo de comida
        if (strlen($tcomidamod) > 250) {

            return false;
        }

        //Validamos que no haya texto que puedan ser inyecciones sql en la descripcion
        if (preg_match("/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s.,;]*$/", $descripcionmod) == false) {
            return false;
        }

        //Validamos que no haya texto que puedan ser inyecciones sql en el tipo de comida
        if (preg_match("/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s.,;]*$/", $tcomidamod) == false) {
            return false;
        }

        //Si pasa todas las validaciones retornamos true
        return true;


    }

    public function datosGeneralesModificar(
        $emailrest,
        $telefono1actual,
        $telefono2actual,
        $descripcionactual,
        $trestauranteactual,
        $tcomidaactual,
        $nacionalidadactual,
        $telefono1mod,
        $telefono2mod,
        $descripcionmod,
        $trestaurantemod,
        $tcomidamod,
        $nacionalidadmod
    ) {

        //Limpiamos las entrada de datos con htmlspecialchars
        $telefono1mod = htmlspecialchars($telefono1mod);
        $telefono2mod = htmlspecialchars($telefono2mod);
        $descripcionmod = htmlspecialchars($descripcionmod);
        $trestaurantemod = htmlspecialchars($trestaurantemod);
        $tcomidamod = htmlspecialchars($tcomidamod);
        $nacionalidadmod = htmlspecialchars($nacionalidadmod);


        //Modificamos el telefono 1 si son diferentes el que ya esta en la BD y el que se quiere modificar
        if ($telefono1actual != $telefono1mod) {

            $resultado = $this->restauranteinstancia->modificarTelefono1Restaurante($emailrest, $telefono1mod);

            if ($resultado == false) {

                return false;
            }

        }

        //Modificamos el telefono 2 si son diferentes el que ya esta en la BD y el que se quiere modificar
        if ($telefono2actual != $telefono2mod) {

            $resultado = $this->restauranteinstancia->modificarTelefono2Restaurante($emailrest, $telefono2mod);

            if ($resultado == false) {

                return false;
            }

        }

        //Modificamos la descripcion si son diferentes el que ya esta en la BD y el que se quiere modificar
        if ($descripcionactual != $descripcionmod) {

            $resultado = $this->restauranteinstancia->modificarDescripcionRestaurante($emailrest, $descripcionmod);

            if ($resultado == false) {

                return false;
            }

        }

        //Modificamos el tipo de restaurante si son diferentes el que ya esta en la BD y el que se quiere modificar
        if ($trestauranteactual != $trestaurantemod) {

            $resultado = $this->restauranteinstancia->modificarTipoRestaurante($emailrest, $trestaurantemod);

            if ($resultado == false) {

                return false;
            }

        }

        //Modificamos el tipo de comida si son diferentes el que ya esta en la BD y el que se quiere modificar
        if ($tcomidaactual != $tcomidamod) {

            $resultado = $this->restauranteinstancia->modificarTipoComidaRestaurante($emailrest, $tcomidamod);

            if ($resultado == false) {

                return false;
            }

        }

        //Modificamos la nacionalidad si son diferentes el que ya esta en la BD y el que se quiere modificar
        if ($nacionalidadactual != $nacionalidadmod) {

            $resultado = $this->nacionalidadinstancia->modificarNacionalidadRest($emailrest, $nacionalidadmod);

            if ($resultado == false) {


                return false;
            }

        }

        //Si se modificaron todos los datos retornamos true
        echo "se modificaron todos los datos";
        return true;



    }

    public function modificarHorariosController($email, $apertura, $hapertura, $hcierre)
    {
        // Creamos un array con los días de la semana
        $diaSemana = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'];

        $exito = true; // Variable para rastrear si todas las modificaciones son exitosas

        // Recorremos el array diaSemana y verificamos si el restaurante está abierto o cerrado ese día
        foreach ($diaSemana as $dia) {

            // Verificamos si el restaurante está abierto o cerrado ese día y guardamos los valores en variables
            $valorApertura = isset($_POST['abiertocerrado'][$dia]) ? 'abierto' : 'cerrado';


            // Verificamos si el restaurante está abierto o cerrado ese día y en el caso de que sí, guardamos los valores
            // de hora de apertura y cierre en variables
            $valorHapertura = $valorApertura === 'abierto' ? $hapertura[$dia] : null;
            $valorHcierre = $valorApertura === 'abierto' ? $hcierre[$dia] : null;

            //Si el restaurante esta abierto y no se ingreso la hora de apertura o cierre retornamos false
            if ($valorApertura == 'abierto' && ($valorHapertura == null || $valorHcierre == null)) {
                return false;
            }

            // Llamamos a la función modificarHorarioRestaurante
            $resultado = $this->horarioinstancia->modificarHorarioRestaurante($email, $dia, $valorHapertura, $valorHcierre, $valorApertura);

            // Verificamos si se insertaron los datos en la BD
            if ($resultado === false) {
                // Si no se insertaron los datos en la BD, marcamos $exito como false pero continuamos la iteración
                return false;
            }
        }

        // Retornamos $exito, que será true si todas las modificaciones son exitosas
        return true;
    }


}


//Si la sesion no esta iniciada redirigimos a login_index.php

if (!isset($_SESSION['email'])) {
    header('Location: login_index.php');
    exit;
}


//Llamamos al controlador que contiene la logica de la pagina
require_once('controllers/restaurante_principal_controller.php');

//Creamos una variable que contenga el controlador
$restaurantecontrollerinstancia = new restaurante_principal_controller();

//Creamos una variable que contenga el modelo
$restaurantemodelinstancia = new restaurante_model();

//llamamos a usuario_model.php
require_once("models/usuario_model.php");

//Creamos una instancia de la clase usuario_model
$usuariomodel = new usuario_model();

//Creamos una instancia de la clase horario_model
$horarioinstancia = new horario_model();

//Creamos una instancia de la clase nacionalidad_model
$nacionalidadinstancia = new nacionalidad_model();

//LLamamos a verificacion_de_usuarios_model.php
require_once("models/verificacion_de_usuarios_model.php");

//Creamos una instancia de la clase verificacion_de_usuarios_model
$verificacioninstancia = new verificacionUsuarios();

//Llamamos a archivos_model.php
require_once("models/archivos_model.php");

//Creamos una instancia de la clase archivos_model
$archivosinstancia = new archivos_model();

//Cremos una intancia de promociones_model.php
require_once('models/promociones_model.php');

//Creamos una instancia de promociones_model
$promocionesinstancia = new promociones_model();


$datosuser = $usuariomodel->obtenerDatosUsuario($_SESSION['email']);

//Si los datos del usuario no se consiguieron llamamos a error.html
if (!$datosuser) {
    header("Location: error.html");
    exit;
}


$alias = $datosuser->alias;

//Verificamos el estado logico, esta funcion si da error redirige a error y si no continua
$verificacioninstancia->verificarEstadoLogico($_SESSION['email']);

//Verificamos que el usuario sea un restaurante, esta funcion si da error redirige a error y si no continua
$verificacioninstancia->verificarRolRestaurante($_SESSION['email']);


//Obtenemos el email del restaurante desde la sesion
$emailrest = $_SESSION['email'];


//VAMOS A DEFIFNIR LAS VARIABLES NECEARIAS PARA MOSTRAR LOS DATOS EN EL APARTADO
//GESTIONAR DATOS   

$datosrestaurante = $restaurantemodelinstancia->obtenerRestaurante($emailrest);

//Si los datos del restaurante no se consiguieron enviamos a error.html con href 
//y detenemos la ejecucion del codigo
if ($datosrestaurante == false) {

    header("Location: error.html");
    exit;
    
}

$ffinmembresia = isset($datosrestaurante->finmembresia) ? $datosrestaurante->finmembresia : false;

//Verificamos si la fecha de fin de membresia es menor a la fecha actual redirigimos
//al LOGIN, porque ahi si el restaurante tiene una renovacion aceptada al 
//loguearse le vamos a actualizar la fecha de fin de membresia con la de la renovacion
if ($ffinmembresia < date("Y-m-d")) {
    header("Location: login_index.php");
    session_destroy();
    exit;
}


$telefono1 = isset($datosrestaurante->telefono1) ? $datosrestaurante->telefono1 : false;
$telefono2 = isset($datosrestaurante->telefono2) ? $datosrestaurante->telefono2 : false;
$direccion = isset($datosrestaurante->dirrestaurante) ? $datosrestaurante->dirrestaurante : false;
$barrio = isset($datosrestaurante->nombrebarrio) ? $datosrestaurante->nombrebarrio : false;
$descripcion = isset($datosrestaurante->descrrestaurante) ? $datosrestaurante->descrrestaurante : false;
$nombrerestaurante = isset($datosrestaurante->nombrerestaurante) ? $datosrestaurante->nombrerestaurante : false;
$tiporestauranteactual = isset($datosrestaurante->trestaurante) ? $datosrestaurante->trestaurante : false;
$tipocomida = isset($datosrestaurante->tipocomida) ? $datosrestaurante->tipocomida : false;

//Llamamos a la funcion obtenerNacionalidades para poder desplegarlas en el select
$nacionalidad = $nacionalidadinstancia->obtenerNacionalidades();

//Obtenemos la nacionalidad actual del restaurante
$datosnacionalidad = $nacionalidadinstancia->obtenerNacionalidadRestaurante($emailrest);

$idnacionalidadaactual = isset($datosnacionalidad->idnacion) ? $datosnacionalidad->idnacion : false;
$nacionrestaurante = isset($datosnacionalidad->nacionrestaurante) ? $datosnacionalidad->nacionrestaurante : false;
//Obtenemos el tipo de restaurante actual
$tiporestaurante = $restaurantecontrollerinstancia->tipoRestauranteActual($datosrestaurante);


//Si se envia el formulario de la parte 1, es decir, el formulario de datos generales llamamos a la funcion
//datosGeneralesModificar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['botonGestionDatos'])) {

    //Obtenemos los datos desde los inputs y selects
    $telefono1mod = isset($_POST['telefono1']) ? $_POST['telefono1'] : false;
    $telefono2mod = isset($_POST['telefono2']) ? $_POST['telefono2'] : false;
    $descripcionmod = isset($_POST['descripcion']) ? $_POST['descripcion'] : false;
    $trestaurantemod = isset($_POST['tipoRestoran']) ? $_POST['tipoRestoran'] : false;
    $tcomidamod = isset($_POST['tipoComida']) ? $_POST['tipoComida'] : false;
    $nacionalidadmod = isset($_POST['nacionalidadRestoran']) ? $_POST['nacionalidadRestoran'] : $idnacionalidadactual;


    //Validamos las entradas de datos
    $validacion = $restaurantecontrollerinstancia->validarEntradasDatosGenerales(
        $telefono1mod,
        $telefono2mod,
        $descripcionmod,
        $tcomidamod
    );

    //Si la validacion es false ponemos mensaje de error en exitoModificarDatos y detener la ejecucion del codigo
    if ($validacion == false) {
        $_SESSION['exitoModificarDatos'] = "Error al guardar los datos, verifique que los datos sean correctos";
        header('Location: restaurante_principal_index.php');
        exit;
    }

    //Llamamos a la funcion datosGeneralesModificar
    $resultado = $restaurantecontrollerinstancia->datosGeneralesModificar(
        $emailrest,
        $telefono1,
        $telefono2,
        $descripcion,
        $tiporestauranteactual,
        $tipocomida,
        $idnacionalidadaactual,
        $telefono1mod,
        $telefono2mod,
        $descripcionmod,
        $trestaurantemod,
        $tcomidamod,
        $nacionalidadmod
    );

    if ($resultado == true) {
        $_SESSION['exitoModificarDatos'] = "Datos modificados con éxito";
    } else {
        $_SESSION['exitoModificarDatos'] = "Error al guardar los datos";
    }

    //REFRESCAMOS LA PAGINA con header y obtenemos la pagina actual del servidor
    header('Location: restaurante_principal_index.php');
    exit;

}

//obtenemos los horarios del restaurante y todo lo relacionado con ellos
$horarios = $horarioinstancia->obtenerHorariosRestaurante($emailrest);

$errorHorarios = null;

//Si dio false ponemos un mensaje de error, que detectaremos desde la view
if ($horarios == false) {
    $errorHorarios = true;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['horariosBtn'])) {

    //Guardamos los datos de los horarios en variables
    $hapertura = isset($_POST['hora_inicio']) ? $_POST['hora_inicio'] : null;
    $hcierre = isset($_POST['hora_fin']) ? $_POST['hora_fin'] : null;

    //Si apertura esta chequeado le ponemos el valor del campo, si no null
    $apertura = isset($_POST['abiertocerrado']) ? $_POST['abiertocerrado'] : null;

    //Llamamos a la funcion modificarHorariosController
    $resultado = $restaurantecontrollerinstancia->modificarHorariosController($emailrest, $apertura, $hapertura, $hcierre);

    //Si el resulatdo es verdader ponemos un echo si es false ponemos otro
    if ($resultado == true) {

        //Guardamos un mensaje de exito en la sesion
        $_SESSION['exitoModificarHorarios'] = "Horarios modificados con éxito";

    } else {

        //Guardamos un mensaje de error en la sesion
        $_SESSION['exitoModificarHorarios'] = "Error al modificar los horarios";
    }

    //refrescamos la pagina
    header('Location: restaurante_principal_index.php');
    exit;



}


//Si hau una peticion del sercidor y el boton apretado es sumbitCrearPromo, obtenemos los datos de los
//Campos y los guardamos en variables

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitCrearPromo'])) {


    //Obtenemos los datos de los campos
    $nombre = isset($_POST['nombreNuevaPromoInput']) ? $_POST['nombreNuevaPromoInput'] : false;
    $descripcion = isset($_POST['descripcionNuevaPromoInput']) ? $_POST['descripcionNuevaPromoInput'] : false;
    $fechainicio = isset($_POST['fechaInicioNuevaPromoInput']) ? $_POST['fechaInicioNuevaPromoInput'] : false;
    $fechafin = isset($_POST['fechaFinNuevaPromoInput']) ? $_POST['fechaFinNuevaPromoInput'] : false;

    if (isset($_FILES['imagenNuevaPromoInput']) && !empty($_FILES['imagenNuevaPromoInput']['name'])) {

        $imagen = $archivosinstancia->subir_imagen_obtener_ruta('img/imagenesusuarios', 'imagenNuevaPromoInput');

        //verificamos si la imagen es false, si es false es porque hubo un error al subir la imagen
        if ($imagen == false) {
            //Si la imagen es false ponemos un mensaje de error en la sesion
            $_SESSION['exitoCrearPromo'] = "Error al crear la promocion, la imagen no se pudo subir";
            //refrescamos la pagina
            header('Location: restaurante_principal_index.php');
            exit;
        }

    } else {
        $imagen = null;
    }

    //Llamamos a la funcion crearPromocion
    $resultado = $promocionesinstancia->crearPromocion($emailrest, $nombre, $descripcion, $fechafin, $fechainicio, $imagen);

    //Si el resultado es true ponemos un mensaje de exito en la sesion
    if ($resultado == true) {
        $_SESSION['exitoCrearPromo'] = "Promocion creada con éxito";
    } else {
        $_SESSION['exitoCrearPromo'] = "Error al crear la promocion";
    }

    //refrescamos la pagina
    header('Location: restaurante_principal_index.php');
    exit;

}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitModificarPromo'])) {

    $idpromo = isset($_POST['idPromoModificar']) ? $_POST['idPromoModificar'] : false;
    //Obtenemos los datos de los campos
    $nombre = isset($_POST['nombreModificarPromoInput']) ? $_POST['nombreModificarPromoInput'] : false;
    $descripcion = isset($_POST['descripcionModificarPromoInput']) ? $_POST['descripcionModificarPromoInput'] : false;
    $fechainicio = isset($_POST['fechaInicioModificarPromoInput']) ? $_POST['fechaInicioModificarPromoInput'] : false;
    $fechafin = isset($_POST['fechaFinModificarPromoInput']) ? $_POST['fechaFinModificarPromoInput'] : false;

    if (isset($_FILES['imagenModificarPromoInput']) && !empty($_FILES['imagenModificarPromoInput']['name'])) {
        // Seleccionó una imagen, procesa la subida de la imagen
        $imagen = $archivosinstancia->subir_imagen_obtener_ruta('img/imagenesusuarios', 'imagenModificarPromoInput');

        //Verificamos si la imagen es false, si es false es porque hubo un error al subir la imagen
        if ($imagen == false) {
            //Si la imagen es false ponemos un mensaje de error en la sesion
            $_SESSION['exitoModificarPromo'] = "Error al modificar la promocion, la imagen no se pudo subir";
            //refrescamos la pagina
            header('Location: restaurante_principal_index.php');
            exit;
        }

    } else {
        // No seleccionó una imagen, asigna false
        $imagen = false;
    }


    //Llamamos a la funcion crearPromocion
    $resultado = $promocionesinstancia->modificarPromocion($idpromo, $nombre, $descripcion, $fechainicio, $fechafin, $imagen);
    //Si el resultado es true ponemos un mensaje de exito en la sesion
    if ($resultado == true) {
        $_SESSION['exitoModificarPromo'] = "Promocion modificada con éxito";
    } else {
        $_SESSION['exitoModificarPromo'] = "Error al modificar la promocion";
    }
    //refrescamos la pagina
    header('Location: restaurante_principal_index.php');
    exit;

}


if (isset($_POST['submitbajaPromo'])) {

    $idpromo = isset($_POST['idPromoModificar']) ? $_POST['idPromoModificar'] : false;

    //Llamamos a ddarBajaPromo
    $resultado = $promocionesinstancia->darBajaPromo($idpromo);

    //Si el resultado es true ponemos un mensaje de exito en la sesion
    if ($resultado == true) {
        $_SESSION['exitoModificarPromo'] = "Promocion dada de baja con éxito";
    } else {
        $_SESSION['exitoModificarPromo'] = "Error al dar de baja la promocion";
    }

    //refrescamos la pagina
    header('Location: restaurante_principal_index.php');
    exit;

}


//obtenemos la pagina actual
if (isset($_GET['paginaActualActivas'])) {
    $paginaActualActivas = $_GET['paginaActualActivas'];
} else {
    $paginaActualActivas = 1;
}

//obtenemos la pagina actual
if (isset($_GET['paginaActualVencidas'])) {
    $paginaActualVencidas = $_GET['paginaActualVencidas'];
} else {
    $paginaActualVencidas = 1;
}


//Obtenemos los datos de las promociones activas del restaurante
$resultados_activas = $restaurantecontrollerinstancia->mostrarPromosActivasPagina($paginaActualActivas, $emailrest);

$promocionesactivas = $resultados_activas['promociones'];

$total_registros_activas = $resultados_activas['total_registros'];

$total_paginas_activas = $resultados_activas['total_paginas'];

$resultados_vencidas = $restaurantecontrollerinstancia->mostrarPromosVencidasPagina($paginaActualVencidas, $emailrest);

$promocionesvencidas = $resultados_vencidas['promociones'];

$total_registros_vencidas = $resultados_vencidas['total_registros'];

$total_paginas_vencidas = $resultados_vencidas['total_paginas'];



//Si se envia una solicitud al servidor y el formulario de busqueda de promociones activas esta seteado
//$promociones activas llama a mostrarPromosActivasPaginaFiltradas
//Si se envía una solicitud al servidor y el formulario de búsqueda de promociones activas está seteado
if (isset($_POST['enviarBusquedaPromoActiva'])) {


    //Obtenemos el valor del input de búsqueda
    $busqueda = isset($_POST['buscadorPromosActivas']) ? $_POST['buscadorPromosActivas'] : false;

    //Obtenemos los datos de las promociones activas del restaurante
    $resultados_activas = $restaurantecontrollerinstancia->mostrarPromosActivasPaginaFiltradas($paginaActualActivas, $emailrest, $busqueda);

    $promocionesactivas = $resultados_activas['promociones'];

    $total_registros_activas = $resultados_activas['total_registros'];

    $total_paginas_activas = $resultados_activas['total_paginas'];

    if (empty($promocionesactivas)) {
        $_SESSION['mensajeActivas'] = "No hay coincidencias";
    }

    $habilitarrestauraractivas = true;
}

if (isset($_POST['restaurarActivasBtn'])) {

    //Obtenemos los datos de las promociones activas del restaurante
    $resultados_activas = $restaurantecontrollerinstancia->mostrarPromosActivasPagina($paginaActualActivas, $emailrest);

    $promocionesactivas = $resultados_activas['promociones'];

    $total_registros_activas = $resultados_activas['total_registros'];

    $total_paginas_activas = $resultados_activas['total_paginas'];


}


if (isset($_POST['enviarBusquedaPromoVencidas'])) {

    //Obtenemos el valor del input de búsqueda
    $busqueda = isset($_POST['buscadorPromosVencidas']) ? $_POST['buscadorPromosVencidas'] : false;

    //guardamos la busqueda en una sesion
    $_SESSION['buscadorPromosVencidas'] = $busqueda;

    //Obtenemos los datos de las promociones vencidas del restaurante
    $resultados_vencidas = $restaurantecontrollerinstancia->mostrarPromosVencidasPaginaFiltradas($paginaActualVencidas, $emailrest, $busqueda);

    $promocionesvencidas = $resultados_vencidas['promociones'];

    $total_registros_vencidas = $resultados_vencidas['total_registros'];

    $total_paginas_vencidas = $resultados_vencidas['total_paginas'];

    if (empty($promocionesvencidas)) {
        $_SESSION['mensajeVencidas'] = "No hay coincidencias";
    }

    $habilitarrestaurarvencidas = true;
}

if (isset($_POST['restaurarVencidasBtn'])) {


    $resultados_vencidas = $restaurantecontrollerinstancia->mostrarPromosVencidasPagina($paginaActualVencidas, $emailrest);

    $promocionesvencidas = $resultados_vencidas['promociones'];

    $total_registros_vencidas = $resultados_vencidas['total_registros'];

    $total_paginas_vencidas = $resultados_vencidas['total_paginas'];

}


//Obtenemos el la fecha de iniciio y fin de membresia del restaurante para mostrar en la section de membresia
$fechainiciomembresia = $datosrestaurante->iniciomembresia;

$fechafinmembresia = $datosrestaurante->finmembresia;



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modificarMenu'])) {

    //Obtenemos el archivo pdf
    $archivo = isset($_FILES['menu_pdf']) ? $_FILES['menu_pdf'] : false;


    //llamamos a subir_pdf_obtener_ruta
    $ruta = $archivosinstancia->subir_pdf_obtener_ruta('archivos/menus', 'menu_pdf');

    //Si la ruta es false ponemos un mensaje de error en la sesion
    if ($ruta == false) {
        $_SESSION['exitoModificarMenu'] = "Error al modificar el menu, el archivo no se pudo subir";
        //refrescamos la pagina
        header('Location: restaurante_principal_index.php');
        exit;
    }

    //Llamamos a modificarMenuRestaurante
    $resultado = $restaurantemodelinstancia->modificarMenuRestaurante($emailrest, $ruta);

    //Si el resultado es true ponemos un mensaje de exito en la sesion
    if ($resultado == true) {
        $_SESSION['exitoModificarMenu'] = "Menu modificado con éxito";
    } else {
        $_SESSION['exitoModificarMenu'] = "Error al modificar el menu";
    }

    //refrescamos la pagina
    header('Location: restaurante_principal_index.php');
    exit;

}


//Obtenemos la rutapfd del menu
$rutapdf = $datosrestaurante->rutapdf;
$visibilidad = $datosrestaurante->visibilidadperfil;


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitVisibilidad'])) {

    //Nos fijamos si el restaurante esta visible o no a traves del checkbox checkVisibilidad, si esta chequeado
//es porque esta visible, si no esta chequeado es porque no esta visible

    $visibilidad = isset($_POST['checkVisibilidad']) ? 'visible' : 'invisible';

    //llamamos a modificarVisibilidadPerfilRestaurante
    $resultado = $restaurantemodelinstancia->modificarVisibilidadPerfilRestaurante($emailrest, $visibilidad);

    //Si el resultado es true ponemos un mensaje de exito en la sesion
    if ($resultado === true) {
        $_SESSION['exitoModificarVisibilidad'] = "Visibilidad modificada con éxito";
    } else {
        $_SESSION['exitoModificarVisibilidad'] = "Error al modificar la visibilidad";
    }

    //refrescamos la pagina
    header('Location: restaurante_principal_index.php');
    exit;

}


//Llamamos a la vista  que contiene la estructura de la pagina
require_once('views/restaurante_principal_view.php');



?>