<?php

namespace App\Controllers;

use App\Models\TransaksiInvestasiModel;

class Penarikan extends BaseController
{
    protected TransaksiInvestasiModel $transaksiModel;

    public function __construct()
    {
        $this->transaksiModel = new TransaksiInvestasiModel();
    }

    public function index(string $id): string
    {
        return view('surat_saham/surat_keterangan_qrcode', [
            'title'          => 'IAA Investment | Data Transaksi',
            'data_penarikan' => $this->transaksiModel->getWhereRow('v_penarikan_investasi', ['id_penarikan' => $id]),
        ]);
    }
}

