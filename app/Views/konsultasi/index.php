<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <!-- Header -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="fw-bold mb-1">Konsultasi Bidan / Dokter</h2>
                    <p class="text-muted mb-0">
                        Konsultasikan pertumbuhan dan kesehatan anak
                    </p>
                </div>
            </div>

            <?php if (($mode ?? 'orangtua') === 'admin'): ?>

                <!-- MODE ADMIN/KADER: Daftar WhatsApp Orang Tua -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0 fw-bold">Daftar Kontak Orang Tua</h5>
                    </div>
                    <div class="card-body p-0">
                        <?php if (! empty($orangtua)): ?>
                            <div class="list-group list-group-flush">
                                <?php foreach ($orangtua as $ot): ?>
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <img src="<?= base_url('uploads/foto/' . ($ot['foto'] ?? '') ?: 'https://ui-avatars.com/api/?name=' . urlencode($ot['nama'])); ?>"
                 class="rounded-circle me-3" width="48" height="48"
                 onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($ot['nama']); ?>'">
                                            <div>
                                                <h6 class="mb-0 fw-semibold"><?= esc($ot['nama']); ?></h6>
                                                <small class="text-muted">
                                                    <?= esc($ot['email']); ?>
                                                    <?php if (! empty($ot['no_hp'])): ?>
                                                        • <?= esc($ot['no_hp']); ?>
                                                    <?php endif; ?>
                                                </small>
                                            </div>
                                        </div>
                                        <?php if (! empty($ot['no_hp'])): ?>
                                            <?php
                                            $nomor = preg_replace('/[^0-9]/', '', $ot['no_hp']);
                                            if (str_starts_with($nomor, '0')) {
                                                $nomor = '62' . substr($nomor, 1);
                                            }
                                            $pesan = rawurlencode(
                                                "Halo " . $ot['nama'] . ", saya dari Posyandu ingin berkomunikasi."
                                            );
                                            ?>
                                            <a href__="https://wa.me/<?= $nomor; ?>?text=<?= $pesan; ?>"
                                               target="_blank"
                                               class="btn btn-success btn-sm">
                                                <i class="fab fa-whatsapp"></i> Chat
                                            </a>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">No HP kosong</span>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center text-muted py-5">
                                <p>Belum ada data orang tua terdaftar</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            <?php else: ?>

                <!-- MODE ORANG TUA: Profil Bidan -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <img src="https://ui-avatars.com/api/?name=Bidan"
                                 class="rounded-circle me-3" width="80">
                            <div>
                                <h4 class="mb-1">
                                    <?= isset($nama_bidan) ? esc($nama_bidan) : 'Bidan Posyandu'; ?>
                                </h4>
                                <span class="badge bg-success">Online</span>
                                <div class="text-muted mt-2">
                                    Jam Layanan: 08.00 - 16.00 WIB
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Layanan Konsultasi</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-group">
                                    <li class="list-group-item">Monitoring Stunting</li>
                                    <li class="list-group-item">Gizi Balita</li>
                                    <li class="list-group-item">Pertumbuhan Anak</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-group">
                                    <li class="list-group-item">Kesehatan Ibu</li>
                                    <li class="list-group-item">Imunisasi</li>
                                    <li class="list-group-item">Konseling Parenting</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
                $pesan = urlencode("Halo Bidan, saya ingin berkonsultasi mengenai kesehatan anak saya.");
                $link_wa = "https://wa.me/" . (isset($nomor_wa) ? $nomor_wa : '') . "?text=" . $pesan;
                ?>

                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="fw-bold mb-3">Mulai Konsultasi</h5>
                        <a href__="<?= esc($link_wa, 'attr'); ?>" target="_blank"
                           class="btn btn-success btn-lg px-5">
                            <i class="fab fa-whatsapp"></i> Chat WhatsApp
                        </a>
                    </div>
                </div>

            <?php endif; ?>

        </div>
    </div>
</div>

<?= $this->endSection() ?>