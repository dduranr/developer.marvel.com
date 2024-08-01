<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class UsersgroupsSeeder extends Seeder
{
    public function run()
    {
        // Para generar los datos fake se usa la clase Faker de fzaninotto: // https://github.com/fzaninotto/Faker, la cual ya viene integrada por defecto en CI4
        $faker = Factory::create();
        $groups = array(
            array(
                'name_group' => 'Admin',
                'publicado' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ),
            array(
                'name_group' => 'Grupo fake 2',
                'publicado' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ),
            array(
                'name_group' => 'Grupo fake 3',
                'publicado' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ),
            array(
                'name_group' => 'SuperAdmin',
                'publicado' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ),
        );

        // Insertamos en BD los datos en bloque
        $builder = $this->db->table('users_groups');
        $builder->insertBatch($groups);
    }
}
