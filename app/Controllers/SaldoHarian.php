<?php

namespace App\Controllers;

use App\Models\SaldoHarianModel;
use App\Models\TransaksiInvestasiModel;
use DateInterval;
use DatePeriod;
use DateTime;

class SaldoHarian extends BaseController
{
    protected SaldoHarianModel $saldoHarianModel;
    protected TransaksiInvestasiModel $transaksiModel;

    public function __construct()
    {
        $this->saldoHarianModel = new SaldoHarianModel();
        $this->transaksiModel   = new TransaksiInvestasiModel();
    }

    public function index(): string
    {
        $tahun = (string) $this->request->getPost('tahun_saldo');
        if ($tahun === '') {
            $tahun = date('Y');
        }

        return $this->renderPage('saldo_harian', [
            'tahun'        => $tahun,
            'daftar_saldo' => $this->saldoHarianModel->saldoHarianList($tahun),
            'title'        => 'IAA Investment | Saldo Harian',
        ]);
    }

    public function getHari(string $tahun): int
    {
        return date('L', strtotime("$tahun-01-01")) ? 366 : 365;
    }

    public function updateSaldo(string $tahun, int $offset = 0, int $limit = 100)
    {
        set_time_limit(500);

        $total = $this->transaksiModel->jumlahRekening();
        $hari  = $this->getHari($tahun);
        $users = $this->transaksiModel->rekeningListLimit($limit, $offset);

        if ($users === []) {
            $this->session->setFlashdata('success', '✅ Semua data berhasil diproses.');

            return redirect()->to(site_url('saldo_harian'));
        }

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            $userId = (string) $this->session->get('user_id');
            $start  = new DateTime("$tahun-01-01");
            $end    = (new DateTime("$tahun-12-31"))->modify('+1 day');
            $period = new DatePeriod($start, new DateInterval('P1D'), $end);

            foreach ($users as $user) {
                $noRekening   = (string) $user['no_rekening'];
                $transaksiAll = $this->transaksiModel->cekTransaksiTahun($noRekening, $tahun);
                $penarikanAll = $this->transaksiModel->cekPenarikanTahun($noRekening, $tahun);

                $transaksiIndex = [];
                foreach ($transaksiAll as $row) {
                    $transaksiIndex[$row['tgl_transaksi']][] = $row;
                }

                $penarikanIndex = [];
                foreach ($penarikanAll as $row) {
                    $penarikanIndex[$row['tgl_penarikan']][] = $row;
                }

                $saldoSkr        = 0.0;
                $penambahanSaldo = 0.0;

                foreach ($period as $date) {
                    $tanggal    = $date->format('Y-m-d');
                    $transaksis = $transaksiIndex[$tanggal] ?? [];
                    $penarikans = $penarikanIndex[$tanggal] ?? [];

                    $jmlTransaksi = array_sum(array_column($transaksis, 'jml_transaksi'));
                    $jmlPenarikan = array_sum(array_column($penarikans, 'jml_penarikan'));

                    $saldoSkr        += ($jmlTransaksi - $jmlPenarikan) + $penambahanSaldo;
                    $penambahanSaldo += ($jmlTransaksi - $jmlPenarikan);
                }

                $rataTahun = $saldoSkr / $hari;

                $this->transaksiModel->editData('saldo_harian', [
                    'rata_rata_harian' => $saldoSkr,
                    'rata_rata_tahun'  => $rataTahun,
                    'updated_at'       => date('Y-m-d H:i:s'),
                    'updated_by'       => $userId,
                ], [
                    'no_rekening' => $noRekening,
                    'tahun'       => $tahun,
                ]);
            }

            $db->transCommit();
            $this->session->setFlashdata('success', "✅ Batch berhasil diproses: $limit data.");
        } catch (\Throwable $e) {
            $db->transRollback();
            log_message('error', 'Gagal proses batch saldo harian: ' . $e->getMessage());
            $this->session->setFlashdata('error', 'Batch gagal diproses.');

            return redirect()->to(site_url('saldo_harian'));
        }

        $nextOffset = $offset + $limit;
        if ($nextOffset < $total) {
            return redirect()->to(site_url("saldo_harian/update_saldo/$tahun/$nextOffset/$limit"));
        }

        $this->session->setFlashdata('success', 'Semua data berhasil disimpan!');

        return redirect()->to(site_url('saldo_harian'));
    }
}

