<p></p>
<h2> PORSI DAN DISTRIBUSI PENDAPATAN <br>KANCA KONA CV. DHARMA IAA <br> TAHUN BUKU <?= esc((string) $tahun) ?></h2>
<p></p>
<table border="1">
    <thead>
        <tr class="text-center">
            <th style="width:10%">No.</th>
            <th colspan="2" style="width:50%">Porsi (%)</th>
            <th style="width:20%">SHU (Rp)</th>
            <th style="width:20%">Jumlah (Rp)</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no                  = 0;
        $total_porsi_dispen  = 0;
        $total_jumlah_dispen = 0;
        foreach ($data_dispen as $dispen) :
            $no                  += 1;
            $total_porsi_dispen  += (float) $dispen['porsi'];
            $total_jumlah_dispen += (float) $dispen['jumlah'];
        ?>
            <tr>
                <td class="tengah"><?= $no ?></td>
                <td class="tengah"><?= esc((string) $dispen['porsi']) . '%' ?></td>
                <td><?= esc((string) $dispen['uraian']) ?></td>
                <td class="kanan"><?= number_format((float) $dispen['shu'], 2, ',', '.') ?></td>
                <td class="kanan"><?= number_format((float) $dispen['jumlah'], 2, ',', '.') ?></td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <td></td>
            <td class="tengah"><?= $total_porsi_dispen . '%' ?></td>
            <td></td>
            <td></td>
            <td class="kanan"><b><?= number_format($total_jumlah_dispen, 2, ',', '.') ?></b></td>
        </tr>
    </tbody>
</table>
<p></p>
<h2> PORSI DAN DISTRIBUSI DANA IAA DAN MANAJEMEN <br>KANCA KONA CV. DHARMA IAA <br> TAHUN BUKU <?= esc((string) $tahun) ?></h2>
<p></p>
<table border="1">
    <thead>
        <tr class="text-center">
            <th style="width:10%">No.</th>
            <th colspan="2" style="width:50%">Porsi (%)</th>
            <th style="width:20%">Manajemen (Rp)</th>
            <th style="width:20%">Jumlah (Rp)</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no                 = 0;
        $total_porsi_disda  = 0;
        $total_jumlah_disda = 0;
        foreach ($data_disda as $disda) :
            $no                 += 1;
            $total_porsi_disda  += (float) $disda['porsi'];
            $total_jumlah_disda += (float) $disda['jumlah'];
        ?>
            <tr>
                <td class="tengah"><?= $no ?></td>
                <td class="tengah"><?= esc((string) $disda['porsi']) . '%' ?></td>
                <td><?= esc((string) $disda['uraian']) ?></td>
                <td class="kanan"><?= number_format((float) ($disda['menejemen'] ?? $disda['manajemen'] ?? 0), 2, ',', '.') ?></td>
                <td class="kanan"><?= number_format((float) $disda['jumlah'], 2, ',', '.') ?></td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <td></td>
            <td class="tengah"><?= $total_porsi_disda . '%' ?></td>
            <td></td>
            <td></td>
            <td class="kanan"><b><?= number_format($total_jumlah_disda, 2, ',', '.') ?></b></td>
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

