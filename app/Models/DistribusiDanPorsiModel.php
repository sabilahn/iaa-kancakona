<?php

namespace App\Models;

use CodeIgniter\Model;

class DistribusiDanPorsiModel extends Model
{
    protected $returnType = 'array';

    /**
     * @return list<array<string, mixed>>
     */
    public function showByTahun(string $table, string $tahun): array
    {
        return $this->db->table($table)
            ->where('tahun', $tahun)
            ->get()
            ->getResultArray();
    }
}

