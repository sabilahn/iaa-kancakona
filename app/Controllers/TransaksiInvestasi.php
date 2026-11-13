<?php

namespace App\Controllers;

use App\Libraries\CetakPdf;
use App\Libraries\QrGenerator;
use App\Models\TransaksiInvestasiModel;
use App\Models\UserModel;

class TransaksiInvestasi extends BaseController
{
    protected TransaksiInvestasiModel $transaksiModel;
    protected UserModel $userModel;
    protected QrGenerator $qrGenerator;
    protected CetakPdf $cetakPdf;

    public function __construct()
    {
        $this->transaksiModel = new TransaksiInvestasiModel();
        $this->userModel      = new UserModel();
        $this->qrGenerator    = new QrGenerator();
        $this->cetakPdf       = new CetakPdf();
    }

    public function index(): string
    {
        $thn = (string) $this->request->getPost('tahun_transaksi');
        if ($thn === '') {
            $thn = date('Y');
        }

        $prevYear     = (string) ((int) $thn - 1);
        $listTransaksi = $this->transaksiModel->transaksiList($thn);

        foreach ($listTransaksi as $i => $transaksi) {
            $saldo = $this->transaksiModel->getWhereRowArray('v_transaksi_thn', [
                'no_rekening' => $transaksi['no_rekening'],
                'tahun'       => $prevYear,
            ]);

            $isSaldoAwal = $saldo !== null
                && isset($saldo['saldo'])
                && (float) $transaksi['jml_transaksi'] === (float) $saldo['saldo']
                && $transaksi['tgl_transaksi'] === $thn . '-01-01';

            $listTransaksi[$i]['is_saldo_awal'] = $isSaldoAwal;
        }

        return $this->renderPage('transaksi_investasi', [
            'title'          => 'IAA Investment | Transaksi Investasi',
            'tahun'          => $thn,
            'data_transaksi' => $listTransaksi,
            'data_rekening'  => $this->transaksiModel->rekeningList(),
        ]);
    }

    public function createQrcode(string $noSertifikat): string
    {
        return $this->qrGenerator->savePng(
            base_url('surat_saham/index/' . $noSertifikat),
            'assets/stisla/images/qrcode/surat_saham',
            $noSertifikat
        );
    }

    public function tambahDataTransaksi()
    {
        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            $noRekening   = (string) $this->request->getPost('rekening');
            $noSertifikat = (string) $this->request->getPost('sertifikat');
            $tglTransaksi = (string) $this->request->getPost('tgl_transaksi');
            $jmlTransaksi = $this->request->getPost('jml_transaksi');
            $userId       = (string) $this->session->get('user_id');

            $data = [
                'no_rekening'   => $noRekening,
                'no_sertifikat' => $noSertifikat,
                'tgl_transaksi' => $tglTransaksi,
                'jml_transaksi' => $jmlTransaksi,
                'created_at'    => date('Y-m-d H:i:s'),
                'created_by'    => $userId,
                'is_deleted'    => 0,
            ];

            if (! $this->transaksiModel->tambahData('transaksi_investasi', $data)) {
                throw new \RuntimeException('Gagal menambah transaksi.');
            }

            $thn  = date('Y', strtotime($tglTransaksi));
            $tgl  = $thn . date('-m-d');
            $hari = $this->transaksiModel->getHariTransaksi($tgl);

            $jmlRataHarian = (float) $jmlTransaksi * $hari[0];
            $cekSaldo      = $this->transaksiModel->cekSaldo($noRekening, $tglTransaksi);

            if ($cekSaldo === []) {
                $this->transaksiModel->tambahData('saldo_harian', [
                    'no_rekening'      => $noRekening,
                    'rata_rata_harian' => $jmlRataHarian,
                    'rata_rata_tahun'  => $jmlRataHarian / $hari[1],
                    'tahun'            => $thn,
                    'created_at'       => date('Y-m-d H:i:s'),
                    'created_by'       => $userId,
                ]);
            } else {
                $this->transaksiModel->recalculateSaldoHarian($noRekening, $thn, $userId);
            }

            $this->userModel->logActivity($userId, 'Tambah Transaksi Berhasil');
            $this->createQrcode($noSertifikat);

            $db->transCommit();
            $this->session->setFlashdata('success', 'Sukses! Data berhasil disimpan.');
        } catch (\Throwable $e) {
            $db->transRollback();
            log_message('error', 'tambahDataTransaksi error: ' . $e->getMessage());
            $this->session->setFlashdata('error', 'Gagal! Data tidak dapat disimpan.');
        }

