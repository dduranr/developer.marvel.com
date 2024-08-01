<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersgroupsModel extends Model
{
    protected $table            = 'users_groups';
    protected $primaryKey       = 'id_group';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false; // Se marca como borrado (poniéndole fecha), pero el registro no se borra
    protected $allowedFields    = ['name_group', 'publicado']; // Campos que se permiten editar
    protected $useTimestamps    = true; // Si o no CI se encarga de manejar las fechas (create, update)
    protected $dateFormat       = 'datetime';
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $deletedField     = 'deleted_at';
}
