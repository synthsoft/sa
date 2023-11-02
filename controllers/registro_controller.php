<?php
// Configura la sesión para usar cookies seguras y HttpOnly
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);

//Iniciamos la sesion
session_start();

// Genera o recupera la sesión PHPSESSID
// (esto debe hacerse antes de cualquier salida al navegador)
if (!isset($_SESSION['PHPSESSID'])) {
    $_SESSION['PHPSESSID'] = session_id();
}

// Generar un token Anti-CSRF y guardarlo en la sesión
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

// Creamos la clase registro_controller
class registro_controller
{
    //Creamos las variables que contendran instancias de las clases que necesitamos
    protected $usuariomodel;
    protected $archivosmodel;
    protected $nacionalidadmodel;
    protected $turistamodel;
    protected $restaurantemodel;
    protected $barriomodel;
    protected $horariomodel;

    // Creamos el constructor de la clase
    public function __construct()
    {
        //llamamos a la clase usuario_model
        require_once('models/usuario_model.php');
        $this->usuariomodel = new usuario_model();

        //Llamamos al archivo archivos_model.php
        require_once('models/archivos_model.php');
        $this->archivosmodel = new archivos_model();

        //Llammos al archivo nacionalidad_model.php
        require_once('models/nacionalidad_model.php');
        $this->nacionalidadmodel = new nacionalidad_model();

        //llamamos a turista_model.php
        require_once('models/turista_model.php');
        $this->turistamodel = new turista_model();

        //llamamos a restaurante_model.php
        require_once('models/restaurante_model.php');
        $this->restaurantemodel = new restaurante_model();

        //llamamos a barrio_model.php
        require_once('models/barrio_model.php');
        $this->barriomodel = new barrio_model();

        //llamamos a horario_model.php
        require_once('models/horario_model.php');
        $this->horariomodel = new horario_model();

    }

    // Creamos la funcion para el registro de usuario turista
    public function registroTuristaController($email, $contrasena1, $contrasena2, $alias, $rutaimagen)
    {
        // Limpiamos y validamos las entradas
        $email = $this->cleanInput($email);
        $contrasena1 = $this->cleanInput($contrasena1);
        $contrasena2 = $this->cleanInput($contrasena2);
        $alias = $this->cleanInput($alias, true); // Asumo que el alias puede tener espacios, que serán reemplazados por guiones bajos

        // Validamos que las contraseñas sean iguales
        if ($contrasena1 !== $contrasena2) {
            return false; // Las contraseñas no coinciden
        }

        //Validamos que las contrasenas tengan entre 8 y 128 caracteres
        if (strlen($contrasena1) < 8 || strlen($contrasena1) > 128) {
            return false; // La contraseña no tiene entre 8 y 128 caracteres
        }

        //Validamos que no tengan saltos de linea
        if (strpos($contrasena1, "\n") !== false) {
            return false; // La contraseña tiene saltos de linea
        }

        // Validamos que el correo y alias no existan en la BD
        if ($this->usuariomodel->verificarEmail($email) || $this->usuariomodel->verificarAlias($alias)) {
            return false; // Email o alias ya existen en la base de datos
        }

        //Validamos que el email tenga como maximo 250 caracteres
        if (strlen($email) > 250) {
            return false; // El email tiene mas de 250 caracteres
        }

        //Validamos que el formato sea de email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false; // El email no tiene un formato valido
        }

        //Validamos que el alias no tenga mas de 50 caracteres 
        if (strlen($alias) > 50) {
            return false; // El alias tiene mas de 50 caracteres
        }

        //Validamos que el alias no tenga caracteres especiales a exepcion de guiones bajos
        if (!preg_match("/^[a-zA-Z0-9_]*$/", $alias)) {
            return false; // El alias tiene caracteres especiales
        }

        // Llamamos a la función registroUsuarioTurista
        $resultado = $this->turistamodel->registroUsuarioTurista($email, $contrasena1, $alias, $rutaimagen);

