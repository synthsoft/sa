<?php

require_once('models/restaurante_model.php');


class promociones_model extends restaurante_model
{

    //Creamos una instancia de archivo_model para poder acceder a sus metodos
    private $archivo_model;


    //Construimos el metodo principal
    function __construct()
    {

        //llamamos al constructor padre
        parent::__construct();

        //Llamamos al archivo archivo_model.php
        require_once('models/archivos_model.php');

        //Creamos una instancia de archivo_model para poder acceder a sus metodos
        $this->archivo_model = new archivos_model();

    }

    public function obtenerTotalPromocionesActivas($emailrest)
    {
        try {
            // Creo la consulta para obtener los datos del restaurante en la BD
            $sql = "SELECT COUNT(*) as totalpromocionesactivas FROM promocion WHERE emailrest=:emailrest AND fyhfinpromo > NOW();";

            // Preparo la consulta
            $query = $this->db->prepare($sql);

            // Ejecuto la consulta
            $query->execute(['emailrest' => $emailrest]);

            // Obtengo los datos del restaurante en la BD
            $totalpromocionesactivas = $query->fetch(PDO::FETCH_OBJ);

            if (!$totalpromocionesactivas) {
                $totalpromocionesactivas = 0;
            }

            // Retorno los datos del restaurante en la BD
            return ($totalpromocionesactivas !== null) ? (int) $totalpromocionesactivas->totalpromocionesactivas : 0;
        } catch (PDOException $e) {
            // En caso de una excepción, puedes registrar el error o manejarlo según tus necesidades.
            // Ejemplo: throw new Exception("Error al obtener el total de promociones activas: " . $e->getMessage());

            return 0; // Retorna un valor predeterminado (en este caso, 0) en caso de error.
        }
    }

    public function obtenerPromocionesActivasPaginadas($pagina, $cant_reg_paginas, $emailrest)
    {
        try {
            $offset = ($pagina - 1) * $cant_reg_paginas;

            // Define un límite mínimo para la cantidad de registros por página
            $cant_reg_paginas_min = 4;

            // Asegúrate de que la cantidad de registros por página sea al menos el límite mínimo
            if ($cant_reg_paginas < $cant_reg_paginas_min) {
                $cant_reg_paginas = $cant_reg_paginas_min;
            }

            $sql = "SELECT * FROM promocion WHERE emailrest=:emailrest AND fyhfinpromo > NOW() LIMIT :offset, :cant_reg_paginas;";

            // Preparo la consulta
            $query = $this->db->prepare($sql);

            // Ejecuto la consulta
            $query->execute(['emailrest' => $emailrest, 'offset' => $offset, 'cant_reg_paginas' => $cant_reg_paginas]);

            // Obtengo los datos del restaurante en la BD
            $promociones = $query->fetchAll(PDO::FETCH_OBJ);

            // Retorno los datos de la promoción en la BD
            return $promociones;
        } catch (PDOException $e) {
            // En caso de una excepción, puedes registrar el error o manejarlo según tus necesidades.
            // Ejemplo: throw new Exception("Error al obtener promociones activas paginadas: " . $e->getMessage());

            return []; // Retorna un array vacío en caso de error.
        }
    }

    public function obtenerTotalPromocionesActivasFiltradas($emailrest, $filtro)
    {
        try {
            // Creo la consulta para obtener los datos del restaurante en la BD con filtro LIKE
            $sql = "SELECT COUNT(*) as totalpromocionesactivas FROM promocion WHERE emailrest=:emailrest AND fyhfinpromo > NOW() AND (nombrepromocion LIKE :filtro OR descrpromocion LIKE :filtro1);";

            // Preparo la consulta
            $query = $this->db->prepare($sql);

            // Ejecuto la consulta
            $query->execute(['emailrest' => $emailrest, 'filtro' => '%' . $filtro . '%', 'filtro1' => '%' . $filtro . '%']);

            // Obtengo los datos del restaurante en la BD
            $totalpromocionesactivas = $query->fetch(PDO::FETCH_OBJ);

            if (!$totalpromocionesactivas) {
                $totalpromocionesactivas = 0;
            }

            // Retorno los datos del restaurante en la BD
            return ($totalpromocionesactivas !== null) ? (int) $totalpromocionesactivas->totalpromocionesactivas : 0;
        } catch (PDOException $e) {
            // En caso de una excepción, puedes registrar el error o manejarlo según tus necesidades.
            // Ejemplo: throw new Exception("Error al obtener el total de promociones activas filtradas: " . $e->getMessage());

            return 0; // Retorna un valor predeterminado (en este caso, 0) en caso de error.
        }
    }


