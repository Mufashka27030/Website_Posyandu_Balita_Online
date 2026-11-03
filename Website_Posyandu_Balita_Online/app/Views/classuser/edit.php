<?= $this->extend('layout/main'); ?>

<?= $this->section('content'); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1 fw-bold">Edit User</h3>
        <p class="text-muted mb-0">Perbarui data akun <?= esc($user['nama'] ?? ''); ?></p>
    </div>
    <a href="<?= site_url('classuser') ?>" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">

        <?= flash_block('error', 'alert-danger'); ?>

        <?php
            $currentRole = strtolower((string) ($user['role'] ?? ''));
            $sessionRole = strtolower((string) session()->get('role'));
        ?>

        <form action="<?= site_url('classuser/update/' . ((int) $user['id_user'])) ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field(); ?>

            <div class="row g-3">
                <!-- Foto Profil -->
                <div class="col-12">
                    <label class="form-label">Foto Profil</label>
                    <?php if (! empty($user['foto'])): ?>
                        <div class="mb-2">
                            <img src="/<?= esc($user['foto'], 'attr'); ?>" alt="Foto Profil"
                                 class="rounded-circle border"
                                 style="width: 80px; height: 80px; object-fit: cover;">
                            <small class="text-muted ms-2">Foto saat ini</small>
                        </div>
                    <?php endif; ?>
                    <input type="file" name="foto" class="form-control" accept="image/jpeg,image/png">
                    <div class="form-text">Kosongkan jika tidak diubah. Format: JPG, PNG. Maks 2MB.</div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control"
                           value="<?= esc(old('nama', $user['nama'] ?? ''), 'attr'); ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control"
                           value="<?= esc(old('email', $user['email'] ?? ''), 'attr'); ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Password Baru</label>
                    <input type="password" name="password" class="form-control"
                           placeholder="Kosongkan jika tidak diubah">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select" required>
                        <?php if ($sessionRole === 'admin'): ?>
                            <option value="admin" <?= $currentRole === 'admin' ? 'selected' : ''; ?>>Admin</option>
                            <option value="kader" <?= $currentRole === 'kader' ? 'selected' : ''; ?>>Kader</option>
                            <option value="orangtua" <?= $currentRole === 'orangtua' ? 'selected' : ''; ?>>Orang Tua</option>
                        <?php else: ?>
                            <?php if ($currentRole === 'kader'): ?>
                                <option value="kader" selected>Kader</option>
                            <?php endif; ?>
                            <option value="orangtua" <?= $currentRole === 'orangtua' ? 'selected' : ''; ?>>Orang Tua</option>
                        <?php endif; ?>
                    </select>
                    <?php if ($sessionRole !== 'admin'): ?>
                        <div class="form-text">Kader hanya bisa mengatur role Kader/Orang Tua.</div>
                    <?php endif; ?>
                </div>

                <div class="col-md-6">
                    <label class="form-label">No. HP</label>
                    <input type="text" name="no_hp" class="form-control"
                           value="<?= esc(old('no_hp', $user['no_hp'] ?? ''), 'attr'); ?>">
                </div>

                <div class="col-12">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="2"><?= esc(old('alamat', $user['alamat'] ?? '')); ?></textarea>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
                <a href="<?= site_url('classuser') ?>" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>

    </div>
</div>

<?= $this->endSection(); ?>