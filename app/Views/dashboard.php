<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Dashboard Sistem Investasi</h1>
        </div>
        <div class="section-body">
            <?php if (session()->getFlashdata('error')) : ?>
                <div class="alert alert-danger alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                        <?= esc((string) session()->getFlashdata('error')) ?>
                    </div>
                </div>
            <?php endif; ?>
            <div class="row">
                <div class="col-12 col-md-6 col-lg-12">
                    <div class="card card-primary">
                        <h4 class="card-header"><?= esc(ucfirst((string) session()->get('username'))) ?></h4>
                        <h2 class="section-title">Panduan petunjuk penggunaan aplikasi investasi dapat diunduh pada lampiran dibawah ini.</h2>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