        return $resultado; // Retornamos el resultado directamente, true o false
    }

    // Función auxiliar para limpiar la entrada
    private function cleanInput($data, $replaceSpaces = false)
    {
        $data = trim($data);
        $data = htmlspecialchars($data);
        $data = strip_tags($data);

        if ($replaceSpaces) {
            $data = str_replace(' ', '_', $data);
        }

        return $data;
    }


    // Creamos la funcion para el registro de usuario restaurante
    public function registroRestauranteController(
        $emailrest,
        $alias,
        $contrasena,
        $direccionrestaurante,
        $nombrerestaurante,
        $psiri,
        $emichelin,
        $tdoro,
        $ciudad,
        $nombrebarrio,
        $rutapdf,
        $tipocomida,
        $latitudrestaurante,
        $longitudrestaurante,
        $rutaimagen,
        $descrrestaurante,
        $tiporestaurante,
        $telefono1,
        $telefono2

    ) {

        //Limpiamos las entradas de datos para evitar inyecciones con htmlspecialchars que sirve para 
        $email = htmlspecialchars($emailrest);
        $alias = htmlspecialchars($alias);
        $contrasena = htmlspecialchars($contrasena);
        $direccionrestaurante = htmlspecialchars($direccionrestaurante);
        $nombrerestaurante = htmlspecialchars($nombrerestaurante);
        $psiri = htmlspecialchars($psiri);
        $emichelin = htmlspecialchars($emichelin);
        $tdoro = htmlspecialchars($tdoro);
        $ciudad = htmlspecialchars($ciudad);
        $nombrebarrio = htmlspecialchars($nombrebarrio);
        $rutapdf = htmlspecialchars($rutapdf);
        $tipocomida = htmlspecialchars($tipocomida);
        $latitudrestaurante = htmlspecialchars($latitudrestaurante);
        $longitudrestaurante = htmlspecialchars($longitudrestaurante);
        $rutaimagen = htmlspecialchars($rutaimagen);
        $descrrestaurante = htmlspecialchars($descrrestaurante);
        $tiporestaurante = htmlspecialchars($tiporestaurante);
        $telefono1 = htmlspecialchars($telefono1);

        //Si alias tiene espacios en blanco los pasamos a guiones bajos
        $alias = str_replace(' ', '_', $alias);

        //Verificamos que el alias no tenga caracteres especiales, excepto ñ, Ñ o guiones bajos
        if (!preg_match("/^[a-zA-Z0-9ñÑ_]*$/", $alias)) {

            // Retornamos false si el alias tiene caracteres especiales
            return false;
        }

        //Verificamos que el correo tenga un formato valido
        if (!filter_var($emailrest, FILTER_VALIDATE_EMAIL)) {

            // Retornamos false si el correo no tiene un formato valido
            return false;
        }

        //Verificamos que la contraseña tenga entre 8 y 128 caracteres
        if (strlen($contrasena) < 8 || strlen($contrasena) > 128) {

            // Retornamos false si la contraseña no tiene entre 8 y 128 caracteres
            return false;
        }

        //Verificamos que la contraseña no tenga saltos de linea
        if (strpos($contrasena, "\n") !== false) {


            // Retornamos false si la contraseña tiene saltos de linea
            return false;
        }

        //Verificamos que el email no tenga mas de 250 caracteres
        if (strlen($emailrest) > 250) {

            // Retornamos false si el email tiene mas de 250 caracteres
            return false;
        }

        //Verificamos que el nombre del restaurante no este vacio o no seteado o sin contenido
        if (empty($nombrerestaurante)) {


            // Retornamos false si el nombre del restaurante esta vacio
            return false;
        }

        //Verificamos que el nombre del restaurante no tenga mas de 250 caracteres
        if (strlen($nombrerestaurante) > 250) {


            // Retornamos false si el nombre del restaurante tiene mas de 250 caracteres
            return false;
        }


        //Verificamos que la direccion no tenga mas de 250 caracteres
        if (strlen($direccionrestaurante) > 250) {

            // Retornamos false si la direccion tiene mas de 250 caracteres
            return false;
        }

        //Verificamos que el barrio no tenga mas de 150 caracteres
        if (strlen($nombrebarrio) > 150) {

            // Retornamos false si el barrio tiene mas de 150 caracteres
            return false;
        }


        //Verificamos que la ciudad no tenga mas de 150 caracteres
        if (strlen($ciudad) > 150) {

            // Retornamos false si la ciudad tiene mas de 150 caracteres
            return false;
        }


        //Verificamos que la descripcion del restaurante no tenga mas de 250 caracteres
        if (strlen($descrrestaurante) > 250) {

            // Retornamos false si la descripcion del restaurante tiene mas de 250 caracteres
            return false;
        }


        //Verificamos que el tipo de comida no tenga mas de 150 caracteres
        if (strlen($tipocomida) > 150) {

            // Retornamos false si el tipo de comida tiene mas de 150 caracteres
            return false;
        }


        //Verificamos que el correo no exista en la BD
        if ($this->usuariomodel->verificarEmail($emailrest)) {

            // Retornamos false si el correo ya existe en la BD
            return false;
        }

        //Verificamos que el alias no exista en la BD
        if ($this->usuariomodel->verificarAlias($alias)) {

            // Retornamos false si el alias ya existe en la BD
            return false;
        }

        //Verificamos que el telefono 1 no tenga mas de 9 caracteres
        if (strlen($telefono1) > 9) {

            // Retornamos false si el telefono 1 tiene mas de 9 caracteres
            return false;
        }

        //Verificamos que el telefono 1 no tenga caracteres especiales, excepto ñ, Ñ o guiones bajos
        if (!preg_match("/^[0-9ñÑ_]*$/", $telefono1)) {

            // Retornamos false si el telefono 1 tiene caracteres especiales
            return false;
        }

        //Verificamos que el telefono 2 no tenga mas de 9 caracteres
        if (strlen($telefono2) > 9) {

            // Retornamos false si el telefono 2 tiene mas de 9 caracteres
            return false;
        }

        //Verificamos que el telefono 2 no tenga caracteres especiales, excepto ñ, Ñ o guiones bajos
        if (!preg_match("/^[0-9ñÑ_]*$/", $telefono2)) {

            // Retornamos false si el telefono 2 tiene caracteres especiales
            return false;
        }

        // Llamamos a la funcion registroUsuario
        $resultado = $this->usuariomodel->registroUsuario($email, $contrasena, $alias, $rutaimagen, 'rest', 'activo');

        // Verificamos si se insertaron los datos en la BD, si es true, procedemos a insertar los datos en la tabla restaurante
        if ($resultado == true) {

            // Llamamos a la funcion registroUsuarioRestaurante
            $resultado = $this->restaurantemodel->registroUsuarioRestaurante(
                $emailrest,
                $direccionrestaurante,
                $nombrerestaurante,
                $psiri,
                $emichelin,
                $tdoro,
                $ciudad,
                $nombrebarrio,
                $rutapdf,
                $tipocomida,
                $latitudrestaurante,
                $longitudrestaurante,
                $descrrestaurante,
                $tiporestaurante,
                $telefono1,
                $telefono2
            );

            // Verificamos si se insertaron los datos del registro de restaurante en la BD
            if ($resultado == true) {

                // Retornamos true si se insertaron los datos en la BD
                return true;
            } else {
                // Retornamos false si no se insertaron los datos en la BD
                return false;
            }



        } else {
            // Retornamos false si no se insertaron los datos en la BD
            return false;
        }
    }


    //Creamos la funcion registroControllerIngresarHorarios
    public function registroControllerIngresarHorarios($email, $apertura, $hapertura, $hcierre)
    {

        //Creamos un array con los dias de la semana
        $diaSemana = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'];

        //Recorremos el array diaSemana y verificamos si el restaurante esta abierto o cerrado ese dia
        foreach ($diaSemana as $dia) {

            //Verificamos si el restaurante esta abierto o cerrado ese dia y guardamos los valores en variables
            $apertura[$dia] = isset($_POST['apertura'][$dia]) ? $_POST['apertura'][$dia] : 'cerrado';

            //Verificamos si el restaurante esta abierto o cerrado ese dia y en el caso de que si guardamos los valores
            //de hora de apertura y cierre en variables
            $valorHapertura = isset($apertura[$dia]) && $apertura[$dia] == 'abierto' ? $hapertura[$dia] : null;
            $valorHcierre = isset($apertura[$dia]) && $apertura[$dia] == 'abierto' ? $hcierre[$dia] : null;

            //Llamamos a la funcion registroUsuarioRestauranteHorario
            $resultado = $this->horariomodel->registroUsuarioRestauranteHorario($email, $dia, $valorHapertura, $valorHcierre, $apertura[$dia]);

            //Verificamos si se insertaron los datos en la BD
            if ($resultado == false) {

                //Si no se insertaron los datos en la BD retornamos false
                return false;

            }

        }
        return true;

    }


    //Creamos funcion para mostrar las nacionalidades en el select
    public function mostrarNacionalidades()
    {

        //Llamamos a la funcion obtenerNacionalidades
        $resultado = $this->nacionalidadmodel->obtenerNacionalidades();

        //Retornamos el resultado de la consulta
        return $resultado;
    }

}

