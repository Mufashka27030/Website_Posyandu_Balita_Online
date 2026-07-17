<h2>
Dashboard Statistik Stunting
</h2>

<?= $this->extend('layout/main'); ?>

<?= $this->section('content'); ?>

<?php if(isset($warning) && $warning > 0): ?>

<h3 style="color:red;">

Ada <?= $warning; ?>
Balita Stunting Berat

</h3>

<?php endif; ?>

<?php if(isset($persentase)): ?>

    <?php if($persentase >= 20): ?>

    <h2 style="color:red;">

        WARNING:
        Tingkat stunting tinggi!

    </h2>

    <?php endif; ?>

<?php endif; ?>


<!-- <a href="/dashboard/statistik">

    Dashboard Statistik

</a> -->

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<canvas id="grafikStatistik"></canvas>

<script type="text/javascript">

const ctx = document.getElementById('grafikStatistik');

new Chart(ctx, {

    type: 'bar',

    data: {

        labels: [
            'Normal',
            'Stunting',
            'Stunting Berat'
        ],

        datasets: [{

            label: 'Jumlah Balita',

            data: [

                <?= isset($total_normal) ? $total_normal : 0; ?>,

                <?= isset($total_stunting) ? $total_stunting : 0; ?>,

                <?= isset($stunting_berat) ? $stunting_berat : 0; ?>
            ],

            borderWidth: 1
        }]
    }
});

</script>

<hr>

<h3>
Total Balita:
<?= isset($total_balita) ? $total_balita : 0; ?>
</h3>

<h3>
Balita Normal:
<?= isset($total_normal) ? $total_normal : 0; ?>
</h3>

<h3>
Balita Stunting:
<?= isset($total_stunting) ? $total_stunting : 0; ?>
</h3>

<h3>
Stunting Berat:
<?= isset($stunting_berat) ? $stunting_berat : 0; ?>
</h3>

<h3>
Persentase Stunting:
<?= isset($persentase) ? $persentase : 0; ?>%
</h3>

<?= $this->endSection(); ?>