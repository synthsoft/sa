<?php
//Llamamos al archivo donde se encuentra la clase asistencia_model
require_once("models/asistencia_model.php");

//Creamos la clase resena_model
class resena_model extends asistencia_model
{

    protected $db;

    protected $resenas;

    //Creamos el metodo constructor
    public function __construct()
    {

        //Llamamos al archivo donde se encuentra la clase conectar
        //con el metodo que genera la conexion con la BD
        require_once("db/db.php");

        //Creamos una instancia de la clase "Conectar", y usamos el metodo "conexion"
        $this->db = Conectar::conexion();

        //Inicializamos el arreglo de resenas
        $this->resenas = array();

    }

    //Creamos la funcion para validar si un turista ya ha realizado una reseña

    public function verificarSiHayResenaPrevia($emailtur, $emailrest)
    {
        try {
            // Creamos la consulta con PDO para verificar si el turista ya ha realizado una reseña
            $consulta = $this->db->prepare("SELECT * FROM resena WHERE emailtur = ? AND emailrest = ?");
            $consulta->execute([$emailtur, $emailrest]);

            // Si la consulta devuelve un resultado, significa que el turista ya ha realizado una reseña
            return $consulta->rowCount() > 0;
        } catch (PDOException $e) {
            // Manejar excepciones de la base de datos aquí
            // Por ejemplo, puedes registrar el error o lanzar una excepción personalizada.
            // Ejemplo: throw new MiExcepciónPersonalizada($e->getMessage());
            return null; // O devolver false en caso de error.
        }
    }


    //Creamos la funcion obtrener resenas restaurante
    public function obtenerResenaRestaurante($emailrest, $emailtur)
    {
        try {
            // Creamos la consulta con PDO para obtener las reseñas de un restaurante
            $sql = "SELECT * FROM resena WHERE emailrest = :emailrest AND emailtur = :emailtur";

            // Preparar la consulta
            $query = $this->db->prepare($sql);
            $query->bindParam(':emailrest', $emailrest, PDO::PARAM_STR);
            $query->bindParam(':emailtur', $emailtur, PDO::PARAM_STR);
            $query->execute();

            // Obtener el registro de reseña de un turista a un restaurante
            $resena = $query->fetch(PDO::FETCH_OBJ);

            // Retornamos el registro de reseña de un turista a un restaurante
            return $resena;
        } catch (PDOException $e) {
            // Manejo de excepciones
            // Puedes registrar o notificar el error según tus necesidades
            error_log('Error al obtener la reseña de un turista a un restaurante: ' . $e->getMessage());
            return null; // En caso de error, puedes manejarlo de acuerdo a tus necesidades, aquí retornamos null
        }
    }


    //Creamos la funcion obtrener resenas restaurante
    public function obtenerResenasPorAreaRestaurante($emailrest, $area)
    {
        try {
            // Eliminamos comillas simples o dobles de la variable $area
            $area = str_replace("'", "", $area);

            // Creamos la consulta con PDO para obtener el promedio de puntuaciones en un área específica
            $consulta = $this->db->prepare("SELECT ROUND(AVG($area), 1) AS promedio FROM resena WHERE emailrest = ?");

            $consulta->bindParam(1, $emailrest);

            // Ejecutamos la consulta
            $consulta->execute();

            // Obtenemos el registro de resena de un turista a un restaurante
            $resena = $consulta->fetch(PDO::FETCH_OBJ);

            // Retornamos el registro de resena de un turista a un restaurante
            return $resena;
        } catch (PDOException $e) {
            // Manejar o registrar el error, por ejemplo, con un mensaje de error o registro de errores.
            // Aquí puedes agregar código para manejar el error, como registrar el error en un archivo de registro.
            // Luego, puedes lanzar una excepción o devolver un valor predeterminado según tus necesidades.
            // Ejemplo:
            // throw new Exception("Error al obtener el promedio de puntuaciones: " . $e->getMessage());
            return null; // Devolver un valor nulo en caso de error.
        }
    }



