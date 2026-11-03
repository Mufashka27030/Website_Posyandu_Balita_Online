<?= $this->extend('layout/main'); ?>

<?= $this->section('content'); ?>

<div class="mb-4">
    <h3 class="mb-1 fw-bold">Dashboard Statistik Stunting</h3>
    <p class="text-muted mb-0">Ringkasan status gizi seluruh balita</p>
</div>

<?php if (isset($warning) && $warning > 0): ?>
<div class="alert alert-danger border-0 d-flex align-items-start gap-2">
    <i class="bi bi-exclamation-triangle-fill mt-1"></i>
    <div><strong>Ada <?= (int)$warning; ?> balita stunting berat!</strong> Segera lakukan intervensi.</div>
</div>
<?php endif; ?>

<?php if (isset($persentase) && $persentase >= 20): ?>
<div class="alert alert-warning border-0 d-flex align-items-start gap-2">
    <i class="bi bi-exclamation-triangle-fill mt-1"></i>
    <div><strong>WARNING:</strong> Tingkat stunting tinggi (<?= $persentase; ?>%)!</div>
</div>
<?php endif; ?>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-2">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #6366f1 !important;">
            <div class="card-body text-center">
                <i class="bi bi-people text-primary mb-2" style="font-size:1.5rem"></i>
                <div class="fs-4 fw-bold" style="color:#6366f1;"><?= $total_balita ?? 0; ?></div>
                <div class="text-muted small">Total Balita</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #16a34a !important;">
            <div class="card-body text-center">
                <i class="bi bi-check-circle text-success mb-2" style="font-size:1.5rem"></i>
                <div class="fs-4 fw-bold text-success"><?= $total_normal ?? 0; ?></div>
                <div class="text-muted small">Normal</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #eab308 !important;">
            <div class="card-body text-center">
                <i class="bi bi-exclamation-triangle text-warning mb-2" style="font-size:1.5rem"></i>
                <div class="fs-4 fw-bold text-warning"><?= $total_stunting ?? 0; ?></div>
                <div class="text-muted small">Stunting</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #dc2626 !important;">
            <div class="card-body text-center">
                <i class="bi bi-x-circle text-danger mb-2" style="font-size:1.5rem"></i>
                <div class="fs-4 fw-bold text-danger"><?= $stunting_berat ?? 0; ?></div>
                <div class="text-muted small">Stunting Berat</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #64748b !important;">
            <div class="card-body text-center">
                <i class="bi bi-question-circle text-secondary mb-2" style="font-size:1.5rem"></i>
                <div class="fs-4 fw-bold" style="color:#64748b;"><?= $belum_diukur ?? 0; ?></div>
                <div class="text-muted small">Belum Diukur (<?= $persen_belum ?? 0; ?>%)</div>
            </div>
        </div>
    </div>
</div>

<!-- Grafik -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-bottom">
        <h5 class="mb-0 fw-bold"><i class="bi bi-bar-chart text-primary me-1"></i>Grafik Statistik</h5>
    </div>
    <div class="card-body">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <canvas id="grafikStatistik" height="100"></canvas>
        <script>
        const ctx = document.getElementById('grafikStatistik');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Normal', 'Stunting', 'Stunting Berat', 'Belum Diukur'],
                datasets: [{
                    label: 'Jumlah Balita',
                    data: [
                        <?= $total_normal ?? 0; ?>,
                        <?= $total_stunting ?? 0; ?>,
                        <?= $stunting_berat ?? 0; ?>,
                        <?= $belum_diukur ?? 0; ?>
                    ],
                    backgroundColor: ['#16a34a', '#eab308', '#dc2626', '#64748b'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
            }
        });
        </script>
    </div>
</div>

<!-- Persentase -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="mb-0 fw-bold">Persentase Stunting</h6>
            <span class="badge bg-<?= ($persentase ?? 0) > 20 ? 'danger' : 'success'; ?>">
                <?= $persentase ?? 0; ?>%
            </span>
        </div>
        <div class="progress" style="height: 20px; border-radius: 8px;">
            <div class="progress-bar bg-<?= ($persentase ?? 0) > 20 ? 'danger' : 'success'; ?>"
                 style="width: <?= min($persentase ?? 0, 100); ?>%"></div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>