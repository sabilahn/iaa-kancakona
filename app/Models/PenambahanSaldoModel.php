<?php

namespace App\Models;

use CodeIgniter\Model;

class PenambahanSaldoModel extends Model
{
    protected $table      = 'saldo_harian';
    protected $returnType = 'array';

    /**
     * @return list<array<string, mixed>>
     */
    public function getUsers(): array
    {
        return $this->db->table('client_account')
            ->select('no_rekening')
            ->get()
            ->getResultArray();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getTransaksi(string $noRekening, string $tahun): ?array
    {
        $row = $this->db->table('v_transaksi_thn')
            ->select('saldo AS jml_transaksi')
            ->where('no_rekening', $noRekening)
            ->where('tahun', $tahun)
            ->get()
            ->getRowArray();

        return is_array($row) ? $row : null;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getSaldo(string $noRekening, string $tahun): ?array
    {
        $row = $this->db->table('v_saldo_harian')
            ->select('rata_rata_harian')
            ->where('no_rekening', $noRekening)
            ->where('tahun', $tahun)
            ->get()
            ->getRowArray();

        return is_array($row) ? $row : null;
    }

    /**
     * @param array<string, mixed> $data
     * @param array<string, mixed> $where
     */
    public function editData(string $table, array $data, array $where): bool
    {
        return (bool) $this->db->table($table)->where($where)->update($data);
    }
}

