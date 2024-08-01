<?php

namespace App\Models;

use CodeIgniter\Model;

class ConfiguracionesModel extends Model
{
    protected $table            = 'configuraciones';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false; // Se marca como borrado (poniéndole fecha), pero el registro no se borra
    protected $allowedFields    = ['nombre', 'datos', 'publicado']; // Campos que se permiten editar
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
    * @return   mixed. Si se encontró coincidencia, devuelve el registro en un array unidimensional; si no, se devuelve null. String si hubo error.
    */
    public function getBy($columna, $valor)
    {
        try {
            $builder = $this->db->table('configuraciones AS c');
            $builder->select('c.*');
            if ($columna === 'id') $builder->where('c.id', $valor);
            if ($columna === 'nombre') $builder->where('c.nombre', $valor);
            if ($columna === 'publicado') $builder->where('c.publicado', $valor);
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
    * Método que recupera los registros solicitados
    *
    * @author   David Durán
    * @param    string. $columna. El nombre de la columna dentro de la tabla
    * @param    mixed. $valor. El valor del campo
    * @return   mixed. Si se encontró coincidencia, devuelven los registros en un array multidimensional; si no, se devuelve null. String si hubo error.
    */
    public function getAllBy($columna, $valor)
    {
        try {
            $builder = $this->db->table('configuraciones AS c');
            $builder->select('c.*');
            if ($columna === 'publicado') $builder->where('c.publicado', $valor);

            $resultado = $builder->get()->getResultArray();

            if ($this->db->error()['code'] > 0) return 'Ooops! Error (' . $this->db->error()['code'] . '): ' . $this->db->error()['message'];
            if (is_array($resultado) && count($resultado) > 0) return $resultado;
            return null;
        }
        catch (\Exception $e) {
            return '('.$e->getCode().'). '.$e->getMessage();
        }
    }



    /**
     * Método que informa si el jurado puede votar
     *
     * @author   David Durán
     * @return   Bool
     */
    public function getIfJudgesCanVote() {
        try {
            $ahora = date('Y-m-d H:i:s');
            $builder = $this->db->table('configuraciones AS c');
            $builder->select('c.*');
            $builder->where('c.id', 1);

            $resultado = $builder->get()->getRowArray();
            $datos = json_decode($resultado['datos'],true);
            $c = date($datos['configuraciones'][2]['dt_iniciaVotacionFJurado_i']);
            $f = date($datos['configuraciones'][2]['dt_iniciaVotacionFJurado_f']);

            if ($this->db->error()['code'] > 0) return 'Ooops! Error (' . $this->db->error()['code'] . '): ' . $this->db->error()['message'];
            elseif ($ahora>=$c && $ahora<=$f) return true;
            return false;
        }
        catch (\Exception $e) {
            return '('.$e->getCode().'). '.$e->getMessage();
        }
    }

}
