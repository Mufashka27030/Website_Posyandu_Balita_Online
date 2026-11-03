<?= $this->include('layout/header'); ?>

<div class="container">

<div class="row justify-content-center mt-5">

<div class="col-lg-4 col-md-6">

<div class="card shadow border-0">

<div class="card-body p-4">

<div class="text-center mb-4">

<h2 class="fw-bold text-primary">

Posyandu Balita Online

</h2>

<p class="text-muted">

Monitoring Stunting Balita

</p>

</div>

<?= flash_block('error', 'alert-danger'); ?>

<form action="<?= site_url('auth/prosesLogin') ?>" method="post">

<?= csrf_field(); ?>

<div class="mb-3">

<label>Email</label>

<input
type="email"
name="email"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Password</label>

<input
type="password"
name="password"
class="form-control"
required>

</div>

<button
type="submit"
class="btn btn-primary w-100">

Login

</button>

</form>

<hr>

<div class="text-center">

Belum punya akun atau lupa password?

Hubungi kader/admin posyandu Anda, atau lihat <a href="<?= site_url('faq') ?>">Pertanyaan yang Sering Diajukan</a>.

</div>

</div>

</div>

</div>

</div>

</div>

<?= $this->include('layout/footer'); ?>
