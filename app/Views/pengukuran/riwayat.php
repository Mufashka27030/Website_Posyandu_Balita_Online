<?= $this->include('layout/header'); ?>
<?= $this->include('layout/navbar'); ?>


<div class="main-content">

<div class="card shadow">

<div class="card-header bg-success text-white">

<h4 class="mb-0">

Riwayat Pengukuran Balita

</h4>

</div>

<div class="card-body">

<?php if(isset($balita)): ?>

<h5 class="mb-4">

<?= $balita['nama_balita']; ?>

</h5>

<?php endif; ?>

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="table-light">

<tr>

<th>No</th>

<th>Tanggal</th>

<th>Usia</th>

<th>BB</th>

<th>TB</th>

<th>Z-Score</th>

<th>Status</th>

</tr>

</thead>

<tbody>

<?php if(isset($riwayat) && !empty($riwayat)): ?>

<?php $no=1; ?>

<?php foreach($riwayat as $row): ?>

<tr>

<td><?= $no++; ?></td>

<td><?= $row['tanggal_ukur']; ?></td>

<td><?= $row['usia_bulan']; ?> bln</td>

<td><?= $row['berat_badan']; ?> kg</td>

<td><?= $row['tinggi_badan']; ?> cm</td>

<td><?= $row['z_score']; ?></td>

<td>

<?php

$status = $row['status_gizi'];

if($status == 'Normal'){

echo '<span class="badge bg-success">Normal</span>';

}
elseif($status == 'Stunting'){

echo '<span class="badge bg-warning text-dark">Stunting</span>';

}
else{

echo '<span class="badge bg-danger">Stunting Berat</span>';

}

?>

</td>

</tr>

<?php endforeach; ?>

<?php else: ?>

<tr>

<td colspan="7" class="text-center">

Belum ada data pengukuran

</td>

</tr>

<?php endif; ?>

</tbody>

</table>

</div>

</div>

</div>

</div>

<?= $this->include('layout/footer'); ?>