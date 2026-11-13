<?php

namespace App\Controllers;

use App\Libraries\QrGenerator;
use App\Models\TransaksiInvestasiModel;
use App\Models\UserModel;

class PenarikanInvestasi extends BaseController
{
    protected TransaksiInvestasiModel $transaksiModel;
    protected UserModel $userModel;
    protected QrGenerator $qrGenerator;

    public function __construct()
    {
        $this->transaksiModel = new TransaksiInvestasiModel();
        $this->userModel      = new UserModel();
        $this->qrGenerator    = new QrGenerator();
    }

    public function index(): string
    {
        return $this->renderPage('penarikan_investasi', [
            'title'         => 'IAA Investment | Penarikan Investasi',
            'data_rekening' => $this->transaksiModel->rekeningList(),
        ]);
    }

    public function getData(string $tahun)
    {
        return $this->response->setJSON($this->transaksiModel->penarikanList($tahun));
    }

    public function getDataID()
    {
        $id = $this->request->getPost('id_penarikan');

        return $this->response->setJSON(
            $this->transaksiModel->getWhereObjects('v_penarikan_investasi', ['id_penarikan' => $id])
        );
    }

    public function tambahDataPenarikan()
    {
        $noRekening   = (string) $this->request->getPost('no_rekening');
        $tglPenarikan = (string) $this->request->getPost('tgl_penarikan');
        $jmlPenarikan = $this->request->getPost('jml_penarikan');
        $userId       = (string) $this->session->get('user_id');

        $id = $this->transaksiModel->tambahId('penarikan_investasi', [
            'no_rekening'   => $noRekening,
            'tgl_penarikan' => $tglPenarikan,
            'jml_penarikan' => $jmlPenarikan,
            'created_at'    => date('Y-m-d H:i:s'),
            'created_by'    => $userId,
        ]);

        $this->userModel->logActivity($userId, 'Tambah Penarikan Transaksi Berhasil');

        $thn = date('Y', strtotime($tglPenarikan !== '' ? $tglPenarikan : date('Y-m-d')));
        $this->transaksiModel->recalculateSaldoHarian($noRekening, $thn, $userId);
        $this->createQrcode((string) $id);

        return $this->response->setJSON('');
    }

    public function update()
    {
        $idPenarikan  = $this->request->getPost('id_penarikan');
        $tglPenarikan = (string) $this->request->getPost('tgl_penarikan');
        $noRekening   = (string) $this->request->getPost('no_rekening');
        $userId       = (string) $this->session->get('user_id');

        $this->transaksiModel->editData('penarikan_investasi', [
            'tgl_penarikan' => $tglPenarikan,
            'updated_at'    => date('Y-m-d H:i:s'),
            'updated_by'    => $userId,
        ], ['id_penarikan' => $idPenarikan]);

        $this->userModel->logActivity($userId, 'Ubah Penarikan Transaksi Berhasil');

        $thn = date('Y', strtotime($tglPenarikan !== '' ? $tglPenarikan : date('Y-m-d')));
        $this->transaksiModel->recalculateSaldoHarian($noRekening, $thn, $userId);

        return $this->response->setJSON($tglPenarikan);
    }

    public function delete()
    {
        $idPenarikan  = $this->request->getPost('id_penarikan');
        $noRekening   = (string) $this->request->getPost('no_rekening');
        $tglPenarikan = (string) $this->request->getPost('tgl_penarikan');
        $userId       = (string) $this->session->get('user_id');

        $this->transaksiModel->hapusData('penarikan_investasi', ['id_penarikan' => $idPenarikan]);
        $this->userModel->logActivity($userId, 'Hapus Penarikan Transaksi Berhasil');

        $thn = date('Y', strtotime($tglPenarikan !== '' ? $tglPenarikan : date('Y-m-d')));
        $this->transaksiModel->recalculateSaldoHarian($noRekening, $thn, $userId);

        return $this->response->setJSON('');
    }

    public function getJumlah()
    {
        $noRekening = (string) $this->request->getPost('rekening');
        $thn        = date('Y');

        return $this->response->setJSON($this->transaksiModel->jmlTransaksi($noRekening, $thn));
    }

    public function createQrcode(string $id): string
    {
        return $this->qrGenerator->savePng(
            base_url('penarikan/index/' . $id),
            'assets/stisla/images/qrcode/penarikan',
            $id
        );
    }
}

