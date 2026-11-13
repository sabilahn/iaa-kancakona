<p></p>
<h2> PENARIKAN INVESTASI <br>KANCA KONA CV. DHARMA IAA <br> TAHUN BUKU <?= esc((string) $tahun) ?></h2>
<p></p>
<table border="1">
    <thead>
        <tr class="text-center">
            <th>No.</th>
            <th>Nomor Rekening</th>
            <th>Nama</th>
            <th>Tanggal Penarikan</th>
            <th>Jumlah Penarikan (Rp)</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no              = 0;
        $total_penarikan = 0;
        foreach ($data_penarikan as $penarikan) :
            $no              += 1;
            $total_penarikan += (float) $penarikan['jml_penarikan'];
        ?>
            <tr>
                <td style="text-align: center"><?= $no ?></td>
                <td style="text-align: center"><?= esc((string) $penarikan['no_rekening']) ?></td>
                <td><?= esc(ucwords((string) $penarikan['nama'])) ?></td>
                <td class="tengah"><?= esc((string) $penarikan['tgl_penarikan']) ?></td>
                <td class="kanan"><?= number_format((float) $penarikan['jml_penarikan'], 2, ',', '.') ?></td>
            </tr>
        <?php endforeach; ?>

        <tr>
            <td colspan="4" class="kanan"><b>Jumlah</b></td>
            <td class="kanan"><b><?= number_format($total_penarikan, 2, ',', '.') ?></b></td>
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

