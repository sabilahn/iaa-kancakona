<?php

namespace App\Models;

use CodeIgniter\Model;

class SisaHasilUsahaModel extends Model
{
    protected $table      = 'sisa_hasil_usaha';
    protected $primaryKey = 'id_shu';
    protected $returnType = 'array';

    /**
     * @return list<array<string, mixed>>
     */
    public function shuList(): array
    {
        return $this->db->table($this->table)->get()->getResultArray();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function cekShuTahun(string $tahun): array
    {
        return $this->db->table($this->table)
            ->where('tahun', $tahun)
            ->get()
            ->getResultArray();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function cekSaldoTahun(string $tahun): array
    {
        return $this->db->table('saldo_harian')
            ->where('YEAR(created_at)', $tahun)
            ->get()
            ->getResultArray();
    }

    /**
     * @param array<string, mixed> $data
     */
    public function tambahData(array $data, string $table): bool
    {
        return (bool) $this->db->table($table)->insert($data);
    }

    /**
     * @param array<string, mixed> $data
     * @param array<string, mixed> $where
     */
    public function editData(string $table, array $data, array $where): bool
    {
        return (bool) $this->db->table($table)->where($where)->update($data);
    }

    /**
     * @param array<string, mixed> $where
     */
    public function hapusData(array $where, string $table): bool
    {
        return (bool) $this->db->table($table)->where($where)->delete();
    }
}

