<?php
class Conectar
{

//Declarar propiedades o métodos de clases como estáticos 
//los hacen accesibles sin la necesidad de instanciar la clase.
	public static function conexion()
	{

		/* Aquí hay que usar la ruta donde se encuentre el archivo con las credenciales*/
        $iniData = parse_ini_file(".private/config.php.ini");
		$servidor = $iniData["servidor"];
		$bbdd = $iniData["bbdd"];
		$usuario = $iniData["usuario"];
		$pass = $iniData["pass"];

		try {

			//Declaro la conexion con la base de datos
			$conexion = new PDO(
				"mysql:host=$servidor;dbname=$bbdd",
				$usuario,
				$pass
			);

			//Soluciona problemas con caracteres
			$conexion->query("SET NAMES 'utf8'");

			//PDO::ATTR_EMULATE_PREPARES: Se usa para desactivar emulación de consultas preparadas 
			//    forzando el uso real de consultas preparadas. 
			//    Es muy importante establecerlo a false para prevenir Inyección SQL. 
			//    Ver: https://es.stackoverflow.com/a/53280/29967

			$conexion->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

			//Ingresamos el modo de error para que nos muestre los errores en pantalla y no en el log de apache
			$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			//Devuelve la conexion
			return $conexion;


			//Si hay un error, retorna falso.

		} catch (PDOException $errorbd) {

			//Redirigimos a la página de error
			header("Location: error.html?m=Error de conexión a la base de datos");
			exit;

		}
	}
}
?>