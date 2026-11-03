<?= $this->extend('layout/main'); ?>

<?= $this->section('content'); ?>

<div class="row justify-content-center mt-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h4 class="mb-0 fw-bold">
                    <i class="bi bi-pencil-square text-warning me-2"></i>Edit Data Pengukuran
                </h4>
            </div>
            <div class="card-body">

                <?= flash_block('error', 'alert-danger'); ?>

                <?php if (isset($pengukuran)): ?>

                <form action="<?= site_url('pengukuran/update/' . ($pengukuran['id_pengukuran'])) ?>" method="post">
                    <?= csrf_field(); ?>

                    <div class="mb-3">
                        <label class="form-label">Tanggal Pengukuran <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_ukur" class="form-control"
                               value="<?= esc($pengukuran['tanggal_ukur'], 'attr'); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Usia Saat Pengukuran (Bulan) <span class="text-danger">*</span></label>
                        <input type="number" name="usia_bulan" class="form-control" min="0" max="60"
                               value="<?= (int)$pengukuran['usia_bulan']; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Berat Badan (Kg) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="berat_badan" class="form-control"
                               value="<?= esc($pengukuran['berat_badan'], 'attr'); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tinggi Badan (Cm) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="tinggi_badan" class="form-control"
                               value="<?= esc($pengukuran['tinggi_badan'], 'attr'); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea name="catatan" class="form-control" rows="3"
                                  placeholder="Catatan pengukuran (opsional)"><?= esc($pengukuran['catatan'] ?? old('catatan', '')); ?></textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-save"></i> Simpan Perubahan
                        </button>
                        <a href="<?= site_url('pengukuran/riwayat/' . ($pengukuran['id_balita'])) ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>

                <?php else: ?>
                    <div class="alert alert-danger">Data pengukuran tidak ditemukan</div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>