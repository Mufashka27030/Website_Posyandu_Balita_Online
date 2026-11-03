<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0">

<title>
Sistem Monitoring Stunting
</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<link
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
rel="stylesheet">

<style>

body{

    background:#f4f7fb;

    font-family:'Segoe UI',sans-serif;
}


/* CARD */

.card{

    border:none;

    border-radius:15px;
}


/* HEADER PAGE */

.page-title{

    font-size:28px;

    font-weight:700;

    color:#1e3a8a;
}


/* BUTTON */

.btn-primary{

    background:#2563eb;

    border:none;
}

.btn-success{

    background:#16a34a;

    border:none;
}


/* TABLE */

.table{

    background:white;
}


/* MOBILE */

@media(max-width:768px){

    .page-title{

        font-size:22px;
    }

}

/* SIDEBAR */

.sidebar{

width:250px;

min-height:100vh;

background:#1e3a8a;

color:white;

position:fixed;

left:0;

top:0;

overflow-y:auto;
}

.sidebar-header{

padding:20px;

text-align:center;

border-bottom:1px solid rgba(255,255,255,.2);
}

.user-panel{

padding:20px;

text-align:center;

border-bottom:1px solid rgba(255,255,255,.15);

}

.user-avatar{

width:75px;

height:75px;

border-radius:50%;

background:white;

color:#1e3a8a;

display:flex;

align-items:center;

justify-content:center;

font-size:32px;

margin:auto;

margin-bottom:10px;

}

.user-name{

font-weight:bold;

font-size:17px;

margin-bottom:8px;

}

.role-badge{

display:inline-block;

padding:6px 15px;

border-radius:20px;

font-size:13px;

font-weight:bold;

}

.role-admin{

background:#dc3545;

color:white;

}

.role-kader{

background:#198754;

color:white;

}

.role-orangtua{

background:#0d6efd;

color:white;

}

.online{

margin-top:8px;

font-size:13px;

color:#90ee90;

}

.sidebar-menu{

list-style:none;

margin:0;

padding:0;
}

.sidebar-menu li{

border-bottom:1px solid rgba(255,255,255,.05);
}

.sidebar-menu a{

display:block;

color:white;

text-decoration:none;

padding:15px 20px;

transition:.3s;
}

.sidebar-menu a:hover{

background:#16a34a;

color:white;
}

.sidebar-menu i{

width:25px;
}

.footer{

background:white;

padding:20px;

margin-top:40px;

border-top:1px solid #dee2e6;

color:#6c757d;

font-size:14px;
}

.main-content{

margin-left:250px;

padding:25px;
}

@media(max-width:768px){

.main-content{

margin-left:0;

}

}

</style>

</head>

<body>