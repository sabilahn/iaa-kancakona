<?php

namespace App\Models;

use CodeIgniter\Model;

class SaldoHarianModel extends Model
{
    protected $table      = 'v_saldo_harian';
    protected $returnType = 'array';

    /**
     * @return list<array<string, mixed>>
     */
    public function saldoHarianList(string $tahun): array
    {
        return $this->db->table('v_saldo_harian')
            ->where('tahun', $tahun)
            ->get()
            ->getResultArray();
    }
}

