<!DOCTYPE html>
<html lang="en">

<head>
    <title><?= esc((string) $title) ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            top: 5px;
            left: 5px;
            right: 5px;
            bottom: 5px;
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        }

        td {
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            font-size: 20px;
            height: 25px;
        }

        .box {
            width: 760px;
            height: 389px;
            position: absolute;
            background-image: url('<?= $base642 ?>');
            background-size: cover;
        }

        .sisi-1 {
            text-align: center;
            padding: 55px;
            margin-top: 240px;
        }

        .sisi-2 {
            width: 580px;
            margin-left: 350px;
            height: 360px;
            text-align: center;
            padding: 20px;
        }

        .logo-title {
            vertical-align: middle;
            margin: auto;
        }

        .isi {
            vertical-align: middle;
        }

        .text-left {
            text-align: left;
        }

        .wrap {
            vertical-align: middle;
            margin-left: -695px;
            margin-top: 10px;
            padding: 20px;
        }

        .no-surat {
            font-size: 16px;
            font-family: Arial, Helvetica, sans-serif;
        }

        .titik2 {
            width: 30px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div id="print">
        <div class="box">
            <div class="sisi-1">
                <div class="logo-title">
                    <div class="wrap">
                        <p class="no-surat">No. <?= esc((string) $data_transaksi->no_sertifikat) ?></p>
                    </div>
                </div>
            </div>
            <div class="sisi-2">
                <div class="isi text-left"><br>
                    <h3>Surat Saham ini diberikan kepada:</h3>
                    <br>
                    <div style="margin-top: 3px; margin-bottom: 7px;">
                        <table>
                            <tr>
                                <td>No. Anggota</td>
                                <td class="titik2">:</td>
                                <td class="noid"><?= esc((string) $profiles->no_rekening) ?></td>
                            </tr>
                            <tr>
                                <td>NIK</td>
                                <td class="titik2">:</td>
                                <td class="name"><?= esc((string) $profiles->nik) ?></td>
                            </tr>
                            <tr>
                                <td>Nama</td>
                                <td class="titik2">:</td>
                                <td class="name"><?= esc(ucwords((string) $profiles->nama)) ?></td>
                            </tr>
                            <tr>
                                <td>Tempat & Tanggal Lahir</td>
                                <td class="titik2">:</td>
                                <td class="ttl"><?= esc(ucwords((string) $profiles->tmp_lahir) . ', ' . date('d-m-Y', strtotime((string) $profiles->tgl_lahir))) ?></td>
                            </tr>
                        </table>
                    </div>
                    <div style="margin-top: -8px; margin-bottom: 7px;">
                        <table>
                            <tr>
                                <td>Tanggal Transaksi</td>
                                <td class="titik2" style="padding-left: 50px;">:</td>
                                <td class="date"><?= esc(date('d-m-Y', strtotime((string) $data_transaksi->tgl_transaksi))) ?></td>
                            </tr>
                            <tr>
                                <td>Nilai Investasi</td>
                                <td class="titik2" style="padding-left: 50px;">:</td>
                                <td class="value" style="font-size: 25px;"><b><?= 'Rp. ' . number_format((float) $data_transaksi->jml_transaksi, 2, ',', '.') ?></b></td>
                            </tr>
                        </table>
                    </div>
                    <div style="margin-top: 10px;">
                        <br>
                        <p><?= 'Sumenep, ' . date('d-m-Y') ?></p>
                        <p>CV. DHARMA IAA</p>
                        <p><b>Direktur,</b></p>
                        <br>
                        <?php if ($base64qr !== '') : ?>
                            <img src="<?= $base64qr ?>" style="width:15%; margin-top: 5px;">
                        <?php endif; ?>
                        <h4>A. KURDY KHAN</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

