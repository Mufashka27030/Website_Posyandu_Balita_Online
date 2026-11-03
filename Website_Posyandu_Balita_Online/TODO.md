# Status audit & perbaikan

Diperbarui 2026-11-03 setelah audit keamanan/performa menyeluruh.
Detail lengkap setiap temuan ada di laporan audit (chat), file ini hanya
berisi apa yang masih perlu dikerjakan di server/database.

## Sudah selesai di kode

- XSS tersimpan di `/mapping` (nama/alamat balita masuk `<script>` tanpa
  encode) dan XSS reflektif dari pesan exception di form pengukuran.
- Seluruh output flash, judul halaman, dan data JSON inline sekarang lewat
  `esc()`/helper `flash_block()` (helper baru: `app/Helpers/flash_helper.php`).
- Rate limit login sebelumnya tidak pernah aktif; sekarang 5 percobaan per
  email dan 20 per IP dengan blokir 15 menit, direset setelah login sukses.
- Sesi diverifikasi ulang ke database tiap request: akun yang di-downgrade
  atau dihapus kehilangan haknya seketika, dan sesi mati sendiri setelah 2
  jam tidak aktif.
- Semua URL di view memakai `site_url()` sehingga aplikasi bisa dipasang di
  subfolder (`http://localhost/Website_Posyandu_Balita_Online/public/`).
- Upload foto disatukan di `BaseController::simpanFotoProfil()`: ekstensi
  ditentukan dari isi file (bukan dari nama yang dikirim browser), maksimal
  2 MB, dan `public/uploads/.htaccess` melarang eksekusi skrip.
- `DBDebug` otomatis mati di staging/production, dan field sensitif
  (`password`, `nik`, `email`, `dst.`) tidak lagi tampil di stack trace.
- Validasi diperketat: NIK 16 digit + unik, koordinat lat/long dalam
  rentang sah, tanggal lahir/pengukuran tidak boleh di masa depan,
  berat < 50 kg dan tinggi < 150 cm, `id_balita`/`id_puskesmas` harus
  benar-benar ada.
- Query N+1 dihapus di halaman grafik pertumbuhan (standar WHO dimuat sekali)
  dan di `Laporan::recap` (rentang tanggal, bukan `MONTH()`/`YEAR()`).
- View mati dihapus: `welcome_message.php`, `layout/navbar.php`,
  `classuser/detail.php`.
- Test dipindah ke database terpisah (`dbpsoyandu_balita_online_test`,
  salinan struktur + data asli) dan feature test yang dulu selalu "lulus"
  tanpa menguji apa pun — karena `session()->set()` dihapus oleh
  `FeatureTestTrait::call()` — sekarang memakai `withSession()` sehingga
  benar-benar menjalankan controller sebagai admin/kader/orang tua.
  Regresi baru: `MappingXssTest`, `LoginThrottleTest`, validasi NIK dan
  koordinat, IDOR orang tua, dan upload bukan-gambar.
  Jalankan: `php vendor/bin/phpunit --no-coverage` (21 test, semua lulus).

## Masih perlu dikerjakan manual

- [ ] **PANGHAPUSAN AKUN BACKDOOR**: database asli
      (`dbpsoyandu_balita_online`) masih berisi 5 akun role `admin`
      hasil PHPUnit lama yang waktu itu menulis ke database produksi:
      `id_user` 29, 30, 31, 32, 33 dengan email
      `*.test.local` dan password `passwordujicoba` (terbaca di
      repository). Hapus dengan:
      `DELETE FROM class_users WHERE email LIKE '%@test.local';`
      (test sekarang sudah diarahkan ke `dbpsoyandu_balita_online_test`,
      jadi tidak akan muncul lagi).
- [ ] Jalankan `php spark migrate` supaya foreign key
      `pengukuran.id_balita -> balita.id_balita (ON DELETE CASCADE)`
      terpasang. Migration `2026-08-29-120007_AddForeignKeyPengukuranBalita`
      sudah ada tapi belum pernah dijalankan, sehingga balita yang dihapus
      meninggalkan baris pengukuran tanpa induk.
      Aman untuk database yang sudah berisi data: semua migration memakai
      `createTable(..., true)` (IF NOT EXISTS) sehingga tabel lama tidak
      disentuh, dan migrasi baru hanya menambah FK plus melepas index duplikat.
- [ ] Migration baru `2026-11-03-000001_CleanupRedundantIndexes` melepas
      3 UNIQUE KEY `email` yang duplikat di `class_users` dan 2 index yang
      tertutupi index lain di `balita`/`pengukuran`. Jalan bersama perintah
      di atas.
- [ ] Perbaiki data lama yang melanggar aturan baru sebelum form kembali
      dipakai: `balita.id_balita = 15` (NIK 18 digit) dan `16` (NIK 14
      digit) akan ditolak sampai NIK-nya 16 digit.
- [ ] `git add app/Views/auth` lalu commit. Baris `/app/Views/auth` di
      `.gitignore` (sudah dihapus) membuat halaman login/register/profile
      tidak pernah masuk ke repository GitHub — hasil clone tidak bisa login.
- [ ] Ganti `.env` di server publik: salin dari file `env`, set
      `CI_ENVIRONMENT = production`, `app.baseURL` sesuai alamat, dan
      `database.default.DBDebug = false`.
- [ ] Putar password akun admin/kader yang ada, karena log lama
      (`writable/logs/*.log`, sudah dipindahkan keluar proyek) memuat hash
      bcrypt dan query lengkap dari percobaan tes.
- [ ] Tabel `konsultasi` masih kosong dan tidak dipakai (fitur konsultasi
      hanya membuka WhatsApp). Pertahankan kalau chat internal akan dibuat,
      hapus lewat migration kalau tidak.
- [ ] Peran `dinkes` masih ada di ENUM `class_users.role` tetapi tidak bisa
      dibuat dari aplikasi mana pun. Putuskan: implementasi atau hilangkan.

## Known limitation

`Laporan::recap()` menghitung **baris pengukuran**, bukan balita unik. Anak
yang diukur dua kali dalam satu bulan tercatat dua kali, jadi angka "total
balita" di rekap bulanan bisa lebih besar dari jumlah anak yang benar-benar
diukur bulan itu. Halaman `/dashboard/statistik` dan `/laporan` sudah memakai
status terbaru per balita dan tidak terkena masalah ini.
