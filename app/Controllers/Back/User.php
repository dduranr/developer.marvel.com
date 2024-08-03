<?php

namespace App\Controllers\Back;

use App\Controllers\BaseController;
use App\Models\HeroesModel;
use App\Models\UsersModel;
use App\Models\UsersinfoModel;
use App\Models\UsersgroupsModel;
use App\Models\ConfiguracionesModel;
use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;



class User extends BaseController
{
    private $usersModel;
    private $usersinfoModel;
    private $usersgroupsModel;
    private $configuracionesModel;
    private $validation;
    public $session;
    public $errores;
    public $generalHelper;
    public $filenameJSONcolegios;
    public $config1;

    public function __construct()
    {
        $this->heroesModel = new HeroesModel();
        $this->usersModel = new UsersModel();
        $this->usersinfoModel = new UsersinfoModel();
        $this->usersgroupsModel = new UsersgroupsModel();
        $this->configuracionesModel = new ConfiguracionesModel();
        $this->validation = \Config\Services::validation();
        $this->session = \Config\Services::session();
        $this->errores = '';
        $this->generalHelper = helper('general');
        $this->config1 = $this->configuracionesModel->getBy('id', 1);
        $this->config1['datos'] = json_decode($this->config1['datos'], true);
    }

    /**
     * Método encargado de mostrar la vista de listado de todos los users
     *
     * @return view Listado de ítems
     */
    public function index()
    {
        return view('back/user/index');
    }


    /**
     * Método encargado de mostrar formulario para crear usuario desde el back
     *
     * @return view Formulario para crear ítem
     */
    public function create()
    {
        $data = [];
        $heroes = $this->heroesModel->get();
        foreach ($heroes->getResult() as $value) {
            $data['heroes'][] = (array)$value;
        }
        return view('back/user/create', $data);
    }

    /**
     * Método encargado de crear ítem en BD
     *
     * @return void
     */
    public function store()
    {
        $this->validation->setRules([
            'nombre'   => 'required|string',
            'apellidop' => 'string',
            'apellidom' => 'string',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'pass'     => 'required|matches[pass-c]|crStrongPass',
            'imgprofile' => 'max_size[imgprofile,310]|mime_in[imgprofile,image/png,image/jpeg,image/gif]|ext_in[imgprofile,png,jpg,jpeg,gif]|is_image[imgprofile]',
            'descripcion' => 'string',
        ]);

        if (!$this->validation->withRequest($this->request)->run()) {
            $this->session->setFlashdata('toastr', '{"tipo": "error", "msg": "Hay información incorrecta y/o faltante: '.implode(', ', $this->validation->getErrors()).'", "title": "¡Ooops!"}');
            return redirect()->back()->withInput()->with('errores', $this->validation->getErrors());
        }
        else {
            $name_imgprofile = '';
            $f_email = trim($this->request->getPost('email'));
            $f_pass = trim($this->request->getPost('pass'));
            $f_descripcion = trim($this->request->getPost('descripcion'));
            $f_imgprofile = $this->request->getFile('imgprofile');

            // Guardamos imagen
            if ($file = $this->request->getFile('imgprofile')) {
                if ($file->isValid() && !$file->hasMoved()) {
                    try {
                        helper('general');
                        $nameWith = getFileNameWithExt($_FILES['imgprofile']['name']);
                        $nameWithout = getFileNameWithoutExt($_FILES['imgprofile']['name']);
                        $extension = getFileExtension($_FILES['imgprofile']['name']);
                        $name_imgprofile = date('YmdHis-').$nameWithout.'-thumb.'.$extension;
                        $image = \Config\Services::image()
                            ->withFile($f_imgprofile->getTempName()) // Trabajamos sobre la imagen del buffer
                            ->fit(30, 30, 'center')
                            ->save(WRITEPATH.'uploads/users/'.$name_imgprofile);
                    }
                    catch (CodeIgniter\Images\ImageException $e) {
                        $error = 'Imposible guardar la imagen de perfil: '.$nameWith.'. '.$e->getMessage();
                        $this->errores .= $error;
                        log_message('error', 'Users.php ('.__LINE__.'). '.$error);
                    }
                }
                else log_message('error', 'Users.php ('.__LINE__.'). El archivo (para imgprofile) no es válido y/o ya se movió');
            }

            // Si no ha habido errores
            if (strlen($this->errores)>0) {
                $this->session->setFlashdata('toastr', '{"tipo": "error", "msg": "'.$this->errores.'", "title": "¡Ooops!"}');
                return redirect()->back()->withInput()->with('errores', $this->validation->getErrors());
            }
            else {
                $emailPosicionArroba = strpos($f_email, '@');
                $username = substr($f_email, 0, $emailPosicionArroba);
                $data1 = [
                    'username' => $username,
                    'email'    => $f_email,
                    'password' => password_hash($f_pass, PASSWORD_DEFAULT),
                    'group' => 1,
                    'heroe' => null,
                    'origen' => 1,
                    'publicado' => 1,
                ];

                if ($this->usersModel->insert($data1)) {
                    $data2 = array();
                    $data2['id_user'] = $this->usersModel->insertID;
                    $data2['name'] = $this->request->getPost('nombre');
                    $data2['apellidop'] = $this->request->getPost('apellidop');
                    $data2['apellidom'] = $this->request->getPost('apellidom');
                    $data2['imgprofile'] = $name_imgprofile;
                    $data2['descripcion'] = $f_descripcion;
                    $data2['notificado'] = null;
                    $data2['celular'] = 0;
                    $data2['colegio'] = null;
                    $data2['grado'] = 0;
                    $data2['periodointeres'] = 0;
                    $data2['carrerainteres'] = 0;

                    if ($this->usersinfoModel->insertar($data2)) {
                        $this->session->setFlashdata('toastr', '{"tipo": "success", "msg": "Usuario creado correctamente", "title": "¡Yeeeha!"}');
                        return redirect()->to(base_url('backend/users/index'))->with('success', 'Recuerda las credenciales del nuevo usuario.<br><strong>User:</strong> '.$data1['username'].'<br><strong>Pass:</strong> '.$f_pass);
                    }
                    else {
                        $this->session->setFlashdata('toastr', '{"tipo": "error", "msg": "[1] Imposible agregar usuario", "title": "¡Ooops!"}');
                        return redirect()->back()->withInput();
                    }
                }
                else {
                    $this->session->setFlashdata('toastr', '{"tipo": "error", "msg": "[2] Imposible agregar usuario", "title": "¡Ooops!"}');
                    return redirect()->back()->withInput();
                }
            }
        }
    }

