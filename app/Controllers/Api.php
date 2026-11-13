<?php

namespace App\Controllers;

use App\Models\ApiModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class Api extends ResourceController
{
    protected ApiModel $apiModel;

    public function __construct()
    {
        $this->apiModel = new ApiModel();
    }

    /**
     * @param list<array<string, mixed>> $records
     * @return list<array<string, mixed>>
     */
    protected function formatClientRows(array $records): array
    {
        $rows = [];
        foreach ($records as $res) {
            $tglLahir  = date('d-m-Y', strtotime((string) $res['tgl_lahir']));
            $tglDaftar = date('d-m-Y', strtotime((string) $res['tgl_daftar']));

            $rows[] = [
                'no_rekening' => $res['no_rekening'],
                'nik'         => $res['nik'],
                'nama'        => ucwords((string) $res['nama']),
                'tgl_lahir'   => ucwords((string) $res['tmp_lahir']) . '/' . $tglLahir,
                'alamat'      => ucwords((string) $res['dsn']) . ' ' . ucwords((string) $res['ds']) . ' RT/RW ' . $res['rt'] . '/' . $res['rw'],
                'kec_kab'     => ucwords((string) $res['kec']) . ' - ' . ucwords((string) $res['kab']),
                'tgl_daftar'  => $tglDaftar,
            ];
        }

        return $rows;
    }

    /**
     * @param list<array<string, mixed>> $records
     * @return list<array<string, mixed>>
     */
    protected function formatTransaksiRows(array $records): array
    {
        $rows = [];
        foreach ($records as $res) {
            $tglLahir     = date('d-m-Y', strtotime((string) $res['tgl_lahir']));
            $tglTransaksi = date('d-m-Y', strtotime((string) $res['tgl_transaksi']));

            $rows[] = [
                'no_rekening'   => $res['no_rekening'],
                'nama'          => ucwords((string) $res['nama']),
                'nik'           => $res['nik'],
                'tgl_lahir'     => ucwords((string) $res['tmp_lahir']) . ', ' . $tglLahir,
                'tgl_transaksi' => $tglTransaksi,
                'jml_transaksi' => number_format((float) $res['jml_transaksi'], 2, ',', '.'),
                'no_sertifikat' => $res['no_sertifikat'],
            ];
        }

        return $rows;
    }

    public function users(): ResponseInterface
    {
        $rows = [];
        foreach ($this->apiModel->getUsers() as $res) {
            $rows[] = [
                'user_id'  => $res['user_id'],
                'name'     => ucwords((string) $res['nama']),
                'username' => $res['username'],
                'role'     => $res['role'],
            ];
        }

        return $this->respond($rows, 200);
    }

    public function clients(): ResponseInterface
    {
        return $this->respond($this->formatClientRows($this->apiModel->getClients()), 200);
    }

    public function clientId(string $noRekening): ResponseInterface
    {
        return $this->respond($this->formatClientRows($this->apiModel->getClientById($noRekening)), 200);
    }

    public function saldoHarianId(string $noRekening): ResponseInterface
    {
        $rows = [];
        foreach ($this->apiModel->getRataHarianById($noRekening) as $res) {
            $rows[] = [
                'tahun'            => $res['tahun'],
                'nilai_investasi'  => number_format((float) $res['nilai_investasi'], 2, ',', '.'),
                'rata_rata_harian' => number_format((float) $res['rata_rata_harian'], 2, ',', '.'),
                'rata_rata_tahun'  => number_format((float) $res['rata_rata_tahun'], 2, ',', '.'),
            ];
        }

        return $this->respond($rows, 200);
    }

    public function dividenId(string $noRekening): ResponseInterface
    {
        $rows = [];
        foreach ($this->apiModel->getDividenById($noRekening) as $res) {
            $rows[] = [
                'tahun'         => $res['tahun'],
                'saham_persero' => number_format((float) $res['saham_persero'], 2, ',', '.'),
                'shu'           => number_format((float) $res['shu'], 2, ',', '.'),
                'bagi_hasil'    => $res['nisbah_bagi_hasil'],
            ];
        }

        return $this->respond($rows, 200);
    }

    public function transaksiId(string $noRekening): ResponseInterface
    {
        return $this->respond($this->formatTransaksiRows($this->apiModel->getTransaksiByRekening($noRekening)), 200);
    }

    public function transaksiSaham(string $noSertifikat): ResponseInterface
    {
        return $this->respond($this->formatTransaksiRows($this->apiModel->getTransaksiBySertifikat($noSertifikat)), 200);
    }

    public function saldoInvestasi(string $noRekening, string $year): ResponseInterface
    {
        $rows = [];
        $temp = 0.0;

        foreach ($this->apiModel->getSaldoInvestasi($noRekening, $year) as $res) {
            $tanggal = date('d-m-Y', strtotime((string) $res['tanggal']));
            $tgl     = explode('-', $tanggal);
            $ket     = ($tgl[0] === '01' && $tgl[1] === '01') ? 'Saldo Awal' : '-';

            $temp = $temp + (float) $res['jml_transaksi'] - (float) $res['jml_penarikan'];

            $rows[] = [
                'no_rekening'   => $res['no_rekening'],
                'tanggal'       => $tanggal,
                'jml_transaksi' => number_format((float) $res['jml_transaksi'], 2, ',', '.'),
                'jml_penarikan' => number_format((float) $res['jml_penarikan'], 2, ',', '.'),
                'saldo'         => number_format($temp, 2, ',', '.'),
                'ket'           => $ket,
            ];
        }

        return $this->respond($rows, 200);
    }
}

