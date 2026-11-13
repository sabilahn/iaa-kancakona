<p></p>
<h2> PROFIL PEMEGANG SAHAM <br>KANCA KONA CV. DHARMA IAA</h2>
<p></p>
<table>
    <thead>
        <tr class="text-center">
            <th rowspan="2">No.</th>
            <th rowspan="2">No. Anggota</th>
            <th rowspan="2">Nama</th>
            <th rowspan="2">NIK</th>
            <th colspan="6">Alamat</th>
            <th rowspan="2">Tanggal Daftar</th>
        </tr>
        <tr>
            <th>RT</th>
            <th>RW</th>
            <th>Dusun</th>
            <th>Desa</th>
            <th>Kec.</th>
            <th>Kab.</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 0;
        foreach ($profiles as $profile) :
            $no += 1;
        ?>
            <tr>
                <td class="tengah"><?= $no ?></td>
                <td class="tengah"><?= esc((string) $profile['no_rekening']) ?></td>
                <td><?= esc(ucwords((string) $profile['nama'])) ?></td>
                <td class="tengah">
                    <font color="#ffffff">'</font><?= esc((string) $profile['nik']) ?>
                </td>
                <td><?= esc((string) $profile['rt']) ?></td>
                <td><?= esc((string) $profile['rw']) ?></td>
                <td><?= esc((string) $profile['dsn']) ?></td>
                <td><?= esc((string) $profile['ds']) ?></td>
                <td><?= esc((string) $profile['kec']) ?></td>
                <td><?= esc((string) $profile['kab']) ?></td>
                <td class="tengah"><?= esc((string) $profile['tgl_daftar']) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

