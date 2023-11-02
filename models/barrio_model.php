<?php 
//Creamos la clase barrio_model

class barrio_model
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

    //Creamos una funcion para chequear si el barrio existe en la BD
    public function verificarBarrio($ciudad, $nombrebarrio)
    {
        try {
            // Creamos la consulta para verificar si el barrio existe en la BD
            $sql = "SELECT * FROM barrio WHERE ciudad = :ciudad AND nombrebarrio = :nombrebarrio";
    
            // Preparamos la consulta
            $query = $this->db->prepare($sql);
    
            // Ejecutamos la consulta con parámetros
            $query->execute(['ciudad' => $ciudad, 'nombrebarrio' => $nombrebarrio]);
    
            // Verificamos si se encontró el barrio en la BD
            if ($query->rowCount() > 0) {
                // Retornamos true si se encontró el barrio en la BD
                return true;
            } else {
                // Retornamos false si no se encontró el barrio en la BD
                return "noexiste";
            }
        } catch (PDOException $e) {
          
            // Manejo de excepciones
            echo 'Error al ejecutar la consulta: ' . $e->getMessage();
            return false;
        }
    }

    //Creamos una funcion para insertar el barrio en la BD
    public function insertarBarrio($ciudad,$nombrebarrio)
    {

        //Limpiamos las entradas de datos para evitar inyecciones con htmlspecialchars que sirve para 
        $ciudad = htmlspecialchars($ciudad);
        $nombrebarrio = htmlspecialchars($nombrebarrio);

        //Creamos la consulta para insertar el barrio en la BD
        $sql = "INSERT INTO barrio (ciudad, nombrebarrio) VALUES (:ciudad, :nombrebarrio)";

        //Preparamos la consulta
        $query = $this->db->prepare($sql);

        //Ejecutamos la consulta
        $query->execute(['ciudad' => $ciudad, 'nombrebarrio' => $nombrebarrio]);

        //Verificamos si se inserto el barrio en la BD
        if ($query->rowCount() > 0) {
            //Retornamos true si se inserto el barrio en la BD
            return true;
        } else {
            //Retornamos false si no se inserto el barrio en la BD
            return false;
        }

    }

    
}

?>