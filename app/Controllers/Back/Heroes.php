<?php

namespace App\Controllers\Back;

use App\Controllers\BaseController;
use App\Models\HeroesModel;

class Heroes extends BaseController
{
    private $heroesModel;
    private $validation;
    public $session;
    public $errores;
    public $respuesta;
    public $generalHelper;

    public function __construct()
    {
        $this->heroesModel = new HeroesModel();
        $this->validation = \Config\Services::validation();
        $this->session = \Config\Services::session();
        $this->errores = '';
        $this->respuesta = '';
        $this->generalHelper = helper('general');
    }

    /**
     * Método encargado de mostrar la vista de listado de ítems
     *
     * @return view Listado de ítems
     */
    public function index()
    {
        return view('back/heroes/index');
    }

    /**
     * Método encargado de mostrar el formulario para crear ítem
     *
     * @return view Formulario para crear ítem
     */
    public function create()
    {
        return view('back/heroes/create');
    }

    /**
     * Método encargado de crear ítem en BD
     *
     * @return void
     */
    public function store()
    {
        $this->validation->setRules([
            'id_marvel' => 'required|integer|is_unique[heroes.id_marvel]',
            'name' => 'required|string|is_unique[heroes.name]',
            'description' => 'permit_empty|string',
            'imagen' => 'max_size[imagen,1024]|mime_in[imagen,image/png,image/jpeg,image/gif]|ext_in[imagen,png,jpg,jpeg,gif]|is_image[imagen]',
            'resourceURI' => 'permit_empty|valid_url|is_unique[heroes.resourceURI]',
            'comics_collectionURI' => 'permit_empty|string|is_unique[heroes.comics_collectionURI]',
            'comic_resourceURI' => 'permit_empty',
            'comic_name' => 'permit_empty',
        ]);

        if (!$this->validation->withRequest($this->request)->run()) {
            $this->session->setFlashdata('toastr', '{"tipo": "error", "msg": "Hay información incorrecta y/o faltante: '.implode(', ', $this->validation->getErrors()).'", "title": "¡Ooops!"}');
            return redirect()->back()->withInput()->with('error', implode(', ', $this->validation->getErrors()));
        }
        else {

            $name_imagen = '';
            $name_imagen_without = '';
            $extension = '';
            $f_imagen = $this->request->getFile('imagen');

            // Guardamos imagen
            if ($file = $this->request->getFile('imagen')) {
                if ($file->isValid() && !$file->hasMoved()) {
                    try {
                        helper('general');
                        $nameWith = getFileNameWithExt($_FILES['imagen']['name']);
                        $nameWithout = getFileNameWithoutExt($_FILES['imagen']['name']);
                        $extension = getFileExtension($_FILES['imagen']['name']);
                        $name_imagen = date('YmdHis-').$nameWithout.'-thumb.'.$extension;
                        $name_imagen_without = date('YmdHis-').$nameWithout.'-thumb';
                        $image = \Config\Services::image()
                            ->withFile($f_imagen->getTempName()) // Trabajamos sobre la imagen del buffer
                            ->fit(100, 100, 'center')
                            ->save(WRITEPATH.'uploads/heroes/'.$name_imagen);
                    }
                    catch (CodeIgniter\Images\ImageException $e) {
                        $error = 'Imposible guardar la imagen: '.$nameWith.'. '.$e->getMessage();
                        $this->errores .= $error;
                        log_message('error', 'Heroes.php ('.__LINE__.'). '.$error);
                    }
                }
                else log_message('error', 'Heroes.php ('.__LINE__.'). El archivo (para imagen) no es válido y/o ya se movió');
            }

            // Armamos los comics
            $comics = '';
            $comic_resourceURIs = $this->request->getPost('comic_resourceURI');
            $comic_names = $this->request->getPost('comic_name');
            $dataToSave = $this->request->getPost();

            if (count($comic_resourceURIs) !== count($comic_names)) {
                $this->errores .= 'Se encuentran '.count($comic_resourceURIs).' comic_resourceURIs y '.count($comic_names).' comic_names. El número debe ser el mismo en ambos.';
            }
            else {
                // Con array_combine hacemos que los valores del array "comic_resourceURI" sean los keys del array $comics, y los valores del array "comic_name" sean los valores
                $comicsRaw = array_combine($comic_resourceURIs, $comic_names);
                $comics = [];

                foreach ($comicsRaw as $key => $value) {
                    $comics[] = [
                        'resourceURI' => $key,
                        'name' => $value
                    ];
                }

                $comicsRaw = json_encode($comicsRaw);
                $dataToSave['comics_items'] = json_encode($comics);
                unset($dataToSave['comic_resourceURI']);
                unset($dataToSave['comic_name']);
            }



            // Si se generaron errores durante la lógica del controlador
            if (strlen($this->errores)>0) {
                $data['errores'] = $this->errores;
                $this->session->setFlashdata('toastr', '{"tipo": "error", "msg": "'.$data['errores'].'", "title": "¡Ooops!"}');
                return redirect()->back()->withInput();
            }
            else {

                unset($dataToSave['imagen']);

                $dataToSave['thumbnail_path'] = $name_imagen_without;
                $dataToSave['thumbnail_extension'] = $extension;
                $dataToSave['origen'] = 1;

                if ($this->heroesModel->insert($dataToSave)) {
                    $this->session->setFlashdata('toastr', '{"tipo": "success", "msg": "Héroe creado correctamente", "title": "¡Yeeeha!"}');
                    return redirect()->to(base_url('backend/heroes/index'));
                }
                else {
                    $this->session->setFlashdata('toastr', '{"tipo": "error", "msg": "Imposible agregar héroe", "title": "¡Ooops!"}');
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
        $data['heroe'] = $this->heroesModel->getBy('id', $id);

        // Armamos la ruta de la imagen dependiendo si es una URL (viene de la API), o si se subió localmente
        $imagen_fullname = '';
        $imagen_name = $data['heroe']['thumbnail_path'];
        $imagen_ext = $data['heroe']['thumbnail_extension'];
        if ((int)$data['heroe']['origen'] === 0) {
            $imagen_fullname = $imagen_name.'.'.$imagen_ext;
        }
        else {
            $imagen_fullname = base_url('uploads/heroes/'.$imagen_name.'.'.$imagen_ext);
        }

        // Armamos el atributo checked del campo publicado
        $data['heroe']['publicado'] = ( (bool)$data['heroe']['publicado'] ) ? 'checked' : '';

        $data['heroe']['imagen'] = $imagen_fullname;
        return view('back/heroes/edit', $data);
    }


    /**
     * Método encargado de actualizar ítem en BD
     *
     * @return void
     */
    public function update($id = null)
    {
        $this->validation->setRules([
            'name' => 'required|string',
            'description' => 'permit_empty|string',
            'imagen' => 'max_size[imagen,1024]|mime_in[imagen,image/png,image/jpeg,image/gif]|ext_in[imagen,png,jpg,jpeg,gif]|is_image[imagen]',
            'resourceURI' => 'permit_empty|valid_url',
            'comics_collectionURI' => 'permit_empty|string',
            'comic_resourceURI' => 'permit_empty',
            'comic_name' => 'permit_empty',
        ]);

        if (!$this->validation->withRequest($this->request)->run()) {
            $this->session->setFlashdata('toastr', '{"tipo": "error", "msg": "Hay información incorrecta y/o faltante: '.implode(', ', $this->validation->getErrors()).'", "title": "¡Ooops!"}');
            return redirect()->back()->withInput()->with('error', implode(', ', $this->validation->getErrors()));
        }
        else {

            $name_imagen = '';
            $name_imagen_without = '';
            $extension = '';
            $f_imagen = $this->request->getFile('imagen');

            // Guardamos imagen
            if ($file = $this->request->getFile('imagen')) {
                if ($file->isValid() && !$file->hasMoved()) {
                    try {
                        helper('general');
                        $nameWith = getFileNameWithExt($_FILES['imagen']['name']);
                        $nameWithout = getFileNameWithoutExt($_FILES['imagen']['name']);
                        $extension = getFileExtension($_FILES['imagen']['name']);
                        $name_imagen = date('YmdHis-').$nameWithout.'-thumb.'.$extension;
                        $name_imagen_without = date('YmdHis-').$nameWithout.'-thumb';
                        $image = \Config\Services::image()
                            ->withFile($f_imagen->getTempName()) // Trabajamos sobre la imagen del buffer
                            ->fit(100, 100, 'center')
                            ->save(WRITEPATH.'uploads/heroes/'.$name_imagen);
                    }
                    catch (CodeIgniter\Images\ImageException $e) {
                        $error = 'Imposible guardar la imagen: '.$nameWith.'. '.$e->getMessage();
                        $this->errores .= $error;
                        log_message('error', 'Heroes.php ('.__LINE__.'). '.$error);
                    }
                }
                else log_message('error', 'Heroes.php ('.__LINE__.'). El archivo (para imagen) no es válido y/o ya se movió');
            }

            // Armamos los comics
            $comics = '';
            $comic_resourceURIs = $this->request->getPost('comic_resourceURI');
            $comic_names = $this->request->getPost('comic_name');
            $dataToSave = $this->request->getPost();

            if (count($comic_resourceURIs) !== count($comic_names)) {
                $this->errores .= 'Se encuentran '.count($comic_resourceURIs).' comic_resourceURIs y '.count($comic_names).' comic_names. El número debe ser el mismo en ambos.';
            }
            else {
                // Con array_combine hacemos que los valores del array "comic_resourceURI" sean los keys del array $comics, y los valores del array "comic_name" sean los valores
                $comicsRaw = array_combine($comic_resourceURIs, $comic_names);
                $comics = [];

                foreach ($comicsRaw as $key => $value) {
                    $comics[] = [
                        'resourceURI' => $key,
                        'name' => $value
                    ];
                }

                $comicsRaw = json_encode($comicsRaw);
                $dataToSave['comics_items'] = json_encode($comics);
                unset($dataToSave['comic_resourceURI']);
                unset($dataToSave['comic_name']);
            }



            // Si se generaron errores durante la lógica del controlador
            if (strlen($this->errores)>0) {
                $data['errores'] = $this->errores;
                $this->session->setFlashdata('toastr', '{"tipo": "error", "msg": "'.$data['errores'].'", "title": "¡Ooops!"}');
                return redirect()->back()->withInput();
            }
            else {

                $dataToSave['publicado'] = ( isset($dataToSave['publicado']) ) ? 1 : 0;

                unset($dataToSave['imagen']);

                $dataToSave['thumbnail_path'] = $name_imagen_without;
                $dataToSave['thumbnail_extension'] = $extension;

                // Si no se cargó imagen, no se actualiza la imagen
                if ( strlen(trim($dataToSave['thumbnail_path'])) === 0 ) {
                    unset($dataToSave['thumbnail_path']);
                    unset($dataToSave['thumbnail_extension']);
                }

                if ($this->heroesModel->update($id, $dataToSave)) {
                    $this->session->setFlashdata('toastr', '{"tipo": "success", "msg": "Héroe actualizado correctamente", "title": "¡Yeeeha!"}');
                    return redirect()->to(base_url('backend/heroes/edit/'.$id));
                }
                else {
                    $this->session->setFlashdata('toastr', '{"tipo": "error", "msg": "Imposible actualizar héroe", "title": "¡Ooops!"}');
                    return redirect()->back()->withInput();
                }
            }
        }
    }



    /**
     * Método encargado de eliminar ítem en BD y borrar QR relacionado
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

        (int)$id = $this->request->getPost('id');
        $data = $this->heroesModel->getBy('id', $id);
        $respuesta['bandera'] = $this->heroesModel->delete($id);
        if ($respuesta['bandera']) {
            $respuesta['title'] = 'Yeeeah! <i class="far fa-check-circle"></i>';
            $respuesta['msg'] = 'Elemento eliminado';
            $respuesta['msgtype'] = 'success';
        }

        exit(json_encode($respuesta));
    }



    /**
     * Método encargado de importar los heroes de https://developer.marvel.com
     *
     * @return void
     */
    public function import()
    {
        $countAll = $this->heroesModel->countAll();

        if (is_int($countAll) && $countAll > 0) {
            $this->errores = 'Los héroes ya han sido importados. Si necesitas volver a importarlos, primero elimina TODOS los héroes de la base de datos (esto evita peticiones innecesarias a la API Marvel).';
            $this->session->setFlashdata('toastr', '{"tipo": "error", "msg": "'.$this->errores.'", "title": "¡Ooops!"}');
        }
        else {
            $heroes = getMarvelAllCharacters();

            if ( $heroes['status'] ) {

                $dataToImport = [];

                if ( count($heroes['data']) > 0 ) {
                    foreach ($heroes['data'] as $val) {

                        $comics = [];
                        if ( isset($val['comics']['items']) && count($val['comics']['items'])>0 ) {
                            foreach ($val['comics']['items'] as $comic) {
                                $comics[] = [
                                    'resourceURI' => $comic['resourceURI'],
                                    'name'        => $comic['name']
                                ];
                            }
                        }

                        $dataToImport[] = [
                            'id_marvel'            => $val['id'],
                            'name'                 => $val['name'],
                            'description'          => $val['description'],
                            'thumbnail_path'       => $val['thumbnail']['path'],
                            'thumbnail_extension'  => $val['thumbnail']['extension'],
                            'resourceURI'          => $val['resourceURI'],
                            'comics_available'     => $val['comics']['available'],
                            'comics_collectionURI' => $val['comics']['collectionURI'],
                            'comics_items'         => json_encode($comics),
                            'publicado'            => 1,
                            'origen'               => 0
                        ];
                    }

                    if ( $this->heroesModel->insertBatch($dataToImport) ) {
                        $this->session->setFlashdata('toastr', '{"tipo": "success", "msg": "Se importaron correctamente los héroes", "title": "¡Yeeeha!"}');
                    }
                    else {
                        $this->session->setFlashdata('toastr', '{"tipo": "error", "msg": "Imposible importar los héroes.", "title": "¡Ooops!"}');
                    }
                }
            }
            else {
                $this->session->setFlashdata('toastr', '{"tipo": "error", "msg": "$héroes[\'msg\']", "title": "¡Ooops!"}');
            }
        }
        return redirect()->back()->withInput();
    }
}
