<?php

namespace App\Controllers\Back;

use App\Controllers\BaseController;
use App\Models\ConfiguracionesModel;

class Configuraciones extends BaseController
{
    private $configuracionesModel;
    private $validation;
    public $session;
    public $errores;

    public function __construct()
    {
        $this->configuracionesModel = new ConfiguracionesModel();
        $this->validation = \Config\Services::validation();
        $this->session = \Config\Services::session();
        $this->errores = '';
    }



    /**
     * Método encargado de mostrar la vista de listado de ítems
     *
     * @return view Listado de ítems
     */
    public function index()
    {
        return view('back/configuraciones/index');
    }



    /**
     * Puesto que cada ítem de configuración tiene campos distintos (y en el futuro se espera que existan otras configuraciones con campos desconocidos ahora), no es posible tener un formulario para generar nuevas configuraciones, ya que todas terminarían teniendo los mismos campos. Por tanto, si se quiere crear una nueva configuración:
     *      1. Agregar manualmente el registro en la tabla configuraciones
     *      2. En la columna "datos" va un jSON que contiene la estructura de "campos" para cada configuración.
     *      3. Crear su vista para cuando se tenga que editar esa configuración
     *      4. Hacer los ajustes para su edición en los métodos edit() y update() de este controlador
     *      5. Es todo.
     */
    public function create() {}
    public function store() {}



    /**
     * Método encargado de mostrar el formulario para editar ítem
     *
     * @return view Formulario para editar ítem
     */
    public function edit($id = null)
    {
        $data['item_data'] = $this->configuracionesModel->getBy('id', $id);

        // Si se intenta editar el registro que contiene las configuraciones generales
        if ($id == 1) {
            $item_data = $this->configuracionesModel->getBy('id', $id);
            $item_data_datos = json_decode($item_data['datos'], true);
            $item_data['datos'] = $item_data_datos;
            $data['item_data'] = $item_data;

            return view('back/configuraciones/generales', $data);
        }
        else {
            exit('Imposible determinar qué registro hay que editar');
        }
    }



    /**
     * Método encargado de actualizar ítem en BD
     *
     * @return void
     */
    public function update($id = null)
    {
        // Si se intenta editar el registro que contiene configuraciones generales
        if ($id == 1) {
            $this->validation->setRules([
                'appname' => 'required|string',
                'mantenimiento' => 'required|in_list[0,1]',
                'emailsupport' => 'permit_empty|valid_email',
            ]);

            if (!$this->validation->withRequest($this->request)->run()) {
                $this->session->setFlashdata('toastr', '{"tipo": "error", "msg": "Hay información incorrecta y/o faltante", "title": "¡Ooops!"}');
                return redirect()->back()->withInput()->with('error', $this->validation->getErrors());
            }
            else {
                // Si se generaron errores durante la lógica del controlador
                if (strlen($this->errores)>0) {
                    $data['errores'] = $this->errores;
                    $this->session->setFlashdata('toastr', '{"tipo": "error", "msg": "'.$data['errores'].'", "title": "¡Ooops!"}');
                    return redirect()->back()->withInput()->with('error', $this->validation->getErrors());
                }
                else {
                    $columnaJSON = '
                    {
                      "id": 1,
                      "configuraciones": {
                        "1": {
                          "appname": "'.$this->request->getPost('appname', FILTER_SANITIZE_STRING).'"
                        },
                        "2": {
                          "mantenimiento": '.$this->request->getPost('mantenimiento', FILTER_SANITIZE_NUMBER_INT).'
                        },
                        "3": {
                          "emailsupport": "'.$this->request->getPost('emailsupport', FILTER_SANITIZE_STRING).'"
                        }
                      }
                    }';
                    $dataToSave['datos'] = trim($columnaJSON);

                    if ($this->configuracionesModel->update($id, $dataToSave)) {
                        $this->session->setFlashdata('toastr', '{"tipo": "success", "msg": "Actualización correcta", "title": "¡Yeeeha!"}');
                        return redirect()->to(base_url('backend/configuraciones/edit/'.$id));
                    }
                    else {
                        $this->session->setFlashdata('toastr', '{"tipo": "error", "msg": "Imposible actualizar", "title": "¡Ooops!"}');
                        return redirect()->back()->withInput();
                    }
                }
            }
        }
        else {
            exit('Imposible determinar qué registro hay que actualizar');
        }
    }



    /**
     * Por lo mismo que comento en los métodos create y store, no existe un método delete(). Si se quiere eliminar una configuración de la BD ir directo a la tabla y borrar el registro correspondiente.
     *
     * @return void
     */
    public function delete() {}
}
