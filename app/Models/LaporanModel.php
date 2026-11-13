<?php

namespace App\Models;

use CodeIgniter\Model;

class LaporanModel extends Model
{
    protected $returnType = 'array';

    /**
     * @return list<array<string, mixed>>
     */
    public function getUsers(): array
    {
        return $this->db->table('client_account')
            ->select([
                'no_rekening',
                'nik',
                'nama',
                'dsn',
                'ds',
                'rt',
                'rw',
                'kec',
                'kab',
                'tgl_daftar',
            ])
            ->get()
            ->getResultArray();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getKec(): array
    {
        return $this->db->table('v_bagi_hasil')
            ->select('kec')
            ->groupBy('kec')
            ->orderBy('kec', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * @param array<string, mixed> $where
     * @return list<array<string, mixed>>
     */
    public function getData(string $table, array $where): array
    {
        return $this->db->table($table)
            ->where($where)
            ->get()
            ->getResultArray();
    }

    /**
     * @param array<string, mixed> $conds
     * @return list<array<string, mixed>>
     */
    public function getDataBagiHasil(array $conds): array
    {
        return $this->db->table('v_bagi_hasil bh')
            ->select('bh.*, rk.rek_bank, rk.nama_bank')
            ->join('v_rek_bank rk', 'rk.no_rekening = bh.no_rekening', 'left')
            ->where($conds)
            ->get()
            ->getResultArray();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getDataTransaksiBln(string $bulan, string $tahun): array
    {
        $tglAwal = $tahun . '-01-01';

        return $this->db->table('v_transaksi')
            ->where('MONTH(tgl_transaksi)', $bulan)
            ->where('YEAR(tgl_transaksi)', $tahun)
            ->where('is_deleted', 0)
            ->where('tgl_transaksi !=', $tglAwal)
            ->get()
            ->getResultArray();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getDataTransaksiTerkini(string $tglAwal, string $tglAkhir): array
    {
        return $this->db->table('v_transaksi')
            ->where('is_deleted', 0)
            ->where('tgl_transaksi >=', $tglAwal)
            ->where('tgl_transaksi <=', $tglAkhir)
            ->get()
            ->getResultArray();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getSaldoAwal(string $tahun): array
    {
        $tglAwal = $tahun . '-01-01';

        return $this->db->table('v_transaksi')
            ->where('tgl_transaksi', $tglAwal)
            ->where('is_deleted', 0)
            ->get()
            ->getResultArray();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getRekeningBank(): array
    {
        return $this->db->table('v_rek_bank')
            ->get()
            ->getResultArray();
    }
}

