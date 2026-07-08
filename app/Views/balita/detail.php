<?= $this->include('layout/header'); ?>
<?= $this->include('layout/navbar'); ?>

<div class="main-content">

<div class="card shadow">

<div class="card-header bg-primary text-white">

    <h4 class="mb-0">

        Detail Data Balita

    </h4>

</div>

<div class="card-body">

<?php if(isset($balita)): ?>

<div class="row">

<div class="col-md-6">

<p>

<strong>Nama Balita</strong>

<br>

<?= $balita['nama_balita']; ?>

</p>

</div>

<div class="col-md-6">

<p>

<strong>Nama Ibu</strong>

<br>

<?= $balita['nama_ibu']; ?>

</p>

</div>

<div class="col-md-6">

<p>

<strong>Jenis Kelamin</strong>

<br>

<?= $balita['jenis_kelamin']; ?>

</p>

</div>

<div class="col-md-6">

<p>

<strong>Tanggal Lahir</strong>

<br>

<?= $balita['tanggal_lahir']; ?>

</p>

</div>

<div class="col-12">

<p>

<strong>Alamat</strong>

<br>

<?= $balita['alamat']; ?>

</p>

</div>

</div>

<a href="/balita"
class="btn btn-secondary">

Kembali

</a>

<?php else: ?>

<div class="alert alert-danger">

Data balita tidak ditemukan

</div>

<?php endif; ?>

</div>

</div>

</div>

<?= $this->include('layout/footer'); ?>