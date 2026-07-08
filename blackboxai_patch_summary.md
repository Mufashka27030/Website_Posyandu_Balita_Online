# Patch summary (audit fixes)

## 1) Proteksi route konsultasi
- File: `app/Config/Routes.php`
- Perubahan: Route `/konsultasi` dan `/konsultasi/anak/(:num)` sekarang dibungkus `filter => 'role:orangtua'`.

## Yang belum selesai (butuh patch lanjutan)
- Perbaikan delete balita (GET -> POST + CSRF) karena lokasi tombol/link hapus belum ditemukan di repository yang terindeks.
- Scoping/ownership IDOR untuk role lain (minimal: orangtua) perlu skema relasi user->balita.

