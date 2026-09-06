<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * OPSIONAL — bukan bagian dari reproduksi skema asli, silakan lewati migration
 * ini kalau tidak diperlukan.
 *
 * Menambahkan foreign key yang saat ini hilang: pengukuran.id_balita ->
 * balita.id_balita. Di database yang berjalan sekarang, kolom ini sudah punya
 * index tapi TIDAK punya foreign key constraint — beda dengan balita->puskesmas,
 * balita->user, dan konsultasi->user yang semuanya sudah terpasang FK.
 * Akibatnya record pengukuran bisa "yatim" (menunjuk ke id_balita yang sudah
 * dihapus) tanpa terdeteksi oleh database.
 *
 * PENTING sebelum menjalankan: pastikan dulu tidak ada data yang sudah "yatim",
 * karena kalau ada, migration ini akan GAGAL. Jalankan dulu query pengecekan:
 *
 *   SELECT * FROM pengukuran
 *   WHERE id_balita IS NOT NULL
 *     AND id_balita NOT IN (SELECT id_balita FROM balita);
 *
 * Kalau query itu mengembalikan baris, hapus/perbaiki dulu baris tersebut
 * sebelum menjalankan `php spark migrate`.
 */
class AddForeignKeyPengukuranBalita extends Migration
{
    public function up()
    {
        $this->forge->addForeignKey(
            fieldName: 'id_balita',
            tableName: 'balita',
            tableField: 'id_balita',
            onDelete: 'CASCADE',
            fkName: 'fk_pengukuran_balita'
        );
        $this->forge->processIndexes('pengukuran');
    }

    public function down()
    {
        $this->forge->dropForeignKey('pengukuran', 'fk_pengukuran_balita');
    }
}
