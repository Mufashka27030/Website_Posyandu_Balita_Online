<!DOCTYPE html>
<html>

<head>

<meta charset="utf-8">

<meta name="viewport"
content="width=device-width, initial-scale=1">

<title>Grafik Pertumbuhan</title>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>

body{

    margin:0;

    padding:20px;

    background:#f5f7fa;

    font-family:Arial,sans-serif;
}

.container{

    max-width:1200px;

    margin:auto;
}

.card{

    background:white;

    padding:20px;

    border-radius:15px;

    margin-bottom:20px;

    box-shadow:0 2px 10px rgba(0,0,0,.1);
}

.nama{

    color:#1565c0;

    margin-bottom:10px;
}

.status{

    padding:10px;

    border-radius:10px;

    background:#e8f5e9;

    color:#2e7d32;

    font-weight:bold;
}

.alert{

    background:#ffebee;

    color:#c62828;

    padding:15px;

    border-radius:10px;

    margin-top:15px;
}

canvas{

    width:100% !important;

    height:400px !important;
}

@media(max-width:768px){

canvas{

    height:250px !important;
}

.card{

    padding:15px;
}
}

</style>

</head>

<body>

<div class="container">

<div class="card">

<h2>
📈 Grafik Pertumbuhan Balita
</h2>

<h3 class="nama">

<?= isset($balita['nama_balita'])
? $balita['nama_balita']
: 'Data Tidak Ada'; ?>

</h3>

<div class="status">

Monitoring Pertumbuhan Balita

</div>

<?php if(isset($alert) && $alert != ''): ?>

<div class="alert">

⚠ <?= $alert; ?>

</div>

<?php endif; ?>

</div>

<div class="card">

<h3>
📏 Grafik Tinggi Badan
</h3>

<canvas id="grafikTinggi"></canvas>

</div>

<div class="card">

<h3>
⚖ Grafik Berat Badan
</h3>

<canvas id="grafikBerat"></canvas>

</div>

</div>

<script>

const labels =
<?= isset($labels)
? $labels
: '[]'; ?>;

const dataTinggi =
<?= isset($tinggi)
? $tinggi
: '[]'; ?>;

const dataBerat =
<?= isset($berat)
? $berat
: '[]'; ?>;


// TINGGI BADAN

new Chart(

document.getElementById('grafikTinggi'),

{

type:'line',

data:{

labels:labels,

datasets:[{

label:'Tinggi Badan (cm)',

data:dataTinggi,

tension:0.4,

borderWidth:3,

fill:false

}]
}

}
);


// BERAT BADAN

new Chart(

document.getElementById('grafikBerat'),

{

type:'line',

data:{

labels:labels,

datasets:[{

label:'Berat Badan (kg)',

data:dataBerat,

tension:0.4,

borderWidth:3,

fill:false

}]
}

}
);

</script>

</body>
</html>