    public function obtenerPromocionesActivasFiltradasPaginadas($pagina, $cant_reg_paginas, $emailrest, $filtro)
    {
        try {
            $offset = ($pagina - 1) * $cant_reg_paginas;

            // Define un límite mínimo para la cantidad de registros por página
            $cant_reg_paginas_min = 4;

            // Asegúrate de que la cantidad de registros por página sea al menos el límite mínimo
            if ($cant_reg_paginas < $cant_reg_paginas_min) {
                $cant_reg_paginas = $cant_reg_paginas_min;
            }

            $sql = "SELECT * FROM promocion WHERE emailrest=:emailrest AND fyhfinpromo > NOW() AND (nombrepromocion LIKE :filtro OR descrpromocion LIKE :filtro1) LIMIT :offset, :cant_reg_paginas;";

            // Preparo la consulta
            $query = $this->db->prepare($sql);

            // Ejecuto la consulta
            $query->execute(['emailrest' => $emailrest, 'filtro' => '%' . $filtro . '%', 'filtro1' => '%' . $filtro . '%', 'offset' => $offset, 'cant_reg_paginas' => $cant_reg_paginas]);

            // Obtengo los datos del restaurante en la BD
            $promociones = $query->fetchAll(PDO::FETCH_OBJ);

            // Retorno los datos de la promoción en la BD
            return $promociones;
        } catch (PDOException $e) {
            // En caso de una excepción, puedes registrar el error o manejarlo según tus necesidades.
            // Ejemplo: throw new Exception("Error al obtener promociones activas filtradas y paginadas: " . $e->getMessage());

            return []; // Retorna un array vacío en caso de error.
        }
    }


    public function obtenerTotalPromocionesVencidas($emailrest)
    {
        try {
            // Creo la consulta para obtener los datos del restaurante en la BD
            $sql = "SELECT COUNT(*) as totalpromocionesactivas FROM promocion WHERE emailrest=:emailrest AND fyhfinpromo < NOW();";

            // Preparo la consulta
            $query = $this->db->prepare($sql);

            // Ejecuto la consulta
            $query->execute(['emailrest' => $emailrest]);

            // Obtengo los datos del restaurante en la BD
            $totalpromocionesactivas = $query->fetch(PDO::FETCH_OBJ);

            if (!$totalpromocionesactivas) {
                $totalpromocionesactivas = 0;
            }

            // Retorno los datos del restaurante en la BD
            return ($totalpromocionesactivas !== null) ? (int) $totalpromocionesactivas->totalpromocionesactivas : 0;
        } catch (PDOException $e) {
            // En caso de una excepción, puedes registrar el error o manejarlo según tus necesidades.
            // Ejemplo: throw new Exception("Error al obtener el total de promociones vencidas: " . $e->getMessage());

            return 0; // Retorna un valor predeterminado (en este caso, 0) en caso de error.
        }
    }


    public function obtenerPromocionesVencidasPaginadas($pagina, $cant_reg_paginas, $emailrest)
    {
        try {
            $offset = ($pagina - 1) * $cant_reg_paginas;

            // Define un límite mínimo para la cantidad de registros por página
            $cant_reg_paginas_min = 4;

            // Asegúrate de que la cantidad de registros por página sea al menos el límite mínimo
            if ($cant_reg_paginas < $cant_reg_paginas_min) {
                $cant_reg_paginas = $cant_reg_paginas_min;
            }

            $sql = "SELECT * FROM promocion WHERE emailrest=:emailrest AND fyhfinpromo < NOW() LIMIT :offset, :cant_reg_paginas;";

            // Preparo la consulta
            $query = $this->db->prepare($sql);

            // Ejecuto la consulta
            $query->execute(['emailrest' => $emailrest, 'offset' => $offset, 'cant_reg_paginas' => $cant_reg_paginas]);

            // Obtengo los datos del restaurante en la BD
            $promociones = $query->fetchAll(PDO::FETCH_OBJ);

            // Retorno los datos de la promoción en la BD
            return $promociones;
        } catch (PDOException $e) {
            // En caso de una excepción, puedes registrar el error o manejarlo según tus necesidades.
            // Ejemplo: throw new Exception("Error al obtener promociones vencidas paginadas: " . $e->getMessage());

            return []; // Retorna un array vacío en caso de error.
        }
    }



