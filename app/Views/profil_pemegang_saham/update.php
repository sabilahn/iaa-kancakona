<!-- Update -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Profil Pemegang Saham</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= site_url('dashboard') ?>">Dashboard</a></div>
                <div class="breadcrumb-item active"><a href="<?= site_url('profil_pemegang_saham') ?>">Profil Pemegang Saham</a></div>
                <div class="breadcrumb-item">Ubah Data</div>
            </div>
        </div>
        <?php foreach ($profiles as $profile) : ?>
            <div class="section-body">
                <div class="card card-primary">
                    <form action="<?= site_url('profil_pemegang_saham/ubah_data') ?>" method="POST">
                        <?= csrf_field() ?>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-md-4 col-lg-4">
                                    <div class="form-group mb-0">
                                        <label>No. Rekening <font color='#ff0000'> *</font></label>
                                        <input type="text" name="rekening_edt" id="rekening_edt" class="form-control" value="<?= esc((string) $profile['no_rekening'], 'attr') ?>" readonly="">
                                    </div>
                                    <div class="form-group mb-0">
                                        <label>NIK</label>
                                        <input type="number" name="nik_edt" id="nik_edt" class="form-control" value="<?= esc((string) $profile['nik'], 'attr') ?>" required="">
                                    </div>
                                    <div class="form-group mb-0">
                                        <label>Nama <font color='#ff0000'> *</font></label>
                                        <input type="text" name="nama_edt" id="nama_edt" class="form-control" value="<?= esc((string) $profile['nama'], 'attr') ?>" required="">
                                    </div>
                                    <div class="form-group mb-0">
                                        <label>Tempat & Tanggal Lahir</label>
                                        <div class="input-group">
                                            <input type="text" name="tmp_lahir_edt" id="tmp_lahir_edt" class="form-control" placeholder="Tmp. Lahir" value="<?= esc((string) $profile['tmp_lahir'], 'attr') ?>">
                                            <input type="text" name="tgl_lahir_edt" id="tgl_lahir_edt" class="form-control datepicker" value="<?= esc((string) $profile['tgl_lahir'], 'attr') ?>">
                                        </div>
                                    </div>
                                    <div class="form-group mb-0">
                                        <label>Pekerjaan</label>
                                        <select class="custom-select" name="pekerjaan_edt" id="pekerjaan_edt">
                                            <option value="">--Pilih Pekerjaan--</option>
                                            <option value="PNS" <?= $profile['pekerjaan'] === 'PNS' ? 'selected' : '' ?>>PNS</option>
                                            <option value="Wiraswasta" <?= $profile['pekerjaan'] === 'Wiraswasta' ? 'selected' : '' ?>>Wiraswasta</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-0">
                                        <label>No. HP</label>
                                        <input type="number" name="no_hp_edt" id="no_hp_edt" class="form-control" value="<?= esc((string) $profile['no_hp'], 'attr') ?>">
                                    </div>
                                </div>

                                <div class="col-12 col-md-4 col-lg-4">
                                    <div class="form-group mb-0">
                                        <label>Alamat Rumah</label>
                                        <div class="input-group">
                                            <input type="text" name="dsn_edt" id="dsn_edt" class="form-control" placeholder="Dusun" value="<?= esc((string) $profile['dsn'], 'attr') ?>">
                                            <input type="text" name="ds_edt" id="ds_edt" class="form-control" placeholder="Desa" value="<?= esc((string) $profile['ds'], 'attr') ?>">
                                        </div>
                                    </div>
                                    <div class="form-group mb-0">
                                        <label>RT/RW</label>
                                        <div class="input-group">
                                            <input type="number" name="rt_edt" id="rt_edt" class="form-control" placeholder="RT" value="<?= esc((string) $profile['rt'], 'attr') ?>">
                                            <input type="number" name="rw_edt" id="rw_edt" class="form-control" placeholder="RW" value="<?= esc((string) $profile['rw'], 'attr') ?>">
                                        </div>
                                    </div>
                                    <div class="form-group mb-0">
                                        <label>Kecamatan</label>
                                        <input type="text" name="kec_edt" id="kec_edt" class="form-control" value="<?= esc((string) $profile['kec'], 'attr') ?>">
                                    </div>
                                    <div class="form-group mb-0">
                                        <label>Kabupaten</label>
                                        <input type="text" name="kab_edt" id="kab_edt" class="form-control" value="<?= esc((string) $profile['kab'], 'attr') ?>">
                                    </div>
                                    <div class="form-group mb-0">
                                        <label>Usaha yang Dikelola</label>
                                        <input type="text" name="usaha_edt" id="usaha_edt" class="form-control" value="<?= esc((string) $profile['usaha'], 'attr') ?>">
                                    </div>
                                    <div class="form-group mb-0">
                                        <label>Omzet/bulan</label>
                                        <input type="number" name="omzet_edt" id="omzet_edt" class="form-control" value="<?= esc((string) $profile['omzet'], 'attr') ?>">
                                    </div>
                                </div>

                                <div class="col-12 col-md-4 col-lg-4">
                                    <div class="form-group mb-0">
                                        <label>Gaji/bulan</label>
                                        <input type="number" name="gaji_edt" id="gaji_edt" class="form-control" value="<?= esc((string) $profile['gaji'], 'attr') ?>">
                                    </div>
                                    <div class="form-group mb-0">
                                        <label>Tanggal Daftar</label>
                                        <input type="text" name="tgl_daftar_edt" id="tgl_daftar_edt" class="form-control datepicker" value="<?= esc((string) $profile['tgl_daftar'], 'attr') ?>">
                                    </div>
                                    <div class="form-group mb-0">
                                        <label>Nama Ibu Kandung</label>
                                        <input type="text" name="nama_ibu_edt" id="nama_ibu_edt" class="form-control" value="<?= esc((string) $profile['nama_ibu'], 'attr') ?>">
                                    </div>
                                    <div class="form-group mb-0">
                                        <label>Nama Bank</label>
                                        <select class="form-control" name="id_bank_edt_<?= esc((string) $profile['no_rekening'], 'attr') ?>" id="id_bank_edt_<?= esc((string) $profile['no_rekening'], 'attr') ?>">
                                            <option value="">-- Pilih Bank --</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-0">
                                        <label>Rekening Bank</label>
                                        <input type="number" name="rek_bank_edt" id="rek_bank_edt" class="form-control" value="<?= esc((string) $profile['rek_bank'], 'attr') ?>">
                                    </div>
                                    <div class="form-group mb-0 mt-1">
                                        <p>
                                            <font color='#ff0000'> ( * ) Form harus diisi </font>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <a href="<?= site_url('profil_pemegang_saham') ?>" class="btn btn-info">Kembali</a>
                            <button type="submit" name="btn_edit" id="btn_edit" class="btn btn-primary">Perbarui</button>
                        </div>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </section>

    <script>
        $(document).ready(function() {
            <?php foreach ($profiles as $profile) : ?>
                (function() {
                    let selectedBankId = "<?= esc((string) ($profile['id_bank'] ?? ''), 'js') ?>";
                    let selectId = "#id_bank_edt_<?= esc((string) $profile['no_rekening'], 'js') ?>";

                    $.ajax({
                        url: "<?= site_url('profil_pemegang_saham/get_bank') ?>",
                        type: "GET",
                        dataType: "json",
                        success: function(banks) {
                            let select = $(selectId);
                            select.empty().append('<option value="">-- Pilih Bank --</option>');
                            $.each(banks, function(i, bank) {
                                let opt = $('<option>', {
                                    value: bank.id_bank,
                                    text: bank.nama_bank,
                                    selected: (bank.id_bank == selectedBankId)
                                });
                                select.append(opt);
                            });
                        },
                        error: function() {
                            alert("Gagal memuat bank untuk <?= esc((string) $profile['no_rekening'], 'js') ?>");
                        }
                    });
                })();
            <?php endforeach; ?>
        });
    </script>
</div>

