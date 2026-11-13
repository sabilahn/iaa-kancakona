<?php

namespace App\Models;

use CodeIgniter\Model;

class ApiModel extends Model
{
    protected $returnType = 'array';

    /**
     * @return list<array<string, mixed>>
     */
    public function getUsers(): array
    {
        return $this->db->table('v_users')
            ->select('user_id, nama, username, role')
            ->get()
            ->getResultArray();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getClients(): array
    {
        return $this->db->table('client_account')
            ->get()
            ->getResultArray();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getClientById(string $noRekening): array
    {
        return $this->db->table('client_account')
            ->where('no_rekening', $noRekening)
            ->get()
            ->getResultArray();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getRataHarianById(string $noRekening): array
    {
        $year = date('Y');

        return $this->db->table('v_saldo_harian')
            ->where('no_rekening', $noRekening)
            ->where('tahun <=', $year)
            ->orderBy('tahun', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getDividenById(string $noRekening): array
    {
        $year = date('Y');

        return $this->db->table('v_bagi_hasil')
            ->where('no_rekening', $noRekening)
            ->where('tahun <=', $year)
            ->orderBy('tahun', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getTransaksiByRekening(string $noRekening): array
    {
        $year = date('Y');

        return $this->db->table('v_transaksi')
            ->where('no_rekening', $noRekening)
            ->where('YEAR(tgl_transaksi) <=', $year)
            ->orderBy('tgl_transaksi', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getTransaksiBySertifikat(string $noSertifikat): array
    {
        return $this->db->table('v_transaksi')
            ->where('no_sertifikat', $noSertifikat)
            ->get()
            ->getResultArray();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getSaldoInvestasi(string $noRekening, string $year): array
    {
        return $this->db->table('v_saldo_investasi')
            ->where('no_rekening', $noRekening)
            ->where('YEAR(tanggal)', $year)
            ->orderBy('tanggal', 'ASC')
            ->get()
            ->getResultArray();
    }
}