    public function obtenerTotalPromocionesVencidasFiltradas($emailrest, $filtro)
    {
        try {
            // Creo la consulta para obtener los datos del restaurante en la BD con filtro LIKE
            $sql = "SELECT COUNT(*) as totalpromocionesactivas FROM promocion WHERE emailrest=:emailrest AND fyhfinpromo < NOW() AND (nombrepromocion LIKE :filtro OR descrpromocion LIKE :filtro1);";

            // Preparo la consulta
            $query = $this->db->prepare($sql);

            // Ejecuto la consulta
            $query->execute(['emailrest' => $emailrest, 'filtro' => '%' . $filtro . '%', 'filtro1' => '%' . $filtro . '%']);

            // Obtengo los datos del restaurante en la BD
            $totalpromocionesactivas = $query->fetch(PDO::FETCH_OBJ);

            if (!$totalpromocionesactivas) {
                $totalpromocionesactivas = 0;
            }

            // Retorno los datos del restaurante en la BD
            return ($totalpromocionesactivas !== null) ? (int) $totalpromocionesactivas->totalpromocionesactivas : 0;
        } catch (PDOException $e) {
            // En caso de una excepción, puedes registrar el error o manejarlo según tus necesidades.
            // Ejemplo: throw new Exception("Error al obtener el total de promociones vencidas filtradas: " . $e->getMessage());

            return 0; // Retorna un valor predeterminado (en este caso, 0) en caso de error.
        }
    }


    public function obtenerPromocionesVencidasFiltradasPaginadas($pagina, $cant_reg_paginas, $emailrest, $filtro)
    {
        try {
            $offset = ($pagina - 1) * $cant_reg_paginas;

            // Define un límite mínimo para la cantidad de registros por página
            $cant_reg_paginas_min = 4;

            // Asegúrate de que la cantidad de registros por página sea al menos el límite mínimo
            if ($cant_reg_paginas < $cant_reg_paginas_min) {
                $cant_reg_paginas = $cant_reg_paginas_min;
            }

            $sql = "SELECT * FROM promocion WHERE emailrest=:emailrest AND fyhfinpromo < NOW() AND (nombrepromocion LIKE :filtro OR descrpromocion LIKE :filtro1) LIMIT :offset, :cant_reg_paginas;";

            // Preparo la consulta
            $query = $this->db->prepare($sql);

            // Ejecuto la consulta
            $query->execute(['emailrest' => $emailrest, 'filtro' => '%' . $filtro . '%', 'filtro1' => '%' . $filtro . '%', 'offset' => $offset, 'cant_reg_paginas' => $cant_reg_paginas]);

            // Obtengo los datos del restaurante en la BD
            $promociones = $query->fetchAll(PDO::FETCH_OBJ);

            // Retorno los datos de la promoción en la BD
            return $promociones;
        } catch (PDOException $e) {
            // En caso de una excepción, puedes registrar el error o manejarlo según tus necesidades.
            // Ejemplo: throw new Exception("Error al obtener promociones vencidas filtradas y paginadas: " . $e->getMessage());

            return []; // Retorna un array vacío en caso de error.
        }
    }


    public function darBajaPromo($idpromocion)
    {
        try {
            // Validación de entrada
            if ($idpromocion === false || $idpromocion === null || $idpromocion === '' || !isset($idpromocion) || empty($idpromocion)) {
                throw new Exception("ID de promoción no válido.");
            }

            // Iniciar una transacción
            $this->db->beginTransaction();

            // Sentencia SQL para dar de baja la promoción
            $sql = "UPDATE promocion
                    SET fyhfinpromo = DATE_SUB(CURDATE(), INTERVAL 1 DAY)
                    WHERE idpromocion = :idpromocion;";

            // Preparar la consulta
            $query = $this->db->prepare($sql);

            // Ejecutar la consulta
            $query->execute(['idpromocion' => $idpromocion]);

            // Confirmar la transacción
            $this->db->commit();

            return true; // Éxito

        } catch (Exception $e) {
            // Revertir la transacción en caso de error
            $this->db->rollBack();

            // Puedes registrar o imprimir el mensaje de error para depuración
            echo 'Error: ' . $e->getMessage();

            return false; // Error
        }
    }



