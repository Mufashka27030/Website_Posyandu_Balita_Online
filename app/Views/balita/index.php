<!DOCTYPE html>
<html>
<head>

<title>Data Balita</title>

<meta name="viewport"
content="width=device-width, initial-scale=1">

<style>

body{
    font-family: Arial, sans-serif;
    background:#f5f7fb;
    margin:0;
    padding:20px;
}

.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    flex-wrap:wrap;
    gap:10px;
}

.btn{
    text-decoration:none;
    padding:10px 15px;
    border-radius:10px;
    color:white;
}

.btn-primary{
    background:#2563eb;
}

.search-box{
    margin-top:20px;
    margin-bottom:20px;
}

.search-box input{
    width:100%;
    padding:12px;
    border-radius:10px;
    border:1px solid #ccc;
}

.card-container{
    display:grid;
    grid-template-columns:
    repeat(auto-fit,minmax(280px,1fr));
    gap:20px;
}

.card{
    background:white;
    padding:20px;
    border-radius:15px;
    box-shadow:0 2px 10px rgba(0,0,0,0.1);
}

.badge{
    padding:5px 10px;
    border-radius:20px;
    color:white;
    font-size:12px;
}

.normal{
    background:green;
}

.stunting{
    background:orange;
}

.berat{
    background:red;
}

.action{
    margin-top:15px;
}

.action a{
    text-decoration:none;
    margin-right:5px;
}

.btn-detail{
    color:#2563eb;
}

.btn-riwayat{
    color:#16a34a;
}

.btn-ukur{
    color:#dc2626;
}

</style>

</head>

<body>

<div class="header">

<h2>Data Balita</h2>

<?php if(session()->get('role') !== 'orangtua'): ?>

<a href="/balita/tambah"
class="btn btn-primary">

+ Tambah Balita

</a>

<div>
<a href="/dashboard" class="btn btn-secondary mb-3">
    <i class="fas fa-arrow-left"></i>
    Kembali
</a>
</div>

</a>

<?php endif; ?>

</div>

<div class="search-box">

<input
type="text"
id="searchInput"
placeholder="Cari Nama Balita">

</div>

<div class="card-container">

<?php if(isset($balita) && !empty($balita)): ?>

<?php foreach($balita as $row): ?>

<div class="card balita-card">

<h3>
<?= esc($row['nama_balita']); ?>
</h3>

<p>

<b>Ibu:</b>

<?= esc($row['nama_ibu']); ?>

</p>

<p>

<b>JK:</b>

<?= esc($row['jenis_kelamin']); ?>

</p>

<p>

<b>Alamat:</b>

<?= esc($row['alamat']); ?>

</p>

<span class="badge normal">

Terdaftar

</span>

<div class="action">

<a
class="btn-detail"
href="/balita/detail/<?= $row['id_balita']; ?>">

Detail

</a>

|

<?php if(session()->get('role') !== 'orangtua'): ?>
<a
class="btn-ukur"
href="/pengukuran/<?= $row['id_balita']; ?>"
Ukur

</a>

|
<?php endif; ?>

<a
class="btn-riwayat"
href="/pengukuran/riwayat/<?= $row['id_balita']; ?>">

Riwayat

</a>


</div>

</div>

<?php endforeach; ?>

<?php else: ?>

<p>Belum ada data balita.</p>

<?php endif; ?>

</div>

<script>

document
.getElementById("searchInput")
.addEventListener("keyup", function(){

let filter =
this.value.toLowerCase();

let cards =
document.querySelectorAll(
".balita-card"
);

cards.forEach(function(card){

if(
card.innerText
.toLowerCase()
.includes(filter)
){
card.style.display = "";
}else{
card.style.display = "none";
}

});

});

</script>

</body>
</html>