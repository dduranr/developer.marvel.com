<?php

    namespace app\Config\ValidationRules;
    use App\Models\HeroesModel;
    
    class CustomRules
    {
        // Para evitar choques en los nombres de las reglas de validación del core de CI 4 y las de este archivo de reglas custom, todos los métodos de esta clase llevarán como prefijo del nombre: cr (Custom Rule)


        /**
        * Esta regla se encarga de validar el Recaptcha V2 de Google
        *
        * @param 	String $str. Cadena que contiene el valor del campo g-recaptcha
        * @param 	String $error. Debe guardar cualquier cadena de error que surja durante la validación
        * @author 	David Durán
        */
        public function crRecaptchaV2(string $str, string &$error = null): bool
        {
            if(strlen($str) > 0) {
                $url_recaptcha_siteverify = 'https://www.google.com/recaptcha/api/siteverify';
                $dataRecaptcha = array('secret' => GOOGLE_SECRETKEY, 'response' => $str);
                $recaptcha_options = array(
                    'http' => array(
                        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                        'method'  => 'POST',
                        'content' => http_build_query($dataRecaptcha)
                    )
                );
                $recaptcha_context      = stream_context_create($recaptcha_options);
                $recaptcha_response     = file_get_contents($url_recaptcha_siteverify, false, $recaptcha_context);
                $recaptcha_responseKeys = json_decode($recaptcha_response,true);
                header('Content-type: application/json');

                if(isset($recaptcha_responseKeys['success']) && $recaptcha_responseKeys['success']===true) {
                    return true;
                }
                else $error .= lang('Custom.google.recaptcha.error3');
            }
            else $error .= lang('Custom.google.recaptcha.error4');
            return false;    
        }


        /**
        * Esta regla se encarga de validar el Recaptcha V3 de Google
        *
        * @param 	String $str. Cadena que contiene el valor del campo g-recaptcha
        * @param 	String $error. Debe guardar cualquier cadena de error que surja durante la validación
        * @author 	David Durán
        */
        public function crRecaptchaV3(string $str, string &$error = null): bool
        {
            if(strlen($str) > 0) {
                $url_recaptcha_siteverify = 'https://www.google.com/recaptcha/api/siteverify';
                $dataRecaptcha = array('secret' => GOOGLE_SECRETKEY, 'response' => $str);
                $recaptcha_options = array(
                    'http' => array(
                        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                        'method'  => 'POST',
                        'content' => http_build_query($dataRecaptcha)
                    )
                );
                $recaptcha_context      = stream_context_create($recaptcha_options);
                $recaptcha_response     = file_get_contents($url_recaptcha_siteverify, false, $recaptcha_context);
                $recaptcha_responseKeys = json_decode($recaptcha_response,true);
                header('Content-type: application/json');
                if($recaptcha_responseKeys['success']) {
                    if (isset($recaptcha_responseKeys['score'])) {
                        if($recaptcha_responseKeys['score'] >= RECAPTCHA_SCORE) return true;
                        else $error .= lang('Custom.google.recaptcha.error1');
                    }
                    else $error .= lang('Custom.google.recaptcha.error2');
                }
                else $error .= lang('Custom.google.recaptcha.error3');
            }
            else $error .= lang('Custom.google.recaptcha.error4');
            return false;            
        }



        /**
        * Esta regla se encarga de validar el campo de email del radioescucha que quiere votar. Valida:
        *   1. Que el email tenga formato correcto
        *   2. Que el email no esté en en nuestra lista negra
        *
        * @param    String $str. Cadena que viene como valor del campo email
        * @param 	String $error. Debe guardar cualquier cadena de error que surja durante la validación
        * @author   David Durán
        */
        public function crEmail(string $str, string &$error = null): bool
        {
            $str = trim($str);

            // Validación 1
            if (filter_var($str, FILTER_VALIDATE_EMAIL)) {
                // Validación 2
                $archivoEmailsFake = fopen(FOLDER_ROOT.'app/Config/fake/emails-prohibidos.txt', 'r');
                while (($line = fgets($archivoEmailsFake)) !== false) {
                    $line = trim($line);
                    if ($str === $line) {
                        $error .= '<p>Este email (<strong>'.$str.'</strong>) está en blacklist, por favor utiliza otro.</p>';
                        return false;
                    }
                }
                fclose($archivoEmailsFake);
            }
            else {
                $error .= '<p>El email (<strong>'.$str.'</strong>) no tiene formato correcto.</p>';
                return false;
            }
            return true;
        }


        /**
        * Esta regla se encarga de validar el campo QR al crear una heroe. Este campo debe cumplir con estos requisitos:
        *   Sin acentos.
        *   Sin espacios.
        *   Sin caracteres especiales (como símbolos de número, porcentaje, paréntesis, etc).
        *   Máximo 50 caracteres de largo.
        *   Sólo números y letras minúsculas.
        *   Se pueden poner guiones medios a modo de espacios.
        *   Sólo debe contener un único punto, y es el que separa al nombre de la extensión. Y la extensión SIEMPRE será SVG.

        * @param    String $str. Cadena que viene como valor del campo email
        * @param 	String $error. Debe guardar cualquier cadena de error que surja durante la validación
        * @author   David Durán
        */
        public function crQRfilename(string $str, string &$error = null): bool
        {
            $str = validaQRfilename($str);
            $heroesModel = new HeroesModel();

            if(!is_null($heroesModel->getBy('qr', $str))) {
                $error .= '<p>El nombre que sugieres para el QR (<strong>'.$str.'</strong>) ya está en uso por otra heroe. Intenta otro nombre.</p>';
                return false;
            }
            return true;
        }


        /**
        * Esta regla se encarga de validar que una contraseña cumpla con requisitos mínimos de seguridad:
        *   Al menos 2 dígitos
        *   Al menos 2 letras minúsculas
        *   Al menos 2 letras mayúsculas
        *   Al menos 2 caracteres especiales
        
        * @param    String $str. Cadena que viene como valor del campo pass
        * @param 	String $error. Debe guardar cualquier cadena de error que surja durante la validación
        * @author   David Durán
        */
        public function crStrongPass(string $str, string &$error = null): bool
        {
            $passValida = validarPass($str, 12);

            if(!$passValida) {
                $error .= '<p>La contraseña no es lo suficientemente fuerte. Por favor asegúrate que cumpla con los requisitos indicados.</p>';
                return false;
            }
            return true;
        }

    }