    /**
     * Método encargado de mostrar el formulario para editar ítem
     *
     * @return view Formulario para editar ítem
     */
    public function edit($id = null)
    {
        // Si el usuario logueado es un admin, o cualquiera que quiera editar su propio perfil
        if($this->session->group == 4 || $this->session->userid == $id) {

            $data['session']= array(
                'userid' => (int)$this->session->get('userid'),
                'username' => $this->session->get('username'),
                'email' => $this->session->get('email'),
                'group' => (int)$this->session->get('group'),
                'name' => $this->session->get('name'),
                'apellidop' => $this->session->get('apellidop')
            );

            $data['user'] = $this->usersModel->getBy('id', $id);
            $data['folderUploadsUsers'] = FOLDERUPLOADSUSERS;
            return view('back/user/edit', $data);
        }
        else {
            return view('errors/html/error_403');
        }

    }


    /**
     * Método encargado de actualizar ítem en BD
     *
     * @return void
     */
    public function update($id = null)
    {
        $this->validation->setRules([
            'nombre'   => 'required|string',
            'apellidop' => 'string',
            'pass'     => 'permit_empty|matches[pass-c]|crStrongPass',
            'imgprofile' => 'max_size[imgprofile,310]|mime_in[imgprofile,image/png,image/jpeg,image/gif]|ext_in[imgprofile,png,jpg,jpeg,gif]|is_image[imgprofile]',
            'descripcion' => 'string',
        ]);

        if (!$this->validation->withRequest($this->request)->run()) {
            $this->session->setFlashdata('toastr', '{"tipo": "error", "msg": "Hay información incorrecta y/o faltante: '.implode('|',$this->validation->getErrors()).'", "title": "¡Ooops!"}');
            return redirect()->back()->withInput()->with('errores', $this->validation->getErrors());
        }
        else {

            if ((int)$this->session->userid===1 && (int)$this->session->userid!==1) {
                $this->session->setFlashdata('toastr', '{"tipo": "error", "msg": "No tienes permiso de editar al usuario #1", "title": "¡Ooops!"}');
                return redirect()->back()->withInput()->with('errores', $this->validation->getErrors());
            }
            else {
                $this->session->setFlashdata('toastr', '{"tipo": "success", "msg": "Hey, eres el usuario #1, eres el único que puede editar este registro", "title": "¡Yeeeha!"}');
            }


            $userDataBeforeUpdate = $this->usersinfoModel->find($id);
            $name_imgprofile = $userDataBeforeUpdate['imgprofile'];
            $f_pass = trim($this->request->getPost('pass'));
            $f_descripcion = trim($this->request->getPost('descripcion'));
            $f_imgprofile = $this->request->getFile('imgprofile');

            // Guardamos imagen
            if ($file = $this->request->getFile('imgprofile')) {

                if ($file->isValid() && !$file->hasMoved()) {
                    try {
                        helper('general');
                        $nameWith = getFileNameWithExt($_FILES['imgprofile']['name']);
                        $nameWithout = getFileNameWithoutExt($_FILES['imgprofile']['name']);
                        $extension = getFileExtension($_FILES['imgprofile']['name']);
                        $name_imgprofile = date('YmdHis-').$nameWithout.'-thumb.'.$extension;
                        $image = \Config\Services::image()
                            ->withFile($f_imgprofile->getTempName()) // Trabajamos sobre la imagen del buffer
                            ->fit(30, 30, 'center')
                            ->save(WRITEPATH.'uploads/users/'.$name_imgprofile);
                    }
                    catch (CodeIgniter\Images\ImageException $e) {
                        $error = 'Imposible guardar la imagen de perfil: '.$nameWith.'. '.$e->getMessage();
                        $this->errores .= $error;
                        log_message('error', 'Users.php ('.__LINE__.'). '.$error);
                    }
                }
                else log_message('error', 'Users.php ('.__LINE__.'). El archivo (para imgprofile) no es válido y/o ya se movió');
            }

            // Si no ha habido errores
            if (strlen($this->errores)>0) {
                $this->session->setFlashdata('toastr', '{"tipo": "error", "msg": "'.$this->errores.'", "title": "¡Ooops!"}');
                return redirect()->back()->withInput()->with('errores', $this->validation->getErrors());
            }
            else {
                $actualizacion1_status = false;
                $actualizacion2_status = false;

                // Actualización tabla 1
                if (strlen($f_pass) > 0) {
                    $data1['password'] = password_hash($f_pass, PASSWORD_DEFAULT);
                    if ($this->usersModel->update($id, $data1)) {
                        $actualizacion1_status = true;
                    }
                    else {
                        $this->session->setFlashdata('toastr', '{"tipo": "error", "msg": "Imposible actualizar registro 1/2 del usuario", "title": "¡Ooops!"}');
                    }
                }
                else $actualizacion1_status = true;

                // Actualización tabla 2
                $data2['name'] = $this->request->getPost('nombre');
                $data2['apellidop'] = $this->request->getPost('apellidop');
                $data2['imgprofile'] = $name_imgprofile;
                $data2['descripcion'] = $f_descripcion;

                if ($this->usersinfoModel->update($id, $data2)) {
                    $actualizacion2_status = true;
                }
                else {
                    $this->session->setFlashdata('toastr', '{"tipo": "error", "msg": "Imposible actualizar registro 2/2 del usuario", "title": "¡Ooops!"}');
                }

                if ($actualizacion1_status && $actualizacion2_status) {
                    $this->session->setFlashdata('toastr', '{"tipo": "success", "msg": "Usuario actualizado correctamente", "title": "¡Yeeeha!"}');
                    return redirect()->to(base_url('backend/users/edit/'.$id));
                }
                else {
                    $this->session->setFlashdata('toastr', '{"tipo": "error", "msg": "Imposible actualizar usuario", "title": "¡Ooops!"}');
                    return redirect()->back()->withInput();
                }
            }
        }
    }

