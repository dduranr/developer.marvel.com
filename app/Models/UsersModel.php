<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false; // Se marca como borrado (poniéndole fecha), pero el registro no se borra
    protected $allowedFields    = ['username', 'email', 'password', 'group', 'publicado']; // Campos que se permiten editar
    protected $useTimestamps    = true; // Si o no CI se encarga de manejar las fechas (create, update, delete)
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
    *
    * @author   David Durán
    * @param    string. $columna. El nombre de la columna dentro de la tabla
    * @param    mixed. $valor. El valor del campo
    * @return   mixed. Si se encontró coincidencia, devuelve el registro en un array unidimensional; si no, se devuelve null. String si hay error
    */
    public function getBy($columna, $valor)
    {
        try {
            // Obtenemos todos los datos del usuario de las tablas users y users_info
            $builder = $this->db->table('users as u');
            $builder->select('u.*, ui.*, ug.name_group as name_group');
            $builder->join('users_info as ui', 'u.id = ui.id_user');
            $builder->join('users_groups as ug', 'u.group = ug.id_group');
                if ($columna === 'id') $builder->where('u.id', $valor);
            elseif ($columna === 'username') $builder->where('u.username', $valor);
            elseif ($columna === 'email') $builder->where('u.email', $valor);
            elseif ($columna === 'group') $builder->where('u.group', $valor);
            elseif ($columna === 'publicado') $builder->where('u.publicado', $valor);
            $resultado = $builder->get()->getRowArray();

            if ($this->db->error()['code'] > 0) return 'Ooops! Error (' . $this->db->error()['code'] . '): ' . $this->db->error()['message'];
            if (is_array($resultado) && count($resultado) > 0) {
                return $resultado;
            }
            return null;
        }
        catch (\Exception $e) {
            return '('.$e->getCode().'). '.$e->getMessage();
        }
    }

}
