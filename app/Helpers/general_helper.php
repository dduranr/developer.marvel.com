<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use chillerlan\QRCode\{QRCode, QROptions};
use chillerlan\QRCode\Data\QRMatrix;
use chillerlan\QRCode\Output\QRGdImagePNG;
use App\Controllers\Back\QRImageWithText;



/**
* Esta función ejecuta una petición cURL a la API de developer.marvel.com para recuperar los heroes
*
* @author  David Durán
* @return  Array. Devuelve un array con 3 elementos: status (booleano que indica si tuvo éxito o no la petición), msg (mensaje descriptivo del resultado de la petición) y data (null si no se encontraron heroes, y un array con los heroes en caso contrario)
*/
if ( !function_exists('getMarvelAllCharacters' ) ){
    function getMarvelAllCharacters() {

        /*
            Documentación
                https://developer.marvel.com/documentation/authorization
        */

        $boolReturn = false;
        $msgReturn = '';
        $dataReturn = null;
        $timestamp = time();
        $hash = md5($timestamp.MARVEL_API_PRIVATE_KEY.MARVEL_API_PUBLIC_KEY);
        $params = array(
            'apikey' => MARVEL_API_PUBLIC_KEY,
            'hash' => $hash,
            'ts' => $timestamp,
        );


        $sesion_curl = curl_init();
        curl_setopt($sesion_curl, CURLOPT_SSL_VERIFYPEER, false); // Si nuestro dominio de pruebas no cuenta con SSL
        curl_setopt($sesion_curl, CURLOPT_URL, 'https://gateway.marvel.com:443/v1/public/characters?'.http_build_query($params));
        curl_setopt($sesion_curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'charset=utf-8',
            'Authentication: bearer ' . MARVEL_API_PRIVATE_KEY,
        ));
        curl_setopt($sesion_curl, CURLOPT_POST, false);
        curl_setopt($sesion_curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($sesion_curl, CURLOPT_HEADER,         true);
        $respuesta_curl = curl_exec($sesion_curl);
        $error_curl = curl_error($sesion_curl);

        if ($msgReturn) {
            $msgReturn = $error_curl;
        }
        else {

            // echo '<h3>HEADERS</h3>';
            // echo '<pre>';
            // print_r($headersCURL);
            // echo '</pre>';
            //      Array (
            //          [0] => HTTP/1.1 200 OK
            //          [1] => ETag: 2b4e29058ef64a71efc61ce70e3f59f63f3d0e83
            //          [2] => Content-Type: application/json; charset=utf-8
            //          [3] => Content-Length: 99813
            //          [4] => Date: Tue, 30 Jul 2024 13:38:05 GMT
            //          [5] => Connection: keep-alive
            //      )

            // echo '<h3>BODY</h3>';
            // echo '<pre>';
            // print_r($bodyCURL);
            // echo '</pre>';
            //      Array (
            //          [code] => 200
            //          [status] => Ok
            //          [copyright] => © 2024 MARVEL
            //          [attributionText] => Data provided by Marvel. © 2024 MARVEL
            //          [attributionHTML] => Data provided by Marvel. © 2024 MARVEL
            //          [etag] => 2b4e29058ef64a71efc61ce70e3f59f63f3d0e83
            //          [data] => Array (
            //                  [offset] => 0
            //                  [limit] => 20
            //                  [total] => 1564
            //                  [count] => 20
            //                  [results] => Array (
            //                          [0] => Array...
            //                          [1] => Array...
            //                          [2] => Array...

            $respuestaCURL = explode("\r\n\r\n", $respuesta_curl);

            if ( is_array($respuestaCURL) && count($respuestaCURL)===2 ) {

                $headersCURL = explode("\n", $respuestaCURL[0]);
                $bodyCURL = json_decode($respuestaCURL[1], true);

                if (isset($bodyCURL['status']) && $bodyCURL['status']==='Ok') {
                    if (isset($bodyCURL['data']['count']) && $bodyCURL['data']['count']>0) {
                        $boolReturn = true;
                        $msgReturn = 'Todo ok.';
                        $dataReturn = $bodyCURL['data']['results'];
                    }
                    else {
                        $msgReturn .= 'La petición cURL fue exitosa pero no devolvió ningún resultado.';
                    }
                }
                else {
                    $msgReturn .= 'La petición cURL se realizó correctamente pero devolvió error: code: '.$bodyCURL['code'].', '.$bodyCURL['status'];
                }
            }
            else {
                $msgReturn .= 'La respuesta cURL no llegó como se esperaba. Ver por qué no pudo explotarse la respuestaCURL con el delimitador: "\r\n\r\n". La presente fallida respuestaCURL se tuvo que haber almacenado en la carpeta /writable/logs/';
                log_message('error', 'general_helper.php ('.__LINE__.'). Respuesta cURL fallida: '.$msgReturn);
            }
        }
        return array(
            'status' => $boolReturn,
            'msg' => $msgReturn,
            'data' => $dataReturn
        );
    }
}










