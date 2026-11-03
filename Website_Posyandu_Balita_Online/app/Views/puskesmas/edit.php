<?= $this->extend('layout/main'); ?>

<?= $this->section('content'); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1 fw-bold">Edit Puskesmas</h3>
        <p class="text-muted mb-0">Perbarui data <?= esc($puskesmas['nama_puskesmas'] ?? ''); ?></p>
    </div>
    <a href="<?= site_url('puskesmas') ?>" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">

        <?= flash_block('error', 'alert-danger'); ?>

        <form action="<?= site_url('puskesmas/update/' . ((int) $puskesmas['id_puskesmas'])) ?>" method="post">
            <?= csrf_field(); ?>

            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Nama Puskesmas</label>
                    <input type="text" name="nama_puskesmas" class="form-control"
                           value="<?= esc(old('nama_puskesmas', $puskesmas['nama_puskesmas'] ?? ''), 'attr'); ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Kelurahan</label>
                    <input type="text" name="kelurahan" class="form-control"
                           value="<?= esc(old('kelurahan', $puskesmas['kelurahan'] ?? ''), 'attr'); ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Kecamatan</label>
                    <input type="text" name="kecamatan" class="form-control"
                           value="<?= esc(old('kecamatan', $puskesmas['kecamatan'] ?? ''), 'attr'); ?>">
                </div>

                <div class="col-md-2">
                    <label class="form-label">RW</label>
                    <input type="text" name="rw" class="form-control"
                           value="<?= esc(old('rw', $puskesmas['rw'] ?? ''), 'attr'); ?>">
                </div>

                <div class="col-12">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="2"><?= esc(old('alamat', $puskesmas['alamat'] ?? '')); ?></textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Latitude</label>
                    <input type="text" name="latitude" class="form-control"
                           value="<?= esc(old('latitude', $puskesmas['latitude'] ?? ''), 'attr'); ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Longitude</label>
                    <input type="text" name="longitude" class="form-control"
                           value="<?= esc(old('longitude', $puskesmas['longitude'] ?? ''), 'attr'); ?>">
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
                <a href="<?= site_url('puskesmas') ?>" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>

    </div>
</div>

<?= $this->endSection(); ?>
