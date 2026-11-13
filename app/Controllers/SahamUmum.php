<?php

namespace App\Controllers;

use App\Models\TransaksiInvestasiModel;

class SahamUmum extends BaseController
{
    protected TransaksiInvestasiModel $transaksiModel;

    public function __construct()
    {
        $this->transaksiModel = new TransaksiInvestasiModel();
    }

    public function index(): string
    {
        return $this->renderPage('saham_umum', [
            'title' => 'IAA Investment | Saham Umum',
        ]);
    }

    public function getData(string $tahun)
    {
        return $this->response->setJSON($this->transaksiModel->saldoInvestasi($tahun));
    }

    public function getDataID()
    {
        $noRekening = (string) $this->request->getPost('no_rekening');
        $tahun      = (string) $this->request->getPost('tahun');

        return $this->response->setJSON($this->transaksiModel->getSaldoId($tahun, $noRekening));
    }
}

