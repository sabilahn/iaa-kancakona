<?php

namespace App\Models;

use CodeIgniter\Model;

class BagiHasilModel extends Model
{
    protected $table      = 'v_bagi_hasil';
    protected $returnType = 'array';

    /**
     * @return list<array<string, mixed>>
     */
    public function getByTahun(string $tahun): array
    {
        return $this->db->table($this->table)
            ->where('tahun', $tahun)
            ->get()
            ->getResultArray();
    }
}

