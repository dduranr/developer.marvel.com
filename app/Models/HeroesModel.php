<?php

namespace App\Models;

use CodeIgniter\Model;

class HeroesModel extends Model
{
    // protected $paginateGroup    = 'marvel';
    protected $table            = 'heroes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false; // Se marca como borrado (poniéndole fecha), pero el registro no se borra
    protected $allowedFields    = ['id_marvel','name','description','thumbnail_path','thumbnail_extension','resourceURI','comics_collectionURI','comics_items','publicado','origen']; // Campos que se permiten editar
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
    * Método que recupera el registro solicitado
    *
    * @author   David Durán
    * @param    string. $columna. El nombre de la columna dentro de la tabla
    * @param    mixed. $valor. El valor del campo
    * @return   mixed. Si se encontró coincidencia, devuelve el registro en un array unidimensional; si no, se devuelve null
    */
    public function getBy($columna, $valor)
    {
        try {
            $builder = $this->db->table('heroes AS a');
            $builder->select('a.*');
            if ($columna === 'id') $builder->where('a.id', $valor);
            if ($columna === 'nombre') $builder->where('a.nombre', $valor);
            if ($columna === 'publicado') $builder->where('a.publicado', $valor);
            $resultado = $builder->get()->getRowArray();

            if ($this->db->error()['code'] > 0) return 'Ooops! Error (' . $this->db->error()['code'] . '): ' . $this->db->error()['message'];
            if (is_array($resultado) && count($resultado) > 0) return $resultado;
            return null;
        }
        catch (\Exception $e) {
            return '('.$e->getCode().'). '.$e->getMessage();
        }
    }




    /**
    * Método que recupera el registro solicitado
    *
    * @author   David Durán
    * @param    string. $columna. El nombre de la columna dentro de la tabla
    * @param    mixed. $valor. El valor del campo
    * @return   mixed. Si se encontró coincidencia, devuelve el registro en un array unidimensional; si no, se devuelve null
    */

    public function getAllBy($columna, $valor)
    {
        try {
            $builder = $this->db->table('heroes AS a');
            $builder->select('a.*');

            if(count($valor)===1) {
                if ($columna === 'id') $builder->where('a.id', $valor);
                if ($columna === 'nombre') $builder->where('a.nombre', $valor);
                if ($columna === 'publicado') $builder->where('a.publicado', $valor);
                $resultado = $builder->get()->getRowArray();
            }
            elseif(count($valor)>1) {
                foreach($valor as $value) {
                    if ($columna === 'id') $builder->where('a.id', $value);
                    if ($columna === 'nombre') $builder->where('a.nombre', $valor);
                    if ($columna === 'publicado') $builder->where('a.publicado', $valor);
                }
                $resultado = $builder->get()->getResultArray();
            }

            if ($this->db->error()['code'] > 0) return 'Ooops! Error (' . $this->db->error()['code'] . '): ' . $this->db->error()['message'];
            if (is_array($resultado) && count($resultado) > 0) return $resultado;
            return null;
        }
        catch (\Exception $e) {
            return '('.$e->getCode().'). '.$e->getMessage();
        }
    }



    /**
    * Método que recupera el número total de registros
    *
    * @author   David Durán
    * @param    string. $columna. El nombre de la columna dentro de la tabla
    * @param    mixed. $valor. El valor del campo
    * @return   mixed. Si se encontró coincidencia, devuelve el registro en un array unidimensional; si no, se devuelve null
    */

    public function countAll()
    {
        try {
            $builder = $this->db->table('heroes AS a');
            $resultado = $builder->countAll();

            if ($this->db->error()['code'] > 0) return 'Ooops! Error (' . $this->db->error()['code'] . '): ' . $this->db->error()['message'];
            if (is_int($resultado)) return $resultado;
            return null;
        }
        catch (\Exception $e) {
            return '('.$e->getCode().'). '.$e->getMessage();
        }
    }
}