/**
* Esta función sirve para poner la clase CSS active para los ítems del menú backend
*
* @author  David Durán
* @param   String $nombreItemMenu. Nombre del controlador que se espera recibir en la página actual (en minúscula)
* @param   String $controladorActualName. Nombre del controlador tal cual lo devuelve CI4 (en minúscula)
* @return  String. La clase CSS que pone activo un ítem de menú.
*/
if ( !function_exists('activeMenuItem' ) ){
    function activeMenuItem($nombreItemMenu, $controladorActualName) {
        return ($nombreItemMenu === $controladorActualName) ? 'active' : '';
    }
}



if ( !function_exists('limpiar_str_to_url' ) ){
    function limpiar_str_to_url( $titulo ) {
        $eliminar = array("!","¡","?","¿","‘","\"","$","(",")",".",":",";","_","/","\\","\$","%","@","#",",", "«", "»", "”", "“");
        $buscados = array(" ","á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","Ñ","ü","à","è","ì","ò","ù","À","È","Ì","Ò","Ù");
        $sustitut = array("-","a","e","i","o","u","a","e","i","o","u","n","n","u","a","e","i","o","u","A","E","I","O","U");

        $final = strtolower(str_replace($buscados,$sustitut,str_replace($eliminar,"",$titulo)));
        $final = str_replace("–","-",$final);
        $final = str_replace("–","-",$final);

        return ( strlen( $final ) > 150 ) ? substr($final,0,strrpos(substr($final,0,120),"-")) : $final;
    }
}



/**
* Esta función recupera el nombre de un archivo (con su extensión)
*
* @author  David Durán
* @param   String $cadena. El string de entrada
* @return  String. El nombre dle archivo
*/
if ( !function_exists( 'getFileNameWithExt' ) ) {
    function getFileNameWithExt($cadena) {
        $name = getFileNameWithoutExt($cadena);
        $ext = getFileExtension($cadena);
        return $name.'.'.$ext;
    }
}



/**
* Esta función recupera el nombre de un archivo (sin la extensión y su punto)
*
* @author  David Durán
* @param   String $cadena. El string de entrada
* @param   String $separador. El caracter que separa a las palabras que componen el nombre del archivo. Por defecto se pone guión medio.
* @return  String. El nombre dle archivo
*/
if ( !function_exists( 'getFileNameWithoutExt' ) ) {
    function getFileNameWithoutExt($cadena, $separador='-') {
        $cadena = trim($cadena);
        $fileName_extension = getFileExtension($cadena);
        $cadena = slugify($cadena,$separador,true);
        $fileName_extension_length = (strlen($fileName_extension))+1; // El length de la extensión incluye el punto (por eso el +1)
        return substr($cadena,0,-$fileName_extension_length); // Se devuelve el nombre del archivo slugified, quitando del final el número de caracteres del length de la extensión
    }
}



/**
 * Esta función recupera la extensión de un archivo (sin el punto)
 *
 * @author  David Durán
 * @param   String $cadena. El string de entrada
 * @return  String. La extensión del nombre de archivo
*/
if ( !function_exists( 'getFileExtension' ) ) {
    function getFileExtension($cadena) {
        $cadena = $cadena;
        $cadena = trim($cadena);
        return substr(strrchr($cadena,'.'),1);
    }
}



/**
* Esta función convierte cadena en formato nombre de archivo
*
* @author  David Durán
* @param   String $cadena. El string de entrada
* @param   String $separador. El 'separador' es cualquier caracter custom con el que queramos separar las palabras entre sícustom
* @return  String. La cadena slugifiada
*/
if ( !function_exists( 'slugify' ) ) {
    function slugify($cadena,$separador) {
        // Quitar acentos
        $cadena = str_replace(
            array('Á','É','Í','Ó','Ú','Ñ','á','é','í','ó','ú','ñ','À','È','Ì','Ò','Ù','à','è','ì','ò','ù'),
            array('a','e','i','o','u','n','a','e','i','o','u','n','a','e','i','o','u','a','e','i','o','u'),
            $cadena);

        // Si el separador viene vacío, no se remplaza ningún caracter por él
        if(!is_null($separador) && $separador!=='') {
            // replace non letter or digits by -
            $cadena = preg_replace('~[^\pL\d]+~u', $separador, $cadena);
            // transliterate
            $cadena = iconv('utf-8', 'us-ascii//TRANSLIT', $cadena);
            // remove unwanted characters
            $cadena = preg_replace('~[^-\w]+~', '', $cadena);
            // trim
            $cadena = trim($cadena, $separador);
            // remove duplicate -
            $cadena = preg_replace('~-+~', $separador, $cadena);
            // remove duplicate -
            $cadena = preg_replace('~_+~', $separador, $cadena);
        }
        // lowercase
        $cadena = strtolower($cadena);

        if (empty($cadena)) {
            return 'n'.$separador.'a';
        }
        return $cadena;
    }
}



