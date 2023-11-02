<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div class="bajalogicaprueba">
        <h1>Baja logica usuario</h1>
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
            <input type="text" name="emailbaja">
            <input type="submit" value="Enviar" name="enviarbaja">
        </form>

    </div>

    <div class="altalogicaprueba">
        <h1>Alta logica usuario</h1>
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
            <input type="text" name="emailalta">
            <input type="submit" value="Enviar" name="enviaralta">
        </form>

    </div>

    <div>
       
    <h1>Probamos el aceptar renovacion de membresia</h1>

    <?php 

    //Instanciamos la clase administrador_model
    require_once("models/administrador_model.php");
    $instanciaclase=new administrador_model();

    
    $emailrest="emailadmin@mail.coms";
    $tiporenovacion=2;

    $resultado=$instanciaclase->aceptarRenovacion($emailrest,$tiporenovacion);

    if($resultado==true){
        echo "Renovacion aceptada correctamente";

    }else{
        echo "Error al aceptar renovacion";
    }
    
    ?>

        

    </div>

    <div>
        <h1>Probamos la funcion dar baja soli membresia</h1>

        <?php 
        //intanciamos restaurante_model
        require_once("models/restaurante_model.php");
        $instanciaclase=new restaurante_model();

        $emailrest="gonzalomartinocampo@gmail.com";
        $tipomembresia=2;
        $comprobante="comprobante.jpg";

        $resultado=$instanciaclase->darBajaSolicitudMembresa($emailrest);

        if($resultado==true){
            echo "Solicitud de membresia bajada correctamente";

        }else{
            echo "Error al enviar baja solicitud de membresia";
        }
        
        ?>
    </div>

    <div>
        <h1>Probamos el renovar membresia</h1>

        <?php 
$emailrest="gonzalomartinocampo@gmail.com";
$tipomembresia=2;
$comprobante="comprobante.jpg";

$resultado=$instanciaclase->solicitarRenovacionMembresia($emailrest,$tipomembresia,$comprobante);

if($resultado==true){
    echo "Solicitud de renovacion de membresia enviada correctamente";

}else{
    echo "Error al enviar solicitud de renovacion de membresia";
}

        
        ?>

    </div>



</body>

</html>

<?php
require_once("models/usuario_model.php");
$usuario = new usuario_model();


if (isset($_POST["enviarbaja"])) {
    $email = $_POST["emailbaja"];
    $resultado = $usuario->bajaLogicaUsuario($email);

    //verficamos si la accion se realizo correctamente
    if ($resultado == true) {
        echo "Usuario dado de baja correctamente";
    } else {
        echo "Error al dar de baja al usuario";
    }
}

if (isset($_POST["enviaralta"])) {
    $email = $_POST["emailalta"];
    $resultado = $usuario->altaLogicaUsuario($email);

    //verficamos si la accion se realizo correctamente
    if ($resultado == true) {
        echo "Usuario dado de baja correctamente";
    } else {
        echo "Error al dar de baja al usuario";
    }
}










?>