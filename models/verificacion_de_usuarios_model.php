<?php

class verificacionUsuarios
{

    //Declaramos las variables
    protected $usuariomodelinstancia;

    //Creamos el constructor de la clase
    function __construct()
    {
        //Llamamos usuario_model.php
        require_once("models/usuario_model.php");

        //Creamos una instancia de usuarios_model
        $this->usuariomodelinstancia = new usuario_model();
    }

    public function verificarEstadoLogico($email)
    {
        // Obtenemos los datos del usuario
        $datosuser = $this->usuariomodelinstancia->obtenerDatosUsuario($email);

        // Verificamos si se obtuvieron los datos del usuario
        if ($datosuser) {
            $estado = $datosuser->estadologico;

            // Verificamos si el usuario es turista o administrador
            if ($estado === 'inactivo') {
                session_destroy();
                header("Location: login_index.php");
                exit;
            }

            return true;

        } else {

            //Redirigimos a error.html
            header("Location: error.html");
            exit;
        }
    }

    public function verificarRolTurista($email)
    {
        // Obtenemos los datos del usuario
        $datosuser = $this->usuariomodelinstancia->obtenerDatosUsuario($email);

        // Verificamos si se obtuvieron los datos del usuario
        if ($datosuser) {
            $roluser = $datosuser->rol;

            // Verificamos si el usuario es turista o administrador
            if ($roluser !== 'tur') {
                //Si el rol usar es admin redirigimos a admin_index.php
                if ($roluser === 'admin') {
                    header("Location: admin_index.php");
                    exit;
                } else {
                    //Si el rol usar es rest redirigimos a restaurante_index.php
                    if ($roluser === 'rest') {
                        header("Location: restaurante_principal_index.php");
                        exit;
                    }
                }

            }
            return true;
        } else {

            //Redirigimos a error.html
            header("Location: error.html");
            exit;
        }

    }

    //Esta funcion sirve para verificar si el usuario es un restaurante o un administrador
    //La usaremos para tener un correcto control de acceso a las paginas dependiendo del rol del usuario

    public function verificarRolRestaurante($email)
    {
        // Obtenemos los datos del usuario
        $datosuser = $this->usuariomodelinstancia->obtenerDatosUsuario($email);


        // Verificamos si se obtuvieron los datos del usuario
        if ($datosuser) {
            $roluser = $datosuser->rol;

            // Verificamos si el usuario es restaurante 
            if ($roluser !== 'rest') {
                //Si el rol usar es admin redirigimos a admin_index.php
                if ($roluser === 'admin') {
                    header("Location: admin_index.php");
                    exit;
                } else {
                    //Si el rol usar es rest redirigimos a restaurante_index.php
                    if ($roluser === 'tur') {
                        header("Location: turista_principal_index.php");
                        exit;
                    }
                }

            }
            return true;
        } else {

            //Redirigimos a error.html
            header("Location: error.html");
            exit;
        }
    }


    //Creamos la funcion para verificar si el usuario es un administrador
    public function verificarRolAdministrador($email)
    {

        //Obtenemos los datos del usuario
        $datosuser = $this->usuariomodelinstancia->obtenerDatosUsuario($email);

        //Obtenemos el rol del usuario
        $roluser = $datosuser->rol;

        // Verificamos si se obtuvieron los datos del usuario
        if ($datosuser) {
            $roluser = $datosuser->rol;

            // Verificamos si el usuario es restaurante 
            if ($roluser !== 'admin') {
                //Si el rol usar es admin redirigimos a admin_index.php
                //Si el rol usar es rest redirigimos a restaurante_index.php
                if ($roluser === 'rest') {
                    header("Location: restaurante_principal_index.php");
                    exit;
                } else {
                    //Si el rol usar es rest redirigimos a restaurante_index.php
                    if ($roluser === 'tur') {
                        header("Location: turista_principal_index.php");
                        exit;
                    }
                }

            }
            return true;
        } else {

            //Redirigimos a error.html
            header("Location: error.html");
            exit;
        }

    }


}





?>