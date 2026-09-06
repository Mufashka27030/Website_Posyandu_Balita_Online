<?= $this->extend('layout/main'); ?>

<?= $this->section('content'); ?>

<div class="alert alert-primary shadow-sm mb-4 border-0">
    <h4 class="mb-0 fw-bold">Selamat Datang, <?= esc(session()->get('nama')); ?> 👋</h4>
    <p class="mb-0 mt-1">Anda login sebagai <b><?= ucfirst(session()->get('role')); ?></b></p>
</div>

<h3 class="mb-4 fw-bold">Dashboard Monitoring Stunting</h3>

<div class="row g-3 mb-4">
    <!-- Total Balita -->
    <div class="col-md-2 col-sm-6">
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
    <div class="col-md-2 col-sm-6">
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
    <div class="col-md-2 col-sm-6">
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

    <!-- Belum Diukur -->
    <div class="col-md-3 col-sm-6">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #64748b !important;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.7rem;">Belum Diukur</small>
                        <h2 class="fw-bold mb-0 mt-1" style="color: #64748b;"><?= $belum_diukur ?? 0; ?></h2>
                        <small class="text-muted" style="font-size: 0.7rem;"><?= $persen_belum ?? 0; ?>% dari total</small>
                    </div>
                    <div class="rounded-3 d-flex align-items-center justify-content-center"
                         style="width: 48px; height: 48px; background: #f1f5f9;">
                        <span style="font-size: 1.4rem;">❓</span>
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
        $totalBalita      = $total_balita ?? 0;
        $persenNormal     = $totalBalita > 0 ? round((($total_normal ?? 0) / $totalBalita) * 100, 1) : 0;
        $persenStunting   = $totalBalita > 0 ? round((($total_stunting ?? 0) / $totalBalita) * 100, 1) : 0;
        $persenStuntingB  = $totalBalita > 0 ? round((($stunting_berat ?? 0) / $totalBalita) * 100, 1) : 0;
        $persenBelum      = $persen_belum ?? 0;
        ?>
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="mb-0 fw-bold">Distribusi Status Gizi</h6>
            <span class="text-muted" style="font-size: 0.8rem;"><?= $totalBalita; ?> balita total</span>
        </div>
        <div class="progress" style="height: 24px; border-radius: 8px;">
            <div class="progress-bar bg-success" style="width: <?= $persenNormal; ?>%">
                Normal <?= $persenNormal; ?>%
            </div>
            <div class="progress-bar bg-warning" style="width: <?= $persenStunting; ?>%">
                Stunting <?= $persenStunting; ?>%
            </div>
            <div class="progress-bar bg-danger" style="width: <?= $persenStuntingB; ?>%">
                Berat <?= $persenStuntingB; ?>%
            </div>
            <div class="progress-bar bg-secondary" style="width: <?= $persenBelum; ?>%">
                Belum <?= $persenBelum; ?>%
            </div>
        </div>
        <div class="d-flex justify-content-between mt-2 flex-wrap" style="font-size: 0.8rem;">
            <span class="text-success fw-semibold">✅ <?= $total_normal ?? 0; ?> Normal (<?= $persenNormal; ?>%)</span>
            <span class="text-warning fw-semibold">⚠️ <?= $total_stunting ?? 0; ?> Stunting (<?= $persenStunting; ?>%)</span>
            <span class="text-danger fw-semibold">🚨 <?= $stunting_berat ?? 0; ?> Stunting Berat (<?= $persenStuntingB; ?>%)</span>
            <span class="text-secondary fw-semibold">❓ <?= $belum_diukur ?? 0; ?> Belum Diukur (<?= $persenBelum; ?>%)</span>
        </div>
    </div>
</div>

<div class="text-center mt-4">
    <a href="/dashboard/statistik" class="btn btn-outline-primary btn-sm">📊 Lihat Statistik Lengkap</a>
</div>

<?= $this->endSection(); ?>