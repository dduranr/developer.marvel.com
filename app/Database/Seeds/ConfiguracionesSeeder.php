<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class ConfiguracionesSeeder extends Seeder
{
    public function run()
    {
        $configuraciones = array();

        $configuraciones = array(
            array(
                'nombre' => 'Generales',
                'datos' => '{"id":1,"configuraciones":{"1":{"appname":"DeveloperMarvelCovalto"},"2":{"versioncaptcha":2},"3":{"emailsupport":"official.dduran@gmail.com"}}}',
                'publicado' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => null,
                'deleted_at' => null
            ),
        );

        // Insertamos en BD los datos en bloque
        $builder = $this->db->table('configuraciones');
        $builder->insertBatch($configuraciones);
    }
}