    public function obtenerResenasPromedio($emailrest)
    {
        try {
            // Creamos la consulta con PDO para obtener el promedio de las reseñas de un restaurante
            $consulta = $this->db->prepare("SELECT 
                ROUND(
                    (AVG(restorangral) + AVG(instalaciones) + AVG(menugastronomico) + AVG(atencion)) / 4, 1
                ) AS promedio_general
            FROM resena
            WHERE emailrest = ?");

            $consulta->bindParam(1, $emailrest);

            // Ejecutamos la consulta
            $consulta->execute();

            // Obtenemos el registro del promedio de reseñas
            $promedio = $consulta->fetch(PDO::FETCH_OBJ);

            // Retornamos el promedio de reseñas
            return $promedio;
        } catch (PDOException $e) {
            // Manejar o registrar el error, por ejemplo, con un mensaje de error o registro de errores.
            // Aquí puedes agregar código para manejar el error, como registrar el error en un archivo de registro.
            // Luego, puedes lanzar una excepción o devolver un valor predeterminado según tus necesidades.
            // Ejemplo:
            // throw new Exception("Error al obtener el promedio de reseñas: " . $e->getMessage());
            return null; // Devolver un valor nulo en caso de error.
        }
    }


    //Creamos la funcion para obtener los registros de resenas 
    public function obtenerTotalResenasRestaurante($emailrest)
    {
        try {
            // Creamos la consulta con PDO para obtener el total de reseñas de un restaurante
            $sql = "SELECT COUNT(*) AS total FROM resena WHERE emailrest = :emailrest";

            // Preparar la consulta
            $query = $this->db->prepare($sql);
            $query->bindParam(':emailrest', $emailrest, PDO::PARAM_STR);
            $query->execute();

            // Obtener el resultado del total de reseñas
            $resenas = $query->fetch(PDO::FETCH_OBJ);

            // Retornamos el total de reseñas
            return $resenas->total;
        } catch (PDOException $e) {
            // Manejo de excepciones
            // Puedes registrar o notificar el error según tus necesidades
            error_log('Error al obtener el total de reseñas de un restaurante: ' . $e->getMessage());
            return 0; // En caso de error, puedes manejarlo de acuerdo a tus necesidades, aquí retornamos 0
        }
    }


    //OBTENEMOS LOS DATOS DE LAS RESEÑAS DE UN RESTAURANTE PARA LA PAGINACIONf
    public function obtenerResenasPaginadasRestaurante(
        $pagina,
        $cant_reg_paginas,
        $emailrest
    ) {
        try {
            $pagina = intval($pagina);
            $offset = ($pagina - 1) * $cant_reg_paginas;

            $sql = "SELECT r.emailtur, r.restorangral, r.instalaciones, r.menugastronomico, r.atencion, r.fyhpuntaje, u.alias, u.fotoperfilologo
                    FROM resena AS r
                    INNER JOIN turista t
                    ON r.emailtur = t.emailtur
                    INNER JOIN usuario u
                    ON t.emailtur = u.email
                    WHERE r.emailrest = :emailrest
                    LIMIT :offset, :cant_reg_paginas;";

            $consulta = $this->db->prepare($sql);

            // Ejecutamos la consulta
            $consulta->execute([
                ':emailrest' => $emailrest,
                ':offset' => $offset,
                ':cant_reg_paginas' => $cant_reg_paginas
            ]);

            // Obtenemos los registros solicitados
            $resenas = $consulta->fetchAll(PDO::FETCH_OBJ);

            // Retornamos los registros solicitados
            return $resenas;
        } catch (PDOException $e) {
            // Manejar excepciones de base de datos aquí
            // Por ejemplo, puedes registrar el error o lanzar una excepción personalizada.
            // Ejemplo: throw new MiExcepciónPersonalizada($e->getMessage());
            return []; // O devolver un valor por defecto, dependiendo de tus necesidades.
        }
    }

    public function obtenerTotalResenasRestauranteBusqueda(
        $emailrest,
        $busqueda
    ) {
        try {
            // Limpiamos la variable de búsqueda con htmlspecialchars UTF-8 y ENT_QUOTES
            $busqueda = htmlspecialchars($busqueda, ENT_QUOTES, 'UTF-8');

            // Le ponemos comodines a la variable $busqueda
            $busqueda = "%" . $busqueda . "%";

            // Creamos la consulta con PDO para obtener los registros de restaurantes en el mismo barrio que el turista
            $consulta = $this->db->prepare("SELECT COUNT(*) AS total FROM resena r
            JOIN turista t ON r.emailtur = t.emailtur
            JOIN usuario u ON t.emailtur = u.email
            WHERE emailrest= :emailrest AND (u.alias LIKE :busquedaalias OR t.emailtur LIKE :busquedacorreo);");

            $consulta->bindParam(':emailrest', $emailrest);
            $consulta->bindParam(':busquedaalias', $busqueda);
            $consulta->bindParam(':busquedacorreo', $busqueda);

            // Ejecutamos la consulta
            $consulta->execute();

            // Obtenemos el total de registros
            $resenas = $consulta->fetch(PDO::FETCH_OBJ);

            // Retornamos el total de registros
            return $resenas->total;
        } catch (PDOException $e) {
            // Manejar excepciones de base de datos aquí
            // Por ejemplo, puedes registrar el error o lanzar una excepción personalizada.
            // Ejemplo: throw new MiExcepciónPersonalizada($e->getMessage());
            return 0; // O devolver un valor por defecto, dependiendo de tus necesidades.
        }
    }


    //Creamos la funcion para obtener los registros con paginacion de los restaurantes en el mismo barrio que el turista
    public function obtenerResenasPaginadasRestauranteBusqueda(
        $pagina,
        $cant_reg_paginas,
        $emailrest,
        $busqueda
    ) {
        try {
            // Limpiamos la variable de búsqueda con htmlspecialchars UTF-8 y ENT_QUOTES
            $busqueda = htmlspecialchars($busqueda, ENT_QUOTES, 'UTF-8');

            // Le ponemos comodines a la variable $busqueda
            $busqueda = "%" . $busqueda . "%";

            $pagina = intval($pagina);

            $offset = ($pagina - 1) * $cant_reg_paginas;

            $sql = "SELECT r.emailrest, r.emailtur, r.restorangral, r.instalaciones, r.menugastronomico,
            r.atencion, r.fyhpuntaje, u.alias, u.fotoperfilologo FROM resena r
            JOIN turista t ON r.emailtur = t.emailtur
            JOIN usuario u ON t.emailtur = u.email
            WHERE emailrest= :emailrest AND (u.alias LIKE :busquedaalias OR t.emailtur LIKE :busquedacorreo)
            LIMIT :offset, :cant_reg_paginas;";

            $consulta = $this->db->prepare($sql);

            // Ejecutamos la consulta
            $consulta->execute([
                ':emailrest' => $emailrest,
                ':busquedaalias' => $busqueda,
                ':busquedacorreo' => $busqueda,
                ':offset' => $offset,
                ':cant_reg_paginas' => $cant_reg_paginas
            ]);

            // Obtenemos los registros solicitados
            $resenas = $consulta->fetchAll(PDO::FETCH_OBJ);

            // Retornamos los registros solicitados
            return $resenas;
        } catch (PDOException $e) {
            // Manejar excepciones de base de datos aquí
            // Por ejemplo, puedes registrar el error o lanzar una excepción personalizada.
            // Ejemplo: throw new MiExcepciónPersonalizada($e->getMessage());
            return []; // O devolver un valor por defecto, dependiendo de tus necesidades.
        }
    }


    //ESTA FUNCION SIRVE PARA ORGANIZAR POR FECHA DE MAYOR A MENOR O VICEVERSA
    public function obtenerResenasPaginadasRestaurantePorOrdenAscDesc(
        $pagina,
        $cant_reg_paginas,
        $emailrest,
        $orden
    ) {
        try {

            if ($orden != "ASC" && $orden != "DESC") {
                return false;
            }

            // LIMPIAMOS LAS COMILLAS SIMPLES O DOBLES A LA VARIABLE $orden
            $orden = str_replace("'", "", $orden);

            $pagina = intval($pagina);
            $offset = ($pagina - 1) * $cant_reg_paginas;

            $sql = "SELECT r.emailtur, r.restorangral, r.instalaciones, r.menugastronomico, r.atencion, r.fyhpuntaje, u.alias, u.fotoperfilologo
            FROM resena AS r
            INNER JOIN turista t
            ON r.emailtur = t.emailtur
            INNER JOIN usuario u
            ON t.emailtur = u.email
            WHERE r.emailrest = :emailrest
            ORDER BY fyhpuntaje $orden
            LIMIT :offset, :cant_reg_paginas;";

            $consulta = $this->db->prepare($sql);

            // Ejecutamos la consulta
            $consulta->execute([
                ':emailrest' => $emailrest,
                ':offset' => $offset,
                ':cant_reg_paginas' => $cant_reg_paginas
            ]);

            // Obtenemos los registros solicitados
            $resenas = $consulta->fetchAll(PDO::FETCH_OBJ);

            // Retorna los registros solicitados
            return $resenas;
        } catch (PDOException $e) {
            // Manejar excepciones de base de datos aquí
            // Por ejemplo, puedes registrar el error o lanzar una excepción personalizada.
            // Ejemplo: throw new MiExcepciónPersonalizada($e->getMessage());
            return []; // O devolver un valor por defecto, dependiendo de tus necesidades.
        }
    }

    //ESTA FUNCION SIRVE PARA ORGANIZAR POR PROMEDIOS DE PUNTAJE DE MAYOR A MENOR O VICEVERSA
    public function obtenerResenasPaginadasRestaurantePorMejorPeor(
        $pagina,
        $cant_reg_paginas,
        $emailrest,
        $orden
    ) {
        try {
            if ($orden != "ASC" && $orden != "DESC") {
                return false;
            }

            $pagina = intval($pagina);
            $offset = ($pagina - 1) * $cant_reg_paginas;

            $sql = "SELECT r.emailtur, r.restorangral, r.instalaciones, r.menugastronomico, r.atencion, r.fyhpuntaje, u.alias, u.fotoperfilologo, (r.restorangral + r.instalaciones + r.menugastronomico + r.atencion) / 4 AS promedio_total
            FROM resena AS r
            INNER JOIN turista t ON r.emailtur = t.emailtur
            INNER JOIN usuario u ON t.emailtur = u.email
            WHERE r.emailrest = :emailrest
            ORDER BY promedio_total $orden
            LIMIT :offset, :cant_reg_paginas;";

            $consulta = $this->db->prepare($sql);

            // Ejecutamos la consulta
            $consulta->execute([
                ':emailrest' => $emailrest,
                ':offset' => $offset,
                ':cant_reg_paginas' => $cant_reg_paginas
            ]);

            // Obtenemos los registros solicitados
            $resenas = $consulta->fetchAll(PDO::FETCH_OBJ);

            // Retorna los registros solicitados
            return $resenas;
        } catch (PDOException $e) {
            // Manejar excepciones de base de datos aquí
            // Por ejemplo, puedes registrar el error o lanzar una excepción personalizada.
            // Ejemplo: throw new MiExcepciónPersonalizada($e->getMessage());
            return []; // O devolver un valor por defecto, dependiendo de tus necesidades.
        }
    }

    //Esta funcion sirve para que el turista en su perfil pueda ver un historial
    //de las reseñas que ha realizado
    public function obtenerTotalResenasTurista($emailtur)
    {
        try {
            // Creo la consulta para obtener los datos del restaurante en la BD
            $sql = "SELECT COUNT(*) AS total FROM resena WHERE emailtur = :emailtur;";

            // Preparo la consulta
            $query = $this->db->prepare($sql);

            // Ejecuto la consulta
            $query->execute(['emailtur' => $emailtur]);

            // Obtengo el total de resenas
            $totalresenas = $query->fetch(PDO::FETCH_OBJ);

            // Retorno el total de resenas
            return $totalresenas->total;
        } catch (PDOException $e) {
            // Manejar excepciones de base de datos aquí
            // Por ejemplo, puedes registrar el error o lanzar una excepción personalizada.
            // Ejemplo: throw new MiExcepcionPersonalizada($e->getMessage());
            return 0; // O devolver un valor por defecto, dependiendo de tus necesidades.
        }
    }


    //Creamos la funcion para obtener los registros con paginacion de los restaurantes en el mismo barrio que el turista
    public function obtenerResenasPaginadasTurista(
        $pagina,
        $cant_reg_paginas,
        $emailtur
    ) {
        $offset = ($pagina - 1) * $cant_reg_paginas;

        try {
            $sql = "SELECT r.*, res.nombrerestaurante FROM resena r
                    INNER JOIN restaurante res
                    ON r.emailrest = res.emailrest
                    WHERE 
                    emailtur = :emailtur
                    LIMIT :offset , :cant_reg_paginas;";

            //Preparo la consulta
            $query = $this->db->prepare($sql);

            //Ejecuto la consulta
            $query->execute(['emailtur' => $emailtur, 'offset' => $offset, 'cant_reg_paginas' => $cant_reg_paginas]);

            //Obtengo las reseñas
            $resenas = $query->fetchAll(PDO::FETCH_OBJ);

            //Retorno las reseñas
            return $resenas;
        } catch (PDOException $e) {
            // Manejar excepciones de base de datos aquí
            // Por ejemplo, puedes registrar el error o lanzar una excepción personalizada.
            // Ejemplo: throw new MiExcepcionPersonalizada($e->getMessage());
            return []; // O devolver un valor por defecto, dependiendo de tus necesidades.
        }
    }


    public function enviarResena($emailtur, $emailrest, $restorangral, $instalaciones, $menugastronomico, $atencion)
    {
        $this->db->beginTransaction(); // Iniciar transacción

        try {
            // Validación de datos
            // ... (Código de validación)

            // Consulta SQL preparada para evitar SQL Injection
            $query = "INSERT INTO resena (emailtur, emailrest, restorangral, instalaciones, menugastronomico, atencion, fyhpuntaje) VALUES (?, ?, ?, ?, ?, ?, NOW())";
            $stmt = $this->db->prepare($query);

            if (!$stmt) {
                throw new Exception("Error en la preparación de la consulta");
            }

            // Enlazar parámetros
            $stmt->bindParam(1, $emailtur);
            $stmt->bindParam(2, $emailrest);
            $stmt->bindParam(3, $restorangral);
            $stmt->bindParam(4, $instalaciones);
            $stmt->bindParam(5, $menugastronomico);
            $stmt->bindParam(6, $atencion);


            if (!$stmt->execute()) {
                throw new Exception("Error al ejecutar la consulta");
            }

            $this->db->commit(); // Confirmar la transacción
            return true; // Reseña enviada con éxito
        } catch (Exception $e) {
            // Manejo de excepciones
            error_log("Error al enviar la reseña: " . $e->getMessage());

            // Realizar un rollback en caso de error
            $this->db->rollback();

            return false; // Hubo un error en el proceso
        }
    }


}


?>