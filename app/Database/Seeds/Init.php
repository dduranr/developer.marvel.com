<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Init extends Seeder
{
    public function run()
    {
        $this->call('UsersgroupsSeeder');
        $this->call('UserSeeder');
        $this->call('UsersinfoSeeder');
        $this->call('ConfiguracionesSeeder');
        $this->call('HeroesSeeder');
    }
}