/**
* Valida el formato de una fecha.
*
* @author  David Durán
* @param   string $formato. El formato de la fecha a validar, por ejemplo: Y-m-d H:i:s
* @param   string $str. La fecha como tal
* @return  bool
*/
if ( !function_exists( 'validarFecha' ) ) {
    function validarFecha($formato, $str) {
        $datetime = DateTime::createFromFormat($formato, $str);
        if($datetime !== false) return true;
        return false;
    }
}


/**
 * Este método genera log custom
 *
 * @author   David Durán
 * @param    String $path_logs. Ruta de la carpeta donde se guardará el archivo log
 * @param    String $ubicacion. Nombre del archivo donde se genera el error
 * @param    String $linea. Línea donde se genera el error
 * @param    String $descripcion. Detalle del error
 * @return   Void
*/
if ( !function_exists( 'log' ) ) {
    function log($path_logs, $ubicacion, $linea, $descripcion) {
        $body_log   = date('Y-m-d H:i:s')." - ".$ubicacion." (".$linea.") - ".$descripcion."\n";
        $archivoLOG = fopen($path_logs."custom_errorlog", "a") or die("ERROR! Imposible abrir custom_errorlog. ");
        fputs($archivoLOG, $body_log);
        fclose($archivoLOG);
    }
}



/**
 * Este método devuelve el array de configuración que permite armar la paginación de registros
 *
 * @author   David Durán
 * @param    String $base_url.  URI de la página donde se muestran los registros
 * @param    Int    $total_rows Número total de registros
 * @param    Int    $per_page   Número de registros a mostrar por página
 * @param    Int    $num_links  Número máximo de links a mostrar en el paginador
 * @return   Array  Devuelve el array de configuración
*/
if ( !function_exists( 'paginationConfig' ) ) {
    function paginationConfig($base_url, $total_rows, $per_page, $num_links) {
        // $config['base_url'] = base_url($base_url);
        $config['base_url'] = site_url($base_url);
        $config['total_rows'] = $total_rows;
        $config['per_page'] = $per_page;
        $config['num_links'] = $num_links;
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = false;
        $config['last_link'] = false;
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = '&laquo';
        $config['prev_tag_open'] = '<li class="prev">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&raquo';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a style="cursor:default;" class="page-link" href="javascript:;">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['attributes'] = array('class' => 'page-link');
        return $config;
    }
}



