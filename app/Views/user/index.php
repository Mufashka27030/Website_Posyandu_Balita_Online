<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="container-fluid py-4">

<div class="d-flex
justify-content-between
align-items-center
mb-4">

    <div>

        <h2 class="fw-bold">

            Manajemen User

        </h2>

        <p class="text-muted">

            Kelola akun sistem

        </p>

    </div>

    <a href="/register"
    class="btn btn-primary">

        + Tambah User

    </a>

</div>

<div class="card border-0 shadow-sm">

<div class="card-body">

<div class="table-responsive">

<form method="get">

<div class="input-group mb-3">

<input
type="text"
name="keyword"
class="form-control"
placeholder="Cari user..."
value="<?= isset($keyword) ? esc($keyword, 'attr') : ''; ?>">

<button
class="btn btn-primary">

Cari

</button>

</div>

</form>

<div class="row mb-4">

<div class="col-md-4">

<div class="card shadow-sm border-0">

<div class="card-body">

<h5>Total User</h5>

<h2>

<?= isset($users)
? count($users)
: 0; ?>

</h2>

</div>

</div>

</div>

</div>

<table class="table table-hover align-middle">

<thead>

<tr>

<th>Nama</th>

<th>Email</th>

<th>Role</th>

<th>Aksi</th>

</tr>

</thead>

<tbody>

<?php if(isset($users)): ?>

<?php foreach($users as $row): ?>

<tr>

<td>

    <strong>

        <?= esc($row['nama']); ?>

    </strong>

</td>

<td>

    <?= esc($row['email']); ?>

</td>

<td>

<?php

$badge = "secondary";

$role = strtolower((string) $row['role']);

if($role=="admin")
{
    $badge = "danger";
}
elseif($role=="kader")
{
    $badge = "success";
}
elseif($role=="orangtua")
{
    $badge = "primary";
}

?>

<span class="badge bg-<?= $badge ?>">

<?= esc(ucfirst($role)); ?>

</span>

</td>

<td>

<a href="/classuser/edit/<?= $row['id_user']; ?>"
class="btn btn-warning btn-sm">

Edit

</a>

<form
action="/classuser/hapus/<?= $row['id_user']; ?>"
method="post"
class="d-inline"
onsubmit="return confirm('Hapus user ini?')">

<?= csrf_field(); ?>

<button
type="submit"
class="btn btn-danger btn-sm">

Hapus

</button>

</form>

</td>

</tr>

<?php endforeach; ?>

<?php endif; ?>

</tbody>

</table>

</div>

</div>

</div>

</div>

<?= $this->endSection() ?>
