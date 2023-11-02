<?php

//Creamos la clase horario_model
class horario_model
{

    //Declaramos las variables, protected asi se puede accder a ellas desde las clases que hereden de esta
    protected $db;

    //Declaramos el constructor, el cual se ejecuta al crear una instancia de la clase y contiene 
    //la conexion a la BD
    public function __construct()
    {

        //Llamamos al archivo donde se encuentra la clase conectar
        //con el metodo que genera la conexion con la BD
        require_once("db/db.php");

        //Creamos una instancia de la clase "Conectar", y usamos el metodo "conexion"
        $this->db = Conectar::conexion();


    }

    //Creamos la funcion para ingresar los horarios de un restaurante en la BD
    public function registroUsuarioRestauranteHorario($email, $dia, $hapertura, $hcierre, $abiertocerrado)
    {
        try {
            // Comienza una transacción
            $this->db->beginTransaction();

            // Crea la consulta para insertar los datos del restaurante en la BD
            $sql = "INSERT INTO horario (dia, emailrest, hapertura, hcierre, abiertocerrado) VALUES (:dia, :emailrest, :hapertura, :hcierre, :abiertocerrado)";

            // Prepara la consulta
            $query = $this->db->prepare($sql);

            // Asigna valores a los parámetros
            $query->bindParam(':dia', $dia);
            $query->bindParam(':emailrest', $email);
            $query->bindParam(':hapertura', $hapertura);
            $query->bindParam(':hcierre', $hcierre);
            $query->bindParam(':abiertocerrado', $abiertocerrado);

            // Ejecuta la consulta
            $query->execute();

            // Confirma la transacción
            $this->db->commit();

            return true; // La inserción se considera exitosa

        } catch (PDOException $e) {
            // Si ocurre un error, revierte la transacción
            $this->db->rollBack();

            // Manejo de excepciones
            echo 'Error al ejecutar la consulta: ' . $e->getMessage();
            return false; // Retorna false en caso de error
        }
    }


    //Creamos la funcion para modificar los horarios de un restaurante en la BD
    public function modificarHorarioRestaurante($email, $dia, $hapertura, $hcierre, $abiertocerrado)
    {
        try {
            // Comienza una transacción
            $this->db->beginTransaction();

            // Creo la consulta para modificar los datos del restaurante en la BD
            $sql = "UPDATE horario SET hapertura = :hapertura, hcierre = :hcierre, abiertocerrado = :abiertocerrado WHERE emailrest = :emailrest AND dia = :dia";

            // Preparo la consulta
            $query = $this->db->prepare($sql);

            // Ejecuto la consulta
            if ($query->execute(['hapertura' => $hapertura, 'hcierre' => $hcierre, 'abiertocerrado' => $abiertocerrado, 'emailrest' => $email, 'dia' => $dia])) {
                // Commit para confirmar la transacción
                $this->db->commit();

                // La consulta se ejecutó sin errores, lo que indica que se intentó la actualización.
                return true;
            } else {
                // Rollback para deshacer la transacción en caso de error
                $this->db->rollBack();

                // Hubo un error durante la ejecución de la consulta.
                return false;
            }
        } catch (PDOException $e) {
            // En caso de una excepción, puedes registrar el error o manejarlo según tus necesidades.
            // Ejemplo: throw new Exception("Error en la actualización del horario: " . $e->getMessage());

            // Rollback para deshacer la transacción en caso de error
            $this->db->rollBack();

            return false;
        }
    }


    //Creamos la funcion para obtener los horarios de un restaurante en la BD
    public function obtenerHorariosRestaurante($email)
    {
        try {
            // Creo la consulta para obtener los horarios de un restaurante en la BD
            $sql = "SELECT * FROM horario WHERE emailrest = :emailrest";

            // Preparo la consulta
            $query = $this->db->prepare($sql);

            // Ejecuto la consulta
            $query->execute(['emailrest' => $email]);

            // Verifico si se ejecutó la consulta sin errores
            if ($query) {
                // Retorno los resultados de la consulta
                return $query->fetchAll();
            } else {
                // En caso de error, retorno un array vacío o puedes manejar el error según tus necesidades
                return [];
            }
        } catch (PDOException $e) {
            // Manejo de excepciones
            // Puedes registrar o notificar el error según tus necesidades
            error_log('Error al obtener los horarios del restaurante: ' . $e->getMessage());
            // En caso de error, retorno un array vacío o puedes manejar el error según tus necesidades
            return false;
        }
    }


    public function obtenerHorariosRestauranteTurista($email)
    {
        try {
            // Creo la consulta para obtener los horarios de un restaurante en la BD
            $sql = "SELECT
                dia,
                emailrest,
                CASE
                    WHEN abiertocerrado = 'abierto' THEN CONCAT(hapertura, ' - ', hcierre)
                    WHEN abiertocerrado = 'cerrado' THEN 'Cerrado'
                END as estado
            FROM
                horario
            WHERE
                emailrest = :emailrest";

            // Preparo la consulta
            $query = $this->db->prepare($sql);

            // Ejecuto la consulta
            $query->execute(['emailrest' => $email]);

            // Verifico si se ejecutó la consulta sin errores
            if ($query) {
                // Retorno los resultados de la consulta
                return $query->fetchAll();
            } else {
                // En caso de error, retorno un array vacío o puedes manejar el error según tus necesidades
                return [];
            }
        } catch (PDOException $e) {
            // Manejo de excepciones
            // Puedes registrar o notificar el error según tus necesidades
            error_log('Error al obtener los horarios del restaurante para turistas: ' . $e->getMessage());
            // En caso de error, retorno un array vacío o puedes manejar el error según tus necesidades
            return [];
        }
    }


    //Creamos funcion para verificar si el restaurante esta abierto o cerrado en este momento
    function abiertoCerrado($emailrest)
    {
        try {
            // Obtén el nombre del día actual en español
            $dias = array('domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado');
            $diaActual = $dias[date('w')];

            // Consulta para obtener los horarios del restaurante en el día actual
            $sql = "SELECT abiertocerrado, hapertura, hcierre FROM horario WHERE emailrest = :emailrest AND dia = :dia";

            // Preparar la consulta
            $query = $this->db->prepare($sql);
            $query->bindParam(':emailrest', $emailrest, PDO::PARAM_STR);
            $query->bindParam(':dia', $diaActual, PDO::PARAM_STR);
            $query->execute();

            // Obtener los resultados de la consulta
            $resultado = $query->fetch(PDO::FETCH_ASSOC);

            // Verificar si el restaurante está abierto en el día actual y dentro del rango de horas
            if ($resultado && $resultado['abiertocerrado'] === 'abierto') {
                $horaActual = date('H:i:s');
                $horaApertura = $resultado['hapertura'];
                $horaCierre = $resultado['hcierre'];

                if ($horaActual >= $horaApertura && $horaActual <= $horaCierre) {
                    return "Abierto";
                }
            }

            return "Cerrado";
        } catch (PDOException $e) {
            // Manejo de excepciones
            // Puedes registrar o notificar el error según tus necesidades
            error_log('Error al verificar si el restaurante está abierto o cerrado: ' . $e->getMessage());
            return false; // En caso de error, puedes manejarlo de acuerdo a tus necesidades
        }
    }


}


?>