<?php

namespace App\Controllers\Front;

use App\Controllers\BaseController;
use App\Models\HeroesModel;
use App\Models\ConfiguracionesModel;
use \PDO;
use \DateTime;

class Frontend extends BaseController
{
    private $validation;
    protected $pdo;
    public $session;
    public $errores;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->heroesModel = new HeroesModel();
        $this->configuracionesModel = new ConfiguracionesModel();
        $this->errores = '';
    }


    /**
     * Método que muestra la vista de la home.
     *
     * @author  David Durán
    */
	public function index()
	{
        $heroesRaw = $this->heroesModel->where('publicado', 1)->findAll();
        $heroes = [];

        if ( count($heroesRaw) > 0 ) {
            foreach ($heroesRaw as $row) {
                // Armamos la ruta de la imagen dependiendo si es una URL (viene de la API), o si se subió localmente
                $imagen_fullname = '';
                $imagen_name = $row['thumbnail_path'];
                $imagen_ext = $row['thumbnail_extension'];
                if ((int)$row['origen'] === 0) {
                    $imagen_fullname = $imagen_name.'.'.$imagen_ext;
                }
                else {
                    $imagen_fullname = base_url('uploads/heroes/'.$imagen_name.'.'.$imagen_ext);
                }

                $row['imagen'] = $imagen_fullname;
                $heroes[] = $row;
            }
        }

        $data['heroes'] = $heroes;
        return view('front/index', $data);
    }




    /**
     * Método que ejecuta la búsqueda de héroes tras hacer submit al formulario de búsqueda
     *
     * @author  David Durán
    */
    public function buscar()
    {
        $this->validation->setRules([
            'buscar' => 'required|string|min_length[3]',
        ]);

        if (!$this->validation->withRequest($this->request)->run()) {
            $this->session->setFlashdata('toastr', '{"tipo": "error", "msg": "Algo no está bien con tu solicitud: '.implode(', ', $this->validation->getErrors()).'", "title": "¡Ooops!"}');
            return redirect()->back()->withInput()->with('error', implode(', ', $this->validation->getErrors()));
        }
        else {

            $heroes = [];
            $buscar = $this->request->getPost('buscar');
            $likeArray = ['name' => $buscar, 'description' => $buscar];
            $heroesRaw = $this->heroesModel->where('publicado', 1)
                ->groupStart()
                ->like('name', $buscar)
                ->orLike('description', $buscar)
                ->groupEnd()
                ->findAll();


            $htmlResult = '';

            if ( count($heroesRaw) > 0 ) {
                if ( count($heroesRaw) === 1 ) {
                    $htmlResult = '<p>Se encontró un superhéroe coincidente con <strong>'.$buscar.'</strong>.</p>';
                }
                else {
                    $htmlResult = '<p>Se encontraron <strong>'.count($heroesRaw).'</strong> superhéroes coincidentes con <strong>'.$buscar.'</strong>.</p>';
                }

                foreach ($heroesRaw as $heroe) {

                    // Armamos la ruta de la imagen dependiendo si es una URL (viene de la API), o si se subió localmente
                    $imagen_fullname = '';
                    $imagen_name = $heroe['thumbnail_path'];
                    $imagen_ext = $heroe['thumbnail_extension'];
                    if ((int)$heroe['origen'] === 0) {
                        $imagen_fullname = $imagen_name.'.'.$imagen_ext;
                    }
                    else {
                        $imagen_fullname = base_url('uploads/heroes/'.$imagen_name.'.'.$imagen_ext);
                    }

                    $heroe['imagen'] = $imagen_fullname;
                    $heroes[] = $heroe;
                }
            }
            else {
                $htmlResult = '<p>No se encontró ningún superhéroe coincidente con <strong>'.$buscar.'</strong>.</p>';
            }

            $data['heroes'] = $heroes;
            $data['expresion'] = $buscar;
            $data['htmlResult'] = $htmlResult;
            return view('front/busqueda-resultados', $data);
        }
    }

}
