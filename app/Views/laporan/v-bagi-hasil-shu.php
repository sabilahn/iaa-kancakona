<p></p>
<?php if ($kecamatan === 'Semua') : ?>
    <h2>DISTRIBUSI NISBAH BAGI HASIL <br>KANCA KONA CV. DHARMA IAA <br> TAHUN BUKU <?= esc((string) $tahun) ?></h2>
<?php else : ?>
    <h2>DISTRIBUSI NISBAH BAGI HASIL KECAMATAN <?= esc(strtoupper((string) $kecamatan)) ?> <br>KANCA KONA CV. DHARMA IAA <br> TAHUN BUKU <?= esc((string) $tahun) ?></h2>
<?php endif; ?>
<p></p>
<?php
$hideBank = ! empty($hide_bank);
$isXls    = ! empty($is_xls);
$colspan  = 3 + ($hideBank ? 0 : 2) + ($kecamatan === 'Semua' ? 1 : 0);
?>
<table border="1">
    <thead>
        <tr class="text-center">
            <th>No.</th>
            <th>Nomor Rekening</th>
            <th>Nama Anggota</th>
            <?php if (! $hideBank) : ?>
                <th>Nama Bank</th>
                <th>Rekening Bank</th>
            <?php endif; ?>
            <?php if ($kecamatan === 'Semua') : ?>
                <th>Kecamatan</th>
            <?php endif; ?>
            <th>Saham Persero (Rp)</th>
            <th>Sisa Hasil Usaha (Rp)</th>
            <th>Total Saldo<br>Rata-rata Harian (Rp)</th>
            <th>Saham Rata-rata<br>Harian Persero (Rp)</th>
            <th>Nisbah Bagi Hasil (Rp)</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no                  = 0;
        $total_saham_persero = 0;
        $total_nisbah        = 0;
        foreach ($data_bagi_hasil as $bagi_hasil) :
            $no                  += 1;
            $total_saham_persero += (float) $bagi_hasil['saham_persero'];
            $total_nisbah        += (float) $bagi_hasil['nisbah_bagi_hasil'];
        ?>
            <tr>
                <td class="tengah"><?= $no ?></td>
                <td class="tengah"><?= esc((string) $bagi_hasil['no_rekening']) ?></td>
                <td><?= esc(ucwords((string) $bagi_hasil['nama'])) ?></td>
                <?php if (! $hideBank) : ?>
                    <td class="tengah"><?= esc((string) ($bagi_hasil['nama_bank'] ?? '')) ?></td>
                    <td class="tengah"><?= ($isXls ? "'" : '') . esc((string) ($bagi_hasil['rek_bank'] ?? '')) ?></td>
                <?php endif; ?>
                <?php if ($kecamatan === 'Semua') : ?>
                    <td><?= esc(ucwords((string) $bagi_hasil['kec'])) ?></td>
                <?php endif; ?>
                <td class="kanan"><?= number_format((float) $bagi_hasil['saham_persero'], 2, ',', '.') ?></td>
                <td class="kanan"><?= number_format((float) $bagi_hasil['shu'], 2, ',', '.') ?></td>
                <td class="kanan"><?= number_format((float) $bagi_hasil['total_rata_harian'], 2, ',', '.') ?></td>
                <td class="kanan"><?= number_format((float) $bagi_hasil['rata_harian_persero'], 2, ',', '.') ?></td>
                <td class="kanan"><?= number_format((float) $bagi_hasil['nisbah_bagi_hasil'], 2, ',', '.') ?></td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <td colspan="<?= $colspan ?>" class="kanan"><b>Jumlah</b></td>
            <td class="kanan"><b><?= number_format($total_saham_persero, 2, ',', '.') ?></b></td>
            <td></td>
            <td></td>
            <td></td>
            <td class="kanan"><b><?= number_format($total_nisbah, 2, ',', '.') ?></b></td>
        </tr>
    </tbody>
</table>
<table>
    <thead>
        <tr class="putih">
            <td class="no-border"></td>
        </tr>
        <tr class="putih">
            <td class="no-border kanan">Sumenep, <?= date('d-m-Y') ?></td>
        </tr>
    </thead>
    <tbody>
        <tr class="putih">
            <td class="no-border kanan">Bendahara,</td>
        </tr>
        <tr class="putih">
            <td class="no-border"></td>
        </tr>
        <tr class="putih">
            <td class="no-border"></td>
        </tr>
        <tr class="putih">
            <td class="no-border kanan"><b>Abd. Basith</b></td>
        </tr>
    </tbody>
</table>
