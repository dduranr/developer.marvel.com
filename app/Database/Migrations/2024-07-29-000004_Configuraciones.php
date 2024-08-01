<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Configuraciones extends Migration
{
    public function up()
    {
        $this->db->disableForeignKeyChecks();
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 12,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],
            'datos' => [
                'type'       => 'LONGTEXT',
                'null'       => true,
            ],
            'publicado' => [
                'type'       => 'INT',
                'constraint' => 1,
                'null'       => false,
                'default'    => 1,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('configuraciones', true);
        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->forge->dropTable('configuraciones');
    }
}