//Declaramos variables que contendran instancias de las clases que necesitamos
$registrocontrollerinstancia = new registro_controller();
$usuariomodelinstancia = new usuario_model();
$barriomodelinstancia = new barrio_model();
$restaurantemodelinstancia = new restaurante_model();
$archivosmodelinstancia = new archivos_model();
$nacionalidadmodelinstancia = new nacionalidad_model();


//Vamos a llamar a la funcion mostrarNacionalidades para mostrar las nacionalidades en el select
//De la parte 4 del registro de restaurante
$nacionalidades = $registrocontrollerinstancia->mostrarNacionalidades();


//Empezamos con el register de turista, verificamos que se haya enviado el formulario por POST y que se haya 
//presionado el boton de registro

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['formularioturista'])) {


    //Obtenemos los valores del formulario y los guardamos en variables
    $aliasturista = $_POST['aliasturista'];
    $contrasenaturista1 = $_POST['contrasenaturista1'];
    $contrasenaturista2 = $_POST['contrasenaturista2'];
    $emailturista = $_POST['emailturista'];
    $csrf = $_POST['csrf_token'];

    //Si el token CSRF no coincide, mostramos un mensaje de error y ponemos exit para que no se ejecute el codigo
    if ($csrf !== $_SESSION['csrf_token']) {

        //Destruimos la sesion
        session_destroy();

        //Refrescamos la pagina
        header("Location: index.php");
        exit;

    }


    //Nos fijamos si hay una foto de perfil del turista, y si no tiene un error, obtenemos la ruta
    if (isset($_FILES['fototurista']) && $_FILES['fototurista']['error'] === UPLOAD_ERR_OK) {
        // El archivo se subió correctamente, procede con el procesamiento.
        $rutaimagen = $archivosmodelinstancia->subir_imagen_obtener_ruta('img/imagenesusuarios', 'fototurista');

    } else {

        //Si no hay una foto de perfil del turista la ruta es null
        $rutaimagen = null;
    }

    //Llamamos a la funcion registroTuristaController, para insertar los datos en la BD
    $resultado = $registrocontrollerinstancia->registroTuristaController($emailturista, $contrasenaturista1, $contrasenaturista2, $aliasturista, $rutaimagen);

    //Si se insertaron los datos en la BD correctamente mostramos un mensaje de exito MensajeFinRegistro
    if ($resultado == true) {

        //Eniamos a turista_principal_index.php con el correo del usuario en la variable de sesion
        echo "<script>window.location.href='login_index.php'</script>";

    } else {

        $errorRegistroTurista = true;

    }

}



