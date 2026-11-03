<?= $this->extend('layout/main'); ?>

<?= $this->section('content'); ?>

<div class="row justify-content-center mt-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h4 class="mb-0 fw-bold">Edit Profil</h4>
            </div>
            <div class="card-body">

                <!-- Foto Preview -->
                <div class="text-center mb-4">
                    <img id="fotoPreview"
                         src="<?= !empty($foto) ? base_url($foto) : 'https://ui-avatars.com/api/?name=' . urlencode($nama ?? 'User'); ?>"
                         class="rounded-circle"
                         width="120" height="120"
                         style="object-fit: cover;"
                         onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($nama ?? 'User'); ?>'">
                    <div class="mt-2">
                        <label for="foto" class="btn btn-outline-primary btn-sm cursor-pointer mb-0">
                            <i class="fas fa-camera"></i> Ganti Foto
                        </label>
                    </div>
                </div>

                <form action="<?= site_url('auth/updateProfile') ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field(); ?>

                    <input type="file" name="foto" id="foto"
                           class="d-none"
                           accept="image/jpeg,image/png,image/jpg"
                           onchange="previewFoto(this)">

                    <?= flash_block('error', 'alert-danger'); ?>

                    <?= flash_block('success', 'alert-success'); ?>

                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control"
                               value="<?= isset($nama) ? esc($nama, 'attr') : ''; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control"
                               value="<?= isset($email) ? esc($email, 'attr') : ''; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">No. HP</label>
                        <input type="text" name="no_hp" class="form-control"
                               value="<?= isset($no_hp) ? esc($no_hp, 'attr') : ''; ?>"
                               placeholder="Contoh: 08123456789">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <input type="text" class="form-control"
                               value="<?= isset($role) ? esc($role, 'attr') : ''; ?>" readonly>
                    </div>

                    <div class="d-grid">
                        <button class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function previewFoto(input) {
    if (input.files && input.files[0]) {
        let reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('fotoPreview').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?= $this->endSection(); ?>