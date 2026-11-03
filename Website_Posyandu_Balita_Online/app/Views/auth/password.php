<?= $this->include('layout/header'); ?>

<?= $this->extend('layout/main'); ?>

<?= $this->section('content'); ?>

<div class="container">

<div class="row justify-content-center mt-5">

<div class="col-lg-6">

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h4>

Ganti Password

</h4>

</div>

<div class="card-body">

<?= flash_block('error', 'alert-danger'); ?>

<form
action="<?= site_url('auth/updatePassword') ?>"
method="post">

<?= csrf_field(); ?>

<div class="mb-3">

<label>Password Lama</label>

<input
type="password"
name="password_lama"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Password Baru</label>

<input
type="password"
name="password_baru"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Konfirmasi Password</label>

<input
type="password"
name="konfirmasi"
class="form-control"
required>

</div>

<button
class="btn btn-primary">

Update Password

</button>

</form>

</div>

</div>

</div>

</div>

</div>

<?= $this->endSection(); ?>
