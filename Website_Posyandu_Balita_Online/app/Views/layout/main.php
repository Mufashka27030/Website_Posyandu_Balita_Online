<!DOCTYPE html>
<html lang="id">

<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= esc($title ?? 'Sistem Monitoring Stunting'); ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
body {
    background: #f4f7fb;
}

.sidebar {
    width: 250px;
    min-height: 100vh;
    background: #0d6efd;
    color: white;
    position: fixed;
    left: 0;
    top: 0;
    z-index: 1000;
    display: flex;
    flex-direction: column;
}

.sidebar-header {
    padding: 20px;
    border-bottom: 1px solid rgba(255,255,255,0.15);
}

.sidebar-header h3 {
    margin: 0;
    font-size: 1.15rem;
    font-weight: 700;
}

.sidebar-header small {
    opacity: 0.8;
    font-size: 0.75rem;
}

.sidebar-nav {
    flex: 1;
    overflow-y: auto;
    padding: 8px 0;
}

.sidebar a {
    display: block;
    color: white;
    text-decoration: none;
    padding: 11px 20px;
    font-size: 0.9rem;
    border-left: 3px solid transparent;
    transition: all 0.15s ease;
}

.sidebar a:hover {
    background: rgba(255,255,255,0.15);
    border-left-color: rgba(255,255,255,0.5);
}

.sidebar a.active {
    background: rgba(255,255,255,0.25);
    font-weight: 600;
    border-left-color: #fff;
}

.sidebar a i {
    width: 20px;
    text-align: center;
    margin-right: 6px;
}

.sidebar-footer {
    padding: 12px 0;
    border-top: 1px solid rgba(255,255,255,0.15);
}

.content {
    margin-left: 250px;
    padding: 25px;
}

@media (max-width: 768px) {
    .sidebar {
        position: fixed;
        left: 0;
        top: 0;
        z-index: 2000;
        height: 100vh;
        width: 250px;
        transform: translateX(-100%);
        transition: transform .2s ease;
    }

    .sidebar.is-open {
        transform: translateX(0);
    }

    .sidebar-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,.45);
        z-index: 1500;
        display: none;
    }

    .sidebar-overlay.is-open {
        display: block;
    }

    .content {
        margin-left: 0;
        padding: 18px 12px;
    }

    img, svg, video, canvas {
        max-width: 100%;
        height: auto;
    }
}
</style>
</head>

<body>

<button type="button" class="btn btn-primary btn-sm" id="sidebarToggle"
    style="position:fixed;top:12px;left:12px;z-index:2100;display:none;" aria-label="Buka menu">
    <i class="bi bi-list"></i>
</button>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="sidebar">

    <!-- Header dengan info user -->
    <div class="sidebar-header">
        <h3>Posyandu Online</h3>
        <small><?= esc(session()->get('nama') ?? 'User'); ?> • <?= ucfirst(esc(session()->get('role') ?? '')); ?></small>
    </div>

    <!-- Navigation -->
    <nav class="sidebar-nav">
        <?php
        $currentPath = '/' . ltrim(uri_string(), '/');
        $role = strtolower((string) session()->get('role'));

        // Prefix match: submenu (balita/detail, pengukuran/riwayat) ikut
        // menandai induknya.
        $isActive = static function (string $path) use ($currentPath): string {
            return str_starts_with($currentPath, $path) ? 'active' : '';
        };

        // Exact match: dipakai untuk Dashboard vs Statistik, yang route-nya
        // berbagi prefix "/dashboard" sehingga prefix match salah terus.
        $isActiveExact = static function (array $paths) use ($currentPath): string {
            return in_array($currentPath, $paths, true) ? 'active' : '';
        };
        ?>

        <!-- Dashboard — role aware -->
        <a href="<?= site_url($role === 'orangtua' ? 'dashboard-orangtua' : 'dashboard') ?>"
           class="<?= $isActiveExact($role === 'orangtua' ? ['/dashboard-orangtua'] : ['/dashboard']) ?>">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        <!-- Data Balita -->
        <a href="<?= site_url('balita') ?>" class="<?= $isActive('/balita') ?>">
            <i class="bi bi-people"></i> Data Balita
        </a>

        <!-- Pengukuran — semua role -->
        <a href="<?= site_url('pengukuran') ?>" class="<?= $isActive('/pengukuran') ?>">
            <i class="bi bi-clipboard2-pulse"></i> Pengukuran
        </a>

        <!-- Statistik — role aware -->
        <a href="<?= site_url($role === 'orangtua' ? 'dashboard/statistik-orangtua' : 'dashboard/statistik') ?>"
           class="<?= $isActiveExact($role === 'orangtua' ? ['/dashboard/statistik-orangtua'] : ['/dashboard/statistik']) ?>">
            <i class="bi bi-bar-chart"></i> Statistik
        </a>

        <?php if ($role !== 'orangtua'): ?>
        <!-- Peta Sebaran -->
        <a href="<?= site_url('mapping') ?>" class="<?= $isActive('/mapping') ?>">
            <i class="bi bi-geo-alt"></i> Peta Sebaran
        </a>

        <!-- Data Puskesmas -->
        <a href="<?= site_url('puskesmas') ?>" class="<?= $isActive('/puskesmas') ?>">
            <i class="bi bi-building"></i> Data Puskesmas
        </a>

        <!-- Laporan -->
        <a href="<?= site_url('laporan') ?>" class="<?= $isActive('/laporan') ?>">
            <i class="bi bi-file-earmark-pdf"></i> Laporan
        </a>
        <?php endif; ?>

        <!-- Konsultasi -->
        <a href="<?= site_url('konsultasi') ?>" class="<?= $isActive('/konsultasi') ?>">
            <i class="bi bi-whatsapp"></i> Konsultasi
        </a>

        <?php if ($role !== 'orangtua'): ?>
        <!-- User Management -->
        <a href="<?= site_url('classuser') ?>" class="<?= $isActive('/classuser') ?>">
            <i class="bi bi-person-gear"></i> Manajemen User
        </a>
        <?php endif; ?>
    </nav>

    <!-- Footer -->
    <div class="sidebar-footer">
        <a href="<?= site_url('profil') ?>" class="<?= $isActive('/profil') ?>">
            <i class="bi bi-person-circle"></i> Profil Saya
        </a>
        <a href="<?= site_url('logout') ?>">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
    </div>

</div>

<div class="content">
    <div class="container-fluid px-2 px-md-0">
        <?= $this->renderSection('content'); ?>
    </div>
</div>

<script>
(function () {
    const toggleBtn = document.getElementById('sidebarToggle');
    const overlay = document.getElementById('sidebarOverlay');
    const sidebar = document.querySelector('.sidebar');

    function setInitial() {
        if (window.innerWidth <= 768) {
            toggleBtn && (toggleBtn.style.display = 'block');
        }
    }

    function openSidebar() {
        if (!sidebar || !overlay) return;
        sidebar.classList.add('is-open');
        overlay.classList.add('is-open');
    }

    function closeSidebar() {
        if (!sidebar || !overlay) return;
        sidebar.classList.remove('is-open');
        overlay.classList.remove('is-open');
    }

    if (toggleBtn) {
        toggleBtn.addEventListener('click', function () {
            const isOpen = sidebar && sidebar.classList.contains('is-open');
            if (isOpen) closeSidebar(); else openSidebar();
        });
    }

    if (overlay) {
        overlay.addEventListener('click', closeSidebar);
    }

    window.addEventListener('resize', function () {
        setInitial();
        if (window.innerWidth > 768) {
            closeSidebar();
        }
    });

    setInitial();
})();
</script>

</body>
</html>