        return redirect()->back();
    }

    public function ubahDataTransaksi()
    {
        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            $idTransaksi  = $this->request->getPost('id_transaksi');
            $noRekening   = (string) $this->request->getPost('rekening_edt');
            $tglTransaksi = (string) $this->request->getPost('tgl_transaksi_edt');
            $userId       = (string) $this->session->get('user_id');

            $dataTransaksi = [
                'no_sertifikat' => (string) $this->request->getPost('sertifikat_edt'),
                'tgl_transaksi' => $tglTransaksi,
                'jml_transaksi' => $this->request->getPost('jml_transaksi_edt'),
                'updated_at'    => date('Y-m-d H:i:s'),
                'updated_by'    => $userId,
            ];

            if (! $this->transaksiModel->editData('transaksi_investasi', $dataTransaksi, ['id_transaksi' => $idTransaksi])) {
                throw new \RuntimeException('Gagal mengubah transaksi.');
            }

            $thn = date('Y', strtotime($tglTransaksi !== '' ? $tglTransaksi : date('Y-m-d')));
            $this->transaksiModel->recalculateSaldoHarian($noRekening, $thn, $userId);
            $this->userModel->logActivity($userId, 'Ubah Transaksi Berhasil');

            $db->transCommit();
            $this->session->setFlashdata('success', 'Sukses! Data berhasil disimpan.');
        } catch (\Throwable $e) {
            $db->transRollback();
            log_message('error', 'ubahDataTransaksi error: ' . $e->getMessage());
            $this->session->setFlashdata('error', 'Gagal! Data tidak dapat diperbarui.');
        }

        return redirect()->back();
    }

    public function hapusDataTransaksi()
    {
        $idTransaksi  = $this->request->getPost('id_transaksi');
        $noRekening   = (string) $this->request->getPost('rekening_hps');
        $tglTransaksi = (string) $this->request->getPost('tgl_transaksi_hps');
        $userId       = (string) $this->session->get('user_id');

        $count = $this->transaksiModel->countTransaksi($noRekening, $tglTransaksi);
        $thn   = date('Y', strtotime($tglTransaksi));

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            $this->transaksiModel->hapusData('transaksi_investasi', ['id_transaksi' => $idTransaksi]);

            if ($count <= 1) {
                $this->transaksiModel->hapusData('saldo_harian', [
                    'no_rekening' => $noRekening,
                    'tahun'       => $thn,
                ]);
            } else {
                $this->transaksiModel->recalculateSaldoHarian($noRekening, $thn, $userId);
            }

            $this->userModel->logActivity($userId, 'Hapus Transaksi Berhasil');

            $db->transCommit();
            $this->session->setFlashdata('success', 'Sukses! Data berhasil dihapus.');
        } catch (\Throwable $e) {
            $db->transRollback();
            log_message('error', 'hapusDataTransaksi error: ' . $e->getMessage());
            $this->session->setFlashdata('error', 'Gagal! Data tidak dapat dihapus.');
        }

        return redirect()->back();
    }

    public function migrasi(string $thn)
    {
        $listTransaksi = $this->transaksiModel->getTransaksiTahun($thn);
        $userId        = (string) $this->session->get('user_id');

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            foreach ($listTransaksi as $transaksi) {
                $rekening     = explode('-', (string) $transaksi['no_rekening']);
                $nextYear     = (int) $thn + 1;
                $tglTransaksi = $nextYear . '-01-01';
                $noSertifikat = $nextYear . '0101' . ($rekening[1] ?? $rekening[0]);

                $cek  = $this->transaksiModel->getWhereArray('transaksi_investasi', ['no_sertifikat' => $noSertifikat]);
                $hari = $this->transaksiModel->getHariTransaksi($tglTransaksi);

                if ($cek === []) {
                    $this->transaksiModel->tambahData('transaksi_investasi', [
                        'no_rekening'   => $transaksi['no_rekening'],
                        'no_sertifikat' => $noSertifikat,
                        'tgl_transaksi' => $tglTransaksi,
                        'jml_transaksi' => $transaksi['saldo'],
                        'created_at'    => date('Y-m-d H:i:s'),
                        'created_by'    => $userId,
                        'is_deleted'    => 0,
                    ]);

                    $jmlRataHarian = (float) $transaksi['saldo'] * $hari[0];
                    $tahunMigrasi  = date('Y', strtotime($tglTransaksi));
                    $cekSaldo      = $this->transaksiModel->cekSaldo((string) $transaksi['no_rekening'], $tglTransaksi);

                    if ($cekSaldo === []) {
                        $this->transaksiModel->tambahData('saldo_harian', [
                            'no_rekening'      => $transaksi['no_rekening'],
                            'rata_rata_harian' => $jmlRataHarian,
                            'rata_rata_tahun'  => $jmlRataHarian / $hari[1],
                            'tahun'            => $tahunMigrasi,
                            'created_at'       => date('Y-m-d H:i:s'),
                            'created_by'       => $userId,
                        ]);
                    } else {
                        $this->transaksiModel->recalculateSaldoHarian((string) $transaksi['no_rekening'], $tahunMigrasi, $userId);
                    }
                } elseif (
                    $cek[0]['no_sertifikat'] === $noSertifikat
                    && (float) $cek[0]['jml_transaksi'] !== (float) $transaksi['saldo']
                ) {
                    $this->transaksiModel->editData(
                        'transaksi_investasi',
                        ['jml_transaksi' => $transaksi['saldo']],
                        ['no_sertifikat' => $noSertifikat]
                    );

                    $tahunMigrasi = date('Y', strtotime($tglTransaksi));
                    $this->transaksiModel->recalculateSaldoHarian((string) $transaksi['no_rekening'], $tahunMigrasi, $userId);
                }
            }

            $db->transCommit();
            $this->session->setFlashdata('success', 'Sukses! Data berhasil disimpan');
        } catch (\Throwable $e) {
            $db->transRollback();
            log_message('error', 'migrasi error: ' . $e->getMessage());
            $this->session->setFlashdata('error', 'Gagal memproses migrasi saldo awal.');
        }

        return redirect()->back();
    }

    public function cetakSuratSaham(string $idTransaksi, string $noRekening)
    {
        $transaksi = $this->transaksiModel->getWhereRow('transaksi_investasi', ['id_transaksi' => $idTransaksi]);
        $profiles  = $this->transaksiModel->getWhereRow('client_account', ['no_rekening' => $noRekening]);

        if ($transaksi === null || $profiles === null) {
            return redirect()->back()->with('error', 'Data surat saham tidak ditemukan.');
        }

        $qrRelPath = 'assets/stisla/images/qrcode/surat_saham/' . $transaksi->no_sertifikat . '.png';
        if (! is_file(FCPATH . $qrRelPath)) {
            $this->createQrcode((string) $transaksi->no_sertifikat);
        }

        $this->cetakPdf->setPaper([0, 0, 771, 395]);
        $this->cetakPdf->filename = 'surat-saham-[' . $noRekening . '].pdf';

        return $this->cetakPdf->streamResponse($this->response, 'surat_saham/surat_saham2', [
            'title'          => 'IAA Investment | Surat Saham',
            'data_transaksi' => $transaksi,
            'profiles'       => $profiles,
            'base64'         => CetakPdf::localImageDataUri('assets/stisla/images/iaa.png'),
            'base642'        => CetakPdf::localImageDataUri('assets/stisla/images/bg.png'),
            'base64qr'       => CetakPdf::localImageDataUri($qrRelPath),
        ]);
    }

    public function cetakSuratKeterangan(string $idPenarikan)
    {
        $penarikan = $this->transaksiModel->getWhereRow('v_penarikan_investasi', ['id_penarikan' => $idPenarikan]);
        if ($penarikan === null) {
            return redirect()->back()->with('error', 'Data penarikan tidak ditemukan.');
        }

        $userId = (string) $this->session->get('user_id');
        $this->userModel->logActivity($userId, 'Cetak Surat Keterangan Berhasil');

        $qrRelPath = 'assets/stisla/images/qrcode/penarikan/' . $penarikan->id_penarikan . '.png';
        if (! is_file(FCPATH . $qrRelPath)) {
            $this->qrGenerator->savePng(
                base_url('penarikan/index/' . $penarikan->id_penarikan),
                'assets/stisla/images/qrcode/penarikan',
                (string) $penarikan->id_penarikan
            );
        }

        $this->cetakPdf->setPaper('A4', 'portrait');
        $this->cetakPdf->filename = 'surat-keterangan-penarikan.pdf';

        return $this->cetakPdf->streamResponse($this->response, 'surat_saham/surat_keterangan', [
            'title'          => 'IAA Investment | Surat Keterangan',
            'data_penarikan' => $penarikan,
            'base64'         => CetakPdf::localImageDataUri('assets/stisla/images/iaa.png'),
            'base64qr'       => CetakPdf::localImageDataUri($qrRelPath),
        ]);
    }
}

