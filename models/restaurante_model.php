<?php
//Instantiamos la clase usuario_model
require_once('models/usuario_model.php');

class restaurante_model extends usuario_model
{

    public function __construct()
    {
        parent::__construct(); // Llama al constructor de la clase base
    }


    //Crear funcion para el registro de restaurante con PDO en la BD
    public function registroUsuarioRestaurante(
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
    ) {
        try {
            // Comienza una transacción
            $this->db->beginTransaction();

            // Creo la consulta para insertar los datos del restaurante en la BD
            $sql = "INSERT INTO restaurante (emailrest, visibilidadperfil, dirrestaurante, nombrerestaurante, psiri, emichelin, tdoro, ciudad, nombrebarrio, rutapdf, tipocomida, latitudrestaurante, longitudrestaurante, descrrestaurante, telefono1, telefono2, trestaurante)
                    VALUES (:emailrest, :visibilidadperfil, :dirrestaurante, :nombrerestaurante, :psiri, :emichelin, :tdoro, :ciudad, :nombrebarrio, :rutapdf, :tipocomida, :latitudrestaurante, :longitudrestaurante, :descrrestaurante, :telefono1, :telefono2, :tiporestaurante)";

            // Preparo la consulta
            $query = $this->db->prepare($sql);

            // Ejecuto la consulta con parámetros
            $query->execute([
                'emailrest' => $emailrest,
                'visibilidadperfil' => "invisible",
                'dirrestaurante' => $direccionrestaurante,
                'nombrerestaurante' => $nombrerestaurante,
                'psiri' => $psiri,
                'emichelin' => $emichelin,
                'tdoro' => $tdoro,
                'ciudad' => $ciudad,
                'nombrebarrio' => $nombrebarrio,
                'rutapdf' => $rutapdf,
                'tipocomida' => $tipocomida,
                'latitudrestaurante' => $latitudrestaurante,
                'longitudrestaurante' => $longitudrestaurante,
                'descrrestaurante' => $descrrestaurante,
                'tiporestaurante' => $tiporestaurante,
                'telefono1' => $telefono1,
                'telefono2' => $telefono2
            ]);

            // Confirma la transacción
            $this->db->commit();

            // Verifico si se insertaron los datos en la BD
            if ($query->rowCount() > 0) {
                // Retorna true si se insertaron los datos en la BD
                return true;
            } else {
                // Retorna false si no se insertaron los datos en la BD
                return false;
            }
        } catch (PDOException $e) {
            // Si ocurre un error, se revierte la transacción
            $this->db->rollback();

            // Manejo de excepciones
            echo 'Error al ejecutar la consulta: ' . $e->getMessage();
            return false;
        }
    }


