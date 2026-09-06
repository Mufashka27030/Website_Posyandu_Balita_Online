<?= $this->extend('layout/main'); ?>
<?= $this->section('content'); ?>

<div class="main-content">

<div class="card shadow">

<div class="card-header bg-warning">

<h4 class="mb-0">

Edit Data Balita

</h4>

</div>

<div class="card-body">

<?php if(isset($balita)): ?>

<!-- <form
action="/balita/update/<?= $balita['id_balita']; ?>"
method="post"> -->

<?= csrf_field(); ?>

<form action="/balita/update/<?= $balita['id_balita']; ?>" method="post">
    <?= csrf_field(); ?>

    <!-- FIX FK: kirim id_user yang sudah ada (tidak boleh berubah saat edit) -->
    <input type="hidden" name="id_user" value="<?= esc(old('id_user', $balita['id_user']), 'attr'); ?>">

    <?php if(session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error'); ?></div>
    <?php endif; ?>

    <!-- ... field lainnya ... -->

<?php if(session()->getFlashdata('error')): ?>

<div class="alert alert-danger">

<?= session()->getFlashdata('error'); ?>

</div>

<?php endif; ?>

<div class="mb-3">

<label>Nama Balita</label>

<input
type="text"
name="nama_balita"
class="form-control"
value="<?= esc(old('nama_balita', $balita['nama_balita']), 'attr'); ?>"
required>

</div>

<div class="form-group">
    <label>NIK Balita</label>
    <input type="text" name="nik" maxlength="20"
           value="<?= esc(old('nik', $balita['nik'] ?? ''), 'attr'); ?>">
</div>

<div class="mb-3">

<label>Nama Ibu</label>

<input
type="text"
name="nama_ibu"
class="form-control"
value="<?= esc(old('nama_ibu', $balita['nama_ibu']), 'attr'); ?>"
required>

</div>

<div class="mb-3">

<label>Jenis Kelamin</label>

<select
name="jenis_kelamin"
class="form-control">

<option value="L"
<?= old('jenis_kelamin', $balita['jenis_kelamin']) == 'L' ? 'selected' : ''; ?>>

Laki-laki

</option>

<option value="P"
<?= old('jenis_kelamin', $balita['jenis_kelamin']) == 'P' ? 'selected' : ''; ?>>

Perempuan

</option>

</select>

</div>

<div class="mb-3">

<label>Tanggal Lahir</label>

<input
type="date"
name="tanggal_lahir"
class="form-control"
value="<?= esc(old('tanggal_lahir', $balita['tanggal_lahir']), 'attr'); ?>">

</div>

<div class="mb-3">

<label>Puskesmas</label>

<select
name="id_puskesmas"
class="form-control">

<option value="">-- Belum ditentukan --</option>
<?php foreach (($puskesmas ?? []) as $p): ?>
<option
value="<?= (int) $p['id_puskesmas']; ?>"
<?= (string) old('id_puskesmas', (string) ($balita['id_puskesmas'] ?? '')) === (string) $p['id_puskesmas'] ? 'selected' : ''; ?>>
<?= esc($p['nama_puskesmas']); ?>
</option>
<?php endforeach; ?>

</select>

<?php if (empty($puskesmas)): ?>
<small class="text-muted">Belum ada data puskesmas. <a href="/puskesmas/tambah">Tambahkan dulu di sini.</a></small>
<?php endif; ?>

</div>

<div class="mb-3">

<label>Alamat</label>

<textarea
name="alamat"
class="form-control"><?= esc(old('alamat', $balita['alamat'])); ?></textarea>

</div>

<div class="mb-3">

<label>Latitude</label>

<input
type="text"
name="latitude"
class="form-control"
value="<?= esc(old('latitude', $balita['latitude'] ?? ''), 'attr'); ?>">

</div>

<div class="mb-3">

<label>Longitude</label>

<input
type="text"
name="longitude"
class="form-control"
value="<?= esc(old('longitude', $balita['longitude'] ?? ''), 'attr'); ?>">

</div>

<button
class="btn btn-success">

Update Data

</button>

<a
href="/balita"
class="btn btn-secondary">

Kembali

</a>

</form>

<?php endif; ?>

</div>

</div>

</div>

<?= $this->endSection(); ?>
