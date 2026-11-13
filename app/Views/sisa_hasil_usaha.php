<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Sisa Hasil Usaha</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= site_url('dashboard') ?>">Dashboard</a></div>
                <div class="breadcrumb-item">Sisa Hasil Usaha</div>
            </div>
        </div>
        <div class="card card-primary">
            <div class="card-header">
                <h2 class="section-title">Daftar Sisa Hasil Usaha</h2>
                <h4></h4>
                <div class="card-header-action">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#tambahModal"><i class="fas fa-plus"> Tambah SHU</i></button>
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
                                    <th>Tahun</th>
                                    <th>Jumlah (Rp)</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 0;
                                foreach ($data_shu as $shu) :
                                    $no += 1;
                                ?>
                                    <tr>
                                        <td class="text-center"><?= $no ?></td>
                                        <td class="text-center"><?= esc((string) $shu['tahun']) ?></td>
                                        <td class="text-right"><?= number_format((float) $shu['jumlah'], 2, ',', '.') ?></td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editModal<?= (int) $shu['id_shu'] ?>"><i class="fas fa-pencil-alt"></i></button>
                                            <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#hapusModal<?= (int) $shu['id_shu'] ?>"><i class="fa fa-trash"></i></button>
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
                <h5 class="modal-title">Tambah Data SHU</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= site_url('sisa_hasil_usaha/tambah_data_shu') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="card card-primary">
                        <br>
                        <div class="form-group mb-0">
                            <label>Tahun</label>
                            <select name="tahun_add" id="tahun_add" class="form-control select">
                                <?php
                                $thn_skr = (int) date('Y');
                                for ($x = $thn_skr; $x >= $thn_skr - 10; $x--) :
                                ?>
                                    <option value="<?= $x ?>"><?= $x ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="form-group mb-0">
                            <label>Jumlah SHU</label>
                            <input type="number" name="jml_shu" id="jml_shu" class="form-control" required="">
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
<!-- Edit -->
<?php foreach ($data_shu as $shu) : ?>
    <div class="modal fade" role="dialog" id="editModal<?= (int) $shu['id_shu'] ?>">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Data SHU</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="<?= site_url('sisa_hasil_usaha/ubah_data_shu') ?>" method="POST">
                    <?= csrf_field() ?>
                    <div class="modal-body">
                        <div class="card card-primary">
                            <br>
                            <input type="hidden" name="id_shu" value="<?= (int) $shu['id_shu'] ?>">
                            <div class="form-group mb-0">
                                <label>Tahun</label>
                                <select name="tahun_edt" class="form-control select">
                                    <?php
                                    $thn_skr = (int) date('Y');
                                    for ($x = $thn_skr; $x >= $thn_skr - 10; $x--) :
                                    ?>
                                        <option value="<?= $x ?>" <?= (string) $shu['tahun'] === (string) $x ? 'selected' : '' ?>><?= $x ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="form-group mb-0">
                                <label>Jumlah SHU</label>
                                <input type="number" name="jml_shu_edt" value="<?= esc((string) $shu['jumlah'], 'attr') ?>" class="form-control" required="">
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
<?php foreach ($data_shu as $shu) : ?>
    <div class="modal fade" tabindex="-1" role="dialog" id="hapusModal<?= (int) $shu['id_shu'] ?>">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Hapus Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="<?= site_url('sisa_hasil_usaha/hapus_data_shu') ?>" method="POST">
                    <?= csrf_field() ?>
                    <div class="modal-body">
                        <div class="card card-danger">
                            <br>
                            <input type="hidden" name="id_shu" value="<?= (int) $shu['id_shu'] ?>">
                            <input type="hidden" name="tahun_hps" value="<?= esc((string) $shu['tahun'], 'attr') ?>">
                            Yakin untuk menghapus data pada tahun <?= esc((string) $shu['tahun']) ?> ?
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

