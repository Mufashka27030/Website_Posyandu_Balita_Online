<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="container py-4">

<div class="row justify-content-center">

<div class="col-lg-8">

<div class="card border-0 shadow-sm">

<div class="card-body text-center">

<img
src="https://ui-avatars.com/api/?name=<?= urlencode(isset($nama) ? $nama : 'User'); ?>&background=0D8ABC&color=fff&size=200"
class="rounded-circle mb-3"
width="120">

<h3>

<?= isset($nama)
? esc($nama)
: '-'; ?>

</h3>

<?php

$role=session()->get('role');

$warna='primary';

$icon='users';

if($role=='admin'){

$warna='danger';

$icon='user-shield';

}

elseif($role=='kader'){

$warna='success';

$icon='user-nurse';

}

?>

<span class="badge bg-<?= $warna; ?> p-2">

<i class="fas fa-<?= $icon; ?>"></i>

<?= ucfirst($role); ?>

</span>

<hr>

<div class="row text-start">

<div class="col-md-6 mb-3">

<label
class="fw-bold">

Nama

</label>

<input
type="text"
class="form-control"
value="<?= isset($nama) ? esc($nama, 'attr') : ''; ?>"
readonly>

</div>

<div class="col-md-6 mb-3">

<label
class="fw-bold">

Email

</label>

<input
type="text"
class="form-control"
value="<?= isset($email) ? esc($email, 'attr') : ''; ?>"
readonly>

</div>

</div>

<div class="mt-3">

<a href="<?= site_url('profil/edit') ?>"
class="btn btn-primary">

Edit Profil

</a>

<a href="<?= site_url('profil/password') ?>"
class="btn btn-warning">

Ganti Password

</a>

</div>

</div>

</div>

</div>

</div>

</div>

<?= $this->endSection() ?>
