<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class UsersinfoSeeder extends Seeder
{
    public function run()
    {
        // Para generar los datos fake se usa la clase Faker de fzaninotto: // https://github.com/fzaninotto/Faker, la cual ya viene integrada por defecto en CI4
        $faker = Factory::create();
        $users = array();
        $created_at = $faker->datetime();
        $updated_at = $faker->dateTimeBetween($created_at); // Para que la fecha update sea posterior a created_at

        $users[] = array(
            'id_user' => 1,
            'name' => 'David Durán',
            'apellidop' => 'Super',
            'apellidom' => 'Admin',
            'imgprofile' => '20240101000000-superadmin-thumb.jpg',
            'descripcion' => null,
            'notificado' => null,
            'created_at' => $created_at->format('Y-m-d H:i:s'),
        );

        $users[] = array(
            'id_user' => 2,
            'name' => 'Covalto',
            'apellidop' => 'Uno',
            'apellidom' => 'One',
            'imgprofile' => '20240101000000-unknown-thumb.jpg',
            'descripcion' => null,
            'notificado' => null,
            'created_at' => $created_at->format('Y-m-d H:i:s'),
        );

        $users[] = array(
            'id_user' => 3,
            'name' => 'Covalto',
            'apellidop' => 'Dos',
            'apellidom' => 'Two',
            'imgprofile' => '20240101000000-unknown-thumb.jpg',
            'descripcion' => null,
            'notificado' => null,
            'created_at' => $created_at->format('Y-m-d H:i:s'),
        );

        // Insertamos en BD los datos en bloque
        $builder = $this->db->table('users_info');
        $builder->insertBatch($users);
    }
}
