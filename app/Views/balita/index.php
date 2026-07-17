<?= $this->extend('layout/main'); ?>

<?= $this->section('content'); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1 fw-bold">Data Balita</h3>
        <p class="text-muted mb-0">Daftar data balita terdaftar</p>
    </div>
    <?php if (session()->get('role') !== 'orangtua'): ?>
        <a href__="/balita/tambah" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Balita
        </a>
    <?php endif; ?>
</div>

<div class="mb-3">
    <input type="text" id="searchInput" class="form-control"
           placeholder="Cari nama balita...">
</div>

<div class="row g-3" id="balitaContainer">
    <?php if (isset($balita) && ! empty($balita)): ?>
        <?php foreach ($balita as $row): ?>
            <div class="col-md-6 col-lg-4 balita-card">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="fw-bold mb-0"><?= esc($row['nama_balita']); ?></h5>
                            <span class="badge bg-<?= $row['jenis_kelamin'] === 'L' ? 'primary' : 'danger'; ?>">
                                <?= $row['jenis_kelamin'] === 'L' ? 'L' : 'P'; ?>
                            </span>
                        </div>
                        <p class="mb-1"><small class="text-muted">Ibu:</small> <?= esc($row['nama_ibu']); ?></p>
                        <p class="mb-1"><small class="text-muted">Tgl Lahir:</small>
                            <?= date('d M Y', strtotime($row['tanggal_lahir'])); ?>
                        </p>
                        <p class="mb-2"><small class="text-muted">Alamat:</small> <?= esc($row['alamat'] ?? '-'); ?></p>
                        <span class="badge bg-success mb-3">Terdaftar</span>

                        <div class="d-flex gap-2 flex-wrap mt-2">
                            <a href__="/balita/detail/<?= $row['id_balita']; ?>"
                               class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                            <?php if (session()->get('role') !== 'orangtua'): ?>
                                <a href__="/pengukuran/<?= $row['id_balita']; ?>"
                                   class="btn btn-outline-danger btn-sm">
                                    <i class="fas fa-ruler"></i> Ukur
                                </a>
                            <?php endif; ?>
                            <a href__="/pengukuran/riwayat/<?= $row['id_balita']; ?>"
                               class="btn btn-outline-success btn-sm">
                                <i class="fas fa-history"></i> Riwayat
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center text-muted py-5">
                    <p class="mb-0">Belum ada data balita.</p>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
document.getElementById("searchInput").addEventListener("keyup", function() {
    let filter = this.value.toLowerCase();
    let cards = document.querySelectorAll(".balita-card");
    cards.forEach(function(card) {
        if (card.innerText.toLowerCase().includes(filter)) {
            card.style.display = "";
        } else {
            card.style.display = "none";
        }
    });
});
</script>

<?= $this->endSection(); ?>