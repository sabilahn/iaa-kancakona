<!-- Read -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Profil Pemegang Saham</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= site_url('dashboard') ?>">Dashboard</a></div>
                <div class="breadcrumb-item active"><a href="<?= site_url('profil_pemegang_saham') ?>">Profil Pemegang Saham</a></div>
                <div class="breadcrumb-item">Lihat Data</div>
            </div>
        </div>
        <?php foreach ($profiles as $profile) : ?>
            <div class="section-body">
                <div class="card card-primary">
                    <form action="">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-md-4 col-lg-4">
                                    <div class="form-group mb-0">
                                        <label>No. Rekening</label>
                                        <input class="form-control" value="<?= esc((string) $profile['no_rekening'], 'attr') ?>" readonly="">
                                    </div>
                                    <div class="form-group mb-0">
                                        <label>NIK</label>
                                        <input class="form-control" value="<?= esc((string) $profile['nik'], 'attr') ?>" readonly="">
                                    </div>
                                    <div class="form-group mb-0">
                                        <label>Nama</label>
                                        <input class="form-control" value="<?= esc((string) $profile['nama'], 'attr') ?>" readonly="">
                                    </div>
                                    <div class="form-group mb-0">
                                        <label>Tempat & Tanggal Lahir</label>
                                        <div class="input-group">
                                            <input class="form-control" placeholder="Tmp. Lahir" value="<?= esc((string) $profile['tmp_lahir'], 'attr') ?>" readonly="">
                                            <input type="text" name="tgl_lahir" id="tgl_lahir" class="form-control datepicker" value="<?= esc((string) $profile['tgl_lahir'], 'attr') ?>" readonly="">
                                        </div>
                                    </div>
                                    <div class="form-group mb-0">
                                        <label>Pekerjaan</label>
                                        <select class="custom-select" disabled>
                                            <option value="">--Pilih Pekerjaan--</option>
                                            <option value="PNS" <?= $profile['pekerjaan'] === 'PNS' ? 'selected' : '' ?>>PNS</option>
                                            <option value="Wiraswasta" <?= $profile['pekerjaan'] === 'Wiraswasta' ? 'selected' : '' ?>>Wiraswasta</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-0">
                                        <label>No. HP</label>
                                        <input class="form-control" value="<?= esc((string) $profile['no_hp'], 'attr') ?>" readonly="">
                                    </div>
                                </div>
                                <div class="col-12 col-md-4 col-lg-4">
                                    <div class="form-group mb-0">
                                        <label>Alamat Rumah</label>
                                        <div class="input-group">
                                            <input class="form-control" placeholder="Dusun" value="<?= esc((string) $profile['dsn'], 'attr') ?>" readonly="">
                                            <input class="form-control" placeholder="Desa" value="<?= esc((string) $profile['ds'], 'attr') ?>" readonly="">
                                        </div>
                                    </div>
                                    <div class="form-group mb-0">
                                        <label>RT/RW</label>
                                        <div class="input-group">
                                            <input class="form-control" placeholder="RT" value="<?= esc((string) $profile['rt'], 'attr') ?>" readonly="">
                                            <input class="form-control" placeholder="RW" value="<?= esc((string) $profile['rw'], 'attr') ?>" readonly="">
                                        </div>
                                    </div>
                                    <div class="form-group mb-0">
                                        <label>Kecamatan</label>
                                        <input class="form-control" value="<?= esc((string) $profile['kec'], 'attr') ?>" readonly="">
                                    </div>
                                    <div class="form-group mb-0">
                                        <label>Kabupaten</label>
                                        <input class="form-control" value="<?= esc((string) $profile['kab'], 'attr') ?>" readonly="">
                                    </div>
                                    <div class="form-group mb-0">
                                        <label>Usaha yang Dikelola</label>
                                        <input class="form-control" value="<?= esc((string) $profile['usaha'], 'attr') ?>" readonly="">
                                    </div>
                                    <div class="form-group mb-0">
                                        <label>Omzet/bulan</label>
                                        <input class="form-control" value="<?= esc((string) $profile['omzet'], 'attr') ?>" readonly="">
                                    </div>
                                </div>
                                <div class="col-12 col-md-4 col-lg-4">
                                    <div class="form-group mb-0">
                                        <label>Gaji/bulan</label>
                                        <input class="form-control" value="<?= esc((string) $profile['gaji'], 'attr') ?>" readonly="">
                                    </div>
                                    <div class="form-group mb-0">
                                        <label>Tanggal Daftar</label>
                                        <input type="text" name="tgl_daftar" id="tgl_daftar" class="form-control datepicker" value="<?= esc((string) $profile['tgl_daftar'], 'attr') ?>" readonly="">
                                    </div>
                                    <div class="form-group mb-0">
                                        <label>Nama Ibu Kandung</label>
                                        <input class="form-control" value="<?= esc((string) $profile['nama_ibu'], 'attr') ?>" readonly="">
                                    </div>
                                    <div class="form-group mb-0">
                                        <label>Nama Bank</label>
                                        <input class="form-control" name="nama_bank" id="nama_bank" readonly="">
                                        <input type="hidden" class="form-control" name="id_bank" id="id_bank" value="<?= esc((string) ($profile['id_bank'] ?? ''), 'attr') ?>" readonly="">
                                    </div>
                                    <div class="form-group mb-0">
                                        <label>Rekening Bank</label>
                                        <input type="number" name="rek_bank" id="rek_bank" class="form-control" value="<?= esc((string) $profile['rek_bank'], 'attr') ?>" readonly="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <a href="<?= site_url('profil_pemegang_saham') ?>" class="btn btn-info">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </section>

    <script>
        $(document).ready(function() {
            const id_bank = $("#id_bank").val();
            $.ajax({
                url: "<?= site_url('profil_pemegang_saham/get_bank') ?>",
                method: "GET",
                dataType: "json",
                success: function(response) {
                    let namaBank = "";
                    $.each(response, function(index, bank) {
                        if (bank.id_bank == id_bank) {
                            namaBank = bank.nama_bank;
                            return false;
                        }
                    });
                    $("#nama_bank").val(namaBank);
                },
                error: function() {
                    $("#nama_bank").val("");
                }
            });
        });
    </script>
</div>