if (isset($_POST['registrocompletorestaurante'])) {

    //Obtenemos los valores de las variables de la parte 1
    $nombrerestaurante = $_POST['nombrerestaurante'];
    $aliasrestaurante = $_POST['aliasrestaurante'];
    $contrasena1restaurante = $_POST['contrasena1restaurante'];
    $contrasena2restaurante = $_POST['contrasena2restaurante'];
    $correorestaurante = $_POST['correorestaurante'];
    $descrrestaurante = $_POST['descrrestaurante'];

    //Limpiamos espacios en blanco en las variables, exluyendo nombres y descripciones
    $aliasrestaurante = trim($aliasrestaurante);
    $contrasena1restaurante = trim($contrasena1restaurante);
    $contrasena2restaurante = trim($contrasena2restaurante);
    $correorestaurante = trim($correorestaurante);

    //Obtenemos los valores de las variables de la parte 2
    $direccionrestaurante = $_POST['direccion'];
    $barriorestaurante = $_POST['barrio'];
    $ciudadrestaurante = $_POST['ciudad'];
    $latitudrestaurante = $_POST['latitud'];
    $longitudrestaurante = $_POST['longitud'];
    $telefono1 = $_POST['telefono1'];
    $telefono2 = $_POST['telefono2'];

    //Obtenemos los valores de las variables de la parte 3
    $estrellasMichelin = $_POST['estrellasMichelin'];
    $tenedorOro = $_POST['tenedorDeOro'];
    $premioSiri = $_POST['premioSiri'];

    //obtenemos los valores de las variables de la parte 4
    $tipoRestoran = $_POST['tipoRestoran'];
    $tipoComidaRestaurante = $_POST['tipoComida'];
    $nacionalidadRestaurante = $_POST['nacionalidadRestoran'];
    $horariosApertura = $_POST['horarios_apertura'];
    $horariosCierre = $_POST['horarios_cierre'];

    $csrf = $_POST['csrf_token'];

    //Si el token CSRF no coincide, mostramos un mensaje de error y ponemos exit para que no se ejecute el codigo
    if ($csrf !== $_SESSION['csrf_token']) {

        //Destruimos la sesion
        session_destroy();

        //Refrescamos la pagina
        header("Location: index.php");
        exit;

    }


    //Validamos que apertura este seleccionado para que no salte Undefined Array key
    if (isset($_POST['apertura'])) {

        $apertura = $_POST['apertura'];

    } else {

        $apertura = null;

    }

    //Si hay una foto de perfil del restaurante obtenemos la ruta
    if (isset($_FILES['fotorestaurante'])) {

        //Llamamos a la funcion subir_imagen_obtener_ruta y le pasamos los parametros
        $rutaimagen = $archivosmodelinstancia->subir_imagen_obtener_ruta('img/imagenesusuarios', 'fotorestaurante');

    } else {

        $rutaimagen = null;

    }


    //Si hay un pdf obtenemos la ruta
    if (isset($_FILES['pdfMenu'])) {

        //Llamamos a la funcion subir_pdf_obtener_ruta y le pasamos los parametros
        $rutapdf = $archivosmodelinstancia->subir_pdf_obtener_ruta('archivos/menus', 'pdfMenu');

        //Si la ruta pdf es false, ponemos null en la variable $rutapdf
        if ($rutapdf == false) {

            $rutapdf = null;

        }

    } else {

        $rutapdf = null;

    }

    //Llamamos a la funcion verificarBarrio
    $resultado = $barriomodelinstancia->verificarBarrio($ciudadrestaurante, $barriorestaurante);

    //Si el barrio no existe en la BD llamamos a la funcion insertarBarrio
    if ($resultado === "noexiste") {

        //Llamamos a la funcion insertarBarrio
        $resultado = $barriomodelinstancia->insertarBarrio($ciudadrestaurante, $barriorestaurante);


    } else {

        if ($resultado == false) {

            //Guardamos true en la variable $errorRegistroRestaurante
            $errorRegistroRestaurante = true;

            //Refrescamos la pagina
            header("Refresh:0");

            //Detenemos la ejecucion del codigo
            exit;
        }


    }

    //Llamamos a la funcion registroRestauranteController
    $resultado = $registrocontrollerinstancia->registroRestauranteController(
        $correorestaurante,
        $aliasrestaurante,
        $contrasena1restaurante,
        $direccionrestaurante,
        $nombrerestaurante,
        $premioSiri,
        $estrellasMichelin,
        $tenedorOro,
        $ciudadrestaurante,
        $barriorestaurante,
        $rutapdf,
        $tipoComidaRestaurante,
        $latitudrestaurante,
        $longitudrestaurante,
        $rutaimagen,
        $descrrestaurante,
        $tipoRestoran,
        $telefono1,
        $telefono2

    );

    //Si se insertaron los datos en la BD correctamente ingresamos los datos relacionados con restaurante
    //pero que estan en otras tablas
    if ($resultado == true) {


        //Llamamos a la funcion registroControllerIngresarHorarios y le pasamos los parametros
        $resultado = $registrocontrollerinstancia->registroControllerIngresarHorarios(
            $correorestaurante,
            $apertura,
            $horariosApertura,
            $horariosCierre
        );

        //Llamamos a la clase nacionalidad_model y la funcion registroUsuarioRestauranteNacionalidad
        $resultado = $nacionalidadmodelinstancia->registroUsuarioRestauranteNacionalidad($correorestaurante, $nacionalidadRestaurante);


        //enviamos al login con parametro de registro exitoso
        echo "<script>window.location.href='login_index.php'</script>";

    } else {
        //Ponemos true en la variable de errorRegistroRestaurante
        $errorRegistroRestaurante = true;
    }

}

//Llamamos a la vista registro_view.php

require_once('views/registro_view.php');



?>