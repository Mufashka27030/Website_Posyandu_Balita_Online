<?= $this->include('layout/header'); ?>

<?= $this->extend('layout/main'); ?>

<?= $this->section('content'); ?>

    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-lg-5 col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Registrasi User</h4>
                    </div>
                        <div class="card-body">

    <form action="<?= site_url('auth/simpanRegister') ?>" method="post">
        <?= csrf_field(); ?>

            <?= flash_block('error', 'alert-danger'); ?>

    <div class="mb-3">
        <label>Nama Lengkap</label>
        <input type="text" name="nama" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Email</label>
            <input type="email" name="email" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Password</label>
            <input type="password" name="password" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>No. HP</label>
            <input type="text" name="no_hp" class="form-control" placeholder="08xxxxxxxxxx">
    </div>

    <div class="mb-3">
        <label>Alamat</label>
            <textarea name="alamat" class="form-control" rows="2" placeholder="Alamat lengkap"></textarea>
    </div>

    <div class="mb-3">
        <label>Role User</label>
            <select name="role" class="form-control" required>
                <?php if(session()->get('role') == 'admin'): ?>
                    <option value="admin">Admin</option>
                    <option value="kader">Kader</option>
                    <option value="orangtua">Orang Tua</option>
                <?php endif; ?>
                <?php if(session()->get('role') == 'kader'): ?>
                    <option value="orangtua">Orang Tua</option>
                <?php endif; ?>
            </select>
    </div>

        <button class="btn btn-primary">Daftar</button>
    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>