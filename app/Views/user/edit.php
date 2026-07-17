<?= $this->extend('layout/main'); ?>

<?= $this->section('content'); ?>

<div class="row justify-content-center mt-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h4 class="mb-0 fw-bold">Edit User</h4>
            </div>
            <div class="card-body">
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger"><?= session()->getFlashdata('error'); ?></div>
                <?php endif; ?>

                <form action="/classuser/update/<?= $user['id_user']; ?>" method="post">
                    <?= csrf_field(); ?>

                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control"
                               value="<?= esc($user['nama'], 'attr'); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control"
                               value="<?= esc($user['email'], 'attr'); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">No. HP</label>
                        <input type="text" name="no_hp" class="form-control"
                               value="<?= esc($user['no_hp'] ?? '', 'attr'); ?>"
                               placeholder="08xxxxxxxxxx">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select">
                            <option value="admin"    <?= strtolower($user['role']) === 'admin' ? 'selected' : ''; ?>>Admin</option>
                            <option value="kader"    <?= strtolower($user['role']) === 'kader' ? 'selected' : ''; ?>>Kader</option>
                            <option value="orangtua" <?= strtolower($user['role']) === 'orangtua' ? 'selected' : ''; ?>>Orang Tua</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password Baru</label>
                        <input type="password" name="password" class="form-control"
                               placeholder="Kosongkan jika tidak ingin ganti password">
                        <small class="text-muted">Minimal 6 karakter.</small>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                        <a href__="/classuser" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>