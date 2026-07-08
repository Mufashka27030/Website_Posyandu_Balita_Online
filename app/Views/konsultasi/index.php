<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="container-fluid py-4">

    <div class="row justify-content-center">

        <div class="col-lg-8">

            <!-- Header -->

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-body">

                    <h2 class="fw-bold mb-1">
                        Konsultasi Bidan / Dokter
                    </h2>

                    <p class="text-muted mb-0">
                        Konsultasikan pertumbuhan dan kesehatan anak
                    </p>

                </div>

            </div>

            <!-- Profile Bidan -->

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <img
                        src="https://ui-avatars.com/api/?name=Bidan"
                        class="rounded-circle me-3"
                        width="80">

                        <div>

                            <h4 class="mb-1">

                                <?= isset($nama_bidan) ? esc($nama_bidan) : 'Bidan Posyandu'; ?>

                            </h4>

                            <span class="badge bg-success">

                                Online

                            </span>

                            <div class="text-muted mt-2">

                                Jam Layanan:
                                08.00 - 16.00 WIB

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <!-- Layanan -->

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-body">

                    <h5 class="fw-bold mb-3">

                        Layanan Konsultasi

                    </h5>

                    <div class="row">

                        <div class="col-md-6">

                            <ul class="list-group">

                                <li class="list-group-item">
                                    Monitoring Stunting
                                </li>

                                <li class="list-group-item">
                                    Gizi Balita
                                </li>

                                <li class="list-group-item">
                                    Pertumbuhan Anak
                                </li>

                            </ul>

                        </div>

                        <div class="col-md-6">

                            <ul class="list-group">

                                <li class="list-group-item">
                                    Kesehatan Ibu
                                </li>

                                <li class="list-group-item">
                                    Imunisasi
                                </li>

                                <li class="list-group-item">
                                    Konseling Parenting
                                </li>

                            </ul>

                        </div>

                    </div>

                </div>

            </div>

            <!-- CTA -->

            <?php

            $pesan = urlencode(
                "Halo Bidan, saya ingin berkonsultasi mengenai kesehatan anak saya."
            );

            $link_wa =
                "https://wa.me/" .
                (isset($nomor_wa) ? $nomor_wa : '') .
                "?text=" .
                $pesan;

            ?>

            <div class="card border-0 shadow-sm">

                <div class="card-body text-center">

                    <h5 class="fw-bold mb-3">

                        Mulai Konsultasi

                    </h5>

                    <a
                    href="<?= esc($link_wa, 'attr') ?>"
                    target="_blank"
                    class="btn btn-success btn-lg px-5">

                        <i class="fab fa-whatsapp"></i>

                        Chat WhatsApp

                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

<?= $this->endSection() ?>
