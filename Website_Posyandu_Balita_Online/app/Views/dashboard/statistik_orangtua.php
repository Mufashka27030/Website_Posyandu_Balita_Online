<?= $this->extend('layout/main'); ?>

<?= $this->section('content'); ?>

<div class="mb-4">
    <h3 class="mb-1 fw-bold">Statistik Balita Saya</h3>
    <p class="text-muted mb-0">Ringkasan status gizi balita Anda</p>
</div>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <i class="bi bi-people text-primary mb-2" style="font-size:1.5rem"></i>
                <div class="fs-4 fw-bold"><?= $total_balita ?? 0; ?></div>
                <div class="text-muted small">Total Balita</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <i class="bi bi-check-circle text-success mb-2" style="font-size:1.5rem"></i>
                <div class="fs-4 fw-bold text-success"><?= $total_normal ?? 0; ?></div>
                <div class="text-muted small">Normal</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <i class="bi bi-exclamation-triangle text-warning mb-2" style="font-size:1.5rem"></i>
                <div class="fs-4 fw-bold text-warning"><?= $total_stunting ?? 0; ?></div>
                <div class="text-muted small">Stunting</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <i class="bi bi-x-circle text-danger mb-2" style="font-size:1.5rem"></i>
                <div class="fs-4 fw-bold text-danger"><?= $stunting_berat ?? 0; ?></div>
                <div class="text-muted small">Stunting Berat</div>
            </div>
        </div>
    </div>
</div>

<!-- Status Gizi Anak Saya — Khusus Orang Tua -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <h5 class="fw-bold mb-3">
            <i class="bi bi-heart-pulse text-danger me-1"></i>
            Status Gizi Anak Saya
        </h5>

        <?php if (($persentase ?? 0) > 0): ?>
            <div class="alert alert-warning border-0 d-flex align-items-start gap-2" role="alert">
                <i class="bi bi-exclamation-triangle-fill text-warning mt-1"></i>
                <div>
                    <strong><?= $persentase ?? 0; ?>% balita Anda</strong> memerlukan perhatian khusus terkait gizi.
                    Segera konsultasikan dengan kader posyandu atau puskesmas terdekat untuk mendapatkan intervensi yang tepat.
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-success border-0 d-flex align-items-start gap-2" role="alert">
                <i class="bi bi-check-circle-fill text-success mt-1"></i>
                <div>
                    <strong>Selamat!</strong> Semua balita Anda dalam status gizi normal. 🎉<br>
                    Tetap jaga rutinitas posyandu dan pola makan si kecil!
                </div>
            </div>
        <?php endif; ?>

        <!-- Progress Bar -->
        <div class="progress mb-2" style="height: 22px;">
            <div class="progress-bar bg-danger" role="progressbar"
                 style="width: <?= min($persentase ?? 0, 100); ?>%;">
                <?= $persentase ?? 0; ?>%
            </div>
        </div>

        <!-- Legend -->
        <div class="d-flex justify-content-between small text-muted">
            <span>
                <i class="bi bi-circle-fill text-success"></i>
                Normal: <?= $total_normal ?? 0; ?> anak
            </span>
            <span>
                <i class="bi bi-circle-fill text-warning"></i>
                Stunting: <?= $total_stunting ?? 0; ?> anak
            </span>
            <span>
                <i class="bi bi-circle-fill text-danger"></i>
                Stunting Berat: <?= $stunting_berat ?? 0; ?> anak
            </span>
        </div>

        <?php if (($persentase ?? 0) > 0): ?>
            <div class="mt-3 p-3 bg-light rounded">
                <small class="text-muted">
                    <i class="bi bi-info-circle me-1"></i>
                    <strong>Tips:</strong> Untuk anak dengan status stunting, tingkatkan asupan gizi seimbang,
                    rutin memantau di posyandu, dan ikuti saran dari tenaga kesehatan.
                </small>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Detail per Balita -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom">
        <h5 class="mb-0 fw-bold">Status Gizi per Balita</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Nama Balita</th>
                        <th>Jenis Kelamin</th>
                        <th>Status Gizi</th>
                        <th>Z-Score</th>
                        <th>Pengukuran Terakhir</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (! empty($balita_list)): ?>
                        <?php foreach ($balita_list as $b): ?>
                            <tr>
                                <td class="ps-4 fw-semibold"><?= esc($b['nama_balita']); ?></td>
                                <td><?= $b['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan'; ?></td>
                                <td>
                                    <?php
                                    $statusClass = match ($b['status_gizi'] ?? '') {
                                        'Normal'         => 'bg-success',
                                        'Stunting'       => 'bg-warning text-dark',
                                        'Stunting Berat' => 'bg-danger',
                                        default          => 'bg-secondary',
                                    };
                                    ?>
                                    <span class="badge <?= $statusClass; ?>">
                                        <?= esc($b['status_gizi'] ?? 'Belum diukur'); ?>
                                    </span>
                                </td>
                                <td class="fw-bold"><?= $b['z_score'] ?? '-'; ?></td>
                                <td>
                                    <?= !empty($b['tanggal_ukur']) ? date('d/m/Y', strtotime($b['tanggal_ukur'])) : '-'; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                Belum ada data balita
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
