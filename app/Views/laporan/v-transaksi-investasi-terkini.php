<p></p>
<h2> TRANSAKSI INVESTASI TAHUN BUKU <?= date('Y') ?> <br> CV. DHARMA IAA S/D <?= date('d') . ' ' . esc((string) $nama_bulan) . ' ' . date('Y') ?></h2>
<p></p>
<table border="1">
    <thead>
        <tr class="text-center">
            <th>No.</th>
            <th>Nomor Rekening</th>
            <th>Nama</th>
            <th>Nomor Sertifikat</th>
            <th>Tanggal Transaksi</th>
            <th>Jumlah Transaksi (Rp)</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no              = 0;
        $total_transaksi = 0;
        foreach ($data_trans_terkini as $trans_terkini) :
            $no              += 1;
            $total_transaksi += (float) $trans_terkini['jml_transaksi'];
        ?>
            <tr>
                <td style="text-align: center"><?= $no ?></td>
                <td style="text-align: center"><?= esc((string) $trans_terkini['no_rekening']) ?></td>
                <td><?= esc(ucwords((string) $trans_terkini['nama'])) ?></td>
                <td class="tengah">
                    <font color="#ffffff">'</font><?= esc((string) $trans_terkini['no_sertifikat']) ?>
                </td>
                <td class="tengah"><?= esc((string) $trans_terkini['tgl_transaksi']) ?></td>
                <td class="kanan"><?= number_format((float) $trans_terkini['jml_transaksi'], 2, ',', '.') ?></td>
            </tr>
        <?php endforeach; ?>

        <tr>
            <td colspan="5" class="kanan"><b>Jumlah</b></td>
            <td class="kanan"><b><?= number_format($total_transaksi, 2, ',', '.') ?></b></td>
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

