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

<!-- Search -->
<div class="mb-3">
    <form method="get" action="/classuser" class="d-flex gap-2">
        <input type="text" name="keyword" class="form-control"
               placeholder="Cari nama atau email..."
               value="<?= esc($keyword ?? '', 'attr'); ?>">
        <button type="submit" class="btn btn-outline-primary">
            <i class="fas fa-search"></i> Cari
        </button>
        <?php if (! empty($keyword)): ?>
            <a href__="/classuser" class="btn btn-outline-secondary">Reset</a>
        <?php endif; ?>
    </form>
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