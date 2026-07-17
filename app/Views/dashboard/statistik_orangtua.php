<?= $this->extend('layout/main'); ?>

<?= $this->section('content'); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1 fw-bold">Statistik Balita Saya</h3>
        <p class="text-muted mb-0">Ringkasan tumbuh kembang balita Anda</p>
    </div>
    <a href__="/dashboard-orangtua" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<!-- Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #6366f1 !important;">
            <div class="card-body">
                <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.7rem;">Total Balita</small>
                <h2 class="fw-bold mb-0 mt-1" style="color: #6366f1;"><?= $total_balita ?? 0; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #16a34a !important;">
            <div class="card-body">
                <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.7rem;">Normal</small>
                <h2 class="fw-bold mb-0 mt-1 text-success"><?= $total_normal ?? 0; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #eab308 !important;">
            <div class="card-body">
                <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.7rem;">Stunting</small>
                <h2 class="fw-bold mb-0 mt-1 text-warning"><?= $total_stunting ?? 0; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #dc2626 !important;">
            <div class="card-body">
                <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.7rem;">Stunting Berat</small>
                <h2 class="fw-bold mb-0 mt-1 text-danger"><?= $stunting_berat ?? 0; ?></h2>
            </div>
        </div>
    </div>
</div>

<!-- Progress Bar -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <?php
        $totalAll = $total_balita ?? 0;
        $persenNormal   = $totalAll > 0 ? round((($total_normal ?? 0) / $totalAll) * 100, 1) : 0;
        $persenStunting = $totalAll > 0 ? round((($total_stunting ?? 0) / $totalAll) * 100, 1) : 0;
        $persenBerat    = $totalAll > 0 ? round((($stunting_berat ?? 0) / $totalAll) * 100, 1) : 0;
        ?>
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="mb-0 fw-bold">Distribusi Status Gizi</h6>
            <span class="text-muted" style="font-size: 0.8rem;"><?= $totalAll; ?> balita total</span>
        </div>
        <div class="progress" style="height: 24px; border-radius: 8px;">
            <div class="progress-bar bg-success" style="width: <?= $persenNormal; ?>%">Normal <?= $persenNormal; ?>%</div>
            <div class="progress-bar bg-warning" style="width: <?= $persenStunting; ?>%">Stunting</div>
            <div class="progress-bar bg-danger" style="width: <?= $persenBerat; ?>%">Berat</div>
        </div>
    </div>
</div>

<!-- Detail Per Balita -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom">
        <h5 class="mb-0 fw-bold">Detail Per Balita</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Nama Balita</th>
                        <th>JK</th>
                        <th>Tgl Lahir</th>
                        <th>Z-Score</th>
                        <th>Status</th>
                        <th class="pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (! empty($balita_list)): ?>
                        <?php foreach ($balita_list as $b): ?>
                            <tr>
                                <td class="ps-4 fw-semibold"><?= esc($b['nama_balita']); ?></td>
                                <td><?= $b['jenis_kelamin'] === 'L' ? 'L' : 'P'; ?></td>
                                <td><?= date('d M Y', strtotime($b['tanggal_lahir'])); ?></td>
                                <td><?= esc($b['z_score'] ?? '-'); ?></td>
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
                                        <?= esc($b['status_gizi'] ?? 'Belum Diukur'); ?>
                                    </span>
                                </td>
                                <td class="pe-4">
                                    <a href__="/pengukuran/grafik/<?= $b['id_balita']; ?>"
                                       class="btn btn-outline-info btn-sm">Grafik</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
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