<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateKonsultasiTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_konsultasi' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'id_user' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'pesan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'tanggal' => [
                'type'    => 'TIMESTAMP',
                'null'    => false,
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);

        $this->forge->addPrimaryKey('id_konsultasi');
        $this->forge->addKey('id_user', false, false, 'fk_konsultasi_user');

        $this->forge->addForeignKey(
            fieldName: 'id_user',
            tableName: 'class_users',
            tableField: 'id_user',
            onUpdate: 'CASCADE',
            onDelete: 'CASCADE',
            fkName: 'fk_konsultasi_user'
        );

        $this->forge->createTable('konsultasi', true, [
            'ENGINE'  => 'InnoDB',
            'CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_general_ci',
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('konsultasi', true);
    }
}
