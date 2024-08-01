<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Para generar los datos fake se usa la clase Faker de fzaninotto: // https://github.com/fzaninotto/Faker, la cual ya viene integrada por defecto en CI4

        $faker = Factory::create();
        $users = array();
        $created_at = $faker->datetime();

        $users[] = array(
            'username' => 'aspirante_a_developer_covalto',
            'email' => 'official.dduran@gmail.com',
            'password' => password_hash('FawIC60p9MplHV$4#NwkmN6jpgybozL6U', PASSWORD_DEFAULT),
            'group' => 4,
            'publicado' => 1,
            'created_at' => $created_at->format('Y-m-d H:i:s'),
        );
        $users[] = array(
            'username' => 'developer1_covalto',
            'email' => 'developer1@covalto.com',
            'password' => password_hash('@brXq@a%E2ToR6hE2@Uc#3kHc!T%aM', PASSWORD_DEFAULT),
            'group' => 1,
            'publicado' => 1,
            'created_at' => $created_at->format('Y-m-d H:i:s'),
        );
        $users[] = array(
            'username' => 'developer2_covalto',
            'email' => 'developer2@covalto.com',
            'password' => password_hash('@brXq@a%E2ToR6hE2@Uc#3kHc!T%aM', PASSWORD_DEFAULT),
            'group' => 1,
            'publicado' => 1,
            'created_at' => $created_at->format('Y-m-d H:i:s'),
        );

        // Insertamos en BD los datos en bloque
        $builder = $this->db->table('users');
        $builder->insertBatch($users);
    }
}
