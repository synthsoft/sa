<?php
//Creamos la clase usuario_model
class usuario_model
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

	//Creamos la funcion para verificar si el correo electronico existe en la BD
	public function verificarEmail($email)
	{
		try {
			$sql = "SELECT COUNT(*) FROM usuario WHERE email = :email";
			$query = $this->db->prepare($sql);
			$query->bindParam(':email', $email, PDO::PARAM_STR);
			$query->execute();

			$count = $query->fetchColumn();

			return $count > 0;
		} catch (PDOException $e) {
			// Manejar la excepción (por ejemplo, registrarla o lanzar una nueva excepción personalizada)
			return false;
		}
	}

	//Creamos la funcion para verificar si el alias existe en la BD
	public function verificarAlias($alias)
	{
		//Envolvemos con un try catch para capturar cualquier excepcion PDO
		try {
			$sql = "SELECT COUNT(*) FROM usuario WHERE alias = :alias";
			$query = $this->db->prepare($sql);
			$query->bindParam(':alias', $alias, PDO::PARAM_STR);
			$query->execute();

			$count = $query->fetchColumn();

			return $count > 0;
		} catch (PDOException $e) {
			// Manejar la excepción (por ejemplo, registrarla o lanzar una nueva excepción personalizada)
			return false;
		}
	}



	//Creamos la funcion LoginUsuario para verificar si el usuario existe en la BD y si la contraseña es correcta
	public function autenticarUsuario($email, $contrasena)
	{
		try {
			// Creamos la consulta para verificar si el usuario existe en la BD y está activo
			$sql = "SELECT email, contrasena FROM usuario WHERE email = :email AND estadologico = 'activo'";

			// Preparamos la consulta
			$query = $this->db->prepare($sql);

			// Ejecutamos la consulta
			$query->execute(['email' => $email]);

			// Obtenemos el resultado de la consulta
			$usuario = $query->fetch(PDO::FETCH_ASSOC);

			// Verificamos si se encontró el usuario en la BD y si está activo
			if ($usuario) {
				// Verificamos la contraseña utilizando password_verify
				if (password_verify($contrasena, $usuario['contrasena'])) {
					// La contraseña es correcta, puedes retornar los datos del usuario o simplemente true si solo necesitas verificar la autenticación
					return true;
				}
			}

			// Si no se cumple ninguna de las condiciones anteriores, retornamos false
			return false;
		} catch (PDOException $e) {
			// Manejo de excepciones
			// Aquí puedes registrar o notificar el error, dependiendo de tus necesidades
			error_log('Error en autenticación: ' . $e->getMessage());
			return false;
		}
	}

	//Creamos la funcion para registrar un usuario en la BD
	public function registroUsuario($email, $contrasena, $alias, $rutaimagen, $rol, $estadologico)
	{
		try {

			// Comienza una transacción
			$this->db->beginTransaction();

			// Encripta la contraseña con password_hash y PASSWORD_DEFAULT
			$contrasena = password_hash($contrasena, PASSWORD_DEFAULT);

			// Crea la consulta para insertar los datos en la BD 
			$sql = "INSERT INTO usuario (email, contrasena, alias, rol, fotoperfilologo, estadologico) 
                VALUES (:email, :contrasena, :alias, :rol, :fotoperfilologo, :estadologico)";

			// Prepara la consulta
			$query = $this->db->prepare($sql);

			// Ejecuta la consulta
			$query->execute([
				'email' => $email,
				'contrasena' => $contrasena,
				'alias' => $alias,
				'rol' => $rol,
				'fotoperfilologo' => $rutaimagen,
				'estadologico' => $estadologico
			]);

			// Confirma la transacción
			$this->db->commit();

			// Retorna true si la inserción fue exitosa
			return true;
		} catch (PDOException $e) {
			// Si ocurre un error, se revierte la transacción
			if ($this->db->inTransaction()) {
				$this->db->rollback();
			}

			// Manejo de excepciones
			echo 'Error al ejecutar la consulta en usuario: ' . $e->getMessage();
			return false;
		}
	}


	//Creamos la funcion para obtener los datos del usuario en la BD
	public function obtenerDatosUsuario($email)
	{
		try {
			// Creamos la consulta para obtener los datos del usuario en la BD
			$sql = "SELECT * FROM usuario WHERE email = :email";
	
			// Preparamos la consulta
			$query = $this->db->prepare($sql);
	
			// Ejecutamos la consulta
			$query->execute(['email' => $email]);
	
			// Obtenemos los datos del usuario como un objeto
			$datosUsuario = $query->fetch(PDO::FETCH_OBJ);
	
			// Verificamos si se encontraron datos del usuario
			if ($datosUsuario) {
				// Puedes realizar validaciones adicionales aquí si es necesario
	
				// Retorna los datos del usuario
				return $datosUsuario;
			}
	
			// Si no se encontraron datos del usuario, retornamos null
			return null;
		} catch (PDOException $e) {
			// Manejo de excepciones
			// Aquí puedes registrar o notificar el error, dependiendo de tus necesidades
			error_log('Error al obtener datos del usuario: ' . $e->getMessage());
			return false;
		}
	}

	//Creamos la funcion para dar el alta lógica al usuario
	public function altaLogicaUsuario($email)
	{
		try {
			// Creamos la consulta para dar de alta lógica al usuario
			$sql = "UPDATE usuario SET estadologico = 'activo' WHERE email = :email";
	
			// Preparamos la consulta
			$query = $this->db->prepare($sql);
	
			// Ejecutamos la consulta
			$query->execute(['email' => $email]);
	
			// Verificamos si se dio de alta lógica al usuario
			if ($query->rowCount() > 0) {
				// Retornamos true si se dio de alta lógica al usuario
				return true;
			} else {
				// Retornamos false si no se dio de alta lógica al usuario
				return false;
			}
		} catch (PDOException $e) {
			// Manejar o registrar el error, por ejemplo, con un mensaje de error o registro de errores.
			// Aquí puedes agregar código para manejar el error, como registrar el error en un archivo de registro.
			// Luego, puedes lanzar una excepción o devolver un valor predeterminado según tus necesidades.
			// Ejemplo:
			// throw new Exception("Error al dar de alta lógica al usuario: " . $e->getMessage());
			return false; // Devolver false en caso de error.
		}
	}
	

	//Creamos la funcion para dar la baja lógica al usuario
	public function bajaLogicaUsuario($email)
	{
		try {
			// Creamos la consulta para dar de baja lógica al usuario
			$sql = "UPDATE usuario SET estadologico = 'inactivo' WHERE email = :email";
	
			// Preparamos la consulta
			$query = $this->db->prepare($sql);
	
			// Ejecutamos la consulta
			$query->execute(['email' => $email]);
	
			// Verificamos si se dio de baja lógica al usuario
			if ($query->rowCount() > 0) {
				// Retornamos true si se dio de baja lógica al usuario
				return true;
			} else {
				// Retornamos false si no se dio de baja lógica al usuario
				return false;
			}
		} catch (PDOException $e) {
			// Manejar o registrar el error, por ejemplo, con un mensaje de error o registro de errores.
			// Aquí puedes agregar código para manejar el error, como registrar el error en un archivo de registro.
			// Luego, puedes lanzar una excepción o devolver un valor predeterminado según tus necesidades.
			// Ejemplo:
			// throw new Exception("Error al dar de baja lógica al usuario: " . $e->getMessage());
			return false; // Devolver false en caso de error.
		}
	}
	

	//CREAMOS LA FUNCION PARA ACTUALIZAR LOS DATOS DEL USUARIO

	//Creamos la funcion para actualizar la contraseña del usuario
	public function actualizarContrasenaUsuario($email, $contrasena)
	{
		try {
			// Encripta la contraseña con password_hash y password_default, que es un algoritmo seguro
			$contrasenaHash = password_hash($contrasena, PASSWORD_DEFAULT);
	
			// Prepara la consulta para actualizar la contraseña del usuario
			$sql = "UPDATE usuario SET contrasena = :contrasena WHERE email = :email";
	
			// Prepara la consulta
			$query = $this->db->prepare($sql);
	
			// Ejecuta la consulta con los parámetros
			$query->execute(['contrasena' => $contrasenaHash, 'email' => $email]);
	
			// Verifica si se actualizó la contraseña del usuario
			if ($query->rowCount() > 0) {
				// Retorna true si se actualizó la contraseña con éxito
				return true;
			} else {
				// Retorna false si no se actualizó la contraseña (puede ser que la contraseña sea la misma)
				return false;
			}
		} catch (PDOException $e) {
			// Manejo de excepciones en caso de error en la base de datos
			error_log('Error al actualizar la contraseña del usuario: ' . $e->getMessage());
			return false; // Retorna false en caso de error
		}
	}
	

	//Creamos funcion para modificar el alias del usuario
	public function actualizarAliasUsuario($email, $alias)
	{
		try {
			//Limpiamos el alias con htmlspecialchars y otros para mejorar la seguridad
			$alias = strip_tags($alias);
			$alias = htmlspecialchars($alias);
			$alias = trim($alias);

			// Verificamos que el alias no tenga caracteres especiales, excepto ñ, Ñ o guiones bajos
			if (!preg_match("/^[a-zA-Z0-9ñÑ_]*$/", $alias)) {
				// Retornamos false si el alias tiene caracteres especiales
				return false;
			}

			// Si el alias tiene espacios, los reemplazamos por guiones bajos
			$alias = str_replace(' ', '_', $alias);

			// Creamos la consulta para actualizar el alias del usuario
			$sql = "UPDATE usuario SET alias = :alias WHERE email = :email";

			// Preparamos la consulta
			$query = $this->db->prepare($sql);

			// Ejecutamos la consulta
			$query->execute(['alias' => $alias, 'email' => $email]);

			// Verificamos si se actualizó el alias del usuario
			if ($query->rowCount() > 0) {
				// Retornamos true si se actualizó el alias del usuario
				return true;
			} else {
				// Retornamos false si no se actualizó el alias del usuario
				return false;
			}
		} catch (PDOException $e) {
			// Capturamos cualquier excepción PDO y devolvemos el mensaje de error
			return $e->getMessage();
		}
	}
}


?>