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
            background-image: url('<?= base_url('assets/stisla/images/penarikan-bg.png') ?>');
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
        <?php if ($data_penarikan !== null) : ?>
            <p class="label">Nama</p>
            <p class="value"><?= esc(ucwords((string) $data_penarikan->nama)) ?></p>
            <p class="label">No. Anggota</p>
            <p class="value"><?= esc((string) $data_penarikan->no_rekening) ?></p>
            <p class="label">NIK</p>
            <p class="value"><?= esc((string) $data_penarikan->nik) ?></p>
            <p class="label">Alamat</p>
            <p class="value"><?= esc('Ds. ' . $data_penarikan->ds . ' RT/RW. ' . $data_penarikan->rt . '/' . $data_penarikan->rw) ?></p>
            <p class="label">Tgl. Penarikan</p>
            <p class="value"><?= esc(date('d-m-Y', strtotime((string) $data_penarikan->tgl_penarikan))) ?></p>
            <p class="label">Jumlah Penarikan</p>
            <p class="value"><?= 'Rp. ' . number_format((float) $data_penarikan->jml_penarikan, 2, ',', '.') ?></p>
            <p class="label">Ditandatangani Oleh</p>
            <p class="value"><?= esc(ucwords((string) $data_penarikan->nama)) ?></p>
            <p class="value">A. Kurdy Khan</p>
        <?php else : ?>
            <p class="value">Data penarikan tidak ditemukan.</p>
        <?php endif; ?>
    </div>
</body>

</html>

