<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\UsersModel;
use App\Models\UsersinfoModel;

class Auth extends BaseController
{
    private $usersModel;
    private $validation;
    public $session;
    public $generalHelper;
    public $errores;

    public function __construct()
    {
        $this->usersModel = new UsersModel();
        $this->usersinfoModel = new UsersinfoModel();
        $this->validation = \Config\Services::validation();
        $this->db = db_connect();
        $this->session = \Config\Services::session();
        $this->generalHelper = helper('general');
        $this->errores = '';

        // Si user está logueado y quiere acceder a la página de login... Ya no es necesario esto en versiones mayores a 4.4.4
//        if (
//            !is_null(session()->get('logueado')) &&
//            session()->get('logueado') === true &&
//            service('request')->uri->getPath() === 'auth/login'
//        ) {
//            exit('Ya estás logueado, así que la página de login no está disponible!');
//        }
    }

    /**
     * Método encargado de mostrar la vista de bienvenida tras loguearse ok
     *
     * @return view
     */
    public function index()
    {
        return view('back/auth/welcome');
    }

    /**
     * Por herencia, este método se encarga de mostrar formulario de inicio de sesión
     *
     * @return view
     */
    public function create()
    {
        if ($this->session->logueado) return redirect()->to(base_url('auth/welcome'));
        return view('back/auth/create');
    }

    /**
     * Por herencia, este método encargado de crear sesión.
     * El recaptcha v2 se pone como div, no como input, así que no hay forma de configurar el name. El name por defecto del v2 es: g-recaptcha-response. En el caso del recaptcha v3 sí se pone un input. Para homologar, cuando se use el v3 hay que ponerle el name del v2: g-recaptcha-response
     * @return void
     */
    public function store()
    {
        $this->validation->setRules([
            'user' => 'required|string',
            'pass' => 'required|string',
            'g-recaptcha-response' => 'required|crRecaptchaV2',
        ]);

        if (!$this->validation->withRequest($this->request)->run()) {
            if(isset($this->validation->getErrors()['g-recaptcha-response'])) {
                $this->session->setFlashdata('toastr', '{"tipo": "error", "msg": "'.$this->validation->getErrors()['g-recaptcha-response'].'", "title": "Error"}');
            }
            return redirect()->back()->withInput()->with('errores', $this->validation->getErrors());
        } else {
            $f_user = trim($this->request->getPost('user'));
            $f_pass = trim($this->request->getPost('pass'));
            $userEncontrado = $this->usersModel->getBy('username', $f_user);

            if (is_array($userEncontrado) && count($userEncontrado) > 0) {
                if (password_verify($f_pass, $userEncontrado['password']) === true) {
                    $sesionDatos = [
                        'logueado'   => true,
                        'userid'     => (int)$userEncontrado['id'],
                        'username'   => $userEncontrado['username'],
                        'email'      => $userEncontrado['email'],
                        'group'      => (int)$userEncontrado['group'],
                        'name'       => $userEncontrado['name'],
                        'apellidop'  => $userEncontrado['apellidop'],
                        'apellidom'  => $userEncontrado['apellidom'],
                        'imgprofile' => $userEncontrado['imgprofile'],
                        'name_group' => $userEncontrado['name_group'],
                    ];

                    $this->session->set($sesionDatos);
                    $this->session->setFlashdata('toastr', '{"tipo": "success", "msg": "Inicias sesión correctamente", "title": "Bienvenido"}');
                    return redirect()->to(base_url('auth/welcome'));
                }
                else {
                    $this->session->setFlashdata(
                        'toastr',
                        '{"tipo": "error", "msg": "Usuario/contraseña inexistente", "title": "Error"}'
                    );
                    return redirect()->back();
                }
            }
            elseif (is_null($userEncontrado)) {
                $this->session->setFlashdata(
                    'toastr',
                    '{"tipo": "error", "msg": "Usuario/contraseña incorrectos", "title": "Error"}'
                );
                return redirect()->back();
            }
            elseif (is_string($userEncontrado)) {
                $nohayusers = strpos($userEncontrado, 'Trying to access array offset');
                $error = addslashes($userEncontrado);
                if ($nohayusers !== false) $error = 'Ooops! Parece que tu usuario no está dado de alta';

                $this->session->setFlashdata(
                    'toastr',
                    '{"tipo": "error", "msg": "' . $error . '", "title": "Error"}'
                );
                return redirect()->back();
            }
        }
    }






    /**
     * Método encargado de cerrar sesión
     *
     * @return mixed
     */
    public function logout()
    {
        $this->session->destroy();
        unset($_SESSION['logueado']);
        unset($_SESSION);
        return redirect('auth/login');
    }
}
