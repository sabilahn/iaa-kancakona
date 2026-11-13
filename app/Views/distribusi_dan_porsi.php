<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Distribusi dan Porsi</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= site_url('dashboard') ?>">Dashboard</a></div>
                <div class="breadcrumb-item">Distribusi dan Porsi SHU</div>
                <div class="breadcrumb-item"><?= esc($tahun) ?></div>
            </div>
        </div>
        <div class="card card-primary">
            <div class="card-body">
                <form action="<?= site_url('distribusi_dan_porsi') ?>" method="POST">
                    <?= csrf_field() ?>
                    <div class="form-group col-md-4 col-lg-4">
                        <label>Tahun</label>
                        <div class="input-group">
                            <select class="custom-select" id="tahunDispor" name="tahunDispor">
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
                <h2 class="section-title">Porsi dan Distribusi Pendapatan</h2>
                <h4></h4>
                <div class="card-header-action">
                    <a data-collapse="#showPendapatan" class="btn btn-icon btn-primary" href="#"><i class="fas fa-minus"></i></a>
                </div>
            </div>
            <div class="collapse show" id="showPendapatan">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="table-1">
                            <thead>
                                <tr class="text-center">
                                    <th>No.</th>
                                    <th colspan="2">Porsi (%)</th>
                                    <th>SHU (Rp)</th>
                                    <th>Jumlah (Rp)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no           = 0;
                                $total_dispen = 0;
                                foreach ($porsi_dispen as $dispen) :
                                    $no           += 1;
                                    $total_dispen += (float) $dispen['jumlah'];
                                ?>
                                    <tr>
                                        <td class="text-center"><?= $no ?></td>
                                        <td class="text-center"><?= esc((string) $dispen['porsi']) . '%' ?></td>
                                        <td><?= esc((string) $dispen['uraian']) ?></td>
                                        <td class="text-right"><?= number_format((float) $dispen['shu'], 2, ',', '.') ?></td>
                                        <td class="text-right"><?= number_format((float) $dispen['jumlah'], 2, ',', '.') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-right"><b>Jumlah</b></td>
                                    <td class="text-right"><b><?= number_format($total_dispen, 2, ',', '.') ?></b></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-primary">
            <div class="card-header">
                <h2 class="section-title">Porsi dan Distribusi Dana IAA dan Menejemen</h2>
                <h4></h4>
                <div class="card-header-action">
                    <a data-collapse="#showDana" class="btn btn-icon btn-primary" href="#"><i class="fas fa-minus"></i></a>
                </div>
            </div>
            <div class="collapse show" id="showDana">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="table-2">
                            <thead>
                                <tr class="text-center">
                                    <th>No.</th>
                                    <th colspan="2">Porsi (%)</th>
                                    <th>Menejemen (Rp)</th>
                                    <th>Jumlah (Rp)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no          = 0;
                                $total_disda = 0;
                                foreach ($porsi_disda as $disda) :
                                    $no          += 1;
                                    $total_disda += (float) $disda['jumlah'];
                                ?>
                                    <tr>
                                        <td class="text-center"><?= $no ?></td>
                                        <td class="text-center"><?= esc((string) $disda['porsi']) . '%' ?></td>
                                        <td><?= esc((string) $disda['uraian']) ?></td>
                                        <td class="text-right"><?= number_format((float) ($disda['menejemen'] ?? $disda['manajemen'] ?? 0), 2, ',', '.') ?></td>
                                        <td class="text-right"><?= number_format((float) $disda['jumlah'], 2, ',', '.') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-right"><b>Jumlah</b></td>
                                    <td class="text-right"><b><?= number_format($total_disda, 2, ',', '.') ?></b></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

