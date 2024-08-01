<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersinfoModel extends Model
{
    protected $table            = 'users_info';
    protected $primaryKey       = 'id_user';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false; // Se marca como borrado (poniéndole fecha), pero el registro no se borra
    protected $allowedFields    = ['id_user', 'name', 'apellidop', 'apellidom', 'imgprofile', 'descripcion','notificado','celular','colegio','grado','periodointeres','carrerainteres']; // Campos que se permiten editar
    protected $useTimestamps    = true; // Si o no CI se encarga de manejar las fechas (create, update)
    protected $dateFormat       = 'datetime';
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $deletedField     = 'deleted_at';
    protected $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = db_connect();
    }





    /**
    * Método que recupera el 1er registro coincidente con la columna y el valor indicados
    * No sé qué pasó que de pronto dejó de funcionar el método nativo de CI4 insert: En la línea "if ($this->usersinfoModel->insertar($data2))..." de User.php funcionaba perfecto, pero de pronto comenzó a devolver un int 0, aunque sí inserta el registro. Por esta razón tuve que crear este método alternativo.
    * @author   David Durán
    * @param    string. $columna. El nombre de la columna dentro de la tabla
    * @param    mixed. $valor. El valor del campo
    * @return   mixed. Si se encontró coincidencia, devuelve el registro en un array unidimensional; si no, se devuelve null. String si hay error
    */
    public function insertar($data)
    {
        try {
            $builder = $this->db->table('users_info');
            $resultado = $builder->insert($data);

            if ($this->db->error()['code'] > 0) return 'Ooops! Error (' . $this->db->error()['code'] . '): ' . $this->db->error()['message'];
            if (is_bool($resultado)) return $resultado;
            return null;
        }
        catch (\Exception $e) {
            return '('.$e->getCode().'). '.$e->getMessage();
        }
    }



    /**
    * Método que actualiza múltiples registros a la vez.
    * @author   David Durán
    * @param    String $columna. Nombre de la columna a actualizar
    * @param    Mixed $valor. El contenido que va a ponerse en la columna
    * @param    Array $ids. Contiene los IDs de los registros a actualizar 
    * @return   mixed. 
    */
    public function actualizarEnBatch($columna, $valor, $ids)
    {
        try {
            $builder = $this->db->table('users_info');
            $builder->set($columna, $valor);
            $builder->whereIn('id_user', $ids);
            $builder->update();
            
            if ($this->db->error()['code'] > 0) return 'Ooops! Error (' . $this->db->error()['code'] . '): ' . $this->db->error()['message'];
            if (is_bool($resultado)) return $resultado;
            return false;
        }
        catch (\Exception $e) {
            return '('.$e->getCode().'). '.$e->getMessage();
        }
    }

}
