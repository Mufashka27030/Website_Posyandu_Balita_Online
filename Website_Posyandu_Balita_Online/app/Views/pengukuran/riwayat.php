<?= $this->extend('layout/main'); ?>

<?= $this->section('content'); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1 fw-bold">Riwayat Pengukuran</h3>
        <p class="text-muted mb-0">
            <?= isset($balita) ? esc($balita['nama_balita']) : 'Semua balita'; ?>
        </p>
    </div>
    <?php if (isset($balita)): ?>
    <a href="<?= site_url('pengukuran/grafik/' . ($balita['id_balita']) . '?from=riwayat') ?>" class="btn btn-outline-info btn-sm">
        <i class="bi bi-graph-up"></i> Lihat Grafik
    </a>
    <?php endif; ?>
</div>

<!-- Form Search -->
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-3">
        <form method="get" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small text-muted mb-1">Cari Tanggal</label>
                <input type="date" name="cari_tanggal" class="form-control"
                       value="<?= esc($cari_tanggal ?? '', 'attr'); ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label small text-muted mb-1">Filter Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="Normal"         <?= ($status_filter ?? '') === 'Normal' ? 'selected' : ''; ?>>Normal</option>
                    <option value="Stunting"      <?= ($status_filter ?? '') === 'Stunting' ? 'selected' : ''; ?>>Stunting</option>
                    <option value="Stunting Berat" <?= ($status_filter ?? '') === 'Stunting Berat' ? 'selected' : ''; ?>>Stunting Berat</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> Cari
                </button>
            </div>
            <?php if (isset($balita)): ?>
            <div class="col-md-2">
                <a href="<?= site_url('pengukuran/riwayat/' . ($balita['id_balita'])) ?>" class="btn btn-outline-secondary w-100">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                </a>
            </div>
            <?php endif; ?>
        </form>
    </div>
</div>

<?php
// Grouping: kelompokkan per balita (jika tidak ada $balita spesifik)
$grouped = [];
if (!empty($riwayat)) {
    if (isset($balita)) {
        // Single balita — tidak perlu grouping
        $grouped[esc($balita['nama_balita'])] = $riwayat;
    } else {
        // Multiple balita — group per nama
        foreach ($riwayat as $row) {
            $nama = $row['nama_balita'] ?? 'Tanpa Nama';
            $grouped[$nama][] = $row;
        }
    }
}
?>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <?php if (! empty($grouped)): ?>
            <?php $globalNo = 1; foreach ($grouped as $nama => $rows): ?>

                <?php if (! isset($balita)): ?>
                <!-- Header Group per Balita -->
                <div class="bg-light px-4 py-2 border-bottom fw-bold">
                    <i class="bi bi-person-fill text-primary"></i>
                    <?= esc($nama); ?>
                    <span class="badge bg-primary ms-2"><?= count($rows); ?> pengukuran</span>
                </div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4" style="width:50px;">No</th>
                                <th>Tanggal</th>
                                <th>Usia</th>
                                <th>BB</th>
                                <th>TB</th>
                                <th>Z-Score</th>
                                <th>Status</th>
                                <th class="pe-4 text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rows as $row): ?>
                            <tr>
                                <td class="ps-4"><?= $globalNo++; ?></td>
                                <td><?= date('d/m/Y', strtotime($row['tanggal_ukur'])); ?></td>
                                <td><?= (int)$row['usia_bulan']; ?> bln</td>
                                <td><?= esc($row['berat_badan']); ?> kg</td>
                                <td><?= esc($row['tinggi_badan']); ?> cm</td>
                                <td class="fw-bold"><?= esc($row['z_score']); ?></td>
                                <td>
                                    <?php
                                    $statusClass = match ($row['status_gizi'] ?? '') {
                                        'Normal'         => 'bg-success',
                                        'Stunting'       => 'bg-warning text-dark',
                                        'Stunting Berat' => 'bg-danger',
                                        default          => 'bg-secondary',
                                    };
                                    ?>
                                    <span class="badge <?= $statusClass; ?>">
                                        <?= esc($row['status_gizi'] ?? '-'); ?>
                                    </span>
                                </td>
                                <td class="pe-4 text-end">
                                    <?php if (session()->get('role') !== 'orangtua'): ?>
                                    <a href="<?= site_url('pengukuran/edit/' . ($row['id_pengukuran'])) ?>" class="btn btn-outline-warning btn-sm">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <?php endif; ?>
                                    <a href="<?= site_url('pengukuran/grafik/' . ($row['id_balita']) . '?from=riwayat') ?>" class="btn btn-outline-info btn-sm">
                                        <i class="bi bi-graph-up"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            <?php endforeach; ?>
        <?php else: ?>
            <div class="text-center text-muted py-5">
                <i class="bi bi-inbox fs-1"></i>
                <p class="mt-2">Belum ada data pengukuran</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection(); ?>