<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1">

<title>Input Pengukuran</title>

<style>

body{
    background:#f5f7fb;
    font-family:Arial,sans-serif;
    padding:20px;
}

.card{
    max-width:700px;
    margin:auto;
    background:white;
    padding:25px;
    border-radius:20px;
    box-shadow:0 2px 10px rgba(0,0,0,.1);
}

h2{
    margin-bottom:20px;
}

.form-group{
    margin-bottom:15px;
}

label{
    display:block;
    margin-bottom:5px;
    font-weight:bold;
}

input{
    width:100%;
    padding:12px;
    border:1px solid #ddd;
    border-radius:10px;
    box-sizing:border-box;
}

.preview{
    margin-top:20px;
    padding:15px;
    background:#eef2ff;
    border-radius:10px;
}

.alert{
    background:#fee2e2;
    color:#991b1b;
    padding:12px;
    border-radius:10px;
    margin-bottom:15px;
}

.btn{
    width:100%;
    background:#2563eb;
    color:white;
    border:none;
    padding:14px;
    border-radius:10px;
    cursor:pointer;
    font-size:16px;
    margin-top:15px;
}

.nama-balita{
    background:#f3f4f6;
}

.back-link{
    display:block;
    margin-top:15px;
    text-align:center;
    text-decoration:none;
    color:#555;
}

</style>

</head>

<body>

<div class="card">

<h2>Input Pengukuran Balita</h2>

<?php if(isset($balita)): ?>

<form
action="/pengukuran/simpan"
method="post">

<?= csrf_field(); ?>

<?php if(session()->getFlashdata('error')): ?>

<div class="alert">

<?= session()->getFlashdata('error'); ?>

</div>

<?php endif; ?>

<input
type="hidden"
name="id_balita"
value="<?= esc(old('id_balita', $balita['id_balita']), 'attr'); ?>">

<div class="form-group">

<label>Nama Balita</label>

<input
class="nama-balita"
type="text"
value="<?= esc($balita['nama_balita'], 'attr'); ?>"
readonly>

</div>

<div class="form-group">

<label>Tanggal Pengukuran</label>

<input
type="date"
name="tanggal_ukur"
value="<?= esc(old('tanggal_ukur'), 'attr'); ?>"
required>

</div>

<div class="form-group">

<label>Berat Badan (Kg)</label>

<input
id="bb"
type="number"
step="0.01"
name="berat_badan"
value="<?= esc(old('berat_badan'), 'attr'); ?>"
required>

</div>

<div class="form-group">

<label>Tinggi Badan (Cm)</label>

<input
id="tb"
type="number"
step="0.01"
name="tinggi_badan"
value="<?= esc(old('tinggi_badan'), 'attr'); ?>"
required>

</div>

<div class="preview">

<h4>Preview Pengukuran</h4>

<p>
Berat:
<span id="previewBB"><?= esc(old('berat_badan', '-')); ?></span>
Kg
</p>

<p>
Tinggi:
<span id="previewTB"><?= esc(old('tinggi_badan', '-')); ?></span>
Cm
</p>

</div>

<a
href="/balita/detail/<?= esc($balita['id_balita'], 'url'); ?>"
class="back-link">
Kembali ke Detail Balita
</a>

<button
type="submit"
class="btn">
Simpan Pengukuran
</button>

</form>

<?php else: ?>

<div class="alert">Data balita tidak ditemukan.</div>

<a
href="/balita"
class="back-link">
Kembali ke Data Balita
</a>

<?php endif; ?>

</div>

<script>
const bb = document.getElementById('bb');
const tb = document.getElementById('tb');

if (bb) {
    bb.addEventListener('input', function() {
        document.getElementById('previewBB').innerText = this.value || '-';
    });
}

if (tb) {
    tb.addEventListener('input', function() {
        document.getElementById('previewTB').innerText = this.value || '-';
    });
}
</script>

</body>
</html>