    public function crearPromocion($emailrest, $nombre, $descripcion, $fechainicio, $fechafin, $imagen)
    {
        try {
            // Validación de entrada
            if ($emailrest === false || $nombre === false || $descripcion === false || $fechainicio === false || $fechafin === false) {
                return false;
            }

            //Verificamos que ni el nombre ni la descripcion tengan mas de 250 caracteres
            if (strlen($nombre) > 250 || strlen($descripcion) > 250) {
                return false;
            }

            // Limpieza de datos
            $nombre = htmlspecialchars($nombre);
            $descripcion = htmlspecialchars($descripcion);

            //Le ponemos un trim a los campos de texto
            $nombre = trim($nombre);
            $descripcion = trim($descripcion);

            // Generar ID único de promoción
            $idpromocion = substr(md5(time()), 0, 9);

            // Iniciar una transacción
            $this->db->beginTransaction();

            // Sentencia SQL para insertar la promoción
            $sql = "INSERT INTO promocion (idpromocion, emailrest, nombrepromocion, descrpromocion, fyhfinpromo, fyhiniciopromo, imagenpromocion) VALUES (:idpromocion, :emailrest, :nombre, :descripcion, :fyhinipromo, :fyhfinpromo, :imagen);";

            // Preparar la consulta
            $query = $this->db->prepare($sql);

            // Ejecutar la consulta
            $query->execute([
                'idpromocion' => $idpromocion,
                'emailrest' => $emailrest,
                'nombre' => $nombre,
                'descripcion' => $descripcion,
                'fyhinipromo' => $fechainicio,
                'fyhfinpromo' => $fechafin,
                'imagen' => $imagen,
            ]);

            // Confirmar la transacción
            $this->db->commit();

            return true; // Éxito

        } catch (Exception $e) {
            // Revertir la transacción en caso de error
            $this->db->rollBack();

            // Puedes registrar o imprimir el mensaje de error para depuración
            echo 'Error: ' . $e->getMessage();

            return false; // Error
        }
    }

    public function modificarPromocion($idpromocion, $nombre, $descripcion, $fechainicio, $fechafin, $imagen)
    {
        try {
            // Validación de entrada
            if ($idpromocion === false || $nombre === false || $descripcion === false || $fechainicio === false || $fechafin === false) {
                throw new Exception("Todos los campos son obligatorios.");
            }

            // Limpieza de datos
            $nombre = htmlspecialchars($nombre);
            $descripcion = htmlspecialchars($descripcion);

            //Si la imagen es null no la modificamos
            if ($imagen === false) {

                // Sentencia SQL para actualizar la promoción
                $sql = "UPDATE promocion SET nombrepromocion = :nombre, descrpromocion = :descripcion, fyhfinpromo = :fechafin, fyhiniciopromo = :fechainicio WHERE idpromocion = :idpromocion;";


                // Iniciar una transacción
                $this->db->beginTransaction();
                // Preparar la consulta
                $query = $this->db->prepare($sql);

                // Ejecutar la consulta
                $query->execute([
                    'idpromocion' => $idpromocion,
                    'nombre' => $nombre,
                    'descripcion' => $descripcion,
                    'fechainicio' => $fechainicio,
                    'fechafin' => $fechafin
                ]);

                // Confirmar la transacción
                $this->db->commit();

                return true; // Éxito


            }

            // Sentencia SQL para actualizar la promoción
            $sql = "UPDATE promocion SET nombrepromocion = :nombre, descrpromocion = :descripcion, fyhfinpromo = :fechafin, fyhiniciopromo = :fechainicio, imagenpromocion = :imagen WHERE idpromocion = :idpromocion;";


            // Iniciar una transacción
            $this->db->beginTransaction();

            // Sentencia SQL para actualizar la promoción
            $sql = "UPDATE promocion SET nombrepromocion = :nombre, descrpromocion = :descripcion, fyhfinpromo = :fechafin, fyhiniciopromo = :fechainicio, imagenpromocion = :imagen WHERE idpromocion = :idpromocion;";

            // Preparar la consulta
            $query = $this->db->prepare($sql);

            // Ejecutar la consulta
            $query->execute([
                'idpromocion' => $idpromocion,
                'nombre' => $nombre,
                'descripcion' => $descripcion,
                'fechainicio' => $fechainicio,
                'fechafin' => $fechafin,
                'imagen' => $imagen,
            ]);

            // Confirmar la transacción
            $this->db->commit();

            return true; // Éxito

        } catch (Exception $e) {
            // Revertir la transacción en caso de error
            $this->db->rollBack();

            // Puedes registrar o imprimir el mensaje de error para depuración
            echo 'Error: ' . $e->getMessage();

            return false; // Error
        }
    }

}


?>