<?php

/**
 * SEEDER DATA DUMMY POSYANDU
 * Cara pakai:
 * 1. Letakkan file ini di folder public/
 * 2. Sesuaikan kredensial database di bawah jika perlu
 * 3. Akses via browser: http://localhost/seed_dummy.php
 * 4. Hapus file ini setelah selesai (keamanan!)
 */

// ==================== KONFIGURASI DATABASE ====================
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'dbpsoyandu_balita_online';

// ==================== KONEKSI ====================
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die("<h3 style='color:red'>❌ Koneksi gagal: " . $conn->connect_error . "</h3>
         <p>Sesuaikan kredensial database di bagian atas file ini.</p>");
}
$conn->set_charset('utf8mb4');

echo "<h2>🌱 Seeder Data Dummy Posyandu</h2><hr>";

// Cek apakah data sudah ada
$check = $conn->query("SELECT id_user FROM class_users WHERE email = 'admin@posyandu.id' LIMIT 1");
if ($check && $check->num_rows > 0) {
    die("<div style='color:orange;font-size:18px;padding:20px'>
           ⚠️ Data dummy sudah pernah di-insert. Hapus data lama dulu jika ingin mengulang.<br>
           Jalankan SQL ini di phpMyAdmin:<br>
           <code>DELETE FROM pengukuran; DELETE FROM balita; DELETE FROM class_users WHERE email LIKE '%@%';</code>
         </div>");
}

// Hash password "password123" untuk semua user
$hash = password_hash('password123', PASSWORD_DEFAULT);

// ==================== 1. INSERT USERS ====================
$users = [
    // [nama, email, role, no_hp, alamat]
    ['Admin Posyandu',  'admin@posyandu.id', 'admin',    '081234500001', 'Jl. Posyandu No. 1, Malang'],
    ['Bidan Sartika',   'sartika@posyandu.id', 'kader',  '081234500002', 'Jl. Melati No. 2, Malang'],
    ['Maria Lestari',   'maria@posyandu.id',  'kader',   '081234500003', 'Jl. Anggrek No. 3, Malang'],
    ['Budi Santoso',    'budi@gmail.com',     'orangtua','081234500004', 'Jl. Mawar No. 5, Malang'],
    ['Sri Wahyuni',     'sri@gmail.com',      'orangtua','081234500005', 'Jl. Kenanga No. 7, Malang'],
    ['Agus Purnomo',    'agus@gmail.com',     'orangtua','081234500006', 'Jl. Anggrek No. 3, Malang'],
    ['Dewi Lestari',    'dewi@gmail.com',     'orangtua','081234500007', 'Jl. Flamboyan No. 2, Malang'],
    ['Joko Susilo',     'joko@gmail.com',     'orangtua','081234500008', 'Jl. Cempaka No. 8, Malang'],
    ['Rina Marlina',    'rina@gmail.com',     'orangtua','081234500009', 'Jl. Dahlia No. 4, Malang'],
    ['Hendra Wijaya',   'hendra@gmail.com',   'orangtua','081234500010', 'Jl. Teratai No. 6, Malang'],
    ['Maya Sari',       'maya@gmail.com',     'orangtua','081234500011', 'Jl. Tulip No. 9, Malang'],
    ['Rudi Hartono',    'rudi@gmail.com',     'orangtua','081234500012', 'Jl. Lavender No. 1, Malang'],
    ['Lina Farida',     'lina@gmail.com',     'orangtua','081234500013', 'Jl. Bougenville No. 11, Malang'],
];

$stmt = $conn->prepare("INSERT INTO class_users (nama, email, password, role, no_hp, alamat) VALUES (?, ?, ?, ?, ?, ?)");
foreach ($users as $u) {
    $stmt->bind_param('ssssss', $u[0], $u[1], $hash, $u[2], $u[3], $u[4]);
    $stmt->execute();
}
echo "<p>✅ Inserted <b>" . count($users) . "</b> users (password: <code>password123</code>)</p>";

// Ambil ID users yang baru di-insert
$userIds = [];
$res = $conn->query("SELECT id_user, email FROM class_users WHERE email LIKE '%@%' ORDER BY id_user");
while ($row = $res->fetch_assoc()) {
    $userIds[$row['email']] = (int) $row['id_user'];
}

// ==================== 2. INSERT BALITA ====================
// Format: [nama_balita, nama_ibu, nik, jenis_kelamin, tanggal_lahir, alamat, email_orangtua]
$balitaData = [
    // Budi Santoso (budi@gmail.com)
    ['Ahmad Pratama',  'Kartika Sari',    '3578010101240001', 'L', '2024-01-10', 'Jl. Mawar No. 5, Malang', 'budi@gmail.com'],
    ['Siti Aisyah',    'Kartika Sari',    '3578010205240002', 'P', '2024-05-15', 'Jl. Mawar No. 5, Malang', 'budi@gmail.com'],
    // Sri Wahyuni (sri@gmail.com)
    ['Budi Anugerah',  'Sri Wahyuni',     '3578010303230003', 'L', '2023-03-20', 'Jl. Kenanga No. 7, Malang', 'sri@gmail.com'],
    // Agus Purnomo (agus@gmail.com)
    ['Dewi Anjani',    'Siti Aminah',     '3578010404240004', 'P', '2024-04-05', 'Jl. Anggrek No. 3, Malang', 'agus@gmail.com'],
    ['Rizki Ramadhan', 'Siti Aminah',     '3578010507220005', 'L', '2022-07-12', 'Jl. Anggrek No. 3, Malang', 'agus@gmail.com'],
    // Dewi Lestari (dewi@gmail.com)
    ['Putri Ayu',      'Dewi Lestari',    '3578010602240006', 'P', '2024-02-28', 'Jl. Flamboyan No. 2, Malang', 'dewi@gmail.com'],
    // Joko Susilo (joko@gmail.com)
    ['Bagas Saputra',  'Ani Yudhoyono',   '3578010706230007', 'L', '2023-06-15', 'Jl. Cempaka No. 8, Malang', 'joko@gmail.com'],
    // Rina Marlina (rina@gmail.com)
    ['Nabila Zahra',   'Rina Marlina',    '3578010803240008', 'P', '2024-03-10', 'Jl. Dahlia No. 4, Malang', 'rina@gmail.com'],
    ['Arya Pratama',   'Rina Marlina',    '3578010909220009', 'L', '2022-09-25', 'Jl. Dahlia No. 4, Malang', 'rina@gmail.com'],
    // Hendra Wijaya (hendra@gmail.com)
    ['Salsabila',      'Indah Permatasari','3578011011230010', 'P', '2023-11-30', 'Jl. Teratai No. 6, Malang', 'hendra@gmail.com'],
    // Maya Sari (maya@gmail.com)
    ['Farel Nugraha',  'Maya Sari',       '3578011101240011', 'L', '2024-01-20', 'Jl. Tulip No. 9, Malang', 'maya@gmail.com'],
    // Rudi Hartono (rudi@gmail.com)
    ['Kayla Pratiwi',  'Sutrisni',        '3578011212220012', 'P', '2022-12-05', 'Jl. Lavender No. 1, Malang', 'rudi@gmail.com'],
    ['Dafa Alfarizi',  'Sutrisni',        '3578011306240013', 'L', '2024-06-18', 'Jl. Lavender No. 1, Malang', 'rudi@gmail.com'],
    // Lina Farida (lina@gmail.com)
    ['Zahra Kamilah',  'Lina Farida',     '3578011408230014', 'P', '2023-08-22', 'Jl. Bougenville No. 11, Malang', 'lina@gmail.com'],
    ['Kenzo Pratama',  'Lina Farida',     '3578011504220015', 'L', '2022-04-14', 'Jl. Bougenville No. 11, Malang', 'lina@gmail.com'],
];

$stmt = $conn->prepare("INSERT INTO balita (nama_balita, nama_ibu, nik, jenis_kelamin, tanggal_lahir, alamat, id_user) VALUES (?, ?, ?, ?, ?, ?, ?)");
foreach ($balitaData as $b) {
    $id_user = $userIds[$b[6]];
    $stmt->bind_param('ssssssi', $b[0], $b[1], $b[2], $b[3], $b[4], $b[5], $id_user);
    $stmt->execute();
}
echo "<p>✅ Inserted <b>" . count($balitaData) . "</b> balita</p>";

// Ambil ID balita
$balitaIds = [];
$res = $conn->query("SELECT id_balita, nama_balita FROM balita ORDER BY id_balita");
while ($row = $res->fetch_assoc()) {
    $balitaIds[$row['nama_balita']] = (int) $row['id_balita'];
}

// ==================== 3. INSERT PENGUKURAN ====================
// Format: [nama_balita, tanggal_ukur, usia_bulan, berat_badan, tinggi_badan, z_score, status_gizi, warna_kms, catatan]
$pengukuranData = [
    // Ahmad Pratama — Normal
    ['Ahmad Pratama',  '2024-07-10', 6,  7.5,  67.0, -0.8, 'Normal',         'Hijau',  'Pertumbuhan baik'],
    ['Ahmad Pratama',  '2025-01-15', 12, 9.5,  72.5, -1.2, 'Normal',         'Hijau',  'Pertumbuhan normal'],
    ['Ahmad Pratama',  '2025-04-20', 15, 10.2, 75.0, -1.0, 'Normal',         'Hijau',  'Sehat'],
    // Siti Aisyah — Normal
    ['Siti Aisyah',    '2024-11-20', 6,  6.8,  64.0, -0.5, 'Normal',         'Hijau',  'Pertumbuhan baik'],
    ['Siti Aisyah',    '2025-05-15', 12, 8.5,  70.0, -0.9, 'Normal',         'Hijau',  'Sehat'],
    // Budi Anugerah — Stunting
    ['Budi Anugerah',  '2023-09-20', 6,  5.5,  62.0, -2.5, 'Stunting',       'Kuning', 'Perlu perbaikan gizi'],
    ['Budi Anugerah',  '2024-03-20', 12, 7.0,  68.0, -2.3, 'Stunting',       'Kuning', 'Perlu suplementasi'],
    ['Budi Anugerah',  '2024-06-20', 15, 7.5,  70.0, -2.1, 'Stunting',       'Kuning', 'Mulai membaik'],
    // Dewi Anjani — Normal
    ['Dewi Anjani',    '2024-10-05', 6,  6.0,  63.0, -1.0, 'Normal',         'Hijau',  'Sehat'],
    ['Dewi Anjani',    '2025-04-05', 12, 8.0,  71.0, -0.7, 'Normal',         'Hijau',  'Pertumbuhan baik'],
    // Rizki Ramadhan — Stunting Berat
    ['Rizki Ramadhan', '2023-01-12', 6,  4.5,  58.0, -3.5, 'Stunting Berat', 'Merah',  'Rujuk ke Puskesmas'],
    ['Rizki Ramadhan', '2023-07-12', 12, 6.0,  65.0, -3.2, 'Stunting Berat', 'Merah',  'Intervensi gizi'],
    ['Rizki Ramadhan', '2024-01-12', 18, 7.0,  70.0, -3.0, 'Stunting Berat', 'Merah',  'Pemantauan ketat'],
    ['Rizki Ramadhan', '2024-07-12', 24, 8.0,  74.0, -2.8, 'Stunting',       'Kuning', 'Membaik sedikit'],
    // Putri Ayu — Normal
    ['Putri Ayu',      '2024-08-28', 6,  6.2,  64.5, -0.6, 'Normal',         'Hijau',  'Sehat'],
    ['Putri Ayu',      '2025-02-28', 12, 8.2,  71.5, -0.4, 'Normal',         'Hijau',  'Pertumbuhan optimal'],
    // Bagas Saputra — Stunting
    ['Bagas Saputra',  '2023-12-15', 6,  5.8,  61.0, -2.2, 'Stunting',       'Kuning', 'Perlu gizi tambahan'],
    ['Bagas Saputra',  '2024-06-15', 12, 7.2,  68.5, -2.0, 'Stunting',       'Kuning', 'Perlu pemantauan'],
    ['Bagas Saputra',  '2024-12-15', 18, 8.5,  73.0, -1.8, 'Normal',         'Hijau',  'Membaik'],
    // Nabila Zahra — Normal
    ['Nabila Zahra',   '2024-09-10', 6,  6.5,  65.0, -0.3, 'Normal',         'Hijau',  'Sehat'],
    ['Nabila Zahra',   '2025-03-10', 12, 8.8,  72.0, -0.2, 'Normal',         'Hijau',  'Pertumbuhan optimal'],
    // Arya Pratama — Stunting
    ['Arya Pratama',   '2023-03-25', 6,  5.0,  60.0, -3.0, 'Stunting Berat', 'Merah',  'Rujuk ke Puskesmas'],
    ['Arya Pratama',   '2023-09-25', 12, 6.5,  66.0, -2.8, 'Stunting',       'Kuning', 'Intervensi gizi'],
    ['Arya Pratama',   '2024-03-25', 18, 7.8,  71.0, -2.5, 'Stunting',       'Kuning', 'Perlu pemantauan'],
    // Salsabila — Normal
    ['Salsabila',      '2024-05-30', 6,  6.3,  64.0, -0.8, 'Normal',         'Hijau',  'Sehat'],
    ['Salsabila',      '2024-11-30', 12, 8.0,  70.5, -0.5, 'Normal',         'Hijau',  'Pertumbuhan baik'],
    // Farel Nugraha — Normal
    ['Farel Nugraha',  '2024-07-20', 6,  7.0,  66.0, -0.4, 'Normal',         'Hijau',  'Sehat'],
    ['Farel Nugraha',  '2025-01-20', 12, 9.0,  73.0, -0.6, 'Normal',         'Hijau',  'Pertumbuhan baik'],
    // Kayla Pratiwi — Stunting
    ['Kayla Pratiwi',  '2023-06-05', 6,  5.2,  59.0, -2.8, 'Stunting',       'Kuning', 'Perlu intervensi gizi'],
    ['Kayla Pratiwi',  '2023-12-05', 12, 6.8,  67.0, -2.5, 'Stunting',       'Kuning', 'Pemantauan rutin'],
    ['Kayla Pratiwi',  '2024-06-05', 18, 8.0,  72.0, -2.3, 'Stunting',       'Kuning', 'Perlu suplementasi'],
    // Dafa Alfarizi — Normal
    ['Dafa Alfarizi',  '2024-12-18', 6,  7.2,  66.5, -0.3, 'Normal',         'Hijau',  'Sehat'],
    // Zahra Kamilah — Stunting Berat
    ['Zahra Kamilah',  '2024-02-22', 6,  4.8,  58.5, -3.2, 'Stunting Berat', 'Merah',  'Rujuk ke Puskesmas'],
    ['Zahra Kamilah',  '2024-08-22', 12, 6.0,  64.0, -3.0, 'Stunting Berat', 'Merah',  'Intervensi gizi intensif'],
    ['Zahra Kamilah',  '2025-02-22', 18, 7.2,  69.0, -2.9, 'Stunting Berat', 'Merah',  'Pemantauan ketat'],
    // Kenzo Pratama — Stunting
    ['Kenzo Pratama',  '2022-10-14', 6,  5.5,  61.0, -2.5, 'Stunting',       'Kuning', 'Perlu gizi tambahan'],
    ['Kenzo Pratama',  '2023-04-14', 12, 7.0,  67.5, -2.3, 'Stunting',       'Kuning', 'Pemantauan rutin'],
    ['Kenzo Pratama',  '2023-10-14', 18, 8.2,  72.0, -2.0, 'Stunting',       'Kuning', 'Membaik sedikit'],
    ['Kenzo Pratama',  '2024-04-14', 24, 9.5,  76.0, -1.8, 'Normal',         'Hijau',  'Membaik'],
];

$stmt = $conn->prepare("INSERT INTO pengukuran (id_balita, tanggal_ukur, usia_bulan, berat_badan, tinggi_badan, z_score, status_gizi, warna_kms, catatan) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
foreach ($pengukuranData as $p) {
    $id_balita = $balitaIds[$p[0]];
    $stmt->bind_param('isiddddss', $id_balita, $p[1], $p[2], $p[3], $p[4], $p[5], $p[6], $p[7], $p[8]);
    $stmt->execute();
}
echo "<p>✅ Inserted <b>" . count($pengukuranData) . "</b> pengukuran</p>";

// ==================== RINGKASAN ====================
echo "<hr><h3>📊 Ringkasan Data</h3>";
echo "<table border='1' cellpadding='8' style='border-collapse:collapse'>";
echo "<tr><th>Tabel</th><th>Jumlah Record</th></tr>";

$countUsers = $conn->query("SELECT COUNT(*) as total FROM class_users")->fetch_assoc()['total'];
$countBalita = $conn->query("SELECT COUNT(*) as total FROM balita")->fetch_assoc()['total'];
$countUkur = $conn->query("SELECT COUNT(*) as total FROM pengukuran")->fetch_assoc()['total'];

echo "<tr><td>class_users</td><td>$countUsers</td></tr>";
echo "<tr><td>balita</td><td>$countBalita</td></tr>";
echo "<tr><td>pengukuran</td><td>$countUkur</td></tr>";
echo "</table>";

echo "<h3>🔑 Akun Login (password: <code>password123</code>)</h3>";
echo "<table border='1' cellpadding='8' style='border-collapse:collapse'>";
echo "<tr><th>Email</th><th>Nama</th><th>Role</th></tr>";
$akun = $conn->query("SELECT nama, email, role FROM class_users ORDER BY FIELD(role,'admin','kader','orangtua'), nama");
while ($a = $akun->fetch_assoc()) {
    echo "<tr><td>{$a['email']}</td><td>{$a['nama']}</td><td>{$a['role']}</td></tr>";
}
echo "</table>";

echo "<hr><p style='color:red;font-weight:bold'>⚠️ HAPUS file ini (seed_dummy.php) setelah selesai untuk keamanan!</p>";

$conn->close();