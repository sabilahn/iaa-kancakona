<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Transaksi Investasi</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= site_url('dashboard') ?>">Dashboard</a></div>
                <div class="breadcrumb-item">Transaksi Investasi</div>
            </div>
        </div>
        <div class="card card-primary">
            <div class="card-body">
                <form action="<?= site_url('transaksi_investasi') ?>" method="POST">
                    <?= csrf_field() ?>
                    <div class="form-group col-md-4 col-lg-4">
                        <label>Tahun</label>
                        <div class="input-group">
                            <select class="custom-select" id="tahun_transaksi" name="tahun_transaksi">
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
                <h2 class="section-title">Daftar Transaksi</h2>
                <h4></h4>
                <div class="card-header-action">
                    <a class="btn btn-warning" href="<?= site_url('transaksi_investasi/migrasi/' . rawurlencode((string) $tahun)) ?>"><i class="fas fa-sync"> Jadikan Saldo Awal</i></a>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#tambahModal"><i class="fas fa-plus"> Tambah Transaksi</i></button>
                </div>
            </div>
            <div class="card">
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
                                    <th>Nomor Sertifikat</th>
                                    <th>Tanggal Transaksi</th>
                                    <th>Jumlah Transaksi (Rp)</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 0;
                                foreach ($data_transaksi as $transaksi) :
                                    $no            += 1;
                                    $id_transaksi  = (int) $transaksi['id_transaksi'];
                                    $no_rekening   = (string) $transaksi['no_rekening'];
                                    $no_sertifikat = (string) $transaksi['no_sertifikat'];
                                    $tgl_transaksi = (string) $transaksi['tgl_transaksi'];
                                    $jml_transaksi = (float) $transaksi['jml_transaksi'];
                                ?>
                                    <tr>
                                        <td class="text-center"><?= $no ?></td>
                                        <td class="text-center"><?= esc($no_rekening) ?></td>
                                        <td class="text-center"><?= esc($no_sertifikat) ?></td>
                                        <td class="text-center"><?= esc($tgl_transaksi) ?></td>
                                        <td class="text-right"><?= number_format($jml_transaksi, 2, ',', '.') ?></td>
                                        <td class="text-center">
                                            <?php if (! empty($transaksi['is_saldo_awal'])) : ?>
                                                <button class="btn btn-sm btn-info" disabled><i class="fa fa-ban"></i></button>
                                            <?php else : ?>
                                                <a target="_blank" href="<?= site_url('transaksi_investasi/cetak_surat_saham/' . $id_transaksi . '/' . rawurlencode($no_rekening)) ?>" class="btn btn-sm btn-info"><i class="fa fa-print"></i></a>
                                            <?php endif; ?>
                                            <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editModal<?= $id_transaksi ?>"><i class="fas fa-pencil-alt"></i></button>
                                            <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#hapusModal<?= $id_transaksi ?>"><i class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- Tambah -->
<div class="modal fade" role="dialog" id="tambahModal">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Transaksi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= site_url('transaksi_investasi/tambah_data_transaksi') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="card card-primary">
                        <br>
                        <div class="form-group mb-0">
                            <label>No. Rekening</label><br>
                            <select name="rekening" id="rekening" class="form-control select2" style="width: 100%">
                                <?php foreach ($data_rekening as $list) : ?>
                                    <option value="<?= esc((string) $list['no_rekening'], 'attr') ?>"><?= esc((string) $list['no_rekening']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group mb-0">
                            <label>No. Sertifikat</label>
                            <input type="text" name="sertifikat" id="sertifikat" class="form-control" required="">
                        </div>
                        <div class="form-group mb-0">
                            <label>Tanggal Transaksi</label>
                            <input type="text" name="tgl_transaksi" id="tgl_transaksi" class="form-control datepicker" required="">
                        </div>
                        <div class="form-group mb-0">
                            <label>Jumlah Transaksi</label>
                            <input type="number" name="jml_transaksi" id="jml_transaksi" class="form-control" required="">
                        </div>
                        <div class="modal-footer bg-whitesmoke br">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Tambah</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Update -->
<?php foreach ($data_transaksi as $transaksi) :
    $id_transaksi = (int) $transaksi['id_transaksi'];
?>
    <div class="modal fade" tabindex="-1" role="dialog" id="editModal<?= $id_transaksi ?>">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Data Transaksi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="<?= site_url('transaksi_investasi/ubah_data_transaksi') ?>" method="POST">
                    <?= csrf_field() ?>
                    <div class="modal-body">
                        <div class="card card-primary">
                            <br>
                            <input type="hidden" name="id_transaksi" value="<?= $id_transaksi ?>">
                            <input type="hidden" name="rekening_edt" value="<?= esc((string) $transaksi['no_rekening'], 'attr') ?>">
                            <div class="form-group mb-0">
                                <label>No. Sertifikat</label>
                                <input type="text" name="sertifikat_edt" class="form-control" value="<?= esc((string) $transaksi['no_sertifikat'], 'attr') ?>">
                            </div>
                            <div class="form-group mb-0">
                                <label>Tanggal Transaksi</label>
                                <input type="text" name="tgl_transaksi_edt" class="form-control datepicker" value="<?= esc((string) $transaksi['tgl_transaksi'], 'attr') ?>" required="">
                            </div>
                            <div class="form-group mb-0">
                                <label>Jumlah Transaksi</label>
                                <input type="number" name="jml_transaksi_edt" class="form-control" value="<?= esc((string) $transaksi['jml_transaksi'], 'attr') ?>" required="">
                            </div>
                            <div class="modal-footer bg-whitesmoke br">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Perbarui</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; ?>
<!-- Hapus -->
<?php foreach ($data_transaksi as $transaksi) :
    $id_transaksi = (int) $transaksi['id_transaksi'];
?>
    <div class="modal fade" tabindex="-1" role="dialog" id="hapusModal<?= $id_transaksi ?>">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Hapus Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="<?= site_url('transaksi_investasi/hapus_data_transaksi') ?>" method="POST">
                    <?= csrf_field() ?>
                    <div class="modal-body">
                        <div class="card card-danger">
                            <br>
                            <input type="hidden" name="id_transaksi" value="<?= $id_transaksi ?>">
                            <input type="hidden" name="rekening_hps" value="<?= esc((string) $transaksi['no_rekening'], 'attr') ?>">
                            <input type="hidden" name="tgl_transaksi_hps" value="<?= esc((string) $transaksi['tgl_transaksi'], 'attr') ?>">
                            Yakin untuk menghapus data dengan kode <?= esc((string) $transaksi['no_rekening']) ?> ?
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; ?>

