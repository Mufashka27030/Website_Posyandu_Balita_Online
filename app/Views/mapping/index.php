<?= $this->extend('layout/main'); ?>

<?= $this->section('content'); ?>

<!DOCTYPE html>
<html>

<head>

<meta charset="utf-8">

<meta name="viewport"
content="width=device-width, initial-scale=1">

<title>Peta Sebaran Stunting</title>

<link rel="stylesheet"
href="https://unpkg.com/leaflet/dist/leaflet.css"/>

<script
src="https://unpkg.com/leaflet/dist/leaflet.js">
</script>

<style>

body{

    margin:0;

    background:#f5f7fa;

    font-family:Arial,sans-serif;
}

.container{

    padding:20px;
}

.card{

    background:white;

    border-radius:15px;

    padding:20px;

    margin-bottom:20px;

    box-shadow:0 2px 10px rgba(0,0,0,.1);
}

.statistik{

    display:grid;

    grid-template-columns:
    repeat(auto-fit,minmax(200px,1fr));

    gap:15px;

    margin-bottom:20px;
}

.box{

    text-align:center;

    padding:20px;

    border-radius:15px;

    color:white;

    font-weight:bold;
}

.normal{

    background:#4caf50;
}

.stunting{

    background:#ff9800;
}

.berat{

    background:#f44336;
}

#map{

    height:600px;

    border-radius:15px;
}

@media(max-width:768px){

#map{

    height:450px;
}
}

</style>

</head>

<body>

<div class="container">

<div class="card">

<h2>

🗺️ Peta Sebaran Stunting

</h2>

<p>

Monitoring lokasi balita berdasarkan hasil pengukuran terakhir.

</p>

</div>


<div class="statistik">

<div class="box normal">

Normal

<br><br>

<?= isset($normal)
? $normal
: 0; ?>

</div>

<div class="box stunting">

Stunting

<br><br>

<?= isset($stunting)
? $stunting
: 0; ?>

</div>

<div class="box berat">

Stunting Berat

<br><br>

<?= isset($berat)
? $berat
: 0; ?>

</div>

</div>


<div class="card">

<div id="map"></div>

</div>

</div>


<script>

var map = L.map('map').setView(

[-7.952,112.633],

13

);

L.tileLayer(

'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',

{

attribution:'OpenStreetMap'

}

).addTo(map);


var dataBalita =

<?= isset($balita)
? json_encode($balita)
: '[]'; ?>;


dataBalita.forEach(function(item){

var warna = 'green';

if(item.status == 'Stunting'){

warna = 'orange';
}

if(item.status == 'Stunting Berat'){

warna = 'red';
}

L.circleMarker(

[item.latitude,item.longitude],

{

color:warna,

fillColor:warna,

fillOpacity:0.8,

radius:10

}

)

.addTo(map)

.bindPopup(

'<b>'+item.nama+'</b><br>'+

item.alamat+'<br><br>'+

'Status: '+item.status

);

});

</script>

</body>

</html>

<?= $this->endSection(); ?>