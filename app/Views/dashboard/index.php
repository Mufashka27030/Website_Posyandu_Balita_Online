<?= $this->extend('layout/main'); ?>

<?= $this->section('content'); ?>

<div class="alert alert-primary shadow-sm mb-4">

<h4>

Selamat Datang,

<b><?= esc(session()->get('nama')); ?></b>

</h4>

<p class="mb-0">

Anda login sebagai

<b><?= ucfirst(session()->get('role')); ?></b>

</p>

</div>

<h2 class="mb-4">

Dashboard Monitoring Stunting

</h2>

<div class="row">

<div class="col-md-3 mb-3">

<div class="card card-dashboard">

<div class="card-body">

<h5>Total Balita</h5>

<h2><?= $total_balita ?? 0; ?></h2>

</div>

</div>

</div>

<div class="col-md-3 mb-3">

<div class="card card-dashboard">

<div class="card-body">

<h5>Normal</h5>

<h2><?= $total_normal ?? 0; ?></h2>

</div>

</div>

</div>

<div class="col-md-3 mb-3">

<div class="card card-dashboard">

<div class="card-body">

<h5>Stunting</h5>

<h2><?= $total_stunting ?? 0; ?></h2>

</div>

</div>

</div>

<div class="col-md-3 mb-3">

<div class="card card-dashboard">

<div class="card-body">

<h5>Stunting Berat</h5>

<h2><?= $stunting_berat ?? 0; ?></h2>

</div>

</div>

</div>

</div>

<?= $this->endSection(); ?>