<?php

namespace App\Models;

use CodeIgniter\Model;

class ProfilPemegangSahamModel extends Model
{
    protected $table            = 'client_account';
    protected $primaryKey       = 'no_rekening';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'no_rekening',
        'nik',
        'nama',
        'tmp_lahir',
        'tgl_lahir',
        'dsn',
        'ds',
        'rt',
        'rw',
        'kec',
        'kab',
        'nama_ibu',
        'pekerjaan',
        'gaji',
        'usaha',
        'omzet',
        'tgl_daftar',
        'no_hp',
        'id_bank',
        'rek_bank',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    /**
     * @return list<array<string, mixed>>
     */
    public function profiles(): array
    {
        return $this->db->table($this->table)->get()->getResultArray();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findProfile(string $noRekening): ?array
    {
        $row = $this->db->table($this->table)
            ->where('no_rekening', $noRekening)
            ->get()
            ->getRowArray();

        return is_array($row) ? $row : null;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function cekRekening(string $table, string $noRekening): ?array
    {
        $row = $this->db->table($table)
            ->where('no_rekening', $noRekening)
            ->get()
            ->getRowArray();

        return is_array($row) ? $row : null;
    }

    /**
     * @param array<string, mixed> $data
     */
    public function tambahData(string $table, array $data): bool
    {
        return (bool) $this->db->table($table)->insert($data);
    }

    /**
     * @param array<string, mixed> $where
     * @param array<string, mixed> $data
     */
    public function ubahAnggota(array $where, array $data): bool
    {
        return (bool) $this->db->table($this->table)->where($where)->update($data);
    }

    /**
     * @param array<string, mixed> $where
     */
    public function hapusData(array $where, string $table): bool
    {
        return (bool) $this->db->table($table)->where($where)->delete();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function daftarBank(): array
    {
        return $this->db->table('daftar_bank')->get()->getResultArray();
    }
}

