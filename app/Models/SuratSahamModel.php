<?php

namespace App\Models;

use CodeIgniter\Model;

class SuratSahamModel extends Model
{
    protected $table      = 'v_transaksi';
    protected $returnType = 'object';

    /**
     * @param array<string, mixed> $where
     */
    public function getData(array $where): ?object
    {
        return $this->db->table($this->table)
            ->where($where)
            ->get()
            ->getRow();
    }
}

