<!DOCTYPE html>
<html lang="id">

<head>

<link
rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title><?= $title ?? 'Sistem Monitoring Stunting'; ?></title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<link
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
rel="stylesheet">

<style>

body{
    background:#f4f7fb;
}

/* Desktop layout */

.sidebar{
    width:250px;
    min-height:100vh;
    background:#0d6efd;
    color:white;
    position:fixed;
    left:0;
    top:0;
    z-index:1000;
}

.sidebar h3{
    padding:20px;
    margin:0;
}

.sidebar a{
    display:block;
    color:white;
    text-decoration:none;
    padding:12px 20px;
}

.sidebar a:hover{
    background:rgba(255,255,255,0.2);
}

.content{
    margin-left:250px;
    padding:25px;
}

/* Mobile layout */

/* Di mobile, sidebar dibuat bisa di-hide (off-canvas) seperti aplikasi mobile.
   Tombol toggle akan membuka/menutup menu. */

@media(max-width:768px){
    /* off-canvas */
    .sidebar{
        position:fixed;
        left:0;
        top:0;
        z-index:2000;
        height:100vh;
        width:250px;
        transform:translateX(-100%);
        transition:transform .2s ease;
        overflow-y:auto;
    }

    .sidebar.is-open{
        transform:translateX(0);
    }

    /* overlay */
    .sidebar-overlay{
        position:fixed;
        inset:0;
        background:rgba(0,0,0,.45);
        z-index:1500;
        display:none;
    }

    .sidebar-overlay.is-open{
        display:block;
    }

    /* konten full width saat sidebar tertutup */
    .content{
        margin-left:0;
        padding:18px 12px;
    }

    /* supaya tombol/komponen tidak terlihat terlalu sempit */
    img, svg, video, canvas{
        max-width:100%;
        height:auto;
    }
}


</style>

</head>

<body>

<button
    type="button"
    class="btn btn-primary btn-sm"
    id="sidebarToggle"
    style="position:fixed;top:12px;left:12px;z-index:2100;display:none;"
    aria-label="Buka menu">
    <i class="bi bi-list"></i>
</button>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="sidebar">

<h3>
Posyandu Online
</h3>


<a href="/dashboard">
<i class="bi bi-speedometer2"></i>
Dashboard
</a>

<a href="/balita">
<i class="bi bi-people"></i>
Data Balita
</a>

<?php if(session()->get('role') !== 'orangtua'): ?>
<a href="/pengukuran">
<i class="bi bi-clipboard2-pulse"></i>
Pengukuran
</a>
<?php endif; ?>

<a href="/dashboard/statistik">
<i class="bi bi-bar-chart"></i>
Statistik
</a>

<?php if(session()->get('role') !== 'orangtua'): ?>
<a href="/mapping">
<i class="bi bi-geo-alt"></i>
Peta Sebaran
</a>
<?php endif; ?>

<?php if(session()->get('role') !== 'orangtua'): ?>
<a href="/laporan">
<i class="bi bi-file-earmark-pdf"></i>
Laporan
</a>
<?php endif; ?>

<a href="/konsultasi">
<i class="bi bi-whatsapp"></i>
Konsultasi
</a>

<?php if(session()->get('role')!='orangtua'): ?>

<a href="/classuser">

<i class="bi bi-person-gear"></i>

User

</a>

<?php endif; ?>

<a href="/logout">
<i class="bi bi-box-arrow-right"></i>
Logout
</a>

<li class="nav-item">

<a
class="nav-link"
href="/profil">

Profil

</a>

</li>

</div>

<div class="content">

<div class="container-fluid px-2 px-md-0">

<?= $this->renderSection('content'); ?>

</div>

<script>
(function(){
  const toggleBtn = document.getElementById('sidebarToggle');
  const overlay = document.getElementById('sidebarOverlay');
  const sidebar = document.querySelector('.sidebar');

  function setInitial(){
    if(window.innerWidth <= 768){
      toggleBtn && (toggleBtn.style.display = 'block');
    }
  }

  function openSidebar(){
    if(!sidebar || !overlay) return;
    sidebar.classList.add('is-open');
    overlay.classList.add('is-open');
  }

  function closeSidebar(){
    if(!sidebar || !overlay) return;
    sidebar.classList.remove('is-open');
    overlay.classList.remove('is-open');
  }

  if(toggleBtn){
    toggleBtn.addEventListener('click', function(){
      const isOpen = sidebar && sidebar.classList.contains('is-open');
      if(isOpen) closeSidebar(); else openSidebar();
    });
  }

  if(overlay){
    overlay.addEventListener('click', closeSidebar);
  }

  window.addEventListener('resize', function(){
    setInitial();
    // kalau desktop, pastikan sidebar tidak terkunci terbuka
    if(window.innerWidth > 768){
      closeSidebar();
    }
  });

  setInitial();
})();
</script>

</div>

</body>

</html>

