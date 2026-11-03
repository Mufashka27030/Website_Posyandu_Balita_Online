<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateClassUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_user' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'role' => [
                'type'       => 'ENUM',
                'constraint' => ['admin', 'kader', 'dinkes', 'orangtua'],
                'null'       => true,
            ],
            'created_at' => [
                'type'    => 'TIMESTAMP',
                'null'    => false,
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'no_hp' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],
            'foto' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'alamat' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'updated_at' => [
                'type'    => 'TIMESTAMP',
                'null'    => true,
                'default' => new RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            ],
        ]);

        $this->forge->addPrimaryKey('id_user');

        // Catatan: di database yang berjalan sekarang ada 4 UNIQUE KEY berbeda
        // nama tapi isinya sama persis (kolom email). Migration ini sengaja
        // mengikuti kondisi asli apa adanya — lihat catatan di chat soal ini.
        $this->forge->addUniqueKey('email', 'email');
        $this->forge->addUniqueKey('email', 'unique_email');
        $this->forge->addUniqueKey('email', 'uq_class_users_email');
        $this->forge->addUniqueKey('email', 'uq_email');

        $this->forge->addKey('role', false, false, 'idx_class_user_role');

        $this->forge->createTable('class_users', true, [
            'ENGINE'  => 'InnoDB',
            'CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_general_ci',
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('class_users', true);
    }
}
