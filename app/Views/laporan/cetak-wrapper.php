<?= view('laporan/header', ['title' => $title]) ?>

<body>
    <div id="page-wrap">
        <div>
            <a class="btn" onclick="cetak()"><i class="fas fa-print"> Cetak</i></a>
            <?php if (! empty($xls_url)) : ?>
                <a class="btn" id="konfersi" href="<?= esc((string) $xls_url, 'attr') ?>"><i class="fas fa-exchange-alt"> Konversi ke XLS</i></a>
            <?php endif; ?>
            <?php if (! empty($pdf_url)) : ?>
                <a target="_blank" class="btn" href="<?= esc((string) $pdf_url, 'attr') ?>"><i class="fas fa-file"> Konversi ke PDF</i></a>
            <?php endif; ?>
        </div>
        <br>
        <div id="print">
            <?= view($body_view) ?>
        </div>
        <?= view('laporan/script') ?>
    </div>
</body>

</html>

