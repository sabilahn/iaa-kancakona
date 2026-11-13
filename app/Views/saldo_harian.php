<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Saldo Harian</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= site_url('dashboard') ?>">Dashboard</a></div>
                <div class="breadcrumb-item">Saldo Harian</div>
                <div class="breadcrumb-item"><?= esc($tahun) ?></div>
            </div>
        </div>
        <div class="card card-primary">
            <div class="card-body">
                <form action="<?= site_url('saldo_harian') ?>" method="POST">
                    <?= csrf_field() ?>
                    <div class="form-group col-md-4 col-lg-4">
                        <label>Tahun</label>
                        <div class="input-group">
                            <select class="custom-select" id="tahun_saldo" name="tahun_saldo">
                                <option value="">--Pilih Tahun--</option>
                                <?php
                                $thn_skr = (int) date('Y');
                                for ($x = $thn_skr; $x >= $thn_skr - 5; $x--) :
                                ?>
                                    <option value="<?= $x ?>" <?= (string) $tahun === (string) $x ? 'selected' : '' ?>><?= $x ?></option>
                                <?php endfor; ?>
                            </select>
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">OK</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card card-primary">
            <div class="card-header">
                <h2 class="section-title">Saldo Harian</h2>
                <h4></h4>
                <div class="card-header-action">
                    <a class="btn btn-primary" href="<?= site_url('saldo_harian/update_saldo/' . rawurlencode((string) $tahun)) ?>"><i class="fas fa-sync"> Sesuaikan Saldo</i></a>
                </div>
            </div>
            <div class="card-body">
                <?php if (session()->getFlashdata('success')) : ?>
                    <div class="alert alert-success alert-dismissible show fade">
                        <div class="alert-body text-center">
                            <button class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                            <b><?= esc(session()->getFlashdata('success')) ?></b>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('error')) : ?>
                    <div class="alert alert-danger alert-dismissible show fade">
                        <div class="alert-body text-center">
                            <button class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                            <b><?= esc(session()->getFlashdata('error')) ?></b>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="table-responsive">
                    <table class="table table-striped" id="table-1">
                        <thead>
                            <tr class="text-center">
                                <th>No.</th>
                                <th>Nomor Rekening</th>
                                <th>Tanggal Daftar</th>
                                <th>Nilai Investasi</th>
                                <th>Saldo Harian (Rp)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 0;
                            foreach ($daftar_saldo as $saldo) :
                                $no += 1;
                            ?>
                                <tr>
                                    <td class="text-center"><?= $no ?></td>
                                    <td class="text-center"><?= esc((string) $saldo['no_rekening']) ?></td>
                                    <td class="text-center"><?= esc((string) $saldo['tgl_daftar']) ?></td>
                                    <td class="text-right"><?= number_format((float) $saldo['nilai_investasi'], 2, ',', '.') ?></td>
                                    <td class="text-right"><?= number_format((float) $saldo['rata_rata_tahun'], 2, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
<div id="statusBatch"></div>

