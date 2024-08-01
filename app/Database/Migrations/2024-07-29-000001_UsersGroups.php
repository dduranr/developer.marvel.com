<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Usersgroups extends Migration
{
    public function up()
    {

        $this->forge->addField([
            'id_group' => [
                'type' => 'INT',
                'constraint' => 12,
                'unsigned' => true,
                'null' => false,
                'auto_increment' => true
            ],
            'name_group' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
            'publicado' => [
                'type' => 'INT',
                'constraint' => 1,
                'null' => false,
                'default' => 1,
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
        $this->forge->addKey('id_group', true);
        $this->forge->createTable('users_groups');
    }

    public function down()
    {
        $this->forge->dropTable('users_groups');
    }
}
