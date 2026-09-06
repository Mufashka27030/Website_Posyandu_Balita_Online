<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreatePengukuranTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_pengukuran' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'id_balita' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'tanggal_ukur' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'usia_bulan' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'berat_badan' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'null'       => true,
            ],
            'tinggi_badan' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'null'       => true,
            ],
            'z_score' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'null'       => true,
            ],
            'status_gizi' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'warna_kms' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],
            'catatan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type'    => 'TIMESTAMP',
                'null'    => false,
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'foto_kms' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id_pengukuran');
        $this->forge->addKey('id_balita', false, false, 'idx_pengukuran_balita');
        $this->forge->addKey('tanggal_ukur', false, false, 'idx_pengukuran_tanggal');
        $this->forge->addKey(['id_balita', 'tanggal_ukur'], false, false, 'idx_pengukuran_lookup');

        // Catatan penting: di database yang berjalan sekarang, id_balita di sini
        // TIDAK punya foreign key constraint (beda dengan balita/konsultasi yang
        // sudah pakai FK). Migration ini mengikuti kondisi asli apa adanya.
        // Lihat migration terpisah "AddForeignKeyPengukuranBalita" kalau mau
        // menambahkan FK yang hilang ini (sifatnya opsional).

        $this->forge->createTable('pengukuran', true, [
            'ENGINE'  => 'InnoDB',
            'CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_general_ci',
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('pengukuran', true);
    }
}
