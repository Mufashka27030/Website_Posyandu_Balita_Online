<?= $this->extend('layout/main'); ?>

<?= $this->section('content'); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1">Detail Data Balita</h3>
        <p class="text-muted mb-0">Informasi lengkap data balita</p>
    </div>
    <a href__="/balita" class="btn btn-outline-secondary btn-sm">
        ← Kembali
    </a>
</div>

<?php if (isset($balita) && $balita): ?>

<div class="row g-4">
    <!-- Data Balita -->
    <div class="col-lg-7">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0 fw-bold">Data Identitas</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <small class="text-muted d-block text-uppercase fw-semibold mb-1" style="font-size: 0.7rem;">Nama Balita</small>
                        <span class="fw-semibold"><?= esc($balita['nama_balita']); ?></span>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block text-uppercase fw-semibold mb-1" style="font-size: 0.7rem;">Nama Ibu</small>
                        <span class="fw-semibold"><?= esc($balita['nama_ibu']); ?></span>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block text-uppercase fw-semibold mb-1" style="font-size: 0.7rem;">Jenis Kelamin</small>
                        <span class="badge bg-<?= $balita['jenis_kelamin'] === 'L' ? 'primary' : 'danger'; ?>">
                            <?= $balita['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan'; ?>
                        </span>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block text-uppercase fw-semibold mb-1" style="font-size: 0.7rem;">Tanggal Lahir</small>
                        <span class="fw-semibold">
                            <?= date('d M Y', strtotime($balita['tanggal_lahir'])); ?>
                        </span>
                    </div>
                    <div class="col-12">
                        <small class="text-muted d-block text-uppercase fw-semibold mb-1" style="font-size: 0.7rem;">Alamat</small>
                        <span><?= esc($balita['alamat'] ?? '-'); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <?php if (session()->get('role') !== 'orangtua'): ?>
        <div class="card shadow-sm border-0 mt-3">
            <div class="card-body py-3">
                <div class="d-flex gap-2 flex-wrap">
                    <a href__="/balita/edit/<?= $balita['id_balita']; ?>" class="btn btn-warning btn-sm">✏ Edit Data</a>
                    <form action="/balita/hapus/<?= $balita['id_balita']; ?>" method="post" class="d-inline"
                          onsubmit="return confirm('Yakin hapus data balita ini?');">
                        <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">
                        <button type="submit" class="btn btn-danger btn-sm">🗑 Hapus</button>
                    </form>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Pengukuran Terbaru -->
    <div class="col-lg-5">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0 fw-bold">Pengukuran Terbaru</h5>
            </div>
            <div class="card-body">
                <?php if (isset($pengukuran) && $pengukuran): ?>
                <div class="text-center mb-3">
                    <?php
                    $warnaClass = match ($pengukuran['warna_kms'] ?? '') {
                        'Hijau'  => 'bg-success',
                        'Kuning' => 'bg-warning',
                        'Merah'  => 'bg-danger',
                        default  => 'bg-secondary',
                    };
                    ?>
                    <span class="badge <?= $warnaClass; ?> px-4 py-2 fs-6">
                        <?= esc($pengukuran['status_gizi'] ?? 'Tidak Diketahui'); ?>
                    </span>
                </div>
                <div class="row g-3">
                    <div class="col-6">
                        <div class="bg-light rounded p-2 text-center">
                            <small class="text-muted d-block" style="font-size: 0.7rem;">TGL UKUR</small>
                            <span class="fw-bold"><?= date('d/m/Y', strtotime($pengukuran['tanggal_ukur'])); ?></span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-light rounded p-2 text-center">
                            <small class="text-muted d-block" style="font-size: 0.7rem;">USIA</small>
                            <span class="fw-bold"><?= (int) $pengukuran['usia_bulan']; ?> bln</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-light rounded p-2 text-center">
                            <small class="text-muted d-block" style="font-size: 0.7rem;">TINGGI</small>
                            <span class="fw-bold"><?= esc($pengukuran['tinggi_badan']); ?> cm</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-light rounded p-2 text-center">
                            <small class="text-muted d-block" style="font-size: 0.7rem;">BERAT</small>
                            <span class="fw-bold"><?= esc($pengukuran['berat_badan']); ?> kg</span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="bg-light rounded p-2 text-center">
                            <small class="text-muted d-block" style="font-size: 0.7rem;">Z-SCORE</small>
                            <span class="fw-bold fs-5"><?= esc($pengukuran['z_score']); ?></span>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <div class="text-center text-muted py-4">
                    <p>Belum ada data pengukuran</p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="card shadow-sm border-0 mt-3">
            <div class="card-body py-3">
                <div class="d-grid gap-2">
                    <a href__="/pengukuran/<?= $balita['id_balita']; ?>" class="btn btn-primary btn-sm">➕ Tambah Pengukuran</a>
                    <a href__="/pengukuran/riwayat/<?= $balita['id_balita']; ?>" class="btn btn-outline-primary btn-sm">📋 Riwayat Pengukuran</a>
                    <a href__="/pengukuran/grafik/<?= $balita['id_balita']; ?>" class="btn btn-outline-info btn-sm">📈 Grafik Pertumbuhan</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php else: ?>

<div class="alert alert-danger">Data balita tidak ditemukan</div>

<?php endif; ?>

<?= $this->endSection(); ?>