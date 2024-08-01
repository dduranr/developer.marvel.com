<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class HeroesSeeder extends Seeder
{
    public function run()
    {
        $heroes = array(
            array(
                'id' => 1,
                'id_marvel' => 9999,
                'name' => 'Super Bérkeley',
                'description' => 'Lorem ipsum dolor situm after.',
                'thumbnail_path' => 'berkeley',
                'thumbnail_extension' => 'jpg',
                'resourceURI' => 'https://berkeley.com',
                'comics_available' => 0,
                'comics_collectionURI' => 'https://berkeley.com/comic',
                'comics_items' => '[]',
                'publicado' => 1,
                'origen' => 1,
            ),
        );

        // Insertamos en BD los datos en bloque
        $builder = $this->db->table('heroes');
        $builder->insertBatch($heroes);
    }
}
