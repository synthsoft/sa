<?php 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if(isset($_POST['correo'])) {
      // Si se envió un correo, ejecutar la función para verificar el correo
      $email = $_POST['correo'];

      // Llamamos al modelo
      require_once("models/usuario_model.php");
      $usuario = new usuario_model();

      // Llamamos a la función verificarEmail
      $correoExiste = $usuario->verificarEmail($email);

      // Enviar una respuesta en texto plano
      if ($correoExiste) {
          echo 'no disponible';
      } else {
          echo 'disponible';
      }
  } elseif(isset($_POST['alias'])) {
      // Si se envió un alias, ejecutar la función correspondiente para verificar el alias
      $alias = $_POST['alias'];

      // Llamamos al modelo
      require_once("models/usuario_model.php");
      $usuario = new usuario_model();

      // Llamamos a la función verificarAlias (debes tener esta función en tu modelo)
      $aliasExiste = $usuario->verificarAlias($alias);

      // Enviar una respuesta en texto plano
      if ($aliasExiste) {
          echo 'no disponible';
      } else {
          echo 'disponible';
      }
  }
}



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['correologin']) && isset($_POST['contrasenalogin'])) {
    // Obtener el correo y la contraseña enviados por AJAX
    $correo = $_POST['correologin'];
    $contrasena = $_POST['contrasenalogin'];

    // Llamamos al modelo
    require_once("models/usuario_model.php");

    // Creamos una instancia del modelo
    $usuario = new usuario_model();

    // Llamamos a la función autenticarUsuario
    $exito = $usuario->autenticarUsuario($correo, $contrasena);

    //SI el usuario existe en la BD y la contraseña es correcta ENVIAMOS UN TEXTO PLANO  credenciales válidas
    if ($exito) {
        echo 'credenciales válidas';
    } else {
        echo 'credenciales inválidas';
    }

} 
   


?>