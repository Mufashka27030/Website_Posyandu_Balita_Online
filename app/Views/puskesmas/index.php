<?= $this->extend('layout/main'); ?>

<?= $this->section('content'); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1 fw-bold">Data Puskesmas</h3>
        <p class="text-muted mb-0">Daftar puskesmas yang tercatat dalam sistem</p>
    </div>
    <a href="/puskesmas/tambah" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Puskesmas
    </a>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('success'); ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error'); ?>
    </div>
<?php endif; ?>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">

        <?php if (empty($puskesmas)): ?>
            <div class="p-4 text-center text-muted">
                Belum ada data puskesmas. Klik "Tambah Puskesmas" untuk menambahkan yang pertama.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Puskesmas</th>
                            <th>Kelurahan</th>
                            <th>Kecamatan</th>
                            <th>RW</th>
                            <th>Alamat</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($puskesmas as $p): ?>
                            <tr>
                                <td><?= esc($p['nama_puskesmas']); ?></td>
                                <td><?= esc($p['kelurahan'] ?? '-'); ?></td>
                                <td><?= esc($p['kecamatan'] ?? '-'); ?></td>
                                <td><?= esc($p['rw'] ?? '-'); ?></td>
                                <td><?= esc($p['alamat'] ?? '-'); ?></td>
                                <td class="text-end">
                                    <a href="/puskesmas/edit/<?= (int) $p['id_puskesmas']; ?>"
                                       class="btn btn-outline-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="/puskesmas/hapus/<?= (int) $p['id_puskesmas']; ?>" method="post"
                                          class="d-inline"
                                          onsubmit="return confirm('Yakin hapus puskesmas ini? Balita yang terhubung tidak akan ikut terhapus, hanya dilepas dari puskesmas ini.');">
                                        <?= csrf_field(); ?>
                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

    </div>
</div>

<?= $this->endSection(); ?>
