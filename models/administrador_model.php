<?php

//Creamos la clase administrador_model
//llamamos a la clase usuario_model
require_once('models/usuario_model.php');

class administrador_model extends usuario_model
{
    //Llamamos al archvo donde se encuentra restaurante_model


    //Declaramos las variables privadas
    protected $db;

    //Creamos restaurante_modelo
    private $restaurantemodelo;



    //Declaramos el constructor, el cual se ejecuta al crear una instancia de la clase y contiene 
    //la conexion a la BD
    public function __construct()
    {
        //Llamamos al constructor de la clase padre
        parent::__construct();

        //LLamamos a restaurante_model
        require_once('models/restaurante_model.php');

        //Creamos una instancia de la clase restaurante_model
        $this->restaurantemodelo = new restaurante_model();


    }

    //Creamos la funcion para el registro de administrador
    public function registroUsuarioAdministrador($email)
    {
        //Creo la consulta para insertar los datos en la BD
        $sql = "INSERT INTO administrador (emailadmin) VALUES (:email)";

        //Preparo la consulta
        $query = $this->db->prepare($sql);

        //Ejecuto la consulta
        $query->execute(['email' => $email]);

        //Verifico si se insertaron los datos en la BD
        if ($query->rowCount() > 0) {
            //Retorno true si se insertaron los datos en la BD
            return true;
        } else {
            //Retorno false si no se insertaron los datos en la BD
            return false;
        }


    }

    //Creamos la funcion para aceptar una membresia y poner su fecha de inicio y fin a partir del dia actual
    public function aceptarMembresia($emailrest, $tipomembresia)
    {

        //Si la membresia es de 1 mes, se le asigna la fecha de inicio y fin a partir del dia actual y se actualiza el estado a vigente
        if ($tipomembresia == 'mensual') {
            //Creo la consulta para actualizar los datos en la BD
            $sql = "UPDATE restaurante SET iniciomembresia = CURDATE(), finmembresia = DATE_ADD(CURDATE(), INTERVAL 1 MONTH), estadomembresia = 'vigente' WHERE emailrest = :emailrest";

            //Preparo la consulta
            $query = $this->db->prepare($sql);

            //Ejecuto la consulta
            $query->execute(['emailrest' => $emailrest]);

            //Verifico si se actualizaron los datos en la BD
            if ($query->rowCount() > 0) {
                //Retorno true si se actualizaron los datos en la BD
                return true;
            } else {
                //Retorno false si no se actualizaron los datos en la BD
                return false;
            }
        } else {

            //Si la membresia dura 1 año, se le asigna la fecha de inicio y fin a partir del dia actual y se actualiza el estado a vigente
            if ($tipomembresia == 'anual') {
                //Creo la consulta para actualizar los datos en la BD
                $sql = "UPDATE restaurante SET iniciomembresia = CURDATE(), finmembresia = DATE_ADD(CURDATE(), INTERVAL 1 YEAR), estadomembresia = 'vigente' WHERE emailrest = :emailrest";

                //Preparo la consulta
                $query = $this->db->prepare($sql);

                //Ejecuto la consulta
                $query->execute(['emailrest' => $emailrest]);

                //Verifico si se actualizaron los datos en la BD
                if ($query->rowCount() > 0) {
                    //Retorno true si se actualizaron los datos en la BD
                    return true;
                } else {
                    //Retorno false si no se actualizaron los datos en la BD
                    return false;
                }

            } else {

                //Si la membresia es de 2 años, se le asigna la fecha de inicio y fin a partir del dia actual y se actualiza el estado a vigente

                //Creo la consulta para actualizar los datos en la BD
                $sql = "UPDATE restaurante SET iniciomembresia = CURDATE(), finmembresia = DATE_ADD(CURDATE(), INTERVAL 2 YEAR), estadomembresia = 'vigente' WHERE emailrest = :emailrest";

                //Preparo la consulta
                $query = $this->db->prepare($sql);

                //Ejecuto la consulta
                $query->execute(['emailrest' => $emailrest]);

                //Verifico si se actualizaron los datos en la BD
                if ($query->rowCount() > 0) {
                    //Retorno true si se actualizaron los datos en la BD
                    return true;
                } else {
                    //Retorno false si no se actualizaron los datos en la BD
                    return false;
                }

            }


        }


    }

    //Creamos la funcion para rechazar una membresia y guardar el mensaje de explicacion de porque se rechazo
    public function rechazarMembresia($emailrest, $mensaje)
    {

        //Creo la consulta para cambiar el estado de mambresia a rechazada y guardar el mensaje de explicacion
        $sql = "UPDATE restaurante SET estadomembresia = 'rechazada', mensajemembresia = :mensaje WHERE emailrest = :emailrest";

        //Preparo la consulta
        $query = $this->db->prepare($sql);

        //Ejecuto la consulta
        $query->execute(['emailrest' => $emailrest, 'mensaje' => $mensaje]);

        //Verifico si se actualizaron los datos en la BD
        if ($query->rowCount() > 0) {
            //Retorno true si se actualizaron los datos en la BD
            return true;
        } else {
            //Retorno false si no se actualizaron los datos en la BD
            return false;
        }

    }

