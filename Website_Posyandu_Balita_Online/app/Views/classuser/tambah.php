<?= $this->extend('layout/main'); ?>

<?= $this->section('content'); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1 fw-bold">Tambah User</h3>
        <p class="text-muted mb-0">Buat akun baru untuk admin, kader, atau orang tua</p>
    </div>
    <a href="<?= site_url('classuser') ?>" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">

        <?= flash_block('error', 'alert-danger'); ?>

        <form action="<?= site_url('classuser/simpan') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field(); ?>

            <div class="row g-3">
                <!-- Foto Profil -->
                <div class="col-12">
                    <label class="form-label">Foto Profil</label>
                    <input type="file" name="foto" class="form-control" accept="image/jpeg,image/png">
                    <div class="form-text">Format: JPG, PNG. Maks 2MB. Opsional.</div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control"
                           value="<?= esc(old('nama'), 'attr'); ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control"
                           value="<?= esc(old('email'), 'attr'); ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Role</label>
                    <?php $sessionRole = strtolower((string) session()->get('role')); ?>
                    <select name="role" class="form-select" required>
                        <option value="">-- Pilih Role --</option>
                        <?php if ($sessionRole === 'admin'): ?>
                            <option value="admin" <?= old('role') === 'admin' ? 'selected' : ''; ?>>Admin</option>
                            <option value="kader" <?= old('role') === 'kader' ? 'selected' : ''; ?>>Kader</option>
                        <?php endif; ?>
                        <option value="orangtua" <?= old('role') === 'orangtua' ? 'selected' : ''; ?>>Orang Tua</option>
                    </select>
                    <?php if ($sessionRole !== 'admin'): ?>
                        <div class="form-text">Kader hanya bisa membuat akun Orang Tua.</div>
                    <?php endif; ?>
                </div>

                <div class="col-md-6">
                    <label class="form-label">No. HP</label>
                    <input type="text" name="no_hp" class="form-control"
                           placeholder="08xxxxxxxxxx" value="<?= esc(old('no_hp'), 'attr'); ?>">
                </div>

                <div class="col-12">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="2"><?= esc(old('alamat')); ?></textarea>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan
                </button>
                <a href="<?= site_url('classuser') ?>" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>

    </div>
</div>

<?= $this->endSection(); ?>