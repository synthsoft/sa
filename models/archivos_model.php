<?php

// Creamos la clase imagenes_model
class archivos_model
{
    // Creamos el constructor de la clase
    public function __construct()
    {
    }

    function subir_imagen_obtener_ruta($directorio_destino, $nombre_fichero)
    {
        
        //Variable super-global que se utiliza para gestionar la subida de archivos en aplicaciones web.
        //$_FILES[]['tmp_name'] El nombre del fichero temporal
        //que se utiliza para almacenar en el servidor el archivo recibido.
        $tmp_name = $_FILES[$nombre_fichero]['tmp_name'];

        //Función is_dir() para verificar si la ruta que especificaste existe y si apunta a un directorio.
        //Función is_uploaded_file() se usa asegurarse de que un usuario malicioso no ha intentado 
        //engañar al script haciéndole trabajar con archivos con los que no debiera de estar trabajando.
        if (is_uploaded_file($tmp_name)) {

            //El nombre original del fichero en la máquina cliente. 
            $img_file = $_FILES[$nombre_fichero]['name'];

            //El tipo mime del fichero (si el navegador lo proporciona). Un ejemplo podría ser "image/gif".
            $img_type = $_FILES[$nombre_fichero]['type'];

            //strpos() busca la posición de una subcadena dentro de una cadena de texto.
            if (((strpos($img_type, "gif") || strpos($img_type, "jpeg") || strpos($img_type, "jpg")) || strpos($img_type, "png"))) {

                //La función move_uploaded_file() se utiliza para mover el archivo a la posición definitiva.
                //Recibe por un lado el nombre temporal del fichero y por otro el nombre que deseamos colocarle definitivamente y,
                //si se desea, la ruta para llegar al directorio donde queremos guardarlo.

                //Vamos a generar un nombre único para el fichero, que no exista ya en la carpeta destino.
                //Para ello vamos a concatenar la función uniqid() que genera un identificador único de 13 caracteres
                //con la función microtime() que devuelve el tiempo actual en microsegundos.
                $img_file = uniqid() . "-" . microtime() . "." . substr($img_type, 6);

                if (move_uploaded_file($tmp_name, $directorio_destino . '/' . $img_file)) {

                    $ruta = $directorio_destino . '/' . $img_file;

                    return $ruta;
                }

            }
        }
        return false;


    }

    function subir_pdf_obtener_ruta($directorio_destino, $nombre_fichero)
    {

        //Variable super-global que se utiliza para gestionar la subida de archivos en aplicaciones web.
        //$_FILES[]['tmp_name'] El nombre del fichero temporal
        //que se utiliza para almacenar en el servidor el archivo recibido.
        $tmp_name = $_FILES[$nombre_fichero]['tmp_name'];

        //Función is_dir() para verificar si la ruta que especificaste existe y si apunta a un directorio.
        //Función is_uploaded_file() se usa asegurarse de que un usuario malicioso no ha intentado 
        //engañar al script haciéndole trabajar con archivos con los que no debiera de estar trabajando.
        if (is_uploaded_file($tmp_name)) {

            //El nombre original del fichero en la máquina cliente. 
            $pdf_file = $_FILES[$nombre_fichero]['name'];

            //El tipo mime del fichero (si el navegador lo proporciona). 
            $pdf_type = $_FILES[$nombre_fichero]['type'];

            //Verificamos que sea un PDF el archivo que se sube
            if (strpos($pdf_type, "pdf")) {

                //La función move_uploaded_file() se utiliza para mover el archivo a la posición definitiva.
                //Recibe por un lado el nombre temporal del fichero y por otro el nombre que deseamos colocarle definitivamente y,
                //si se desea, la ruta para llegar al directorio donde queremos guardarlo.

                //Vamos a generar un nombre único para el fichero, que no exista ya en la carpeta destino.
                //Para ello vamos a concatenar la función uniqid() que genera un identificador único de 13 caracteres
                //con la función microtime() que devuelve el tiempo actual en microsegundos.
                $pdf_file = uniqid() . "-" . microtime() . "." . 'pdf';

                if (move_uploaded_file($tmp_name, $directorio_destino . '/' . $pdf_file)) {

                    $ruta = $directorio_destino . '/' . $pdf_file;

                    return $ruta;
                }

            }
        }
        return false;


    }

    //Creamos la funcion subir_pdf_o_imagen_obtener_ruta
    function subir_pdf_o_imagen_obtener_ruta($directorio_destino, $nombre_fichero)
    {


        //Variable super-global que se utiliza para gestionar la subida de archivos en aplicaciones web.
        //$_FILES[]['tmp_name'] El nombre del fichero temporal
        //que se utiliza para almacenar en el servidor el archivo recibido.
        $tmp_name = $_FILES[$nombre_fichero]['tmp_name'];

        //Función is_dir() para verificar si la ruta que especificaste existe y si apunta a un directorio.
        //Función is_uploaded_file() se usa asegurarse de que un usuario malicioso no ha intentado 
        //engañar al script haciéndole trabajar con archivos con los que no debiera de estar trabajando.
        if (is_uploaded_file($tmp_name)) {

            //El nombre original del fichero en la máquina cliente. 
            $img_file = $_FILES[$nombre_fichero]['name'];

            //El tipo mime del fichero (si el navegador lo proporciona). Un ejemplo podría ser "image/gif".
            $img_type = $_FILES[$nombre_fichero]['type'];

            //strpos() busca la posición de una subcadena dentro de una cadena de texto.
            if (((strpos($img_type, "gif") || strpos($img_type, "jpeg") || strpos($img_type, "jpg")) || strpos($img_type, "png"))) {

                //La función move_uploaded_file() se utiliza para mover el archivo a la posición definitiva.
                //Recibe por un lado el nombre temporal del fichero y por otro el nombre que deseamos colocarle definitivamente y,
                //si se desea, la ruta para llegar al directorio donde queremos guardarlo.

                //Vamos a generar un nombre único para el fichero, que no exista ya en la carpeta destino.
                //Para ello vamos a concatenar la función uniqid() que genera un identificador único de 13 caracteres
                //con la función microtime() que devuelve el tiempo actual en microsegundos.
                $img_file = uniqid() . "-" . microtime() . "." . substr($img_type, 6);

                if (move_uploaded_file($tmp_name, $directorio_destino . '/' . $img_file)) {

                    $ruta = $directorio_destino . '/' . $img_file;

                    return $ruta;
                }


            } else if (strpos($img_type, "pdf")) {

                //La función move_uploaded_file() se utiliza para mover el archivo a la posición definitiva.
                //Recibe por un lado el nombre temporal del fichero y por otro el nombre que deseamos colocarle definitivamente y,
                //si se desea, la ruta para llegar al directorio donde queremos guardarlo.

                //Vamos a generar un nombre único para el fichero, que no exista ya en la carpeta destino.
                //Para ello vamos a concatenar la función uniqid() que genera un identificador único de 13 caracteres
                //con la función microtime() que devuelve el tiempo actual en microsegundos.
                $pdf_file = uniqid() . "-" . microtime() . "." . 'pdf';

                if (move_uploaded_file($tmp_name, $directorio_destino . '/' . $pdf_file)) {

                    $ruta = $directorio_destino . '/' . $pdf_file;

                    return $ruta;
                }

            }


        }
    }




}


?>