<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Melepas index yang isinya duplikat dari index lain pada kolom yang sama.
 *
 * Hasil SHOW INDEX di database yang berjalan:
 *   class_users  : 4 UNIQUE KEY berbeda nama di kolom email
 *                  (email, unique_email, uq_class_users_email, uq_email)
 *   balita       : idx_balita_nik (biasa) di kolom yang sudah punya uq_nik
 *   pengukuran   : idx_pengukuran_balita, padahal idx_pengukuran_lookup
 *                  (id_balita, tanggal_ukur) sudah memakai id_balita sebagai
 *                  kolom paling kiri
 *
 * Tidak ada risiko kehilangan proteksi data: yang dibuang hanya salinan,
 * constraint aslinya (UNIQUE email dan UNIQUE nik) tetap dibiarkan.
 */
class CleanupRedundantIndexes extends Migration
{
    public function up()
    {
        foreach (['unique_email', 'uq_class_users_email', 'uq_email'] as $nama) {
            $this->forge->dropKey('class_users', $nama);
        }

        $this->forge->dropKey('balita', 'idx_balita_nik');
        $this->forge->dropKey('pengukuran', 'idx_pengukuran_balita');
    }

    public function down()
    {
        $this->forge->addUniqueKey('email', 'unique_email');
        $this->forge->addUniqueKey('email', 'uq_class_users_email');
        $this->forge->addUniqueKey('email', 'uq_email');
        $this->forge->processIndexes('class_users');

        $this->forge->addKey('nik', false, false, 'idx_balita_nik');
        $this->forge->processIndexes('balita');

        $this->forge->addKey('id_balita', false, false, 'idx_pengukuran_balita');
        $this->forge->processIndexes('pengukuran');
    }
}
