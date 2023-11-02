<?php

class asistencia_model
{

    //declaramos las variables privadas
    protected $db;
    protected $hospedajeinstancia;

    //Creamos el constructor con la BD
    public function __construct()
    {
        //Llamamos al archivo donde se encuentra la clase conectar
        //con el metodo que genera la conexion con la BD
        require_once("db/db.php");

        //Creamos una instancia de la clase "Conectar", y usamos el metodo "conexion"
        $this->db = Conectar::conexion();

        //Llamamos a la clase hospeaje_model.php
        require_once("models/hospedaje_model.php");

        //Creamos una instancia de la clase hospedaje_model
        $this->hospedajeinstancia = new hospedaje_model();

    }

    //Creamos la funcion para validar si un turista ya ha realizado una
    //asistencia a un restaurante el periodo de hospedaje
    public function verificarSiHayAsistenciaPrevia($emailtur, $emailrest, $fInicioHospedaje) {
        try {
            // Creamos la consulta con PDO para verificar si el turista ya ha realizado una asistencia
            $consulta = $this->db->prepare("SELECT * FROM asistencia WHERE emailtur = ? AND emailrest = ? AND finiciohospedaje = ?");
    
            // Ejecutamos la consulta
            $consulta->execute([$emailtur, $emailrest, $fInicioHospedaje]);
    
            // Si la consulta devuelve un resultado, significa que el turista ya ha realizado una asistencia
            return $consulta->rowCount() > 0;
        } catch (PDOException $e) {
            // Manejar excepciones de la base de datos aquí
            // Por ejemplo, puedes registrar el error o lanzar una excepción personalizada.
            // Ejemplo: throw new MiExcepciónPersonalizada($e->getMessage());
            return false; // O devolver un valor por defecto, dependiendo de tus necesidades.
        }
    }
    

    //Creamos funcion para obtener los datos una asistencia
    public function obtenerDatosAsistencia($emailtur, $emailrest, $fInicioHospedaje) {
        try {
            // Creamos la consulta con PDO para obtener los datos de una asistencia
            $consulta = $this->db->prepare("SELECT * FROM asistencia WHERE emailtur = ? AND emailrest = ? AND finiciohospedaje = ?");
            $consulta->execute([$emailtur, $emailrest, $fInicioHospedaje]);
    
            // Obtenemos los datos de la asistencia
            $datosAsistencia = $consulta->fetch(PDO::FETCH_ASSOC);
    
            return $datosAsistencia;
        } catch (PDOException $e) {
            // Manejar excepciones de la base de datos aquí
            // Por ejemplo, puedes registrar el error o lanzar una excepción personalizada.
            // Ejemplo: throw new MiExcepciónPersonalizada($e->getMessage());
            return null; // O devolver un valor por defecto, dependiendo de tus necesidades.
        }
    }
    
    //Creamos funcion para que el turista solicite la asistencia a un restaurante
    public function solicitarAsistencia($emailtur, $emailrest, $fInicioHospedaje)
    {
        try {
            // Iniciamos una transacción
            $this->db->beginTransaction();
    
            // Creamos la consulta con PDO para insertar los datos de la asistencia en la BD
            $consulta = $this->db->prepare("INSERT INTO asistencia (emailtur, emailrest, validaresena, finiciohospedaje) VALUES (?, ?, ?, ?)");
    
            // Ejecutamos la consulta
            $consulta->execute([$emailtur, $emailrest, 'no', $fInicioHospedaje]);
    
            // Confirmamos la transacción
            $this->db->commit();
    
            // Si la consulta se ejecuta correctamente, significa que la asistencia se registró correctamente
            if ($consulta->rowCount() > 0) {
                // Retornamos true
                return true;
            } else {
                // Retornamos false
                return false;
            }
        } catch (PDOException $e) {
            // En caso de error, anulamos la transacción
            $this->db->rollBack();
            // Manejar excepciones de base de datos aquí
            // Por ejemplo, puedes registrar el error o lanzar una excepción personalizada.
            // Ejemplo: throw new MiExcepciónPersonalizada($e->getMessage());
            return false;
        }
    }
    

