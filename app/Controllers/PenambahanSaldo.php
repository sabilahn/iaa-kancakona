<?php

namespace App\Controllers;

use App\Models\PenambahanSaldoModel;

class PenambahanSaldo extends BaseController
{
    protected PenambahanSaldoModel $penambahanSaldoModel;

    public function __construct()
    {
        $this->penambahanSaldoModel = new PenambahanSaldoModel();
    }

    public function index()
    {
        $users = $this->penambahanSaldoModel->getUsers();
        $thn   = date('Y');
        $hari  = $this->getHari();

        foreach ($users as $user) {
            $noRekening     = (string) $user['no_rekening'];
            $totalInvestasi = $this->penambahanSaldoModel->getTransaksi($noRekening, $thn);
            $saldoHarian    = $this->penambahanSaldoModel->getSaldo($noRekening, $thn);

            if (
                $saldoHarian !== null
                && $totalInvestasi !== null
                && $saldoHarian['rata_rata_harian'] !== null
                && $totalInvestasi['jml_transaksi'] !== null
            ) {
                $totalSaldo = (float) $saldoHarian['rata_rata_harian'] + (float) $totalInvestasi['jml_transaksi'];

                $this->penambahanSaldoModel->editData('saldo_harian', [
                    'rata_rata_harian' => $totalSaldo,
                    'rata_rata_tahun'  => $totalSaldo / max(1, $hari),
                ], [
                    'no_rekening' => $noRekening,
                    'tahun'       => $thn,
                ]);
            }
        }

        return $this->response->setJSON(['status' => 'ok']);
    }

    public function getHari(): float
    {
        $tglSkrg = strtotime(date('Y-m-d'));
        $tglAwal = date('Y-m-d', strtotime(date('Y') . '-01-01 -1 days'));

        return ($tglSkrg - strtotime($tglAwal)) / 86400;
    }
}

