<?= $this->extend('layout/main'); ?>

<?= $this->section('content'); ?>

<div class="alert alert-primary shadow-sm mb-4 border-0">
    <h4 class="mb-0 fw-bold">Selamat Datang, <?= esc(session()->get('nama')); ?> 👋</h4>
    <p class="mb-0 mt-1">Anda login sebagai <b><?= ucfirst(session()->get('role')); ?></b></p>
</div>

<h3 class="mb-4 fw-bold">Dashboard Monitoring Stunting</h3>

<div class="row g-3 mb-4">
    <!-- Total Balita -->
    <div class="col-md-3 col-sm-6">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #6366f1 !important;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.7rem;">Total Balita</small>
                        <h2 class="fw-bold mb-0 mt-1" style="color: #6366f1;"><?= $total_balita ?? 0; ?></h2>
                    </div>
                    <div class="rounded-3 d-flex align-items-center justify-content-center"
                         style="width: 48px; height: 48px; background: #eef2ff;">
                        <span style="font-size: 1.4rem;">👶</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Normal -->
    <div class="col-md-3 col-sm-6">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #16a34a !important;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.7rem;">Normal</small>
                        <h2 class="fw-bold mb-0 mt-1 text-success"><?= $total_normal ?? 0; ?></h2>
                    </div>
                    <div class="rounded-3 d-flex align-items-center justify-content-center"
                         style="width: 48px; height: 48px; background: #dcfce7;">
                        <span style="font-size: 1.4rem;">✅</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stunting -->
    <div class="col-md-3 col-sm-6">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #eab308 !important;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.7rem;">Stunting</small>
                        <h2 class="fw-bold mb-0 mt-1 text-warning"><?= $total_stunting ?? 0; ?></h2>
                    </div>
                    <div class="rounded-3 d-flex align-items-center justify-content-center"
                         style="width: 48px; height: 48px; background: #fef9c3;">
                        <span style="font-size: 1.4rem;">⚠️</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stunting Berat -->
    <div class="col-md-3 col-sm-6">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #dc2626 !important;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.7rem;">Stunting Berat</small>
                        <h2 class="fw-bold mb-0 mt-1 text-danger"><?= $stunting_berat ?? 0; ?></h2>
                    </div>
                    <div class="rounded-3 d-flex align-items-center justify-content-center"
                         style="width: 48px; height: 48px; background: #fee2e2;">
                        <span style="font-size: 1.4rem;">🚨</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Progress Bar Stunting -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <?php
        $totalStunting = ($total_stunting ?? 0) + ($stunting_berat ?? 0);
        $totalBalita  = $total_balita ?? 0;
        $persenStunting = $totalBalita > 0 ? round(($totalStunting / $totalBalita) * 100, 1) : 0;
        $persenNormal   = $totalBalita > 0 ? round((($total_normal ?? 0) / $totalBalita) * 100, 1) : 0;
        ?>
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="mb-0 fw-bold">Distribusi Status Gizi</h6>
            <span class="text-muted" style="font-size: 0.8rem;"><?= $totalBalita; ?> balita total</span>
        </div>
        <div class="progress" style="height: 24px; border-radius: 8px;">
            <div class="progress-bar bg-success" style="width: <?= $persenNormal; ?>%">
                Normal <?= $persenNormal; ?>%
            </div>
            <div class="progress-bar bg-warning" style="width: <?= $totalBalita > 0 ? round((($total_stunting ?? 0) / $totalBalita) * 100, 1) : 0; ?>%">
                Stunting
            </div>
            <div class="progress-bar bg-danger" style="width: <?= $totalBalita > 0 ? round((($stunting_berat ?? 0) / $totalBalita) * 100, 1) : 0; ?>%">
                Berat
            </div>
        </div>
        <div class="d-flex justify-content-between mt-2" style="font-size: 0.8rem;">
            <span class="text-success fw-semibold">✅ <?= $total_normal ?? 0; ?> Normal</span>
            <span class="text-warning fw-semibold">⚠️ <?= $total_stunting ?? 0; ?> Stunting</span>
            <span class="text-danger fw-semibold">🚨 <?= $stunting_berat ?? 0; ?> Stunting Berat</span>
        </div>
    </div>
</div>

<div class="text-center mt-4">
    <a href__="/dashboard/statistik" class="btn btn-outline-primary btn-sm">📊 Lihat Statistik Lengkap</a>
</div>

<?= $this->endSection(); ?>