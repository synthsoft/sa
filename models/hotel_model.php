<?php

//Creamos la clase HotelModel


class hotel_model
{

    //Creamos una variable protegida para la conexion
    protected $db;
    protected $barriomodelinstancia;

    //Creamos el constructor de la clase
    public function __construct()
    {

        //Llamamos al archivo donde se encuentra la clase conectar
        //con el metodo que genera la conexion con la BD
        require_once("db/db.php");

        //Creamos una instancia de la clase "Conectar", y usamos el metodo "conexion"
        $this->db = Conectar::conexion();

        //llamamos al archivo donde se encuentra barrio_model.php
        require_once("models/barrio_model.php");

        //Creamos una instancia de la clase barrio_model.php
        $this->barriomodelinstancia = new barrio_model();

    }

    public function VerificarHotelBD($nombreHotel, $direccionHotel)
    {
        try {
            // Creamos la consulta con PDO para verificar si el hotel ya existe en la BD
            $sql = "SELECT * FROM hotel WHERE nombrehotel = :nombreHotel AND dirhotel = :direccionHotel";
            $query = $this->db->prepare($sql);
            $query->execute(['nombreHotel' => $nombreHotel, 'direccionHotel' => $direccionHotel]);

            // Si la consulta devuelve un resultado, significa que el hotel ya existe en la BD
            if ($query->rowCount() > 0) {
                return true; // Retornamos true
            }

            // Si la consulta no devuelve un resultado, significa que el hotel no existe en la BD
            return "noexiste"; // Retornamos false
        } catch (PDOException $e) {
            // Manejo de excepciones
            // Puedes registrar o notificar el error según tus necesidades
            error_log('Error al verificar el hotel en la base de datos: ' . $e->getMessage());
            return false;
        }
    }


    //Creamos la funcion para registrar un hotel en la base de datos
    public function registrarHotelBD($nombreHotel, $direccionHotel)
    {
        try {
            // Iniciar una transacción
            $this->db->beginTransaction();

            // Crear la consulta con PDO para registrar el hotel en la base de datos
            $consulta = $this->db->prepare("INSERT INTO hotel (nombrehotel, dirhotel) VALUES (?, ?)");
            $consulta->execute([$nombreHotel, $direccionHotel]);

            // Confirmar la transacción
            $this->db->commit();

            // Si la consulta se ejecuta correctamente, significa que el hotel se registró correctamente
            if ($consulta->rowCount() > 0) {
                // Retornamos true
                return true;
            }
        } catch (PDOException $e) {
            // Ocurrió un error, deshacer la transacción
            $this->db->rollBack();
            // Manejar o registrar el error, por ejemplo, con un mensaje de error o registro de errores.
            // Aquí puedes agregar código para manejar el error, como registrar el error en un archivo de registro.
            // Luego, puedes lanzar una excepción o devolver false, según tus necesidades.
            // Ejemplo:
            // throw new Exception("Error al registrar el hotel: " . $e->getMessage());
            return false;
        }
    }


}





?>