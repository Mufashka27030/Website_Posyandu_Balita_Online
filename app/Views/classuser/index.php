<?= $this->extend('layout/main'); ?>

<?= $this->section('content'); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1 fw-bold">Manajemen User</h3>
        <p class="text-muted mb-0">Daftar seluruh pengguna terdaftar</p>
    </div>
    <a href__="/classuser/tambah" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah User
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4" style="width: 50px;">No</th>
                        <th>Foto</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>No. HP</th>
                        <th>Role</th>
                        <th class="text-end pe-4" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (! empty($users)): ?>
                        <?php $no = 1; foreach ($users as $u): ?>
                            <tr>
                                <td class="ps-4"><?= $no++; ?></td>
                                <td>
                                    <img src="<?= !empty($u['foto']) ? base_url($u['foto']) : 'https://ui-avatars.com/api/?name=' . urlencode($u['nama']); ?>"
                                         class="rounded-circle" width="40" height="40"
                                         style="object-fit: cover;"
                                         onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($u['nama']); ?>'">
                                </td>
                                <td class="fw-semibold"><?= esc($u['nama']); ?></td>
                                <td><?= esc($u['email']); ?></td>
                                <td><?= esc($u['no_hp'] ?? '-'); ?></td>
                                <td>
                                    <?php
                                    $roleClass = match (strtolower($u['role'])) {
                                        'admin'    => 'bg-danger',
                                        'kader'    => 'bg-warning text-dark',
                                        'orangtua' => 'bg-primary',
                                        default    => 'bg-secondary',
                                    };
                                    ?>
                                    <span class="badge <?= $roleClass; ?>">
                                        <?= ucfirst(esc($u['role'])); ?>
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <a href__="/classuser/edit/<?= $u['id_user']; ?>"
                                       class="btn btn-outline-warning btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                Belum ada data user
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
app/Views/dashboard/orangtua.php (Issue #7)
<?= $this->extend('layout/main'); ?>

<?= $this->section('content'); ?>

<div class="alert alert-primary shadow-sm mb-4 border-0">
    <h4 class="mb-0 fw-bold">Selamat Datang, <?= esc(session()->get('nama')); ?> 👋</h4>
    <p class="mb-0 mt-1">Dashboard Orang Tua — Pantau tumbuh kembang balita Anda</p>
</div>

<!-- Statistik Ringkas -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #6366f1 !important;">
            <div class="card-body">
                <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.7rem;">Jumlah Balita</small>
                <h2 class="fw-bold mb-0 mt-1" style="color: #6366f1;"><?= $total_balita ?? 0; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #16a34a !important;">
            <div class="card-body">
                <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.7rem;">Status Normal</small>
                <h2 class="fw-bold mb-0 mt-1 text-success"><?= $total_normal ?? 0; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #dc2626 !important;">
            <div class="card-body">
                <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.7rem;">Perlu Perhatian</small>
                <h2 class="fw-bold mb-0 mt-1 text-danger"><?= ($total_stunting ?? 0) + ($stunting_berat ?? 0); ?></h2>
            </div>
        </div>
    </div>
</div>

<!-- Daftar Balita Saya -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">Balita Saya</h5>
        <a href__="/balita" class="btn btn-outline-primary btn-sm">Lihat Semua</a>
    </div>
    <div class="card-body">
        <?php if (! empty($balita_list)): ?>
            <div class="row g-3">
                <?php foreach ($balita_list as $b): ?>
                    <div class="col-md-6">
                        <div class="card border shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="fw-bold mb-0"><?= esc($b['nama_balita']); ?></h6>
                                    <?php
                                    $statusClass = match ($b['status_gizi'] ?? '') {
                                        'Normal'          => 'bg-success',
                                        'Stunting'        => 'bg-warning text-dark',
                                        'Stunting Berat'  => 'bg-danger',
                                        default           => 'bg-secondary',
                                    };
                                    ?>
                                    <span class="badge <?= $statusClass; ?>">
                                        <?= esc($b['status_gizi'] ?? 'Belum Diukur'); ?>
                                    </span>
                                </div>
                                <p class="mb-1 text-muted" style="font-size: 0.85rem;">
                                    <?= $b['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan'; ?> •
                                    <?= date('d M Y', strtotime($b['tanggal_lahir'])); ?>
                                </p>
                                <?php if (isset($b['z_score'])): ?>
                                    <p class="mb-2 text-muted" style="font-size: 0.85rem;">
                                        Z-Score: <b><?= esc($b['z_score']); ?></b>
                                    </p>
                                <?php endif; ?>
                                <div class="d-flex gap-1 flex-wrap">
                                    <a href__="/balita/detail/<?= $b['id_balita']; ?>"
                                       class="btn btn-outline-primary btn-sm">Detail</a>
                                    <a href__="/pengukuran/riwayat/<?= $b['id_balita']; ?>"
                                       class="btn btn-outline-success btn-sm">Riwayat</a>
                                    <a href__="/pengukuran/grafik/<?= $b['id_balita']; ?>"
                                       class="btn btn-outline-info btn-sm">Grafik</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center text-muted py-4">
                <p class="mb-0">Belum ada data balita terdaftar untuk akun Anda.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Quick Actions -->
<div class="row g-3">
    <div class="col-md-4">
        <a href__="/konsultasi" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100 text-center">
                <div class="card-body py-4">
                    <span style="font-size: 2rem;">💬</span>
                    <h6 class="fw-bold mt-2 mb-0">Konsultasi</h6>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href__="/dashboard/statistik-orangtua" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100 text-center">
                <div class="card-body py-4">
                    <span style="font-size: 2rem;">📊</span>
                    <h6 class="fw-bold mt-2 mb-0">Statistik</h6>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href__="/profil" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100 text-center">
                <div class="card-body py-4">
                    <span style="font-size: 2rem;">👤</span>
                    <h6 class="fw-bold mt-2 mb-0">Profil Saya</h6>
                </div>
            </div>
        </a>
    </div>
</div>

<?= $this->endSection(); ?>