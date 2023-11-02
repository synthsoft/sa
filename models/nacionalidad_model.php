<?php

//Creamos la clase nacionalidad_model
class nacionalidad_model
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

    //Creamos la funcion para obtener las nacionalidades de la BD
    public function obtenerNacionalidades()
    {
        try {
            // Creamos la consulta para obtener las nacionalidades de la base de datos
            $sql = "SELECT * FROM nacionalidadrestaurante";
    
            // Preparamos la consulta
            $query = $this->db->prepare($sql);
    
            // Ejecutamos la consulta
            $query->execute();
    
            // Retornamos el resultado de la consulta
            return $query->fetchAll();
        } catch (PDOException $e) {
            // Manejar o registrar el error, por ejemplo, con un mensaje de error o registro de errores.
            // Aquí puedes agregar código para manejar el error, como registrar el error en un archivo de registro.
            // Luego, puedes lanzar una excepción o devolver un valor predeterminado según tus necesidades.
            // Ejemplo:
            // throw new Exception("Error al obtener las nacionalidades: " . $e->getMessage());
            return [];
        }
    }
    
    public function registroUsuarioRestauranteNacionalidad($email, $nacionalidad)
    {
        try {
            // Comienza una transacción
            $this->db->beginTransaction();
    
            // Crea la consulta para insertar los datos del restaurante en la BD
            $sql = "INSERT INTO es (emailrest, idnacion) VALUES (:emailrest, :idnacion)";
    
            // Prepara la consulta
            $query = $this->db->prepare($sql);
    
            // Asigna valores a los parámetros
            $query->bindParam(':emailrest', $email);
            $query->bindParam(':idnacion', $nacionalidad);
    
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
    

    //Creamos la funcion para obtener la nacionalidad de un restaurante en especifico
    public function obtenerNacionalidadRestaurante($emailrest) {
        try {
            // Creo la consulta para obtener la nacionalidad del restaurante
            $sql = "SELECT e.*, n.nacionrestaurante 
                    FROM es e
                    INNER JOIN nacionalidadrestaurante n ON e.idnacion = n.idnacion
                    WHERE e.emailrest = :emailrest";
    
            // Preparo la consulta
            $query = $this->db->prepare($sql);
    
            // Ejecuto la consulta
            $query->execute(['emailrest' => $emailrest]);
    
            // Obtenemos la nacionalidad del restaurante
            $nacionalidad = $query->fetch(PDO::FETCH_OBJ);
    
            // Retornamos la nacionalidad del restaurante
            return $nacionalidad;
        } catch (PDOException $e) {
            // Manejar excepciones de la base de datos aquí
            // Por ejemplo, puedes registrar el error o lanzar una excepción personalizada.
            // Ejemplo: throw new MiExcepciónPersonalizada($e->getMessage());
            return null; // O devolver un valor por defecto, dependiendo de tus necesidades.
        }
    }
    
    public function modificarNacionalidadRest($emailrest, $nuevaNacionalidad)
    {
        try {
            // Crear la consulta de actualización
            $sql = "UPDATE es SET idnacion = :nuevaNacionalidad
                WHERE emailrest = :emailrest";

            // Preparar la consulta
            $query = $this->db->prepare($sql);

            // Ejecutar la consulta con los valores proporcionados
            $query->execute([
                'nuevaNacionalidad' => $nuevaNacionalidad,
                'emailrest' => $emailrest
            ]);

            // Verificar si se realizó la actualización correctamente
            if ($query->rowCount() > 0) {
                return true; // La actualización se realizó con éxito
            } else {
                return false; // No se realizó ninguna actualización
            }
        } catch (PDOException $e) {
            // Manejar cualquier error de la base de datos
            // Puedes registrar el error o lanzar una excepción según tus necesidades
            return false; // Devuelve false en caso de error
        }
    }
}


?>