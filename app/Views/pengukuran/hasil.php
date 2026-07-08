<?= $this->extend('layout/main'); ?>

<?= $this->section('content'); ?>

<div class="container">

<div class="row justify-content-center">

<div class="col-lg-6">

<div class="card shadow border-0 rounded-4">

<div class="card-header bg-primary text-white">

<h4 class="mb-0">

Hasil Analisis Stunting

</h4>

</div>

<div class="card-body">

<div class="row mt-4">

<div class="col-md-4 mb-2">

<a
href="/pengukuran/riwayat/<?= isset($balita['id_balita']) ? $balita['id_balita'] : 0; ?>"
class="btn btn-primary w-100">

Riwayat

</a>

</div>

<div class="col-md-4 mb-2">

<a
href="/pengukuran/riwayat/<?= isset($balita['id_balita']) ? $balita['id_balita'] : 0; ?>"
class="btn btn-success w-100">

Grafik

</a>

</div>

<div class="col-md-4 mb-2">

<a
href="/balita"
class="btn btn-secondary w-100">

Kembali

</a>

</div>

</div>

<h5>

<?= $balita['nama_balita'] ?? '-'; ?>

</h5>

<hr>

<p>

<b>Usia :</b>

<?= $usia_bulan ?? 0; ?> Bulan

</p>

<p>

<b>Tinggi Badan :</b>

<?= $tinggi_badan ?? 0; ?> cm

</p>

<p>

<b>Z-Score :</b>

<?= $zscore ?? 0; ?>

</p>

<hr>

<?php

$warnaBadge = "secondary";

if(($status ?? '') == "Normal"){

    $warnaBadge = "success";
}

elseif(($status ?? '') == "Stunting"){

    $warnaBadge = "warning";
}

elseif(($status ?? '') == "Stunting Berat"){

    $warnaBadge = "danger";
}

?>

<div class="text-center">

<span class="badge bg-<?= $warnaBadge; ?> p-3">

<?= $status ?? '-'; ?>

</span>

</div>

<?php if(($status ?? '') == "Stunting Berat"): ?>

<div class="alert alert-danger mt-4">

⚠ Segera konsultasi ke tenaga kesehatan.

</div>

<?php endif; ?>