    //Creamos la funcion para validar una renovacion de membresia y poner su fecha de inicio a partir de la fecha de finalizacion de la membresia actual
    //y la fecha de fin sumandole la cantidad de tiempo de la membresia que se renueva a partir de la fecha de finalizacion de la membresia actual
    public function aceptarRenovacion($emailrest, $tiporenovacion)
    {

        //Creo la consulta para obtener los datos de la membresia actual
        $sql = "SELECT finmembresia FROM restaurante WHERE emailrest = :emailrest";

        //Preparo la consulta
        $query = $this->db->prepare($sql);

        //Ejecuto la consulta
        $query->execute(['emailrest' => $emailrest]);

        //Obtengo los datos de la consulta
        $datos = $query->fetch(PDO::FETCH_ASSOC);

        //Obtengo la fecha de finalizacion de la membresia actual
        $fechafin = $datos['finmembresia'];

        //A partir de esa fecha dependiendo del tiporenovacion, se le asigna la fecha de inicio y fin a partir de la fecha de finalizacion de la membresia actual

        //Si la renovacion es de 1 mes, se le asigna la fecha de inicio y fin a partir de la fecha de finalizacion de la membresia actual mas un dia y se actualiza el estado a vigente
        if ($tiporenovacion == 'mensual') {
            //Creo la consulta para actualizar los datos en la BD
            $sql = "UPDATE restaurante SET iniciorenovacion = DATE_ADD(:fechafin, INTERVAL 1 DAY), finrenovacion = DATE_ADD(:fechafin1, INTERVAL 1 MONTH), estadorenovacion = 'valida' WHERE emailrest = :emailrest";

            //Preparo la consulta
            $query = $this->db->prepare($sql);

            //Ejecuto la consulta
            $query->execute(['emailrest' => $emailrest, 'fechafin' => $fechafin, 'fechafin1' => $fechafin]);

            //Verifico si se actualizaron los datos en la BD
            if ($query->rowCount() > 0) {
                //Retorno true si se actualizaron los datos en la BD
                return true;
            } else {
                //Retorno false si no se actualizaron los datos en la BD
                return false;
            }

        } else {

            //Si la renovacion es de 1 año, se le asigna la fecha de inicio y fin a partir de la fecha de finalizacion de la membresia actual mas un dia y se actualiza el estado a vigente
            if ($tiporenovacion == 'anual') {
                //Creo la consulta para actualizar los datos en la BD
                $sql = "UPDATE restaurante SET iniciorenovacion = DATE_ADD(:fechafin, INTERVAL 1 DAY), finrenovacion = DATE_ADD(:fechafin1, INTERVAL 1 YEAR), estadorenovacion = 'valida' WHERE emailrest = :emailrest";

                //Preparo la consulta
                $query = $this->db->prepare($sql);

                //Ejecuto la consulta
                $query->execute(['emailrest' => $emailrest, 'fechafin' => $fechafin, 'fechafin1' => $fechafin]);

                //Verifico si se actualizaron los datos en la BD
                if ($query->rowCount() > 0) {
                    //Retorno true si se actualizaron los datos en la BD
                    return true;
                } else {
                    //Retorno false si no se actualizaron los datos en la BD
                    return false;
                }

            } else {

                //Si la renovacion es de 2 años, se le asigna la fecha de inicio y fin a partir de la fecha de finalizacion de la membresia actual mas un dia y se actualiza el estado a vigente
                if ($tiporenovacion == 'bianual') {
                    //Creo la consulta para actualizar los datos en la BD
                    $sql = "UPDATE restaurante SET iniciorenovacion = DATE_ADD(:fechafin, INTERVAL 1 DAY), finrenovacion = DATE_ADD(:fechafin1, INTERVAL 2 YEAR), estadorenovacion = 'valida' WHERE emailrest = :emailrest";

                    //Preparo la consulta
                    $query = $this->db->prepare($sql);

                    //Ejecuto la consulta
                    $query->execute(['emailrest' => $emailrest, 'fechafin' => $fechafin, 'fechafin1' => $fechafin]);

                    //Verifico si se actualizaron los datos en la BD
                    if ($query->rowCount() > 0) {
                        //Retorno true si se actualizaron los datos en la BD

                        return true;

                    } else {

                        //Retorno false si no se actualizaron los datos en la BD
                        return false;

                    }

                }
            }
        }

    }

    public function rechazarRenovacion($emailrest, $mensaje)
    {

        //Creo la consulta para cambiar el estado de mambresia a rechazada y guardar el mensaje de explicacion
        $sql = "UPDATE restaurante SET estadorenovacion = 'rechazada', mensajemembresia = :mensaje WHERE emailrest = :emailrest";

        //Preparo la consulta
        $query = $this->db->prepare($sql);

        //Ejecuto la consulta
        $query->execute(['emailrest' => $emailrest, 'mensaje' => $mensaje]);

        //Verifico si se actualizaron los datos en la BD
        if ($query->rowCount() > 0) {
            //Retorno true si se actualizaron los datos en la BD
            return true;
        } else {
            //Retorno false si no se actualizaron los datos en la BD
            return false;
        }

    }


}

?>