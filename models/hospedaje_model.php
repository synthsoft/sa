<?php

//Creamos la clase HotelModel

class hospedaje_model
{

    //Creamos una variable protegida para la conexion
    protected $db;

    //Creamos el constructor de la clase
    public function __construct()
    {

        //Llamamos al archivo donde se encuentra la clase conectar
        //con el metodo que genera la conexion con la BD
        require_once("db/db.php");

        //Creamos una instancia de la clase "Conectar", y usamos el metodo "conexion"
        $this->db = Conectar::conexion();
    }

    //Creamos la funcion para ingresar una estadia en la base de datos
    public function nuevoHospedaje($emailtur, $fInicioHospedaje, $fFinHospedje, $direccionHotel, $ciudad, $nombrebarrio)
    {
        try {
            // Iniciamos una transacción
            $this->db->beginTransaction();

            // Creamos la consulta con PDO, para registrar el hospedaje en la BD
            $consulta = $this->db->prepare("INSERT INTO hospeda (emailtur, finiciohospedaje, ffinhospedaje, dirhotel, ciudad, nombrebarrio) VALUES (?, ?, ?, ?, ?, ?)");
            $consulta->execute([$emailtur, $fInicioHospedaje, $fFinHospedje, $direccionHotel, $ciudad, $nombrebarrio]);

            // Comprobamos si la consulta se ejecutó correctamente
            if ($consulta->rowCount() > 0) {
                // Confirmamos la transacción si todo salió bien
                $this->db->commit();
                // Retornamos true
                return true;
            } else {
                // Si la consulta no se ejecuta correctamente, revertimos la transacción
                $this->db->rollBack();
                // Retornamos false
                return false;
            }
        } catch (PDOException $e) {
            // En caso de error, revertimos la transacción y manejamos la excepción
            $this->db->rollBack();
            // Aquí puedes registrar el error, lanzar una excepción personalizada o realizar otro manejo específico del error
            return false; // O devolver un valor por defecto, dependiendo de tus necesidades
        }
    }


    //Creamos la funcion para saber si hay un hospedaje en curso en la base de datos
    public function verificarHospedajeEnCurso($emailtur)
    {
        try {
            // Validar la entrada, si esta vacia o no tiene el formato adecuado retornamos datosinvalidos
            //usamos filter_validate_email que es un tipo de filtro de php para validar el email
            if (empty($emailtur) || !filter_var($emailtur, FILTER_VALIDATE_EMAIL)) {
                return 'datosinvalidos';
            }

            // Crear la consulta SQL con PDO para verificar si hay un hospedaje en curso en la BD
            $consulta = $this->db->prepare("SELECT * FROM hospeda WHERE emailtur = ? AND ffinhospedaje > NOW()");
            $consulta->execute([$emailtur]);

            // Verificar si la consulta devuelve un resultado
            if ($consulta->rowCount() > 0) {
                // Retornar true si hay un hospedaje en curso en la BD
                return true;
            } else {
                // Retornar false si no hay un hospedaje en curso en la BD
                return false;
            }
        } catch (PDOException $e) {
            // Manejar la excepción de base de datos (por ejemplo, registrarla o notificarla)
            return 'errorbd'; // Puedes definir un código de error personalizado
        }
    }

    //Creamos una funcion para obtener los datos del hospedaje en curso
    public function obtenerDatosHospedajeEnCurso($emailtur)
    {
        try {
            // Creamos la consulta con PDO para obtener los datos del hospedaje en curso
            $sql = "SELECT *, ho.nombrehotel FROM hospeda h
            INNER JOIN hotel ho
            ON h.dirhotel = ho.dirhotel
            WHERE emailtur = :emailtur AND ffinhospedaje > NOW()";

            $query = $this->db->prepare($sql);
            $query->execute(['emailtur' => $emailtur]);

            // Verificamos si se encontró un hospedaje en curso
            $datosHospedaje = $query->fetch(PDO::FETCH_OBJ);
            if ($datosHospedaje) {
                return $datosHospedaje;
            } else {
                return "nohayhospedaje";
            }
        } catch (PDOException $e) {
            // Manejo de excepciones
            // Puedes registrar o notificar el error según tus necesidades
            error_log('Error al obtener los datos del hospedaje en curso: ' . $e->getMessage());
            return false; // Retornamos null en caso de error
        }
    }


