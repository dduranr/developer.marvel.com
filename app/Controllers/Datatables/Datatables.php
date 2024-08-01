<?php

namespace App\Controllers\Datatables;

use App\Controllers\BaseController;
use App\Libraries\SSP; // Server Side Processing
use App\Models\HeroesModel;

// header('Content-Type: text/html; charset=utf-8');


/**
* Todos los métodos de esta clase se encargan de recueperar info de la BD y armar la data para enviársela a los formularios Datatables que listan los ítems en cuestión (users, héroes, etc)
*
* @return jSON
*/
class Datatables extends BaseController
{
    protected $sql_details;
    protected $primaryKey;
    protected $tablePrefix;
    protected $datatablesSSP;

    public function __construct()
    {
        $this->datatablesSSP = new SSP();
        $this->primaryKey = 'id';
        $this->tablePrefix = 'covalto_';
        $this->heroesModel = new HeroesModel();
        $this->sql_details = array(
            'user' => 'root',
            'pass' => '',
            'db'   => 'developermarvelcovalto',
            'host' => 'localhost'
        );
    }



    public function index()
    {
    }



    public function heroes()
    {
        $table = $this->tablePrefix.'heroes';
        $columns = array(
            array('db' => 'id', 'dt' => 'id'),
            array('db' => 'id_marvel', 'dt' => 'id_marvel'),
            array(
                'db' => 'name',
                'dt' => 'name',
                'formatter' => function ($name) {
                    return utf8_encode($name);
                }
            ),
            array(
                'db' => 'thumbnail_path',
                'dt' => 'thumbnail_path',
                'formatter' => function ($thumbnail_path, $data) {

                    // Si el héroe viene de la API, el SRC de la imagen será la URL que viene de developer.marvel.com. De lo contrario, si el héroe se cargó localmente...
                    $imagen = $thumbnail_path.'.'.$data['thumbnail_extension'];
                    $srcAttr = $imagen;
                    if ($data['origen'] === 1) {
                        $srcAttr = base_url('uploads/heroes/'.$imagen);
                    }
                    return '<img class="responsive-img" style="width:80px;" src="'.$srcAttr.'" alt="'.utf8_encode($data['name']).'" />';
                }
            ),
            array('db' => 'thumbnail_extension', 'dt' => 'thumbnail_extension'),
            array('db' => 'origen', 'dt' => 'origen'),
            array(
                'db' => 'publicado',
                'dt' => 'publicado',
                'formatter' => function ($publicado) {
                    return ($publicado == 1) ? 'Sí' : 'No';
                }
            ),
            array(
                'db' => 'id',
                'dt' => 'editar',
                'formatter' => function ($id) {
                    return '<a href="' . base_url('backend/heroes/edit/' . $id) . '" class="waves-effect waves-light btn green"><i class="material-icons">edit</i></a>';
                }
            ),
            array(
                'db' => 'id',
                'dt' => 'eliminar',
                'formatter' => function ($id) {
                    return '<button class="waves-effect waves-light btn red" data="' . base_url('backend/heroes/delete') . '" data2="' . $id . '" data3="table_heroes" onclick="eliminar(this)"><i class="material-icons">delete</i></button>';
                }
            )
        );
        echo json_encode($this->datatablesSSP->simple($_GET, $this->sql_details, $table, $this->primaryKey, $columns));
    }



    public function configuraciones()
    {
        $table = $this->tablePrefix.'configuraciones';
        $columns = array(
            array('db' => 'id', 'dt' => 'id'),
            array(
                'db' => 'nombre',
                'dt' => 'nombre',
                'formatter' => function ($nombre) {
                    return utf8_encode($nombre);
                }
            ),
            array(
                'db' => 'datos',
                'dt' => 'datos',
                'formatter' => function ($datos) {
                    return '<i>Sin previsualización</i>';
                }
            ),
            array(
                'db' => 'publicado',
                'dt' => 'publicado',
                'formatter' => function ($publicado) {
                    return ($publicado == 1) ? 'Sí' : 'No';
                }
            ),
            array(
                'db' => 'id',
                'dt' => 'editar',
                'formatter' => function ($id) {
                    return '<a href="' . base_url('backend/configuraciones/edit/' . $id) . '" class="waves-effect waves-light btn green"><i class="material-icons">edit</i></a>';
                }
            ),
        );
        echo json_encode($this->datatablesSSP->simple($_GET, $this->sql_details, $table, $this->primaryKey, $columns));
    }


}
