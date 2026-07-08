<?= $this->include('layout/header'); ?>
<?= $this->include('layout/navbar'); ?>
<?= $this->include('layout/sidebar'); ?>

<div class="container-fluid py-4">

    <div class="card shadow border-0">

        <div class="card-header bg-primary text-white">

            <h4 class="mb-0">
                Edit Data Pengukuran
            </h4>

        </div>

        <div class="card-body">

            <?php if(isset($pengukuran)): ?>

            <form
            action="/pengukuran/update/<?= $pengukuran['id_pengukuran']; ?>"
            method="post">

                <div class="mb-3">

                    <label class="form-label">
                        Berat Badan (Kg)
                    </label>

                    <input
                    type="number"
                    step="0.1"
                    name="berat_badan"
                    class="form-control"
                    value="<?= $pengukuran['berat_badan']; ?>"
                    required>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Tinggi Badan (Cm)
                    </label>

                    <input
                    type="number"
                    step="0.1"
                    name="tinggi_badan"
                    class="form-control"
                    value="<?= $pengukuran['tinggi_badan']; ?>"
                    required>

                </div>

                <button
                type="submit"
                class="btn btn-success">

                    Simpan Perubahan

                </button>

                <a
                href="/balita"
                class="btn btn-secondary">

                    Kembali

                </a>

            </form>

            <?php else: ?>

            <div class="alert alert-danger">

                Data pengukuran tidak ditemukan

            </div>

            <?php endif; ?>

        </div>

    </div>

</div>

<?= $this->include('layout/footer'); ?>