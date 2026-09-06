<?= $this->extend('layout/main'); ?>

<?= $this->section('content'); ?>

<div class="row justify-content-center mt-4">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h4 class="mb-0 fw-bold">
                    <i class="bi bi-clipboard2-pulse text-primary me-2"></i>Input Pengukuran Balita
                </h4>
            </div>
            <div class="card-body">

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger"><?= session()->getFlashdata('error'); ?></div>
                <?php endif; ?>

                <?php if (isset($balita)): ?>
                <form action="/pengukuran/simpan" method="post" id="formPengukuran">
                    <?= csrf_field(); ?>

                    <input type="hidden" name="id_balita" value="<?= esc(old('id_balita', $balita['id_balita']), 'attr'); ?>">

                    <!-- Info Balita (read-only) -->
                    <div class="mb-3">
                        <label class="form-label">Nama Balita</label>
                        <input type="text" class="form-control bg-light" value="<?= esc($balita['nama_balita'], 'attr'); ?>" readonly>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Jenis Kelamin</label>
                                <input type="text" class="form-control bg-light" value="<?= $balita['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan'; ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tanggal Lahir</label>
                                <input type="date" class="form-control bg-light"
                                       value="<?= esc(old('tanggal_lahir', $balita['tanggal_lahir']), 'attr'); ?>"
                                       readonly>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal Pengukuran <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_ukur" id="tanggal_ukur"
                               class="form-control"
                               value="<?= esc(old('tanggal_ukur', date('Y-m-d')), 'attr'); ?>"
                               data-tgl-lahir="<?= esc($balita['tanggal_lahir'], 'attr'); ?>"
                               required>
                    </div>

                    <!-- USIA -->
                    <div class="mb-3">
                        <label class="form-label">Usia Balita (Bulan) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" name="usia_bulan" id="usia_bulan"
                                   class="form-control"
                                   value="<?= esc(old('usia_bulan', '0'), 'attr'); ?>"
                                   min="0" max="60" required>
                            <span class="input-group-text">Bulan</span>
                        </div>
                        <small class="text-muted">
                            <i class="bi bi-info-circle"></i> Usia otomatis dihitung dari tanggal lahir & tanggal ukur.
                        </small>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Berat Badan (Kg) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="berat_badan" id="bb"
                                       class="form-control"
                                       value="<?= esc(old('berat_badan'), 'attr'); ?>"
                                       required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tinggi Badan (Cm) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="tinggi_badan" id="tb"
                                       class="form-control"
                                       value="<?= esc(old('tinggi_badan'), 'attr'); ?>"
                                       required>
                            </div>
                        </div>
                    </div>

                    <!-- Preview -->
                    <div class="alert alert-light border">
                        <h6 class="fw-bold mb-2"><i class="bi bi-eye"></i> Preview Pengukuran</h6>
                        <div class="row g-2 small">
                            <div class="col-4">
                                <span class="text-muted">Usia:</span>
                                <strong id="previewUsia">-</strong> bln
                            </div>
                            <div class="col-4">
                                <span class="text-muted">BB:</span>
                                <strong id="previewBB">-</strong> kg
                            </div>
                            <div class="col-4">
                                <span class="text-muted">TB:</span>
                                <strong id="previewTB">-</strong> cm
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Simpan Pengukuran
                        </button>
                        <a href="/balita/detail/<?= $balita['id_balita']; ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>

                <?php else: ?>
                    <div class="alert alert-danger">Data balita tidak ditemukan.</div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<script>
(function() {
    const tglUkur   = document.getElementById('tanggal_ukur');
    const usiaInput = document.getElementById('usia_bulan');
    const bbInput   = document.getElementById('bb');
    const tbInput   = document.getElementById('tb');
    const pUsia     = document.getElementById('previewUsia');
    const pBB       = document.getElementById('previewBB');
    const pTB       = document.getElementById('previewTB');

    function hitungUsia() {
        if (!tglUkur || !usiaInput) return;
        const tglLahir = tglUkur.getAttribute('data-tgl-lahir');
        if (!tglLahir || !tglUkur.value) return;

        const lahir = new Date(tglLahir);
        const ukur  = new Date(tglUkur.value);

        if (ukur < lahir) {
            usiaInput.value = 0;
            pUsia.textContent = '0';
            return;
        }

        let bulan = (ukur.getFullYear() - lahir.getFullYear()) * 12;
        bulan += ukur.getMonth() - lahir.getMonth();
        if (ukur.getDate() < lahir.getDate()) bulan--;

        usiaInput.value = bulan;
        pUsia.textContent = bulan;
    }

    if (tglUkur) tglUkur.addEventListener('change', hitungUsia);
    if (bbInput) bbInput.addEventListener('input', function() { pBB.textContent = this.value || '-'; });
    if (tbInput) tbInput.addEventListener('input', function() { pTB.textContent = this.value || '-'; });

    hitungUsia();
})();
</script>

<?= $this->endSection(); ?>