<!DOCTYPE html>
<html lang="en">

<head>
    <title><?= esc((string) $title) ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/stisla/vendors/fontawesome/css/all.min.css') ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

        body {
            background-image: url('<?= base_url('assets/stisla/images/surat-saham-bg.png') ?>');
            background-size: cover;
            background-repeat: no-repeat;
        }

        .label {
            font-size: 15px;
            padding-top: 8px;
        }

        .value {
            font-size: 20px;
            font-weight: bold;
        }

        .cont {
            margin-left: 15%;
            margin-top: 63%;
        }

        @media only screen and (max-width: 600px) {
            header, main {
                font-size: 15px;
            }
        }
    </style>
</head>

<body>
    <div class="cont">
        <?php if ($data_transaksi !== null) : ?>
            <p class="label">No. Anggota</p>
            <p class="value"><?= esc((string) $data_transaksi->no_rekening) ?></p>
            <p class="label">NIK</p>
            <p class="value"><?= esc((string) $data_transaksi->nik) ?></p>
            <p class="label">Nama</p>
            <p class="value"><?= esc(ucwords((string) $data_transaksi->nama)) ?></p>
            <p class="label">Tempat & Tgl. Lahir</p>
            <p class="value"><?= esc(ucwords((string) $data_transaksi->tmp_lahir) . ', ' . date('d-m-Y', strtotime((string) $data_transaksi->tgl_lahir))) ?></p>
            <p class="label">Tgl. Transaksi</p>
            <p class="value"><?= esc(date('d-m-Y', strtotime((string) $data_transaksi->tgl_transaksi))) ?></p>
            <p class="label">Nilai Investasi</p>
            <p class="value"><?= 'Rp. ' . number_format((float) $data_transaksi->jml_transaksi, 2, ',', '.') ?></p>
            <p class="label">Ditandatangani Oleh</p>
            <p class="value">A. Kurdy Khan</p>
        <?php else : ?>
            <p class="value">Data sertifikat tidak ditemukan.</p>
        <?php endif; ?>
    </div>
</body>

</html>

