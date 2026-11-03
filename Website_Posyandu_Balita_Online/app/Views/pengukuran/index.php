<?= $this->extend('layout/main'); ?>

<?= $this->section('content'); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1 fw-bold">Data Pengukuran</h3>
        <p class="text-muted mb-0">Riwayat pengukuran seluruh balita</p>
    </div>
    <!-- Search di pojok kanan -->
    <form method="get" action="<?= site_url('pengukuran') ?>" class="d-flex gap-2" style="width: 400px;">
        <input type="text" name="keyword" class="form-control"
               value="<?= esc($keyword ?? '', 'attr'); ?>"
               placeholder="Cari nama balita...">
        <select name="status" class="form-select" style="width: 150px;">
            <option value="">Semua Status</option>
            <option value="Normal"         <?= ($status_filter ?? '') === 'Normal' ? 'selected' : ''; ?>>Normal</option>
            <option value="Stunting"      <?= ($status_filter ?? '') === 'Stunting' ? 'selected' : ''; ?>>Stunting</option>
            <option value="Stunting Berat" <?= ($status_filter ?? '') === 'Stunting Berat' ? 'selected' : ''; ?>>Stunting Berat</option>
        </select>
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-search"></i>
        </button>
    </form>
</div>

<!-- Sort By -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <div class="d-flex align-items-center gap-2">
        <span class="text-muted small">Urutkan:</span>
        <select id="sortBy" class="form-select form-select-sm" style="width: auto;">
            <option value="tanggal_desc">Tanggal (Terbaru)</option>
            <option value="tanggal_asc">Tanggal (Terlama)</option>
            <option value="nama_asc">Nama (A-Z)</option>
            <option value="nama_desc">Nama (Z-A)</option>
            <option value="usia_asc">Usia (Terkecil)</option>
            <option value="usia_desc">Usia (Terbesar)</option>
        </select>
    </div>
    <a href="<?= site_url('pengukuran') ?>" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-counterclockwise"></i> Reset Filter
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle" id="tablePengukuran">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4" style="width: 50px;">No</th>
                        <th data-sort="nama">Nama Balita</th>
                        <th>JK</th>
                        <th data-sort="tanggal">Tgl Ukur</th>
                        <th data-sort="usia">Usia</th>
                        <th>Berat</th>
                        <th>Tinggi</th>
                        <th>Z-Score</th>
                        <th>Status</th>
                        <th class="pe-4 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (! empty($pengukuran)): ?>
                        <?php $no = 1; foreach ($pengukuran as $p): ?>
                            <tr>
                                <td class="ps-4"><?= $no++; ?></td>
                                <td class="fw-semibold"><?= esc($p['nama_balita']); ?></td>
                                <td><?= esc($p['jenis_kelamin']); ?></td>
                                <td><?= date('d/m/Y', strtotime($p['tanggal_ukur'])); ?></td>
                                <td><?= (int) $p['usia_bulan']; ?> bln</td>
                                <td><?= esc($p['berat_badan']); ?> kg</td>
                                <td><?= esc($p['tinggi_badan']); ?> cm</td>
                                <td class="fw-bold"><?= esc($p['z_score']); ?></td>
                                <td>
                                    <?php
                                    $statusClass = match ($p['status_gizi']) {
                                        'Normal'         => 'bg-success',
                                        'Stunting'       => 'bg-warning text-dark',
                                        'Stunting Berat' => 'bg-danger',
                                        default          => 'bg-secondary',
                                    };
                                    ?>
                                    <span class="badge <?= $statusClass; ?>">
                                        <?= esc($p['status_gizi'] ?? '-'); ?>
                                    </span>
                                </td>
                                <td class="pe-4 text-end">
                                    <a href="<?= site_url('pengukuran/grafik/' . ($p['id_balita'])) ?>"
                                       class="btn btn-outline-info btn-sm" title="Grafik">
                                        <i class="fas fa-chart-line"></i>
                                    </a>
                                    <?php if (session()->get('role') !== 'orangtua'): ?>
                                        <a href="<?= site_url('pengukuran/edit/' . ($p['id_pengukuran'])) ?>"
                                           class="btn btn-outline-warning btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="<?= site_url('pengukuran/hapus/' . ($p['id_pengukuran'])) ?>" method="post"
                                              class="d-inline"
                                              onsubmit="return confirm('Yakin hapus data pengukuran ini?');">
                                            <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">
                                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" class="text-center text-muted py-4">
                                Belum ada data pengukuran
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
// Client-side sort
document.getElementById('sortBy').addEventListener('change', function() {
    const sort = this.value;
    const tbody = document.querySelector('#tablePengukuran tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));

    rows.sort(function(a, b) {
        let valA, valB;
        if (sort.startsWith('tanggal')) {
            valA = new Date(a.children[3].textContent.split('/').reverse().join('-'));
            valB = new Date(b.children[3].textContent.split('/').reverse().join('-'));
        } else if (sort.startsWith('nama')) {
            valA = a.children[1].textContent.toLowerCase();
            valB = b.children[1].textContent.toLowerCase();
        } else if (sort.startsWith('usia')) {
            valA = parseInt(a.children[4].textContent);
            valB = parseInt(b.children[4].textContent);
        }
        if (valA < valB) return sort.endsWith('asc') ? -1 : 1;
        if (valA > valB) return sort.endsWith('asc') ? 1 : -1;
        return 0;
    });

    rows.forEach(function(row, i) {
        row.children[0].textContent = i + 1;
        tbody.appendChild(row);
    });
});
</script>

<?= $this->endSection(); ?>