    public function obtenerTotalHospedajes($emailtur)
{
    try {
        // Creo la consulta para obtener el total de hospedajes en la BD
        $sql = "SELECT COUNT(*) as hospedajes FROM hospeda WHERE emailtur = :emailtur AND ffinhospedaje < NOW();";

        // Preparo la consulta
        $query = $this->db->prepare($sql);

        // Ejecuto la consulta
        $query->execute(['emailtur' => $emailtur]);

        // Obtengo el total de hospedajes
        $totalhospedajes = $query->fetch(PDO::FETCH_OBJ);

        // Retorno el total de hospedajes
        return $totalhospedajes->hospedajes;
    } catch (PDOException $e) {
        // Manejar la excepción
        // Por ejemplo, puedes registrar el error o lanzar una excepción personalizada.
        // Ejemplo: throw new MiExcepcionPersonalizada($e->getMessage());
        return null; // O devolver un valor por defecto, dependiendo de tus necesidades.
    }
}


    //Creamos la funcion para obtener los registros con paginacion de los restaurantes en el mismo barrio que el turista
    public function obtenerHospedajesPaginados(
        $pagina,
        $cant_reg_paginas,
        $emailtur
    ) {
        try {
            $offset = ($pagina - 1) * $cant_reg_paginas;
    
            // Define un límite mínimo para la cantidad de registros por página
            $cant_reg_paginas_min = 4;
    
            // Asegúrate de que la cantidad de registros por página sea al menos el límite mínimo
            if ($cant_reg_paginas < $cant_reg_paginas_min) {
                $cant_reg_paginas = $cant_reg_paginas_min;
            }
    
            $sql = "SELECT *
                FROM hospeda h
                JOIN hotel ho ON h.dirhotel = ho.dirhotel
                WHERE emailtur = :emailtur AND ffinhospedaje < NOW() 
                LIMIT :offset, :cant_reg_paginas;";
    
            // Preparo la consulta
            $query = $this->db->prepare($sql);
    
            // Ejecuto la consulta
            $query->execute(['emailtur' => $emailtur, 'offset' => $offset, 'cant_reg_paginas' => $cant_reg_paginas]);
    
            // Obtengo los hospedajes
            $hospedajes = $query->fetchAll(PDO::FETCH_OBJ);
    
            // Retorno los hospedajes
            return $hospedajes;
        } catch (PDOException $e) {
            // Manejar la excepción
            // Por ejemplo, puedes registrar el error o lanzar una excepción personalizada.
            // Ejemplo: throw new MiExcepcionPersonalizada($e->getMessage());
            return []; // O devolver un valor por defecto, dependiendo de tus necesidades.
        }
    }
    

    public function bajaEstadia($emailtur)
    {
        try {
            // Iniciar la transacción
            $this->db->beginTransaction();

            // Creamos la consulta con PDO para dar la baja lógica al hospedaje
            $consulta = $this->db->prepare("UPDATE hospeda SET ffinhospedaje = NOW() WHERE emailtur = ? AND ffinhospedaje > NOW()");
            $consulta->execute([$emailtur]);

            // Si la consulta se ejecuta correctamente, significa que el hospedaje se dio de baja correctamente
            if ($consulta->rowCount() > 0) {
                // Confirmar y cerrar la transacción
                $this->db->commit();
                return true;
            }

            // Si la consulta no se ejecuta correctamente, significa que el hospedaje no se dio de baja correctamente
            else {
                // Revertir la transacción en caso de error
                $this->db->rollBack();
                return false;
            }
        } catch (PDOException $e) {
            // Manejar la excepción
            // Por ejemplo, puedes registrar el error o lanzar una excepción personalizada.
            // Ejemplo: throw new MiExcepcionPersonalizada($e->getMessage());
            $this->db->rollBack();
            return false;
        }
    }

}




?>