<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Heroes extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 12,
                'unsigned' => true,
                'null' => false,
                'auto_increment' => true,
            ],
            'id_marvel' => [
                'type' => 'INT',
                'null' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'description' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'thumbnail_path' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'thumbnail_extension' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'resourceURI' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'comics_available' => [
                'type' => 'INT',
                'null' => true,
            ],
            'comics_collectionURI' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'comics_items' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'publicado' => [
                'type' => 'INT',
                'constraint' => 1,
                'null' => false,
                'default' => 1,
            ],
            'origen' => [
                'type' => 'INT',
                'null' => false,
                'default' => 0,
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

        $attributes = ['ENGINE' => 'InnoDB'];
        $this->forge->addKey('id', true);
        $this->forge->createTable('heroes', true, $attributes);
        // produces: CREATE TABLE `heroes` (...) ENGINE = InnoDB DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci

    }

    public function down()
    {
        $this->forge->dropTable('heroes');
    }
}
