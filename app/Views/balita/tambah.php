<?= $this->extend('layout/main'); ?>

<?= $this->section('content'); ?>


<?php

/** @var array $orangtua */

?>

<!DOCTYPE html>
<html>

<head>

    <title>Tambah Balita</title>

    <meta name="viewport"
        content="width=device-width, initial-scale=1">

    <style>
        body {
            background: #f5f7fb;
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        .card {

            max-width: 700px;

            margin: auto;

            background: white;

            padding: 30px;

            border-radius: 15px;

            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {

            margin-bottom: 20px;
        }

        .form-group {

            margin-bottom: 15px;
        }

        label {

            display: block;

            margin-bottom: 5px;

            font-weight: bold;
        }

        input,
        select,
        textarea {

            width: 100%;

            padding: 12px;

            border: 1px solid #ddd;

            border-radius: 10px;

            box-sizing: border-box;
        }

        .button-group {

            margin-top: 20px;

            display: flex;

            gap: 10px;

            flex-wrap: wrap;
        }

        .btn {

            text-decoration: none;

            border: none;

            padding: 12px 20px;

            border-radius: 10px;

            cursor: pointer;
        }

        .btn-primary {

            background: #2563eb;

            color: white;
        }

        .btn-secondary {

            background: #e5e7eb;

            color: black;
        }
    </style>

</head>

<script>
    const selectUser =
        document.getElementById('id_user');

    const preview =
        document.getElementById('nama_ibu_preview');

    function updatePreview() {

        const option =
            selectUser.options[
                selectUser.selectedIndex
            ];

        preview.value =
            option.dataset.nama || '';

    }

    selectUser.addEventListener(
        'change',
        updatePreview
    );

    updatePreview();
</script>

<body>

    <div class="card">

        <h2>
            Tambah Data Balita
        </h2>

        <form action="/balita/simpan"
            method="post">

            <?= csrf_field(); ?>

            <?php if (session()->getFlashdata('error')): ?>

                <div style="background:#fee2e2;color:#991b1b;padding:12px;border-radius:10px;margin-bottom:15px;">

                    <?= session()->getFlashdata('error'); ?>

                </div>

            <?php endif; ?>

            <div class="form-group">

                <label>Nama Balita</label>

                <input
                    type="text"
                    name="nama_balita"
                    value="<?= esc(old('nama_balita'), 'attr'); ?>"
                    required>

            </div>

            <div class="form-group">

                <label>Orang Tua / Pemilik Akun</label>

                <select id="id_user" name="id_user" required>

                    <option value="">
                        -- Pilih Orang Tua --
                    </option>
                    <?php foreach ($orangtua as $user): ?>

                        <option
                            value="<?= $user['id_user']; ?>">

                            <?= esc($user['nama']); ?>

                        </option>

                    <?php endforeach; ?>

                </select>

            </div>

            <div class="form-group">

                <label>Nama Ibu</label>

                <input
                    type="text"
                    name="nama_ibu"
                    value="<?= esc(old('nama_ibu'), 'attr'); ?>">

            </div>

            <div class="form-group">

                <label>Jenis Kelamin</label>

                <select name="jenis_kelamin">

                    <option value="L" <?= old('jenis_kelamin') === 'L' ? 'selected' : ''; ?>>
                        Laki-laki
                    </option>

                    <option value="P" <?= old('jenis_kelamin') === 'P' ? 'selected' : ''; ?>>
                        Perempuan
                    </option>

                </select>

            </div>

            <div class="form-group">

                <label>Tanggal Lahir</label>

                <input
                    type="date"
                    name="tanggal_lahir"
                    value="<?= esc(old('tanggal_lahir'), 'attr'); ?>"
                    required>

            </div>

            <div class="form-group">

                <label>Alamat</label>

                <textarea
                    name="alamat"
                    rows="3"><?= esc(old('alamat')); ?></textarea>

            </div>

            <div class="form-group">

                <label>Latitude</label>

                <input
                    type="text"
                    name="latitude"
                    value="<?= esc(old('latitude'), 'attr'); ?>">

            </div>

            <div class="form-group">

                <label>Longitude</label>

                <input
                    type="text"
                    name="longitude"
                    value="<?= esc(old('longitude'), 'attr'); ?>">

            </div>

            <div class="button-group">

                <button
                    type="submit"
                    class="btn btn-primary">

                    Simpan

                </button>

            </div>

        </form>

    </div>

</body>

</html>

<?= $this->endSection(); ?>