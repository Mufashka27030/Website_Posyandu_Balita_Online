<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<title>Laporan Stunting</title>

<style>

body{
    font-family: DejaVu Sans, sans-serif;
    font-size:12px;
}

.header{
    text-align:center;
}

.line{
    border-top:2px solid black;
    margin-top:10px;
    margin-bottom:10px;
}

table{
    width:100%;
    border-collapse:collapse;
}

table,
th,
td{
    border:1px solid black;
}

th{
    background:#f2f2f2;
}

th,td{
    padding:6px;
    text-align:center;
}

.info{
    margin-bottom:20px;
}

.ttd{
    margin-top:50px;
    width:100%;
}

.right{
    text-align:right;
}

</style>

</head>

<body>

<div class="header">

    <h3>
        PEMERINTAH KOTA MALANG
    </h3>

    <h3>
        DINAS KESEHATAN
    </h3>

    <h4>
        POSYANDU SARTIKA RW 05
    </h4>

    <p>
        Kelurahan Polowijen - Kecamatan Blimbing
    </p>

</div>

<div class="line"></div>

<h3 align="center">

LAPORAN MONITORING STUNTING BALITA

</h3>

<br>

<div class="info">

<b>Total Balita :</b>
<?= isset($total_balita) ? $total_balita : 0; ?>

<br>

<b>Normal :</b>
<?= isset($total_normal) ? $total_normal : 0; ?>

<br>

<b>Stunting :</b>
<?= isset($total_stunting) ? $total_stunting : 0; ?>

<br>

<b>Stunting Berat :</b>
<?= isset($stunting_berat) ? $stunting_berat : 0; ?>

<br>

<b>Persentase :</b>
<?= isset($persentase) ? $persentase : 0; ?>%

</div>

<table>

<tr>

    <th>No</th>

    <th>Nama Balita</th>

    <th>JK</th>

    <th>Tanggal Lahir</th>

    <th>Z-Score</th>

    <th>Status</th>

</tr>

<?php $no = 1; ?>

<?php if(isset($balita)): ?>

<?php foreach($balita as $row): ?>

<tr>

    <td><?= $no++; ?></td>

    <td><?= $row['nama']; ?></td>

    <td><?= $row['jenis_kelamin']; ?></td>

    <td><?= $row['tanggal_lahir']; ?></td>

    <td><?= $row['zscore']; ?></td>

    <td><?= $row['status']; ?></td>

</tr>

<?php endforeach; ?>

<?php endif; ?>

</table>

<div class="ttd">

<div class="right">

    Malang,
    <?= date('d-m-Y'); ?>

    <br><br><br><br>

    _______________________

    <br>

    Kader Posyandu

</div>

</div>

</body>
</html>