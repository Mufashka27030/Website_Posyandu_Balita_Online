<div class="sidebar-header">

<h4>

<i class="fas fa-heartbeat"></i>

Posyandu Online

</h4>

</div>

<div class="user-panel">

<div class="user-avatar">

<i class="fas fa-user-circle"></i>

</div>

<div class="user-name">

<?= esc(session()->get('nama')); ?>

</div>

<div class="online">

<i class="fas fa-circle"></i>

Online

</div>

<?php

$role = strtolower(session()->get('role'));

?>

<?php if($role=='admin'): ?>

<span class="role-badge role-admin">

<i class="fas fa-user-shield"></i>

Administrator

</span>

<?php elseif($role=='kader'): ?>

<span class="role-badge role-kader">

<i class="fas fa-user-nurse"></i>

Kader Posyandu

</span>

<?php else: ?>

<span class="role-badge role-orangtua">

<i class="fas fa-users"></i>

Orang Tua

</span>

<?php endif; ?>

</div>

<ul class="sidebar-menu">

<?php if(session()->get('role') != 'orangtua'): ?>

    <li>
        <a href="/dashboard">
            <i class="fas fa-home"></i>
            Dashboard
        </a>
    </li>

    <li>
        <a href="/balita">
            <i class="fas fa-child"></i>
            Data Balita
        </a>
    </li>

    <li>
        <a href="/dashboard/statistik">
            <i class="fas fa-chart-bar"></i>
            Statistik
        </a>
    </li>

    <li>
        <a href="/mapping">
            <i class="fas fa-map-marker-alt"></i>
            Peta Sebaran
        </a>
    </li>

    <li>
        <a href="/laporan">
            <i class="fas fa-file-pdf"></i>
            Laporan
        </a>
    </li>

<?php endif; ?>

    <li>
        <a href="/konsultasi">
            <i class="fab fa-whatsapp"></i>
            Konsultasi
        </a>
    </li>

    <li>
        <a href="/profil">
            <i class="fas fa-user"></i>
            Profil Akun
        </a>
    </li>

<?php if(session()->get('role') == 'admin' || session()->get('role') == 'kader'): ?>

    <li>
        <a href="/classuser">
            <i class="fas fa-users-cog"></i>
            Manajemen User
        </a>
    </li>

<?php endif; ?>

</ul>

