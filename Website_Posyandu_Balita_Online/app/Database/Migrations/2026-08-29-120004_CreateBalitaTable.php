<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateBalitaTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_balita' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'id_puskesmas' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'nama_balita' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'nama_ibu' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'nik' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],
            'jenis_kelamin' => [
                'type'       => 'ENUM',
                'constraint' => ['L', 'P'],
                'null'       => true,
            ],
            'tanggal_lahir' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'alamat' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type'    => 'TIMESTAMP',
                'null'    => false,
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type'    => 'TIMESTAMP',
                'null'    => true,
                'default' => new RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            ],
            'latitude' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'longitude' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'id_user' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id_balita');
        $this->forge->addUniqueKey('nik', 'uq_nik');
        $this->forge->addKey('id_user', false, false, 'idx_id_user');
        $this->forge->addKey('id_puskesmas', false, false, 'idx_balita_posyandu');
        $this->forge->addKey('nik', false, false, 'idx_balita_nik');

        $this->forge->addForeignKey(
            fieldName: 'id_puskesmas',
            tableName: 'puskesmas',
            tableField: 'id_puskesmas',
            onDelete: 'SET NULL',
            fkName: 'fk_balita_puskesmas'
        );
        $this->forge->addForeignKey(
            fieldName: 'id_user',
            tableName: 'class_users',
            tableField: 'id_user',
            onDelete: 'CASCADE',
            fkName: 'fk_balita_user'
        );

        $this->forge->createTable('balita', true, [
            'ENGINE'  => 'InnoDB',
            'CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_general_ci',
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('balita', true);
    }
}
