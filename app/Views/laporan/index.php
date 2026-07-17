<?= $this->extend('layout/main'); ?>

<?= $this->section('content'); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1 fw-bold">Laporan Monitoring Stunting</h3>
        <p class="text-muted mb-0">Rekapitulasi data stunting balita</p>
    </div>
    <div class="d-flex gap-2">
        <a href__="/laporan/pdf" target="_blank" class="btn btn-danger btn-sm">
            <i class="fas fa-file-pdf"></i> Cetak PDF
        </a>
        <a href__="/laporan/excel" class="btn btn-success btn-sm">
            <i class="fas fa-file-excel"></i> Export Excel
        </a>
    </div>
</div>

<!-- Auto Recap Bulanan -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-bottom">
        <h5 class="mb-0 fw-bold">
            <i class="fas fa-calendar-alt text-primary"></i> Auto Recap Bulanan
        </h5>
    </div>
    <div class="card-body">
        <p class="text-muted mb-3">Pilih bulan untuk melihat rekapitulasi otomatis:</p>
        <div class="row g-2 mb-3">
            <div class="col-md-4">
                <select id="bulanSelect" class="form-select">
                    <option value="1">Januari</option>
                    <option value="2">Februari</option>
                    <option value="3">Maret</option>
                    <option value="4">April</option>
                    <option value="5">Mei</option>
                    <option value="6">Juni</option>
                    <option value="7">Juli</option>
                    <option value="8">Agustus</option>
                    <option value="9">September</option>
                    <option value="10">Oktober</option>
                    <option value="11">November</option>
                    <option value="12" selected>Desember</option>
                </select>
            </div>
            <div class="col-md-3">
                <select id="tahunSelect" class="form-select">
                    <?php for ($y = date('Y'); $y >= date('Y') - 5; $y--): ?>
                        <option value="<?= $y; ?>" <?= $y == date('Y') ? 'selected' : ''; ?>><?= $y; ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-md-3">
                <button id="btnRecap" class="btn btn-primary w-100">
                    <i class="fas fa-sync"></i> Generate Recap
                </button>
            </div>
            <div class="col-md-2">
                <button id="btnCetakBulanan" class="btn btn-outline-danger w-100">
                    <i class="fas fa-print"></i> Cetak
                </button>
            </div>
        </div>
        <div id="recapResult" class="mt-3"></div>
    </div>
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

<!-- Tabel Data -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom">
        <h5 class="mb-0 fw-bold">Data Lengkap</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4" style="width: 50px;">No</th>
                        <th>Nama Balita</th>
                        <th>JK</th>
                        <th>Tanggal Lahir</th>
                        <th>Z-Score</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (! empty($balita)): ?>
                        <?php $no = 1; foreach ($balita as $row): ?>
                            <tr>
                                <td class="ps-4"><?= $no++; ?></td>
                                <td class="fw-semibold"><?= esc($row['nama']); ?></td>
                                <td><?= esc($row['jenis_kelamin']); ?></td>
                                <td><?= esc($row['tanggal_lahir']); ?></td>
                                <td><?= esc($row['zscore']); ?></td>
                                <td>
                                    <?php
                                    $statusClass = match ($row['status']) {
                                        'Normal'         => 'bg-success',
                                        'Stunting'       => 'bg-warning text-dark',
                                        'Stunting Berat' => 'bg-danger',
                                        default          => 'bg-secondary',
                                    };
                                    ?>
                                    <span class="badge <?= $statusClass; ?>"><?= esc($row['status']); ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada data</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.getElementById('btnRecap').addEventListener('click', function() {
    let bulan = document.getElementById('bulanSelect').value;
    let tahun = document.getElementById('tahunSelect').value;
    let resultDiv = document.getElementById('recapResult');

    resultDiv.innerHTML = '<div class="text-center py-3"><div class="spinner-border text-primary"></div><p class="mt-2 text-muted">Menggenerate recap...</p></div>';

    fetch('/laporan/recap/' + tahun + '?bulan=' + bulan)
        .then(r => r.json())
        .then(data => {
            if (data.status === 'success') {
                resultDiv.innerHTML = `
                    <div class="alert alert-success">
                        <h6 class="fw-bold mb-2">Rekap Bulan ${data.bulan_nama} ${data.tahun}</h6>
                        <table class="table table-sm mb-0">
                            <tr><td>Total Balita Diukur</td><td class="fw-bold">${data.total_balita}</td></tr>
                            <tr><td>Normal</td><td class="fw-bold text-success">${data.total_normal}</td></tr>
                            <tr><td>Stunting</td><td class="fw-bold text-warning">${data.total_stunting}</td></tr>
                            <tr><td>Stunting Berat</td><td class="fw-bold text-danger">${data.stunting_berat}</td></tr>
                            <tr><td>Persentase Stunting</td><td class="fw-bold">${data.persentase}%</td></tr>
                        </table>
                    </div>`;
            } else {
                resultDiv.innerHTML = '<div class="alert alert-danger">Gagal generate recap</div>';
            }
        })
        .catch(() => {
            resultDiv.innerHTML = '<div class="alert alert-danger">Terjadi kesalahan</div>';
        });
});

document.getElementById('btnCetakBulanan').addEventListener('click', function() {
    let bulan = document.getElementById('bulanSelect').value;
    let tahun = document.getElementById('tahunSelect').value;
    window.open('/laporan/pdf?bulan=' + bulan + '&tahun=' + tahun, '_blank');
});
</script>

<?= $this->endSection(); ?>