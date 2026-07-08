# TODO - Patch audit keamanan & performa

## Tahap 1: Proteksi route konsultasi
- [ ] Tambahkan group `filter => 'role:...'` untuk `/konsultasi` dan `/konsultasi/anak/(:num)` di `app/Config/Routes.php`.
- [ ] Tambahkan validasi authorization di `app/Controllers/Konsultasi.php` (minimal: pastikan session logged_in dan role yang diizinkan).

## Tahap 2: Perbaiki delete balita (GET -> POST) + CSRF
- [ ] Ubah route `balita/hapus/(:num)` dari GET menjadi POST di `app/Config/Routes.php`.
- [ ] Ubah tombol/link hapus di `app/Views/balita/index.php` menjadi form POST dengan `<?= csrf_field(); ?>`.
- [ ] Pastikan `Balita::hapus($id)` tetap mengandalkan method route POST (tanpa asumsi GET).

## Tahap 3: Kurangi IDOR (scoping/ownership) untuk role orangtua
- [ ] Tentukan dari skema database relasi user->balita (jika ada field/kolom). Audit belum menemukan model relasi ownership.
- [ ] Jika relasi tidak ada, buat minimal guard: untuk role `orangtua`, batasi akses hanya ke data tertentu (butuh info skema).

## Tahap 4: Optimasi laporan
- [ ] Refactor `app/Controllers/Laporan.php::dataLaporan()` agar tidak N+1 query (ambil pengukuran terakhir per balita dalam 1-2 query).

## Tahap 5: Konsistensi role di view
- [ ] Standardisasi role display dengan `strtolower(session()->get('role'))` di `app/Views/layout/sidebar.php`.

## Tahap 6: Testing
- [ ] Test flow: login, akses konsultasi, delete balita (CSRF), detail/riwayat dengan role berbeda.
- [ ] Test export/report (PDF/Excel jika ada), pastikan output sama.

