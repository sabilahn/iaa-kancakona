<?= view('laporan/header_pdf', ['title' => $title]) ?>

<body>
    <?= view($body_view, ['hide_bank' => $hide_bank ?? false]) ?>
</body>

</html>

