<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Usersinfo extends Migration
{
    public function up()
    {
        $this->db->disableForeignKeyChecks();
        $this->forge->addField([
            'id_user' => [
                'type' => 'INT',
                'constraint' => 12,
                'unsigned' => true,
                'auto_increment' => false, // Este campo está vinculado con el users.id, no debería ser autoincrement
                'null' => false,
                // 'default' => 24,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
            'apellidop' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
            'apellidom' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
            'imgprofile' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'default' => null,
            ],
            'descripcion' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'notificado' => [
                'type' => 'INT',
                'constraint' => 1,
                'null' => true,
                'default' => null,
            ],
            'celular' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => false,
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
        $this->forge->addKey('id_user', true);
        $this->forge->addForeignKey('id_user', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('users_info');
        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->forge->dropTable('users_info');
    }
}