    /**
     * Método encargado de eliminar ítem en BD y borrar imágenes relacionadas
     *
     * @return void
     */
    public function delete()
    {
        $respuesta = array(
            'bandera' => false,
            'title' => 'Ooops! <i class="far fa-times-circle"></i>',
            'msg' => 'Error desconocido',
            'msgtype' => 'error'
        );
        $this->validation->setRules([
            'id' => 'required|integer',
        ]);

        if (!$this->validation->withRequest($this->request)->run()) {
            $respuesta['msg'] = 'La solicitud no contiene información correcta. ('.implode(', ', $this->validation->getErrors()).')';
        }

        try {
            (int)$id = $this->request->getPost('id');
            $respuesta['bandera'] = $this->usersModel->delete($id);
            if ($respuesta['bandera']) {
                $respuesta['title'] = 'Yeeeah! <i class="far fa-check-circle"></i>';
                $respuesta['msg'] = 'Elemento eliminado';
                $respuesta['msgtype'] = 'success';
            }
        }
        catch (\CodeIgniter\Database\Exceptions\DatabaseException $e) {
            $errorCode = $e->getCode();
            $errorMessage = $e->getMessage();

            if (strpos($errorMessage, "foreign key constraint fails") !== false) {
                $respuesta['msg'] = 'No puedes eliminar este usuario porque...';
            }
            else {
                $respuesta['msg'] = 'Error desconocido: <strong>'.$errorMessage.'</strong>';
            }
        }
        catch (Exception $e) {
            $errorCode = $e->getCode();
            $errorMessage = $e->getMessage();
            $respuesta['msg'] = 'Excepción general: <strong>'.$errorMessage.'</strong>';
        }
        exit(json_encode($respuesta));
    }

}
