<?= $this->extend('layout/main'); ?>

<?= $this->section('content'); ?>

<div class="row justify-content-center mt-4">
    <div class="col-lg-6">
        <div class="card shadow border-0 rounded-4">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    <i class="bi bi-clipboard2-check me-2"></i>Hasil Analisis Stunting
                </h4>
            </div>
            <div class="card-body">

                <!-- Navigasi -->
                <div class="row mb-4">
                    <div class="col-md-4 mb-2">
                        <a href="/pengukuran/riwayat/<?= $balita['id_balita'] ?? 0; ?>" class="btn btn-primary w-100">
                            <i class="bi bi-list-ul"></i> Riwayat
                        </a>
                    </div>
                    <div class="col-md-4 mb-2">
                        <a href="/pengukuran/grafik/<?= $balita['id_balita'] ?? 0; ?>" class="btn btn-success w-100">
                            <i class="bi bi-graph-up"></i> Grafik
                        </a>
                    </div>
                    <div class="col-md-4 mb-2">
                        <a href="/balita/detail/<?= $balita['id_balita'] ?? 0; ?>" class="btn btn-secondary w-100">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>

                <!-- Info Balita -->
                <h5 class="fw-bold mb-3">
                    <i class="bi bi-person-fill text-primary me-1"></i>
                    <?= esc($balita['nama_balita'] ?? '-'); ?>
                </h5>
                <hr>

                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <div class="bg-light rounded p-2 text-center">
                            <small class="text-muted d-block" style="font-size:0.7rem;">USIA</small>
                            <span class="fw-bold fs-5"><?= $usia_bulan ?? 0; ?> Bulan</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-light rounded p-2 text-center">
                            <small class="text-muted d-block" style="font-size:0.7rem;">TGL UKUR</small>
                            <span class="fw-bold"><?= $tanggal_ukur ?? '-'; ?></span>
                        </div>
                    </div>
                </div>

                <table class="table table-sm">
                    <tr>
                        <td class="text-muted">Berat Badan</td>
                        <td class="fw-bold text-end"><?= $berat_badan ?? 0; ?> kg</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Tinggi Badan</td>
                        <td class="fw-bold text-end"><?= $tinggi_badan ?? 0; ?> cm</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Z-Score</td>
                        <td class="fw-bold text-end fs-5 text-primary"><?= $zscore ?? 0; ?></td>
                    </tr>
                </table>
                <hr>

                <!-- Status Badge -->
                <?php
                $warnaBadge = 'secondary';
                if (($status ?? '') === 'Normal') $warnaBadge = 'success';
                elseif (($status ?? '') === 'Stunting') $warnaBadge = 'warning';
                elseif (($status ?? '') === 'Stunting Berat') $warnaBadge = 'danger';
                ?>
                <div class="text-center my-3">
                    <span class="badge bg-<?= $warnaBadge; ?> px-4 py-2 fs-5">
                        <?= esc($status ?? '-'); ?>
                    </span>
                </div>

                <?php if (($status ?? '') === 'Stunting Berat'): ?>
                    <div class="alert alert-danger mt-3 d-flex align-items-start gap-2">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <div><strong>⚠ Segera konsultasi ke tenaga kesehatan.</strong><br>
                        Anak Anda terdeteksi stunting berat dan memerlukan penanganan medis segera.</div>
                    </div>
                <?php elseif (($status ?? '') === 'Stunting'): ?>
                    <div class="alert alert-warning mt-3 d-flex align-items-start gap-2">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <div><strong>Perlu perhatian.</strong><br>
                        Tingkatkan asupan gizi seimbang dan rutin memantau di posyandu.</div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-success mt-3 d-flex align-items-start gap-2">
                        <i class="bi bi-check-circle-fill"></i>
                        <div><strong>Alhamdulillah!</strong> Status gizi balita Anda normal. 🎉</div>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>