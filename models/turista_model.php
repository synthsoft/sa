<?php
require_once('models/usuario_model.php');
/**
 * Summary of turista_model
 */
class turista_model extends usuario_model
{
    protected $usuariomodel;

    public function __construct()
    {
        parent::__construct(); // Llama al constructor de la clase base

    }

    //Creo la funcion para el registro de usuario turista, para ingresar usuarios a la BD

    public function registroUsuarioTurista($email, $contrasena, $alias, $rutaimagen)
    {
        try {

            // Llama a la función registroUsuario de la clase padre usuario_model
            $resultado = parent::registroUsuario($email, $contrasena, $alias, $rutaimagen, 'tur', 'activo');

            // Comienza una transacción
            $this->db->beginTransaction();

            // Verifica si la inserción en la tabla usuario fue exitosa
            if ($resultado == true) {
                // Crea la consulta para insertar los datos en la tabla turista
                $sql = "INSERT INTO turista (emailtur) VALUES (:emailtur)";

                // Prepara la consulta
                $query = $this->db->prepare($sql);

                // Ejecuta la consulta
                $query->execute(['emailtur' => $email]);

                // Confirma la transacción
                $this->db->commit();

                // Retorna true si ambas inserciones son exitosas
                return true;
            } else {
                // Si la inserción en usuario falla, se revierte la transacción
                $this->db->rollback();

                // Retorna false si no se insertaron los datos en la tabla usuario
                return false;
            }
        } catch (PDOException $e) {
            // Si ocurre un error, se revierte la transacción
            if ($this->db->inTransaction()) {
                $this->db->rollback();
            }

            // Manejo de excepciones
            echo 'Error al ejecutar la consulta en turista: ' . $e->getMessage();
            return false;
        }
    }


    public function obtenerTotalTuristasParaAdmin($busqueda = '') {
        try {
            // Creo la consulta para obtener los datos de turistas en la BD
            $sql = "SELECT COUNT(*) as totalturistas FROM turista t 
            JOIN usuario u ON t.emailtur = u.email
            WHERE u.estadologico = 'activo'";
    
            if (!empty($busqueda)) {
                $sql .= " AND (u.alias LIKE :busquedaalias OR t.emailtur LIKE :busquedaemailtur)";
            }
    
            // Preparo la consulta
            $query = $this->db->prepare($sql);
    
            if (!empty($busqueda)) {
                // Ejecuto la consulta
                $query->execute(['busquedaalias' => '%' . $busqueda . '%', 'busquedaemailtur' => '%' . $busqueda . '%']);
            } else {
                // Ejecuto la consulta
                $query->execute();
            }
    
            // Obtengo el total de turistas
            $totalturistas = $query->fetch(PDO::FETCH_OBJ);
    
            // Retorno el total de turistas
            return $totalturistas->totalturistas;
        } catch (PDOException $e) {
            // Manejar excepciones de base de datos aquí
            // Por ejemplo, puedes registrar el error o lanzar una excepción personalizada.
            // Ejemplo: throw new MiExcepciónPersonalizada($e->getMessage());
            return 0; // O devolver un valor por defecto, dependiendo de tus necesidades.
        }
    }
    

    //Creamos la funcion para obtener los registros con paginacion de los restaurantes en el mismo barrio que el turista
    public function obtenerTuristasPaginadosParaAdmin($pagina, $cant_reg_paginas, $busqueda) {
        try {
            $offset = ($pagina - 1) * $cant_reg_paginas;
    
            $sql = "SELECT u.alias, u.fotoperfilologo, t.emailtur
                FROM turista t 
                JOIN usuario u ON t.emailtur = u.email
                WHERE u.estadologico = 'activo'";
    
            if (!empty($busqueda)) {
                $sql .= " AND (u.alias LIKE :busquedaalias OR t.emailtur LIKE :busquedaemailtur)";
            }
    
            $sql .= " ORDER BY u.alias ASC LIMIT :offset, :cant_reg_paginas";
    
            // Preparo la consulta
            $query = $this->db->prepare($sql);
    
            if (!empty($busqueda)) {
                $query->execute(['offset' => $offset, 'cant_reg_paginas' => $cant_reg_paginas, 'busquedaalias' => '%' . $busqueda . '%', 'busquedaemailtur' => '%' . $busqueda . '%']);
            } else {
                $query->execute(['offset' => $offset, 'cant_reg_paginas' => $cant_reg_paginas]);
            }
    
            // Obtengo los turistas
            $turistas = $query->fetchAll(PDO::FETCH_OBJ);
    
            // Retorno los turistas
            return $turistas;
        } catch (PDOException $e) {
            // Manejar excepciones de base de datos aquí
            // Por ejemplo, puedes registrar el error o lanzar una excepción personalizada.
            // Ejemplo: throw new MiExcepciónPersonalizada($e->getMessage());
            return []; // O devolver un valor por defecto, dependiendo de tus necesidades.
        }
    }
    

}

?>