/**
 * Este método valida las imágenes provenientes de input files. Los primeros tres caracteres de los names de todos los input files DEBEN ser: img. Sólo así este método sabe qué archivo hallado en $_FILES debe presuponerse como imagen. Como se ve, este método busca dentro del superglobal $_FILES, así que no hay que indicarle qué imágenes hay que validar. Valida todas las que encuentre.
 *
 * @author   David Durán
 * @param    String $returnErrorAs. Indica qué se devuelve en caso de existir errores. Por defecto devuelve una cadena de párrafos. Cualquier otro valor devuelve un array.
 * @return   Mix En caso de existir errores, devuelve un string o array según el valor del parámetro $returnErrorAs. Si no hay errores, devuelve booleano true.
*/
if ( !function_exists( 'validarImgs' ) ) {
    function validarImgs($returnErrorAs='string') {
        $res = array();
        $resString = '';
        if (isset($_FILES) && count($_FILES)>0) {
            foreach ($_FILES as $inputName => $file) {
                // Si se intenta hacer update a un formulario que contiene input files pero no se carga ningún archivo, $_FILES igual les hace referencia a los input pero les pone error 4, de que no se subió el archivo. Por tanto, sólo validamos a los inputs que sí se subieron
                if ($file['error']!==4) {
                    $esIMG = false;
                    $esIMG = (substr($inputName,0,3)==='img') ? true : false;

                    // Primero validamos que las imágenes vengan ok (si vienen)
                    if ($esIMG) {
                        // Si el archivo se subió sin problemas
                        if ($file['error']===0) {
                            $size=$finfofile = null;
                            $size = filesize($_FILES[$inputName]['tmp_name']);
                            $finfofile = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $_FILES[$inputName]['tmp_name']);

                            // Si La imagen tiene un peso permitido
                            if ($size <= MAXUPLOADSIZE_EN_BYTES) {
                                // Si La imagen tiene un tipo mime permitido
                                if (in_array($finfofile, MIMETYPES_IMGS)) {
                                    // Sólo si la imagen actual validó ok su peso y el mimetype
                                    $res['ok'][$inputName] = true;
                                }
                                else {
                                    if (!isset($res['error'][$inputName])) {
                                        if (!isset($res['error'])) $res['error'] = array();
                                        if (!isset($res['error'][$inputName])) $res['error'][$inputName] = null;
                                    }
                                    $res['error'][$inputName] .= 'La imagen '.strtoupper($inputName).' tiene un problema interno. Por favor genérala nuevamente. ';
                                }
                            }
                            else {
                                if (!isset($res['error'][$inputName])) {
                                    if (!isset($res['error'])) $res['error'] = array();
                                    if (!isset($res['error'][$inputName])) $res['error'][$inputName] = null;
                                }
                                $res['error'][$inputName] .= 'La imagen '.strtoupper($inputName).' tiene un peso mayor al permitido: '.formatBytes($size).'. ';
                            }
                        }
                        else {
                            if (!isset($res['error'][$inputName])) {
                                if (!isset($res['error'])) $res['error'] = array();
                                if (!isset($res['error'][$inputName])) $res['error'][$inputName] = null;
                            }
                            switch ($_FILES[$inputName]['error']) {
                                case 1: $res['error'][$inputName] .= 'El fichero subido excede la directiva upload_max_filesize de php.ini.';
                                case 2: $res['error'][$inputName] .= 'El fichero subido excede la directiva MAX_FILE_SIZE especificada en el formulario HTML.';
                                case 3: $res['error'][$inputName] .= 'El fichero fue sólo parcialmente subido.';
                                case 4: $res['error'][$inputName] .= 'No se subió ningún fichero.';
                                case 6: $res['error'][$inputName] .= 'Falta la carpeta temporal.';
                                case 7: $res['error'][$inputName] .= 'No se pudo escribir el fichero en el disco.';
                                case 8: $res['error'][$inputName] .= 'Una extensión de PHP detuvo la subida de ficheros. PHP no proporciona una forma de determinar la extensión que causó la parada de la subida de ficheros; el examen de la lista de extensiones cargadas con phpinfo() puede ayudar.';
                            }
                        }
                    }
                }
            }
            // En cualquier caso, si no existe error se devuelve true. Pero si sí, entonces se devuelve string o array según se indique
            if (!isset($res['error'])) return true;
            else {
                if ($returnErrorAs === 'string') {
                    foreach ($res['error'] as $inputName => $strError) $resString .= $strError.'<br>';
                    return $resString;
                }
                return $res;
            }
        }
        else return true;
    }
}


if ( !function_exists( 'convertAcentosToHTMLentities' ) ) {
    function convertAcentosToHTMLentities($cadena) {

        $caracteres_entidades = [
            'á' => '&aacute;', 'à' => '&agrave;', 'â' => '&acirc;', 'ã' => '&atilde;', 'ä' => '&auml;',
            'é' => '&eacute;', 'è' => '&egrave;', 'ê' => '&ecirc;', 'ë' => '&euml;',
            'í' => '&iacute;', 'ì' => '&igrave;', 'î' => '&icirc;', 'ï' => '&iuml;',
            'ó' => '&oacute;', 'ò' => '&ograve;', 'ô' => '&ocirc;', 'õ' => '&otilde;', 'ö' => '&ouml;',
            'ú' => '&uacute;', 'ù' => '&ugrave;', 'û' => '&ucirc;', 'ü' => '&uuml;',
            'Á' => '&Aacute;', 'À' => '&Agrave;', 'Â' => '&Acirc;', 'Ã' => '&Atilde;', 'Ä' => '&Auml;',
            'É' => '&Eacute;', 'È' => '&Egrave;', 'Ê' => '&Ecirc;', 'Ë' => '&Euml;',
            'Í' => '&Iacute;', 'Ì' => '&Igrave;', 'Î' => '&Icirc;', 'Ï' => '&Iuml;',
            'Ó' => '&Oacute;', 'Ò' => '&Ograve;', 'Ô' => '&Ocirc;', 'Õ' => '&Otilde;', 'Ö' => '&Ouml;',
            'Ú' => '&Uacute;', 'Ù' => '&Ugrave;', 'Û' => '&Ucirc;', 'Ü' => '&Uuml;',
            'ñ' => '&ntilde;', 'ç' => '&ccedil;',
            'Ñ' => '&Ntilde;', 'Ç' => '&Ccedil;'
        ];
        return strtr($cadena, $caracteres_entidades);
    }
}