    //Creamos la funcion para obtener los registros de restaurantes en el mismo barrio que el turista
    public function obtenerTotalRestaurantesParaTurista(
        $nombrebarrio,
        $ciudad,
        $busqueda = ''
    ) {
        try {
            // Creo la consulta para obtener los datos del restaurante en la BD
            $sql = "SELECT COUNT(*) as totalrestaurantes FROM restaurante r
            JOIN usuario u ON r.emailrest = u.email
            LEFT JOIN es ON r.emailrest = es.emailrest
            LEFT JOIN nacionalidadrestaurante n ON es.idnacion = n.idnacion
            WHERE r.nombrebarrio = :nombrebarrio AND r.ciudad = :ciudad
            AND u.estadologico = 'activo' -- Condición de estado lógico del usuario
            AND r.visibilidadperfil = 'visible'";

            // Si se proporciona una cadena de búsqueda, agregamos condiciones LIKE a la consulta
            if (!empty($busqueda)) {
                $sql .= " AND (r.nombrerestaurante LIKE :busquedanombre
                               OR r.tipocomida LIKE :busquedatipocomida
                               OR n.nacionrestaurante LIKE :busquedanacionalidad)";
            }

            //Preparo la consulta
            $query = $this->db->prepare($sql);

            //Ejecuto la consulta
            if (!empty($busqueda)) {
                // Si se proporciona una cadena de búsqueda, agregamos condiciones LIKE a la consulta
                $query->execute(['nombrebarrio' => $nombrebarrio, 'ciudad' => $ciudad, 'busquedanombre' => "%$busqueda%", 'busquedatipocomida' => "%$busqueda%", 'busquedanacionalidad' => "%$busqueda%"]);
            } else {
                // Si no se proporciona una cadena de búsqueda, ejecutamos la consulta sin condiciones LIKE
                $query->execute(['nombrebarrio' => $nombrebarrio, 'ciudad' => $ciudad]);
            }

            // Obtengo la cantidad de restaurantes en el mismo barrio que el turista
            $totalrestaurantes = $query->fetch(PDO::FETCH_OBJ);

            // Retorno la cantidad de restaurantes en el mismo barrio que el turista
            return $totalrestaurantes->totalrestaurantes;
        } catch (PDOException $e) {
            // En caso de una excepción, puedes registrar el error o manejarlo según tus necesidades.
            // Ejemplo: throw new Exception("Error al obtener el total de restaurantes para el turista: " . $e->getMessage());

            return 0; // Retorna 0 en caso de error.
        }
    }


    //Creamos la funcion para obtener los registros con paginacion de los restaurantes en el mismo barrio que el turista
    public function obtenerRestaurantesPaginadosParaTurista(
        $pagina,
        $cant_reg_paginas,
        $nombrebarrio,
        $ciudad,
        $busqueda = ''
    ) {
        try {
            $offset = ($pagina - 1) * $cant_reg_paginas;

            // Define un límite mínimo para la cantidad de registros por página
            $cant_reg_paginas_min = 4;

            // Asegúrate de que la cantidad de registros por página sea al menos el límite mínimo
            if ($cant_reg_paginas < $cant_reg_paginas_min) {
                $cant_reg_paginas = $cant_reg_paginas_min;
            }

            // Consulta SQL base
            $sql = "SELECT r.nombrerestaurante, r.dirrestaurante, r.descrrestaurante, u.fotoperfilologo, r.emailrest
                    FROM restaurante r
                    JOIN usuario u ON r.emailrest = u.email
                    LEFT JOIN resena res ON r.emailrest = res.emailrest
                    LEFT JOIN es ON r.emailrest = es.emailrest
                    LEFT JOIN nacionalidadrestaurante n ON es.idnacion = n.idnacion
                    WHERE r.nombrebarrio = :nombrebarrio AND r.ciudad = :ciudad
                    AND u.estadologico = 'activo'
                    AND r.visibilidadperfil = 'visible'";

            // Si se proporciona una cadena de búsqueda, agregamos condiciones LIKE a la consulta
            if (!empty($busqueda)) {
                $sql .= " AND (r.nombrerestaurante LIKE :busquedanombre
                           OR r.tipocomida LIKE :busquedatipocomida
                           OR n.nacionrestaurante LIKE :busquedanacionalidad)";
            }

            $sql .= " GROUP BY r.emailrest
                     ORDER BY AVG((res.restorangral + res.instalaciones + res.menugastronomico + res.atencion) / 4) DESC
                     LIMIT :offset, :cant_reg_paginas";

            // Preparo la consulta
            $query = $this->db->prepare($sql);

            // Ejecuto la consulta, pasando la cadena de búsqueda como parámetro
            // Si se proporciona una cadena de búsqueda, agregamos condiciones LIKE a la consulta
            if (!empty($busqueda)) {
                $query->execute([
                    'nombrebarrio' => $nombrebarrio,
                    'ciudad' => $ciudad,
                    'busquedanombre' => "%$busqueda%",
                    'busquedatipocomida' => "%$busqueda%",
                    'busquedanacionalidad' => "%$busqueda%",
                    'offset' => $offset,
                    'cant_reg_paginas' => $cant_reg_paginas
                ]);
            } else {
                // Si no se proporciona una cadena de búsqueda, ejecutamos la consulta sin condiciones LIKE
                $query->execute([
                    'nombrebarrio' => $nombrebarrio,
                    'ciudad' => $ciudad,
                    'offset' => $offset,
                    'cant_reg_paginas' => $cant_reg_paginas
                ]);
            }

            // Obtengo los restaurantes que coinciden con la búsqueda
            $restaurantes = $query->fetchAll(PDO::FETCH_OBJ);

            // Retorno los restaurantes en el mismo barrio que el turista
            return $restaurantes;
        } catch (PDOException $e) {
            // En caso de una excepción, puedes registrar el error o manejarlo según tus necesidades.
            // Ejemplo: throw new Exception("Error al obtener restaurantes para el turista: " . $e->getMessage());

            return []; // Retorna un array vacío en caso de error.
        }
    }

    public function obtenerTotalRestaurantesPorTipoParaTurista(
        $nombrebarrio,
        $ciudad,
        $tipoRestaurante
    ) {
        try {
            // Creo la consulta para obtener los datos del restaurante en la BD
            $sql = "SELECT COUNT(*) as totalrestaurantes FROM restaurante r
            JOIN usuario u ON r.emailrest = u.email
            WHERE r.nombrebarrio = :nombrebarrio 
            AND r.ciudad = :ciudad
            AND u.estadologico = 'activo' 
            AND r.visibilidadperfil = 'visible' 
            AND r.trestaurante = :tipoRestaurante";

            // Preparo la consulta
            $query = $this->db->prepare($sql);

            // Ejecuto la consulta
            $query->execute(['nombrebarrio' => $nombrebarrio, 'ciudad' => $ciudad, 'tipoRestaurante' => $tipoRestaurante]);

            // Obtengo la cantidad de restaurantes en el mismo barrio que el turista
            $totalrestaurantes = $query->fetch(PDO::FETCH_OBJ);

            // Retorno la cantidad de restaurantes en el mismo barrio que el turista
            return $totalrestaurantes->totalrestaurantes;
        } catch (PDOException $e) {
            // Manejar excepciones de base de datos aquí
            // Por ejemplo, puedes registrar el error o lanzar una excepción personalizada.
            // Ejemplo: throw new MiExcepcionPersonalizada($e->getMessage());
            return 0; // O devolver un valor por defecto, dependiendo de tus necesidades.
        }
    }

    public function obtenerRestaurantesPaginadosPorTipoParaTurista(
        $pagina,
        $cant_reg_paginas,
        $nombrebarrio,
        $ciudad,
        $tipoRestaurante
    ) {
        try {
            $offset = ($pagina - 1) * $cant_reg_paginas;
            $cant_reg_paginas_min = 4;

            if ($cant_reg_paginas < $cant_reg_paginas_min) {
                $cant_reg_paginas = $cant_reg_paginas_min;
            }

            $sql = "SELECT r.nombrerestaurante, r.dirrestaurante, r.descrrestaurante, u.fotoperfilologo, r.emailrest
            FROM restaurante r
            JOIN usuario u ON r.emailrest = u.email
            LEFT JOIN resena res ON r.emailrest = res.emailrest
            WHERE r.nombrebarrio = :nombrebarrio 
            AND r.ciudad = :ciudad
            AND u.estadologico = 'activo' 
            AND r.visibilidadperfil = 'visible' 
            AND r.trestaurante = :tipoRestaurante -- Condición para filtrar por tipo de restaurante
            GROUP BY r.emailrest
            ORDER BY AVG((res.restorangral + res.instalaciones + res.menugastronomico + res.atencion) / 4) DESC
            LIMIT :offset, :cant_reg_paginas";

            $query = $this->db->prepare($sql);
            $query->execute(['nombrebarrio' => $nombrebarrio, 'ciudad' => $ciudad, 'tipoRestaurante' => $tipoRestaurante, 'offset' => $offset, 'cant_reg_paginas' => $cant_reg_paginas]);

            $restaurantes = $query->fetchAll(PDO::FETCH_OBJ);

            return $restaurantes;
        } catch (PDOException $e) {
            // Manejar excepciones de base de datos, como una conexión fallida, aquí
            // Por ejemplo, puedes registrar el error o lanzar una excepción personalizada.
            // Ejemplo: throw new MiExcepcionPersonalizada($e->getMessage());
            return []; // O devolver un valor por defecto, dependiendo de tus necesidades.
        }
    }

    //Creamos la funcion para obtener la informacion de un restaurante en especifico
    public function obtenerRestaurante($emailrest)
    {
        try {
            // Consulta SQL para obtener los datos del restaurante
            $sql = "SELECT r.*, u.fotoperfilologo
                FROM restaurante r
                JOIN usuario u ON r.emailrest = u.email
                WHERE r.emailrest = :emailrest";

            // Preparamos la consulta
            $query = $this->db->prepare($sql);

            // Ejecutamos la consulta
            $query->execute(['emailrest' => $emailrest]);

            // Obtenemos los datos del restaurante
            $restaurante = $query->fetch(PDO::FETCH_OBJ);

            return $restaurante;
        } catch (PDOException $e) {
            // Manejo de excepciones
            // Puedes registrar o notificar el error según tus necesidades
            error_log('Error al obtener datos del restaurante: ' . $e->getMessage());
            return false; // Otra opción es lanzar una excepción aquí
        }
    }


    public function obtenerTotalRestaurantesParaAdmin(
        $busqueda = ''
    ) {
        try {
            // Creo la consulta para obtener los datos del restaurante en la BD
            $sql = "SELECT COUNT(*) as totalrestaurantes FROM restaurante r
                JOIN usuario u ON r.emailrest = u.email";

            // Si se proporciona una cadena de búsqueda, agregamos una cláusula WHERE
            if (!empty($busqueda)) {
                $sql .= " WHERE r.nombrerestaurante LIKE :busquedanombre OR u.alias LIKE :busquedaalias or r.emailrest LIKE :busquedaemail";
            }

            $query = $this->db->prepare($sql);

            if (!empty($busqueda)) {
                // Ejecuto la consulta
                $query->execute(['busquedanombre' => "%$busqueda%", 'busquedaalias' => "%$busqueda%", 'busquedaemail' => "%$busqueda%"]);
            } else {
                // Ejecuto la consulta
                $query->execute();
            }

            // Obtengo la cantidad de restaurantes en la misma BD
            $totalrestaurantes = $query->fetch(PDO::FETCH_OBJ);

            // Retorno la cantidad de restaurantes
            return $totalrestaurantes->totalrestaurantes;
        } catch (PDOException $e) {
            // Manejar excepciones de base de datos aquí
            // Por ejemplo, puedes registrar el error o lanzar una excepción personalizada.
            // Ejemplo: throw new MiExcepciónPersonalizada($e->getMessage());
            return 0; // O devolver un valor por defecto, dependiendo de tus necesidades.
        }
    }

    public function obtenerRestaurantesPaginadosParaAdmin(
        $pagina,
        $cant_reg_paginas,
        $busqueda = ''
    ) {
        try {
            $offset = ($pagina - 1) * $cant_reg_paginas;

            // Define un límite mínimo para la cantidad de registros por página
            $cant_reg_paginas_min = 4;

            // Asegúrate de que la cantidad de registros por página sea al menos el límite mínimo
            if ($cant_reg_paginas < $cant_reg_paginas_min) {
                $cant_reg_paginas = $cant_reg_paginas_min;
            }

            // Consulta SQL base
            $sql = "SELECT r.nombrerestaurante, u.fotoperfilologo, r.emailrest, u.alias
                    FROM restaurante r
                    JOIN usuario u ON r.emailrest = u.email";

            // Si se proporciona una cadena de búsqueda, agregamos una cláusula WHERE
            if (!empty($busqueda)) {
                $sql .= " WHERE r.nombrerestaurante LIKE :busquedanombre
                           OR u.alias LIKE :busquedaalias OR r.emailrest LIKE :busquedaemail";
            }

            $sql .= " LIMIT :offset, :cant_reg_paginas";

            // Preparo la consulta
            $query = $this->db->prepare($sql);

            // Ejecuto la consulta, pasando la cadena de búsqueda como parámetro
            if (!empty($busqueda)) {
                $query->execute([
                    'busquedanombre' => "%$busqueda%",
                    'busquedaalias' => "%$busqueda%",
                    'busquedaemail' => "%$busqueda%",
                    'offset' => $offset,
                    'cant_reg_paginas' => $cant_reg_paginas
                ]);
            } else {
                $query->execute([
                    'offset' => $offset,
                    'cant_reg_paginas' => $cant_reg_paginas
                ]);
            }

            // Obtengo los restaurantes que coinciden con la búsqueda
            $restaurantes = $query->fetchAll(PDO::FETCH_OBJ);

            // Retorno los restaurantes
            return $restaurantes;
        } catch (PDOException $e) {
            // Manejar excepciones de base de datos aquí
            // Por ejemplo, puedes registrar el error o lanzar una excepción personalizada.
            // Ejemplo: throw new MiExcepciónPersonalizada($e->getMessage());
            return []; // O devolver un valor por defecto, dependiendo de tus necesidades.
        }
    }

    //Creamos una funcion para obtener el total de restaurantes vencidos
    public function obtenerTotalRestaurantesVencidosParaAdmin()
    {
        try {
            // Creo la consulta para obtener los datos de los restaurantes en la BD
            $sql = "SELECT COUNT(*) as totalrestaurantes FROM restaurante r
                JOIN usuario u ON r.emailrest = u.email
                WHERE (r.estadomembresia = 'vencida' OR r.finmembresia < NOW())
                AND (r.estadorenovacion IS NULL OR r.estadorenovacion = '')
                AND u.estadologico = 'activo';";

            // Preparo la consulta
            $query = $this->db->prepare($sql);

            // Ejecuto la consulta
            $query->execute();

            // Obtengo la cantidad de restaurantes vencidos
            $totalrestaurantes = $query->fetch(PDO::FETCH_OBJ);

            // Retorno la cantidad de restaurantes vencidos
            return $totalrestaurantes->totalrestaurantes;
        } catch (PDOException $e) {
            // Manejar excepciones de base de datos aquí
            // Por ejemplo, puedes registrar el error o lanzar una excepción personalizada.
            // Ejemplo: throw new MiExcepciónPersonalizada($e->getMessage());
            return 0; // O devolver un valor por defecto, dependiendo de tus necesidades.
        }
    }

    // Creamos una funcion para obtener los registros con paginacion de los restaurantes vencidos
    public function obtenerRestaurantesVencidosPaginadosParaAdmin($pagina, $cant_reg_paginas)
    {
        try {
            $offset = ($pagina - 1) * $cant_reg_paginas;


            $sql = "SELECT r.nombrerestaurante, r.dirrestaurante, u.fotoperfilologo, r.emailrest, r.estadomembresia, r.iniciomembresia, r.finmembresia,
            r.estadorenovacion, r.telefono1, r.telefono2
            FROM restaurante r
            JOIN usuario u ON r.emailrest = u.email
            WHERE (r.estadomembresia = 'vencida' OR r.finmembresia < NOW())
            AND (r.estadorenovacion IS NULL OR r.estadorenovacion = '')
            AND u.estadologico = 'activo' -- Agrega esta condición
            LIMIT :offset, :cant_reg_paginas";

            // Preparo la consulta
            $query = $this->db->prepare($sql);

            // Ejecuto la consulta
            $query->execute(['offset' => $offset, 'cant_reg_paginas' => $cant_reg_paginas]);

            // Obtengo los restaurantes vencidos
            $restaurantes = $query->fetchAll(PDO::FETCH_OBJ);

            // Retorno los restaurantes vencidos
            return $restaurantes;
        } catch (PDOException $e) {
            // Manejar excepciones de base de datos aquí
            // Por ejemplo, puedes registrar el error o lanzar una excepción personalizada.
            // Ejemplo: throw new MiExcepciónPersonalizada($e->getMessage());
            return []; // O devolver un valor por defecto, dependiendo de tus necesidades.
        }
    }


    public function obtenerTotalSolicitudesRestaurantesParaAdmin()
    {
        try {
            // Creo la consulta para obtener el número de solicitudes de restaurantes pendientes
            $sql = "SELECT COUNT(*) as totalrestaurantes FROM restaurante WHERE estadomembresia='pendiente' OR estadorenovacion='pendiente';";

            // Preparo la consulta
            $query = $this->db->prepare($sql);

            // Ejecuto la consulta
            $query->execute();

            // Obtengo la cantidad de restaurantes con solicitudes pendientes
            $totalrestaurantes = $query->fetch(PDO::FETCH_OBJ);

            // Retorno la cantidad de restaurantes con solicitudes pendientes
            return $totalrestaurantes->totalrestaurantes;
        } catch (PDOException $e) {
            // Manejar excepciones de la base de datos aquí
            // Por ejemplo, puedes registrar el error o lanzar una excepción personalizada.
            // Ejemplo: throw new MiExcepciónPersonalizada($e->getMessage());
            return 0; // O devolver un valor por defecto, dependiendo de tus necesidades.
        }
    }

    //Creamos la funcion para obtener los registros con paginacion de las solicitudes de membresia restaurantes
    public function obtenerSolicitudesRestaurantesPaginadosParaAdmin($pagina, $cant_reg_paginas)
    {
        try {
            $offset = ($pagina - 1) * $cant_reg_paginas;

            // Define un límite mínimo para la cantidad de registros por página
            $cant_reg_paginas_min = 4;

            // Asegúrate de que la cantidad de registros por página sea al menos el límite mínimo
            if ($cant_reg_paginas < $cant_reg_paginas_min) {
                $cant_reg_paginas = $cant_reg_paginas_min;
            }

            $sql = "SELECT r.*, u.*
                FROM restaurante r
                JOIN usuario u ON r.emailrest = u.email
                WHERE r.estadomembresia='pendiente' OR r.estadorenovacion='pendiente'
                LIMIT :offset, :cant_reg_paginas";

            // Preparo la consulta
            $query = $this->db->prepare($sql);

            // Ejecuto la consulta
            $query->execute(['offset' => $offset, 'cant_reg_paginas' => $cant_reg_paginas]);

            // Obtengo las solicitudes de restaurantes
            $solicitudes = $query->fetchAll(PDO::FETCH_OBJ);

            // Retorno las solicitudes de restaurantes
            return $solicitudes;
        } catch (PDOException $e) {
            // Manejar excepciones de base de datos aquí
            // Por ejemplo, puedes registrar el error o lanzar una excepción personalizada.
            // Ejemplo: throw new MiExcepciónPersonalizada($e->getMessage());
            return []; // O devolver un valor por defecto, dependiendo de tus necesidades.
        }
    }


    //CREAMOS MUTIPLES FUNCIONES PARA EDITAR ASPECTOS DEL RESTAURANTE
    //1) Modificar el tipo de restaurante
    public function modificarTipoRestaurante($emailrest, $tiporestaurante)
    {
        try {
            // Comenzar una transacción
            $this->db->beginTransaction();

            // Creo la consulta para modificar el tipo de restaurante
            $sql = "UPDATE restaurante SET trestaurante = :trestaurante WHERE emailrest = :emailrest";

            // Preparo la consulta
            $query = $this->db->prepare($sql);

            // Ejecuto la consulta
            $query->execute(['trestaurante' => $tiporestaurante, 'emailrest' => $emailrest]);

            // Verifico si se modificó el tipo de restaurante
            if ($query->rowCount() > 0) {
                // Confirmar la transacción si la consulta se ejecutó con éxito
                $this->db->commit();
                // Retorno true si se modificó el tipo de restaurante
                return true;
            } else {
                // Si no se modificó, hacemos un rollback para deshacer la transacción
                $this->db->rollBack();
                // Retorno false si no se modificó el tipo de restaurante
                return false;
            }
        } catch (PDOException $e) {
            // Manejar excepciones de base de datos aquí
            // Por ejemplo, puedes registrar el error o lanzar una excepción personalizada.
            // Ejemplo: throw new MiExcepciónPersonalizada($e->getMessage());
            // Hacer un rollback en caso de excepción
            $this->db->rollBack();
            return false; // O devolver un valor por defecto, dependiendo de tus necesidades.
        }
    }


    //2) Modificar el telefono 1 del restaurante
    public function modificarTelefono1Restaurante($emailrest, $telefono1)
    {
        try {
            // Comenzar una transacción
            $this->db->beginTransaction();

            // Creo la consulta para modificar el teléfono 1 del restaurante
            $sql = "UPDATE restaurante SET telefono1 = :telefono1 WHERE emailrest = :emailrest";

            // Preparo la consulta
            $query = $this->db->prepare($sql);

            // Ejecuto la consulta
            $query->execute(['telefono1' => $telefono1, 'emailrest' => $emailrest]);

            // Verifico si se modificó el teléfono 1 del restaurante
            if ($query->rowCount() > 0) {
                // Confirmar la transacción si la consulta se ejecutó con éxito
                $this->db->commit();
                // Retorno true si se modificó el teléfono 1 del restaurante
                return true;
            } else {
                // Si no se modificó, hacemos un rollback para deshacer la transacción
                $this->db->rollBack();
                // Retorno false si no se modificó el teléfono 1 del restaurante
                return false;
            }
        } catch (PDOException $e) {
            // Manejar excepciones de base de datos aquí
            // Por ejemplo, puedes registrar el error o lanzar una excepción personalizada.
            // Ejemplo: throw new MiExcepciónPersonalizada($e->getMessage());
            // Hacer un rollback en caso de excepción
            $this->db->rollBack();
            return false; // O devolver un valor por defecto, dependiendo de tus necesidades.
        }
    }


    //3) Modificar el telefono 2 del restaurante
    public function modificarTelefono2Restaurante($emailrest, $telefono2)
    {
        try {
            // Comenzar una transacción
            $this->db->beginTransaction();

            // Creo la consulta para modificar el teléfono 2 del restaurante
            $sql = "UPDATE restaurante SET telefono2 = :telefono2 WHERE emailrest = :emailrest";

            // Preparo la consulta
            $query = $this->db->prepare($sql);

            // Ejecuto la consulta
            $query->execute(['telefono2' => $telefono2, 'emailrest' => $emailrest]);

            // Verifico si se modificó el teléfono 2 del restaurante
            if ($query->rowCount() > 0) {
                // Confirmar la transacción si la consulta se ejecutó con éxito
                $this->db->commit();
                // Retorno true si se modificó el teléfono 2 del restaurante
                return true;
            } else {
                // Si no se modificó, hacemos un rollback para deshacer la transacción
                $this->db->rollBack();
                // Retorno false si no se modificó el teléfono 2 del restaurante
                return false;
            }
        } catch (PDOException $e) {
            // Manejar excepciones de base de datos aquí
            // Por ejemplo, puedes registrar el error o lanzar una excepción personalizada.
            // Ejemplo: throw new MiExcepciónPersonalizada($e->getMessage());
            // Hacer un rollback en caso de excepción
            $this->db->rollBack();
            return false; // O devolver un valor por defecto, dependiendo de tus necesidades.
        }
    }


    //4) Modificar la descripcion del restaurante
    public function modificarDescripcionRestaurante($emailrest, $descrrestaurante)
    {
        try {
            // Comenzar una transacción
            $this->db->beginTransaction();

            // Creo la consulta para modificar la descripción del restaurante
            $sql = "UPDATE restaurante SET descrrestaurante = :descrrestaurante WHERE emailrest = :emailrest";

            // Preparo la consulta
            $query = $this->db->prepare($sql);

            // Ejecuto la consulta
            $query->execute(['descrrestaurante' => $descrrestaurante, 'emailrest' => $emailrest]);

            // Verifico si se modificó la descripción del restaurante
            if ($query->rowCount() > 0) {
                // Confirmar la transacción si la consulta se ejecutó con éxito
                $this->db->commit();
                // Retorno true si se modificó la descripción del restaurante
                return true;
            } else {
                // Si no se modificó, hacemos un rollback para deshacer la transacción
                $this->db->rollBack();
                // Retorno false si no se modificó la descripción del restaurante
                return false;
            }
        } catch (PDOException $e) {
            // Manejar excepciones de la base de datos aquí
            // Por ejemplo, puedes registrar el error o lanzar una excepción personalizada.
            // Ejemplo: throw new MiExcepciónPersonalizada($e->getMessage());
            // Hacer un rollback en caso de excepción
            $this->db->rollBack();
            return false; // O devolver un valor por defecto, dependiendo de tus necesidades.
        }
    }


    //5) Modificar el tipo de comida del restaurante
    public function modificarTipoComidaRestaurante($emailrest, $tipocomida)
    {
        try {
            // Comenzar una transacción
            $this->db->beginTransaction();

            // Creo la consulta para modificar el tipo de comida del restaurante
            $sql = "UPDATE restaurante SET tipocomida = :tipocomida WHERE emailrest = :emailrest";

            // Preparo la consulta
            $query = $this->db->prepare($sql);

            // Ejecuto la consulta
            $query->execute(['tipocomida' => $tipocomida, 'emailrest' => $emailrest]);

            // Verifico si se modificó el tipo de comida del restaurante
            if ($query->rowCount() > 0) {
                // Confirmar la transacción si la consulta se ejecutó con éxito
                $this->db->commit();
                // Retorno true si se modificó el tipo de comida del restaurante
                return true;
            } else {
                // Si no se modificó, hacemos un rollback para deshacer la transacción
                $this->db->rollBack();
                // Retorno false si no se modificó el tipo de comida del restaurante
                return false;
            }
        } catch (PDOException $e) {
            // Manejar excepciones de la base de datos aquí
            // Por ejemplo, puedes registrar el error o lanzar una excepción personalizada.
            // Ejemplo: throw new MiExcepciónPersonalizada($e->getMessage());
            // Hacer un rollback en caso de excepción
            $this->db->rollBack();
            return false; // O devolver un valor por defecto, dependiendo de tus necesidades.
        }
    }



    //Creamos la funcion para modificar la visibilidad del perfil del restaurante
    public function modificarVisibilidadPerfilRestaurante($emailrest, $visibilidadperfil)
    {
        try {
            // Creo la consulta para modificar la visibilidad del perfil del restaurante
            $sql = "UPDATE restaurante SET visibilidadperfil = :visibilidadperfil WHERE emailrest = :emailrest";

            // Preparo la consulta
            $query = $this->db->prepare($sql);

            // Ejecuto la consulta
            $query->execute(['visibilidadperfil' => $visibilidadperfil, 'emailrest' => $emailrest]);

            // Verifico si se modificó la visibilidad del perfil del restaurante
            if ($query->rowCount() > 0) {
                // Retorno true si se modificó la visibilidad del perfil del restaurante
                return true;
            } else {
                // Retorno false si no se modificó la visibilidad del perfil del restaurante
                return false;
            }
        } catch (PDOException $e) {
            // En caso de una excepción, puedes registrar el error o manejarlo según tus necesidades.
            // Ejemplo: throw new Exception("Error al modificar la visibilidad del perfil del restaurante: " . $e->getMessage());

            return false; // Retorna false en caso de error.
        }
    }


    //Creamos funcion para modificar el menu del restaurante
    public function modificarMenuRestaurante($emailrest, $rutapdf)
    {
        try {
            // Comenzar una transacción
            $this->db->beginTransaction();

            // Creo la consulta para modificar el menú del restaurante
            $sql = "UPDATE restaurante SET rutapdf = :rutapdf WHERE emailrest = :emailrest";

            // Preparo la consulta
            $query = $this->db->prepare($sql);

            // Ejecuto la consulta
            $query->execute(['rutapdf' => $rutapdf, 'emailrest' => $emailrest]);

            // Verifico si se modificó el menú del restaurante
            if ($query->rowCount() > 0) {
                // Confirmar la transacción si la consulta se ejecutó con éxito
                $this->db->commit();
                // Retorno true si se modificó el menú del restaurante
                return true;
            } else {
                // Si no se modificó, hacemos un rollback para deshacer la transacción
                $this->db->rollBack();
                // Retorno false si no se modificó el menú del restaurante
                return false;
            }
        } catch (PDOException $e) {
            // Manejar excepciones de la base de datos aquí
            // Por ejemplo, puedes registrar el error o lanzar una excepción personalizada.
            // Ejemplo: throw new MiExcepciónPersonalizada($e->getMessage());
            // Hacer un rollback en caso de excepción
            $this->db->rollBack();
            return false; // O devolver un valor por defecto, dependiendo de tus necesidades.
        }
    }

    //Creamos la funcion para solicitar una membresia
    public function solicitarMembresia($emailrest, $tipomembresia, $urlcomprobantepago)
    {
        try {
            // Iniciamos una transacción
            $this->db->beginTransaction();

            // Creamos la consulta para solicitar una membresía y cambiar el estado de membresía
            $sql = "UPDATE restaurante SET estadomembresia = 'pendiente', tipomembresia = :tipomembresia, comprobante_pago = :comprobante_pago WHERE emailrest = :emailrest";

            // Preparamos la consulta
            $query = $this->db->prepare($sql);

            // Ejecutamos la consulta
            if ($query->execute(['tipomembresia' => $tipomembresia, 'comprobante_pago' => $urlcomprobantepago, 'emailrest' => $emailrest])) {
                // Confirmamos la transacción
                $this->db->commit();
                return true; // La membresía se solicitó con éxito
            } else {
                // Revertimos la transacción
                $this->db->rollBack();
                return false; // La consulta no se ejecutó con éxito
            }

        } catch (PDOException $e) {
            // Revertimos la transacción en caso de error
            $this->db->rollBack();
            // Manejo de excepciones
            // Puedes registrar o notificar el error según tus necesidades
            error_log('Error al solicitar la membresía: ' . $e->getMessage());
            return false;
        }
    }


    //Creamos la funcion para solicitar renovacion de membresia
    public function solicitarRenovacionMembresia($emailrest, $tipomembresia, $urlcomprobantepago)
    {
        try {
            // Comenzar una transacción
            $this->db->beginTransaction();

            // Creo la consulta para solicitar una renovación de membresía cambiando el estado de renovación
            $sql = "UPDATE restaurante SET estadorenovacion = 'pendiente', tiporenovacion = :tipomembresia, comprobante_pago_renovacion = :comprobante_pago WHERE emailrest = :emailrest";

            // Preparo la consulta
            $query = $this->db->prepare($sql);

            // Ejecuto la consulta
            $query->execute(['tipomembresia' => $tipomembresia, 'comprobante_pago' => $urlcomprobantepago, 'emailrest' => $emailrest]);

            // Verifico si se solicitó la renovación de membresía
            if ($query->rowCount() > 0) {
                // Confirmar la transacción si la consulta se ejecutó con éxito
                $this->db->commit();
                // Retorno true si se solicitó la renovación de membresía
                return true;
            } else {
                // Si no se solicitó, hacemos un rollback para deshacer la transacción
                $this->db->rollBack();
                // Retorno false si no se solicitó la renovación de membresía
                return false;
            }
        } catch (PDOException $e) {
            // Manejar excepciones de la base de datos aquí
            // Por ejemplo, puedes registrar el error o lanzar una excepción personalizada.
            // Ejemplo: throw new MiExcepciónPersonalizada($e->getMessage());
            // Hacer un rollback en caso de excepción
            $this->db->rollBack();
            return false; // O devolver un valor por defecto, dependiendo de tus necesidades.
        }
    }


    //Creamos la funcion para dar de baja la solicitud de membresia
    public function darBajaSolicitudMembresia($emailrest)
    {
        try {
            // Comenzar una transacción
            $this->db->beginTransaction();

            // Creo la consulta para dar de baja la solicitud de membresía cambiando el estado de membresía
            $sql = "UPDATE restaurante SET estadomembresia = null, tipomembresia = null, comprobante_pago = null WHERE emailrest = :emailrest";

            // Preparo la consulta
            $query = $this->db->prepare($sql);

            // Ejecuto la consulta
            $query->execute(['emailrest' => $emailrest]);

            // Verifico si se dio de baja la solicitud de membresía
            if ($query->rowCount() > 0) {
                // Confirmar la transacción si la consulta se ejecutó con éxito
                $this->db->commit();
                // Retorno true si se dio de baja la solicitud de membresía
                return true;
            } else {
                // Si no se dio de baja, hacemos un rollback para deshacer la transacción
                $this->db->rollBack();
                // Retorno false si no se dio de baja la solicitud de membresía
                return false;
            }
        } catch (PDOException $e) {
            // Manejar excepciones de la base de datos aquí
            // Por ejemplo, puedes registrar el error o lanzar una excepción personalizada.
            // Ejemplo: throw new MiExcepciónPersonalizada($e->getMessage());
            // Hacer un rollback en caso de excepción
            $this->db->rollBack();
            return false; // O devolver un valor por defecto, dependiendo de tus necesidades.
        }
    }


    public function cambiarEstadoMembresia($emailrest)
    {
        try {
            // Llamamos a obtener datos restaurante para obtener la información de la membresía
            $datosrest = $this->obtenerRestaurante($emailrest);

            // Verificamos si se obtuvieron los datos del restaurante
            if ($datosrest === null) {
                return false;
            }

            // Verificamos si la fecha de finalización de la membresía es anterior a la fecha actual
            $fechaActual = date("Y-m-d");

            if ($datosrest->estadomembresia !== "pendiente" && $datosrest->finmembresia !== null && $datosrest->finmembresia < $fechaActual) {
                // La membresía está vencida, cambiamos el estado
                $nuevoEstado = "vencida";

            } else {

                if ($datosrest->estadomembresia !== "pendiente" && $datosrest->finmembresia !== null && $datosrest->finmembresia > $fechaActual) {

                    // La membresía sigue vigente
                    $nuevoEstado = "vigente";

                } else {
                    return "pendiente";
                }


            }

            // Actualizamos el estado de la membresía
            $sql = "UPDATE restaurante SET estadomembresia = :nuevoEstado WHERE emailrest = :emailrest";
            $query = $this->db->prepare($sql);

            // Verificamos si se cambió el estado de la membresía a partir del execute y no query 
            // porque query devuelve las filas pero si la membresia estaba vigente no va a actualizar nada
            if ($query->execute(['nuevoEstado' => $nuevoEstado, 'emailrest' => $emailrest])) {
                return $nuevoEstado;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            // Manejo de excepciones
            // Puedes registrar o notificar el error según tus necesidades
            error_log('Error al cambiar el estado de la membresía: ' . $e->getMessage());
            return false;
        }
    }
    //Ahora creamos una funcion  para verificar si el restaurante tiene una renovacion de membresia
    //ya aceptada o pendiente
    public function verificarRenovacionMembresia($emailrest)
    {
        try {
            // Consulta SQL para verificar el estado de la renovación de membresía
            $sql = "SELECT estadorenovacion FROM restaurante WHERE emailrest = :emailrest 
                    AND (estadorenovacion = 'pendiente' OR estadorenovacion = 'valida')";

            // Preparamos la consulta
            $query = $this->db->prepare($sql);

            // Ejecutamos la consulta
            $query->execute(['emailrest' => $emailrest]);

            // Obtenemos el resultado de la consulta
            $estadoRenovacion = $query->fetchColumn();

            // Verificamos el estado de la renovación de membresía
            if ($estadoRenovacion === false) {
                return "notienerenovacion";
            } elseif ($estadoRenovacion === "pendiente") {
                return "renovacionpendiente";
            } elseif ($estadoRenovacion === "valida") {
                return "renovacionvalida";
            } else {
                return "estadoinvalido"; // Manejo de un estado no esperado
            }
        } catch (PDOException $e) {
            // Manejo de excepciones
            error_log('Error al verificar renovación de membresía: ' . $e->getMessage());
            return false; // Otra opción es lanzar una excepción aquí
        }
    }


    //Creamos la funcion para que si habia una renovacion ya valida, se remplaze 
    //en los campos de la membresia y queden null los campos de la renovacion
    public function remplazarMembresiaPorRenovacion($emailrest)
    {
        try {
            // Comienza una transacción
            $this->db->beginTransaction();

            // Creo la consulta para remplazar la membresia por la renovación
            $sql = "UPDATE restaurante SET estadomembresia = 'vigente', tipomembresia = tiporenovacion, iniciomembresia = iniciorenovacion, finmembresia = finrenovacion, estadorenovacion = null, tiporenovacion = null, iniciorenovacion = null, finrenovacion = null WHERE emailrest = :emailrest";

            // Preparo la consulta
            $query = $this->db->prepare($sql);

            // Ejecuto la consulta
            $query->execute(['emailrest' => $emailrest]);

            // Verifico si se remplazó la membresía por la renovación
            if ($query->rowCount() > 0) {
                // Commit para confirmar la transacción
                $this->db->commit();

                // Retorno true si se remplazó la membresía por la renovación
                return true;
            } else {
                // Rollback para deshacer la transacción en caso de error
                $this->db->rollBack();

                // Retorno false si no se remplazó la membresía por la renovación
                return false;
            }
        } catch (PDOException $e) {
            // En caso de una excepción, puedes registrar el error o manejarlo según tus necesidades
            // Aquí puedes agregar código para registrar el error en un archivo de registro, enviar un correo electrónico, etc.
            // Luego, puedes lanzar una nueva excepción si lo deseas o simplemente retornar false.
            // Ejemplo: throw new Exception("Error en la actualización de membresía: " . $e->getMessage());
            return false;
        }
    }

}

?>