    //Creamos la funcion para obtener el total de las solicitudes de asistencia de un turista al restaurante
    public function obtenerTotalSolicitudesParaRestaurantes($emailrest)
    {
        try {
            // Creamos la consulta con PDO para obtener los registros de restaurantes en el mismo barrio que el turista
            $consulta = $this->db->prepare("SELECT COUNT(*) AS total FROM asistencia 
            INNER JOIN hospeda
            ON asistencia.emailtur = hospeda.emailtur
            WHERE asistencia.validaresena='no' AND hospeda.ffinhospedaje > NOW() AND asistencia.emailrest=?;");

            $consulta->bindParam(1, $emailrest);

            // Ejecutamos la consulta
            $consulta->execute();

            // Obtenemos los registros de restaurantes en el mismo barrio que el turista
            $solicitudes = $consulta->fetch(PDO::FETCH_OBJ);

            // Retornamos los registros de restaurantes en el mismo barrio que el turista
            return $solicitudes->total;
        } catch (PDOException $e) {
            // Manejar excepciones de base de datos aquí
            // Por ejemplo, puedes registrar el error o lanzar una excepción personalizada.
            // Ejemplo: throw new MiExcepcionPersonalizada($e->getMessage());
            return 0; // O devolver un valor por defecto, dependiendo de tus necesidades.
        }
    }


    //Creamos la funcion para obtener las solicitudes de asistencia de un turista al restaurante
    public function obtenerSolicitudesPaginadosParaRestaurantes($pagina, $cant_reg_paginas, $emailrest)
    {
        try {
            $pagina = intval($pagina);
    
            $offset = ($pagina - 1) * $cant_reg_paginas;
    
            $sql = "SELECT a.emailtur, h.ffinhospedaje, u.alias
            FROM asistencia AS a
            INNER JOIN hospeda AS h
            ON a.emailtur = h.emailtur AND a.finiciohospedaje = h.finiciohospedaje
            INNER JOIN usuario AS u
            ON h.emailtur = u.email
            WHERE a.emailrest = :emailrest
            AND a.validaresena = 'no' AND h.ffinhospedaje > NOW() 
            LIMIT :offset, :cant_reg_paginas;";
    
            $consulta = $this->db->prepare($sql);
    
            //Ejecutamos la consulta
            $consulta->execute([
                ':emailrest' => $emailrest,
                ':offset' => $offset,
                ':cant_reg_paginas' => $cant_reg_paginas
            ]);
    
            // Obtiene los registros solicitados
            $solicitudes = $consulta->fetchAll(PDO::FETCH_OBJ);
    
            // Retorna los registros solicitados
            return $solicitudes;
        } catch (PDOException $e) {
            // Manejar excepciones de base de datos aquí
            // Por ejemplo, puedes registrar el error o lanzar una excepción personalizada.
            // Ejemplo: throw new MiExcepcionPersonalizada($e->getMessage());
            return []; // O devolver un valor por defecto, dependiendo de tus necesidades.
        }
    }
    

    // Función para buscar el total de solicitudes de restaurantes que coincidan con un criterio de búsqueda
    public function buscarTotalSolicitudesParaRestaurantes($emailrest, $criterio)
    {
        try {
            // Utiliza PDO para evitar la inyección de SQL
            $criterio = "%$criterio%"; // Agrega comodines para búsqueda parcial

            // Prepara la consulta SQL con PDO
            $sql = "SELECT COUNT(*) AS total
                FROM asistencia AS a
                INNER JOIN hospeda AS h ON a.emailtur = h.emailtur AND a.finiciohospedaje = h.finiciohospedaje
                INNER JOIN usuario AS u ON h.emailtur = u.email
                WHERE a.emailrest = :emailrest
                AND a.validaresena = 'no'
                AND h.ffinhospedaje > NOW()
                AND (u.alias LIKE :criterio OR a.emailtur LIKE :criterio1)";

            $consulta = $this->db->prepare($sql);

            // Bind de los parámetros
            $consulta->bindParam(':emailrest', $emailrest, PDO::PARAM_STR);
            $consulta->bindParam(':criterio', $criterio, PDO::PARAM_STR);
            $consulta->bindParam(':criterio1', $criterio, PDO::PARAM_STR);

            // Ejecuta la consulta
            $consulta->execute();

            // Obtiene el total de resultados de la búsqueda
            $totalSolicitudes = $consulta->fetch(PDO::FETCH_OBJ);

            // Retorna el total de solicitudes
            return $totalSolicitudes->total;
        } catch (PDOException $e) {
            // Maneja cualquier error de la base de datos aquí
            // Puedes registrar el error o lanzar una excepción personalizada si lo deseas
            return 0;
        }
    }

    // Función para buscar y paginar solicitudes de restaurantes que coincidan con un criterio de búsqueda
    public function buscarSolicitudesPaginadasParaRestaurantes($emailrest, $criterio, $pagina, $cant_reg_paginas)
    {
        try {
            // Calcula el offset
            $offset = ($pagina - 1) * $cant_reg_paginas;

            // Utiliza PDO para evitar la inyección de SQL
            $criterio = "%$criterio%"; // Agrega comodines para búsqueda parcial

            // Prepara la consulta SQL con PDO
            $sql = "SELECT a.emailtur, h.ffinhospedaje, u.alias
                FROM asistencia AS a
                INNER JOIN hospeda AS h ON a.emailtur = h.emailtur AND a.finiciohospedaje = h.finiciohospedaje
                INNER JOIN usuario AS u ON h.emailtur = u.email
                WHERE a.emailrest = :emailrest
                AND a.validaresena = 'no'
                AND h.ffinhospedaje > NOW()
                AND (u.alias LIKE :criterio OR a.emailtur LIKE :criterio1)
                ORDER BY h.ffinhospedaje DESC
                LIMIT :offset, :cant_reg_paginas";

            $consulta = $this->db->prepare($sql);

            // Bind de los parámetros
            $consulta->bindParam(':emailrest', $emailrest, PDO::PARAM_STR);
            $consulta->bindParam(':criterio', $criterio, PDO::PARAM_STR);
            $consulta->bindParam(':criterio1', $criterio, PDO::PARAM_STR);
            $consulta->bindParam(':offset', $offset, PDO::PARAM_INT);
            $consulta->bindParam(':cant_reg_paginas', $cant_reg_paginas, PDO::PARAM_INT);

            // Ejecuta la consulta
            $consulta->execute();

            // Obtiene los resultados de la búsqueda paginada
            $solicitudes = $consulta->fetchAll(PDO::FETCH_OBJ);

            // Retorna los resultados paginados
            return $solicitudes;
        } catch (PDOException $e) {
            // Maneja cualquier error de la base de datos aquí
            // Puedes registrar el error o lanzar una excepción personalizada si lo deseas
            return [];
        }
    }

    // Función para aceptar una solicitud de asistencia
    public function aceptarSolicitud($emailrest, $emailtur)
    {
        try {
            // Comienza una transacción
            $this->db->beginTransaction();

            // Actualiza el campo validaresena a 'si' en la tabla asistencia
            $sql = "UPDATE asistencia SET validaresena = 'si' WHERE emailrest = :emailrest AND emailtur = :emailtur";

            $query = $this->db->prepare($sql);

            // Bind de los parámetros
            $query->bindParam(':emailrest', $emailrest, PDO::PARAM_STR);
            $query->bindParam(':emailtur', $emailtur, PDO::PARAM_STR);

            // Ejecuta la consulta
            $query->execute();

            // Confirma la transacción
            $this->db->commit();

            // Retorna un mensaje de éxito
            return true;
        } catch (PDOException $e) {
            // En caso de error, revierte la transacción y maneja el error
            $this->db->rollback();
            return false;
        }
    }

}

?>