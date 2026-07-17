<?php

namespace App\Controllers;

class Seeder extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        // Cek apakah sudah pernah di-seed
        $existing = $db->table('class_users')->where('email', 'admin@posyandu.id')->countAllResults();
        if ($existing > 0) {
            return '<div style="font-family:sans-serif;padding:30px;color:orange;font-size:18px">
                        ⚠️ Data dummy sudah pernah di-insert.<br><br>
                        Hapus data lama dulu via phpMyAdmin jika ingin mengulang:<br>
                        <code>DELETE FROM pengukuran; DELETE FROM balita; DELETE FROM class_users WHERE email LIKE "%@%";</code>
                    </div>';
        }

        $hash = password_hash('password123', PASSWORD_DEFAULT);

        // ==================== 1. USERS ====================
        $users = [
            ['Admin Posyandu',  'admin@posyandu.id',   'admin',    '081234500001', 'Jl. Posyandu No. 1, Malang'],
            ['Bidan Sartika',   'sartika@posyandu.id', 'kader',   '081234500002', 'Jl. Melati No. 2, Malang'],
            ['Maria Lestari',   'maria@posyandu.id',   'kader',   '081234500003', 'Jl. Anggrek No. 3, Malang'],
            ['Budi Santoso',    'budi@gmail.com',      'orangtua','081234500004', 'Jl. Mawar No. 5, Malang'],
            ['Sri Wahyuni',     'sri@gmail.com',       'orangtua','081234500005', 'Jl. Kenanga No. 7, Malang'],
            ['Agus Purnomo',    'agus@gmail.com',      'orangtua','081234500006', 'Jl. Anggrek No. 3, Malang'],
            ['Dewi Lestari',    'dewi@gmail.com',      'orangtua','081234500007', 'Jl. Flamboyan No. 2, Malang'],
            ['Joko Susilo',     'joko@gmail.com',      'orangtua','081234500008', 'Jl. Cempaka No. 8, Malang'],
            ['Rina Marlina',    'rina@gmail.com',      'orangtua','081234500009', 'Jl. Dahlia No. 4, Malang'],
            ['Hendra Wijaya',   'hendra@gmail.com',    'orangtua','081234500010', 'Jl. Teratai No. 6, Malang'],
            ['Maya Sari',       'maya@gmail.com',      'orangtua','081234500011', 'Jl. Tulip No. 9, Malang'],
            ['Rudi Hartono',    'rudi@gmail.com',      'orangtua','081234500012', 'Jl. Lavender No. 1, Malang'],
            ['Lina Farida',     'lina@gmail.com',      'orangtua','081234500013', 'Jl. Bougenville No. 11, Malang'],
        ];

        $db->transStart();

        foreach ($users as $u) {
            $db->table('class_users')->insert([
                'nama'     => $u[0],
                'email'    => $u[1],
                'password' => $hash,
                'role'     => $u[2],
                'no_hp'    => $u[3],
                'alamat'   => $u[4],
            ]);
        }

        // Ambil ID users
        $userIds = [];
        $rows = $db->table('class_users')->where('email LIKE "%@%"')->orderBy('id_user', 'ASC')->get()->getResultArray();
        foreach ($rows as $r) {
            $userIds[$r['email']] = (int) $r['id_user'];
        }

        // ==================== 2. BALITA ====================
        $balitaData = [
            ['Ahmad Pratama',  'Kartika Sari',     '3578010101240001', 'L', '2024-01-10', 'Jl. Mawar No. 5, Malang',     'budi@gmail.com'],
            ['Siti Aisyah',    'Kartika Sari',     '3578010205240002', 'P', '2024-05-15', 'Jl. Mawar No. 5, Malang',     'budi@gmail.com'],
            ['Budi Anugerah',  'Sri Wahyuni',      '3578010303230003', 'L', '2023-03-20', 'Jl. Kenanga No. 7, Malang',   'sri@gmail.com'],
            ['Dewi Anjani',    'Siti Aminah',      '3578010404240004', 'P', '2024-04-05', 'Jl. Anggrek No. 3, Malang',   'agus@gmail.com'],
            ['Rizki Ramadhan', 'Siti Aminah',      '3578010507220005', 'L', '2022-07-12', 'Jl. Anggrek No. 3, Malang',   'agus@gmail.com'],
            ['Putri Ayu',      'Dewi Lestari',     '3578010602240006', 'P', '2024-02-28', 'Jl. Flamboyan No. 2, Malang', 'dewi@gmail.com'],
            ['Bagas Saputra',  'Ani Yudhoyono',    '3578010706230007', 'L', '2023-06-15', 'Jl. Cempaka No. 8, Malang',   'joko@gmail.com'],
            ['Nabila Zahra',   'Rina Marlina',      '3578010803240008', 'P', '2024-03-10', 'Jl. Dahlia No. 4, Malang',    'rina@gmail.com'],
            ['Arya Pratama',   'Rina Marlina',      '3578010909220009', 'L', '2022-09-25', 'Jl. Dahlia No. 4, Malang',    'rina@gmail.com'],
            ['Salsabila',      'Indah Permatasari', '3578011011230010', 'P', '2023-11-30', 'Jl. Teratai No. 6, Malang',   'hendra@gmail.com'],
            ['Farel Nugraha',  'Maya Sari',         '3578011101240011', 'L', '2024-01-20', 'Jl. Tulip No. 9, Malang',     'maya@gmail.com'],
            ['Kayla Pratiwi',  'Sutrisni',          '3578011212220012', 'P', '2022-12-05', 'Jl. Lavender No. 1, Malang',  'rudi@gmail.com'],
            ['Dafa Alfarizi',  'Sutrisni',          '3578011306240013', 'L', '2024-06-18', 'Jl. Lavender No. 1, Malang',  'rudi@gmail.com'],
            ['Zahra Kamilah',  'Lina Farida',       '3578011408230014', 'P', '2023-08-22', 'Jl. Bougenville No. 11, Malang','lina@gmail.com'],
            ['Kenzo Pratama',  'Lina Farida',       '3578011504220015', 'L', '2022-04-14', 'Jl. Bougenville No. 11, Malang','lina@gmail.com'],
        ];

        foreach ($balitaData as $b) {
            $db->table('balita')->insert([
                'nama_balita'   => $b[0],
                'nama_ibu'      => $b[1],
                'nik'           => $b[2],
                'jenis_kelamin' => $b[3],
                'tanggal_lahir' => $b[4],
                'alamat'        => $b[5],
                'id_user'       => $userIds[$b[6]],
            ]);
        }

        // Ambil ID balita
        $balitaIds = [];
        $rows = $db->table('balita')->orderBy('id_balita', 'ASC')->get()->getResultArray();
        foreach ($rows as $r) {
            $balitaIds[$r['nama_balita']] = (int) $r['id_balita'];
        }

        // ==================== 3. PENGUKURAN ====================
        $pengukuranData = [
            ['Ahmad Pratama',  '2024-07-10', 6,  7.5,  67.0, -0.8, 'Normal',         'Hijau',  'Pertumbuhan baik'],
            ['Ahmad Pratama',  '2025-01-15', 12, 9.5,  72.5, -1.2, 'Normal',         'Hijau',  'Pertumbuhan normal'],
            ['Ahmad Pratama',  '2025-04-20', 15, 10.2, 75.0, -1.0, 'Normal',         'Hijau',  'Sehat'],
            ['Siti Aisyah',    '2024-11-20', 6,  6.8,  64.0, -0.5, 'Normal',         'Hijau',  'Pertumbuhan baik'],
            ['Siti Aisyah',    '2025-05-15', 12, 8.5,  70.0, -0.9, 'Normal',         'Hijau',  'Sehat'],
            ['Budi Anugerah',  '2023-09-20', 6,  5.5,  62.0, -2.5, 'Stunting',       'Kuning', 'Perlu perbaikan gizi'],
            ['Budi Anugerah',  '2024-03-20', 12, 7.0,  68.0, -2.3, 'Stunting',       'Kuning', 'Perlu suplementasi'],
            ['Budi Anugerah',  '2024-06-20', 15, 7.5,  70.0, -2.1, 'Stunting',       'Kuning', 'Mulai membaik'],
            ['Dewi Anjani',    '2024-10-05', 6,  6.0,  63.0, -1.0, 'Normal',         'Hijau',  'Sehat'],
            ['Dewi Anjani',    '2025-04-05', 12, 8.0,  71.0, -0.7, 'Normal',         'Hijau',  'Pertumbuhan baik'],
            ['Rizki Ramadhan', '2023-01-12', 6,  4.5,  58.0, -3.5, 'Stunting Berat', 'Merah',  'Rujuk ke Puskesmas'],
            ['Rizki Ramadhan', '2023-07-12', 12, 6.0,  65.0, -3.2, 'Stunting Berat', 'Merah',  'Intervensi gizi'],
            ['Rizki Ramadhan', '2024-01-12', 18, 7.0,  70.0, -3.0, 'Stunting Berat', 'Merah',  'Pemantauan ketat'],
            ['Rizki Ramadhan', '2024-07-12', 24, 8.0,  74.0, -2.8, 'Stunting',       'Kuning', 'Membaik sedikit'],
            ['Putri Ayu',      '2024-08-28', 6,  6.2,  64.5, -0.6, 'Normal',         'Hijau',  'Sehat'],
            ['Putri Ayu',      '2025-02-28', 12, 8.2,  71.5, -0.4, 'Normal',         'Hijau',  'Pertumbuhan optimal'],
            ['Bagas Saputra',  '2023-12-15', 6,  5.8,  61.0, -2.2, 'Stunting',       'Kuning', 'Perlu gizi tambahan'],
            ['Bagas Saputra',  '2024-06-15', 12, 7.2,  68.5, -2.0, 'Stunting',       'Kuning', 'Perlu pemantauan'],
            ['Bagas Saputra',  '2024-12-15', 18, 8.5,  73.0, -1.8, 'Normal',         'Hijau',  'Membaik'],
            ['Nabila Zahra',   '2024-09-10', 6,  6.5,  65.0, -0.3, 'Normal',         'Hijau',  'Sehat'],
            ['Nabila Zahra',   '2025-03-10', 12, 8.8,  72.0, -0.2, 'Normal',         'Hijau',  'Pertumbuhan optimal'],
            ['Arya Pratama',   '2023-03-25', 6,  5.0,  60.0, -3.0, 'Stunting Berat', 'Merah',  'Rujuk ke Puskesmas'],
            ['Arya Pratama',   '2023-09-25', 12, 6.5,  66.0, -2.8, 'Stunting',       'Kuning', 'Intervensi gizi'],
            ['Arya Pratama',   '2024-03-25', 18, 7.8,  71.0, -2.5, 'Stunting',       'Kuning', 'Perlu pemantauan'],
            ['Salsabila',      '2024-05-30', 6,  6.3,  64.0, -0.8, 'Normal',         'Hijau',  'Sehat'],
            ['Salsabila',      '2024-11-30', 12, 8.0,  70.5, -0.5, 'Normal',         'Hijau',  'Pertumbuhan baik'],
            ['Farel Nugraha',  '2024-07-20', 6,  7.0,  66.0, -0.4, 'Normal',         'Hijau',  'Sehat'],
            ['Farel Nugraha',  '2025-01-20', 12, 9.0,  73.0, -0.6, 'Normal',         'Hijau',  'Pertumbuhan baik'],
            ['Kayla Pratiwi',  '2023-06-05', 6,  5.2,  59.0, -2.8, 'Stunting',       'Kuning', 'Perlu intervensi gizi'],
            ['Kayla Pratiwi',  '2023-12-05', 12, 6.8,  67.0, -2.5, 'Stunting',       'Kuning', 'Pemantauan rutin'],
            ['Kayla Pratiwi',  '2024-06-05', 18, 8.0,  72.0, -2.3, 'Stunting',       'Kuning', 'Perlu suplementasi'],
            ['Dafa Alfarizi',  '2024-12-18', 6,  7.2,  66.5, -0.3, 'Normal',         'Hijau',  'Sehat'],
            ['Zahra Kamilah',  '2024-02-22', 6,  4.8,  58.5, -3.2, 'Stunting Berat', 'Merah',  'Rujuk ke Puskesmas'],
            ['Zahra Kamilah',  '2024-08-22', 12, 6.0,  64.0, -3.0, 'Stunting Berat', 'Merah',  'Intervensi gizi intensif'],
            ['Zahra Kamilah',  '2025-02-22', 18, 7.2,  69.0, -2.9, 'Stunting Berat', 'Merah',  'Pemantauan ketat'],
            ['Kenzo Pratama',  '2022-10-14', 6,  5.5,  61.0, -2.5, 'Stunting',       'Kuning', 'Perlu gizi tambahan'],
            ['Kenzo Pratama',  '2023-04-14', 12, 7.0,  67.5, -2.3, 'Stunting',       'Kuning', 'Pemantauan rutin'],
            ['Kenzo Pratama',  '2023-10-14', 18, 8.2,  72.0, -2.0, 'Stunting',       'Kuning', 'Membaik sedikit'],
            ['Kenzo Pratama',  '2024-04-14', 24, 9.5,  76.0, -1.8, 'Normal',         'Hijau',  'Membaik'],
        ];

        foreach ($pengukuranData as $p) {
            $db->table('pengukuran')->insert([
                'id_balita'    => $balitaIds[$p[0]],
                'tanggal_ukur' => $p[1],
                'usia_bulan'   => $p[2],
                'berat_badan'  => $p[3],
                'tinggi_badan' => $p[4],
                'z_score'      => $p[5],
                'status_gizi'  => $p[6],
                'warna_kms'    => $p[7],
                'catatan'      => $p[8],
            ]);
        }

        $db->transComplete();

        // ==================== OUTPUT ====================
        $totalUsers    = $db->table('class_users')->countAll();
        $totalBalita   = $db->table('balita')->countAll();
        $totalUkur     = $db->table('pengukuran')->countAll();

        $html = '<div style="font-family:sans-serif;padding:30px;max-width:800px;margin:0 auto">';
        $html .= '<h2 style="color:#16a34a">✅ Seeder Berhasil!</h2><hr>';
        $html .= '<table border="1" cellpadding="10" style="border-collapse:collapse;margin-bottom:20px">';
        $html .= "<tr><th>Tabel</th><th>Total Record</th></tr>";
        $html .= "<tr><td>class_users</td><td>{$totalUsers}</td></tr>";
        $html .= "<tr><td>balita</td><td>{$totalBalita}</td></tr>";
        $html .= "<tr><td>pengukuran</td><td>{$totalUkur}</td></tr>";
        $html .= '</table>';

        $html .= '<h3>🔑 Akun Login (password: <code>password123</code>)</h3>';
        $html .= '<table border="1" cellpadding="8" style="border-collapse:collapse">';
        $html .= '<tr><th>Email</th><th>Nama</th><th>Role</th></tr>';
        $akun = $db->table('class_users')->orderBy('FIELD(role,"admin","kader","orangtua"), nama', '', false)->get()->getResultArray();
        foreach ($akun as $a) {
            $html .= "<tr><td>{$a['email']}</td><td>{$a['nama']}</td><td>{$a['role']}</td></tr>";
        }
        $html .= '</table>';

        $html .= '<p style="color:red;font-weight:bold;margin-top:20px">⚠️ Hapus file app/Controllers/Seeder.php dan route /seeder setelah ini!</p>';
        $html .= '</div>';

        return $html;
    }
}