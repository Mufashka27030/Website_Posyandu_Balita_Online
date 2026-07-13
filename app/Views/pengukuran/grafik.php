<?= $this->extend('layout/main'); ?>

<?= $this->section('content'); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1 fw-bold">📈 Grafik Pertumbuhan Balita</h3>
        <p class="text-muted mb-0"><?= esc($balita['nama_balita'] ?? 'Data Tidak Ada'); ?> — Monitoring Pertumbuhan</p>
    </div>
    <a href__="/balita/detail/<?= $balita['id_balita'] ?? ''; ?>" class="btn btn-outline-secondary btn-sm">← Kembali</a>
</div>

<?php if (isset($alert) && $alert !== ''): ?>
<div class="alert alert-danger d-flex align-items-center" role="alert">
    <span class="me-2">⚠️</span>
    <span><?= esc($alert); ?></span>
</div>
<?php endif; ?>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between">
        <h5 class="mb-0 fw-bold">📏 Grafik Tinggi Badan</h5>
        <small class="text-muted">cm vs usia (bulan)</small>
    </div>
    <div class="card-body">
        <canvas id="grafikTinggi" style="max-height: 350px;"></canvas>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between">
        <h5 class="mb-0 fw-bold">⚖️ Grafik Berat Badan</h5>
        <small class="text-muted">kg vs usia (bulan)</small>
    </div>
    <div class="card-body">
        <canvas id="grafikBerat" style="max-height: 350px;"></canvas>
    </div>
</div>

<!-- Legend WHO -->
<div class="card shadow-sm border-0">
    <div class="card-header bg-white border-bottom">
        <h6 class="mb-0 fw-bold">Keterangan Garis Standar WHO</h6>
    </div>
    <div class="card-body">
        <div class="row g-2" style="font-size: 0.85rem;">
            <div class="col-md-3 col-6">
                <span style="display:inline-block;width:20px;height:3px;background:#3b82f6;vertical-align:middle;margin-right:6px;border-radius:2px;"></span>
                <strong>Tinggi/Berat Balita</strong>
            </div>
            <div class="col-md-3 col-6">
                <span style="display:inline-block;width:20px;height:2px;background:#16a34a;vertical-align:middle;margin-right:6px;"></span>
                Median (Normal)
            </div>
            <div class="col-md-3 col-6">
                <span style="display:inline-block;width:20px;height:2px;background:#eab308;vertical-align:middle;margin-right:6px;border-style:dashed;"></span>
                -2 SD (Stunting)
            </div>
            <div class="col-md-3 col-6">
                <span style="display:inline-block;width:20px;height:2px;background:#dc2626;vertical-align:middle;margin-right:6px;border-style:dashed;"></span>
                -3 SD (Stunting Berat)
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const labels    = <?= isset($labels) ? $labels : '[]'; ?>;
const dataTinggi = <?= isset($tinggi) ? $tinggi : '[]'; ?>;
const dataBerat  = <?= isset($berat) ? $berat : '[]'; ?>;
const whoMedian  = <?= isset($whoMedian) ? $whoMedian : '[]'; ?>;
const whoMinus2  = <?= isset($whoMinus2) ? $whoMinus2 : '[]'; ?>;
const whoMinus3  = <?= isset($whoMinus3) ? $whoMinus3 : '[]'; ?>;

const commonOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: { display: true, position: 'top' },
        tooltip: { mode: 'index', intersect: false }
    },
    scales: {
        x: { title: { display: true, text: 'Usia (bulan)' } },
    }
};

// GRAFIK TINGGI BADAN
new Chart(document.getElementById('grafikTinggi'), {
    type: 'line',
    data: {
        labels: labels,
        datasets: [
            {
                label: 'Tinggi Badan Balita',
                data: dataTinggi,
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59,130,246,0.1)',
                borderWidth: 3,
                tension: 0.3,
                fill: true,
                pointRadius: 5,
                pointBackgroundColor: '#3b82f6'
            },
            {
                label: 'Median WHO',
                data: whoMedian,
                borderColor: '#16a34a',
                borderWidth: 2,
                borderDash: [],
                pointRadius: 0,
                fill: false
            },
            {
                label: '-2 SD (Stunting)',
                data: whoMinus2,
                borderColor: '#eab308',
                borderWidth: 2,
                borderDash: [6, 4],
                pointRadius: 0,
                fill: false
            },
            {
                label: '-3 SD (Stunting Berat)',
                data: whoMinus3,
                borderColor: '#dc2626',
                borderWidth: 2,
                borderDash: [6, 4],
                pointRadius: 0,
                fill: false
            }
        ]
    },
    options: { ...commonOptions, scales: { ...commonOptions.scales, y: { title: { display: true, text: 'Tinggi Badan (cm)' } } } }
});

// GRAFIK BERAT BADAN
new Chart(document.getElementById('grafikBerat'), {
    type: 'line',
    data: {
        labels: labels,
        datasets: [
            {
                label: 'Berat Badan Balita',
                data: dataBerat,
                borderColor: '#8b5cf6',
                backgroundColor: 'rgba(139,92,246,0.1)',
                borderWidth: 3,
                tension: 0.3,
                fill: true,
                pointRadius: 5,
                pointBackgroundColor: '#8b5cf6'
            }
        ]
    },
    options: { ...commonOptions, scales: { ...commonOptions.scales, y: { title: { display: true, text: 'Berat Badan (kg)' } } } }
});
</script>

<?= $this->endSection(); ?>