<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStandarWhoTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'jenis_kelamin' => [
                'type'       => 'ENUM',
                'constraint' => ['L', 'P'],
                'null'       => true,
            ],
            'usia_bulan' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'median' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'null'       => true,
            ],
            'sd_minus_1' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'null'       => true,
            ],
            'sd_minus_2' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'null'       => true,
            ],
            'sd_minus_3' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'null'       => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id');

        // Catatan: dua index ini beda nama tapi kolomnya sama persis
        // (jenis_kelamin, usia_bulan). Mengikuti kondisi asli apa adanya.
        $this->forge->addKey(['jenis_kelamin', 'usia_bulan'], false, false, 'idx_who_lookup');
        $this->forge->addKey(['jenis_kelamin', 'usia_bulan'], false, false, 'idx_lookup_who');

        $this->forge->createTable('standar_who', true, [
            'ENGINE'  => 'InnoDB',
            'CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_general_ci',
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('standar_who', true);
    }
}
