<?php

namespace App\Controllers;

use App\Models\SuratSahamModel;

class SuratSaham extends BaseController
{
    protected SuratSahamModel $suratSahamModel;

    public function __construct()
    {
        $this->suratSahamModel = new SuratSahamModel();
    }

    public function index(string $noSertifikat): string
    {
        return view('surat_saham/surat_saham_qrcode', [
            'title'          => 'IAA Investment | Data Transaksi',
            'data_transaksi' => $this->suratSahamModel->getData(['no_sertifikat' => $noSertifikat]),
        ]);
    }
}

