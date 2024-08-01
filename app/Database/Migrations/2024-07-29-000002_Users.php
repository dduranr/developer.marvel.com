<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Users extends Migration
{
    public function up()
    {
        $this->db->disableForeignKeyChecks();
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 12,
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,
            ],
            'username' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
                'unique' => true,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
                'unique' => true,
            ],
            'password' => [
                'type' => 'VARCHAR',
                'constraint' => 60,
                'null' => false,
            ],
            'group' => [
                'type' => 'INT',
                'constraint' => 12,
                'unsigned' => true,
                'null' => false,
            ],
            'publicado' => [
                'type' => 'INT',
                'constraint' => 1,
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
        // Parámetros del addForeignKey()
        //  1. group        Columna de la 1ra tabla: users.group
        //  2. users_groups Tabla 2 con la que se va a relacionar la 1ra tabla
        //  3. id_group     Columna de la 2da tabla: users_groups.id_groups
        //  4. CASCADE      Qué sucede en 1ra tabla al hacer UPDATE en la 2da tabla
        //  5. NO ACTION    Qué sucede en 1ra tabla al hacer DELETE en la 2da tabla
        // Se lee: Establecer un foreign key que relacione el users.group con groups.id_group, de tal forma que cuando se actualice un registro en groups también se actualice su registro concomitante en users; y cuando se elimine un registro en groups NO se eliminen los registros que tienen asignado ese grupo en tabla users. El NO ACTION hace que sólo se pueda eliminar el grupo hasta que ninguno de los registros en users tenga asignado ese grupo.
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('group', 'users_groups', 'id_group', 'CASCADE', 'NO ACTION');
        $this->forge->createTable('users');
        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
