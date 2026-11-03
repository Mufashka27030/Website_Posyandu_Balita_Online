<?= $this->extend('layout/main'); ?>

<?= $this->section('content'); ?>

<div class="mb-4">
    <h3 class="mb-1 fw-bold">Pertanyaan yang Sering Diajukan</h3>
    <p class="text-muted mb-0">Penjelasan seputar akun, istilah, dan fitur di Posyandu Balita Online</p>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-bottom">
        <h5 class="mb-0 fw-bold"><i class="bi bi-person-badge text-primary me-2"></i>Akun &amp; Login</h5>
    </div>
    <div class="card-body">
        <div class="accordion" id="accordionAkun">

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#akun1">
                        Bagaimana cara mendapatkan akun?
                    </button>
                </h2>
                <div id="akun1" class="accordion-collapse collapse" data-bs-parent="#accordionAkun">
                    <div class="accordion-body">
                        Akun dibuatkan oleh kader atau admin posyandu, biasanya saat anak pertama kali didaftarkan.
                        Silakan hubungi kader atau admin posyandu setempat untuk dibuatkan akun.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#akun2">
                        Saya lupa password, bagaimana caranya?
                    </button>
                </h2>
                <div id="akun2" class="accordion-collapse collapse" data-bs-parent="#accordionAkun">
                    <div class="accordion-body">
                        Saat ini belum ada fitur reset password mandiri. Silakan hubungi kader atau admin posyandu
                        untuk dibantu mengatur ulang password Anda.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#akun3">
                        Bisakah saya mengubah data profil saya sendiri?
                    </button>
                </h2>
                <div id="akun3" class="accordion-collapse collapse" data-bs-parent="#accordionAkun">
                    <div class="accordion-body">
                        Bisa. Buka menu <strong>Profil</strong> untuk mengubah nama, email, nomor HP, foto, atau
                        password Anda kapan saja.
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-bottom">
        <h5 class="mb-0 fw-bold"><i class="bi bi-clipboard2-pulse text-success me-2"></i>Memahami Data Pertumbuhan Anak</h5>
    </div>
    <div class="card-body">
        <div class="accordion" id="accordionData">

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#data1">
                        Apa itu Z-Score?
                    </button>
                </h2>
                <div id="data1" class="accordion-collapse collapse" data-bs-parent="#accordionData">
                    <div class="accordion-body">
                        Z-Score menunjukkan seberapa jauh tinggi badan anak dibanding standar pertumbuhan WHO untuk
                        usia yang sama. Angka mendekati 0 berarti sesuai standar; semakin negatif, semakin jauh di
                        bawah standar.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#data2">
                        Apa bedanya status Normal, Stunting, dan Stunting Berat?
                    </button>
                </h2>
                <div id="data2" class="accordion-collapse collapse" data-bs-parent="#accordionData">
                    <div class="accordion-body">
                        <strong>Normal</strong> berarti tinggi badan sesuai usia. <strong>Stunting</strong> berarti
                        tinggi badan di bawah standar. <strong>Stunting Berat</strong> berarti jauh di bawah standar
                        dan perlu perhatian lebih. Status ini bukan diagnosis akhir — selalu diskusikan hasil ini
                        dengan bidan atau kader untuk langkah selanjutnya.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#data3">
                        Apa arti warna hijau, kuning, dan merah?
                    </button>
                </h2>
                <div id="data3" class="accordion-collapse collapse" data-bs-parent="#accordionData">
                    <div class="accordion-body">
                        Warna ini mengikuti Kartu Menuju Sehat (KMS): <strong class="text-success">Hijau</strong> =
                        pertumbuhan baik, <strong class="text-warning">Kuning</strong> = perlu diperhatikan,
                        <strong class="text-danger">Merah</strong> = perlu penanganan segera.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#data4">
                        Garis-garis di Grafik Pertumbuhan itu artinya apa?
                    </button>
                </h2>
                <div id="data4" class="accordion-collapse collapse" data-bs-parent="#accordionData">
                    <div class="accordion-body">
                        Garis putus-putus adalah garis standar WHO (rata-rata dan batas bawah pertumbuhan anak
                        seusia). Garis pertumbuhan anak Anda dibandingkan dengan garis-garis ini untuk melihat apakah
                        masih berada di jalur yang sehat.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#data5">
                        Kenapa saya tidak bisa menambahkan data pengukuran sendiri?
                    </button>
                </h2>
                <div id="data5" class="accordion-collapse collapse" data-bs-parent="#accordionData">
                    <div class="accordion-body">
                        Pengukuran tinggi dan berat badan dilakukan langsung oleh kader saat jadwal penimbangan di
                        posyandu supaya hasilnya akurat. Anda bisa melihat hasilnya di menu Riwayat dan Grafik setelah
                        kader mencatatnya.
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-bottom">
        <h5 class="mb-0 fw-bold"><i class="bi bi-whatsapp text-success me-2"></i>Konsultasi</h5>
    </div>
    <div class="card-body">
        <div class="accordion" id="accordionKonsul">

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#konsul1">
                        Apa itu menu Konsultasi?
                    </button>
                </h2>
                <div id="konsul1" class="accordion-collapse collapse" data-bs-parent="#accordionKonsul">
                    <div class="accordion-body">
                        Menu Konsultasi membuka aplikasi WhatsApp dengan pesan yang sudah terisi otomatis berisi data
                        anak Anda, langsung ke nomor bidan posyandu. Ini bukan obrolan di dalam sistem, jadi pastikan
                        WhatsApp sudah terpasang di perangkat Anda.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#konsul2">
                        Apakah riwayat konsultasi tersimpan di sistem ini?
                    </button>
                </h2>
                <div id="konsul2" class="accordion-collapse collapse" data-bs-parent="#accordionKonsul">
                    <div class="accordion-body">
                        Tidak. Percakapan berlangsung sepenuhnya di WhatsApp Anda, bukan di dalam sistem ini, sehingga
                        tidak tersimpan sebagai riwayat di aplikasi.
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="alert alert-light border d-flex align-items-center gap-2">
    <i class="bi bi-info-circle text-primary fs-5"></i>
    <div>Tidak menemukan jawaban yang kamu cari? Hubungi kader atau admin posyandu setempat.</div>
</div>

<?= $this->endSection(); ?>
