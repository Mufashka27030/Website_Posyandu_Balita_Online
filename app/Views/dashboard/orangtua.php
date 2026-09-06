<?= $this->extend('layout/main'); ?>

<?= $this->section('content'); ?>

<!-- Welcome Hero — Khusus Orang Tua -->
<div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);">
    <div class="card-body text-white p-4">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h4 class="mb-1 fw-bold">
                    <i class="bi bi-emoji-smile me-1"></i>
                    Halo, <?= esc(session()->get('nama') ?? 'Orang Tua'); ?>!
                </h4>
                <p class="mb-0 opacity-75">Pantau tumbuh kembang si kecil di sini 💚</p>
            </div>
            <div class="text-end">
                <div class="opacity-75 small">Hari Ini</div>
                <div class="fw-bold fs-5"><?= date('d M Y'); ?></div>
            </div>
        </div>
    </div>
</div>

<!-- Stat Cards — Khusus Orang Tua -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                        <i class="bi bi-heart-fill text-primary fs-5"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Anak Saya</div>
                        <div class="fs-4 fw-bold"><?= $total_balita ?? 0; ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                        <i class="bi bi-check-circle-fill text-success fs-5"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Sehat</div>
                        <div class="fs-4 fw-bold text-success"><?= $total_normal ?? 0; ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                        <i class="bi bi-exclamation-triangle-fill text-warning fs-5"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Perlu Perhatian</div>
                        <div class="fs-4 fw-bold text-warning"><?= $total_stunting ?? 0; ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-danger bg-opacity-10 p-3 me-3">
                        <i class="bi bi-heart-pulse-fill text-danger fs-5"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Butuh Penanganan</div>
                        <div class="fs-4 fw-bold text-danger"><?= $stunting_berat ?? 0; ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Daftar Balita -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom">
        <h5 class="mb-0 fw-bold">Balita Saya</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Nama Balita</th>
                        <th>Jenis Kelamin</th>
                        <th>Tanggal Lahir</th>
                        <th>Status Gizi</th>
                        <th>Z-Score</th>
                        <th>Pengukuran Terakhir</th>
                        <th class="pe-4 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (! empty($balita_list)): ?>
                        <?php foreach ($balita_list as $b): ?>
                            <tr>
                                <td class="ps-4 fw-semibold"><?= esc($b['nama_balita']); ?></td>
                                <td><?= $b['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan'; ?></td>
                                <td><?= date('d/m/Y', strtotime($b['tanggal_lahir'])); ?></td>
                                <td>
                                    <?php
                                    $statusClass = match ($b['status_gizi'] ?? '') {
                                        'Normal'         => 'bg-success',
                                        'Stunting'       => 'bg-warning text-dark',
                                        'Stunting Berat' => 'bg-danger',
                                        default          => 'bg-secondary',
                                    };
                                    $statusText = $b['status_gizi'] ?? 'Belum diukur';
                                    ?>
                                    <span class="badge <?= $statusClass; ?>"><?= esc($statusText); ?></span>
                                </td>
                                <td class="fw-bold"><?= $b['z_score'] ?? '-'; ?></td>
                                <td>
                                    <?= !empty($b['tanggal_ukur']) ? date('d/m/Y', strtotime($b['tanggal_ukur'])) : '-'; ?>
                                </td>
                                <td class="pe-4 text-end">
                                    <a href="/balita/detail/<?= $b['id_balita']; ?>"
                                       class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                Belum ada data balita terdaftar
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>