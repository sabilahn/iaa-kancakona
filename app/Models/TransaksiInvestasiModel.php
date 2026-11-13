<?php

namespace App\Models;

use CodeIgniter\Model;

class TransaksiInvestasiModel extends Model
{
    protected $table            = 'transaksi_investasi';
    protected $primaryKey       = 'id_transaksi';
    protected $returnType       = 'array';

    /**
     * @return list<array<string, mixed>>
     */
    public function transaksiList(string $tahun): array
    {
        $start = "$tahun-01-01";
        $end   = "$tahun-12-31";

        return $this->db->table('transaksi_investasi')
            ->select('id_transaksi, no_rekening, no_sertifikat, jml_transaksi, tgl_transaksi, created_at')
            ->where('tgl_transaksi >=', $start)
            ->where('tgl_transaksi <=', $end)
            ->where('is_deleted', 0)
            ->orderBy('created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * @return list<object>
     */
    public function jmlTransaksi(string $noRekening, string $tahun): array
    {
        return $this->db->table('v_transaksi_thn')
            ->select('saldo')
            ->where('no_rekening', $noRekening)
            ->where('tahun', $tahun)
            ->get()
            ->getResult();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function cekTransaksi(string $noRekening, string $tglTransaksi): array
    {
        return $this->db->table('transaksi_investasi')
            ->select('jml_transaksi')
            ->where([
                'no_rekening'   => $noRekening,
                'tgl_transaksi' => $tglTransaksi,
                'is_deleted'    => 0,
            ])
            ->get()
            ->getResultArray();
    }

    public function countTransaksi(string $noRekening, string $tglTransaksi): int
    {
        $tahun = date('Y', strtotime($tglTransaksi));
        $row   = $this->db->table('transaksi_investasi')
            ->select('COUNT(id_transaksi) AS banyak_transaksi')
            ->where('no_rekening', $noRekening)
            ->where('tgl_transaksi >=', "$tahun-01-01")
            ->where('tgl_transaksi <=', "$tahun-12-31")
            ->get()
            ->getRowArray();

        return (int) ($row['banyak_transaksi'] ?? 0);
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function cekSaldo(string $noRekening, string $tglTransaksi): array
    {
        $tahun = date('Y', strtotime($tglTransaksi));

        return $this->db->table('saldo_harian')
            ->select('no_rekening')
            ->where([
                'no_rekening' => $noRekening,
                'tahun'       => $tahun,
            ])
            ->get()
            ->getResultArray();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function rekeningList(): array
    {
        return $this->db->table('client_account')
            ->select('no_rekening')
            ->get()
            ->getResultArray();
    }

    /**
     * @param array<string, mixed> $data
     */
    public function tambahData(string $table, array $data): bool
    {
        return (bool) $this->db->table($table)->insert($data);
    }

    /**
     * @param array<string, mixed> $data
     */
    public function tambahId(string $table, array $data): int
    {
        $this->db->table($table)->insert($data);

        return (int) $this->db->insertID();
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
    public function hapusData(string $table, array $where): bool
    {
        return (bool) $this->db->table($table)->where($where)->delete();
    }

    /**
     * @return list<object>
     */
    public function penarikanList(string $tahun): array
    {
        $start = "$tahun-01-01";
        $end   = "$tahun-12-31 23:59:59";

        return $this->db->table('penarikan_investasi')
            ->select('id_penarikan, no_rekening, jml_penarikan, tgl_penarikan, created_at')
            ->where('created_at >=', $start)
            ->where('created_at <=', $end)
            ->orderBy('created_at', 'DESC')
            ->get()
            ->getResult();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function cekPenarikan(string $noRekening, string $tglPenarikan): array
    {
        return $this->db->table('penarikan_investasi')
            ->select('jml_penarikan')
            ->where([
                'no_rekening'   => $noRekening,
                'tgl_penarikan' => $tglPenarikan,
            ])
            ->get()
            ->getResultArray();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function cekTransaksiTahun(string $noRekening, string $tahun): array
    {
        $start = "$tahun-01-01";
        $end   = "$tahun-12-31";

        return $this->db->table('transaksi_investasi')
            ->where('no_rekening', $noRekening)
            ->where('tgl_transaksi >=', $start)
            ->where('tgl_transaksi <=', $end)
            ->where('is_deleted', 0)
            ->get()
            ->getResultArray();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function cekPenarikanTahun(string $noRekening, string $tahun): array
    {
        $start = "$tahun-01-01";
        $end   = "$tahun-12-31";

        return $this->db->table('penarikan_investasi')
            ->where('no_rekening', $noRekening)
            ->where('tgl_penarikan >=', $start)
            ->where('tgl_penarikan <=', $end)
            ->get()
            ->getResultArray();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getTransaksiTahun(string $tahun): array
    {
        return $this->db->table('v_transaksi_thn')
            ->where('tahun', $tahun)
            ->where('jml_transaksi !=', '')
            ->get()
            ->getResultArray();
    }

    /**
     * @param array<string, mixed> $where
     * @return list<array<string, mixed>>
     */
    public function getWhereArray(string $table, array $where): array
    {
        return $this->db->table($table)->where($where)->get()->getResultArray();
    }

    /**
     * @param array<string, mixed> $where
     */
    public function getWhereRow(string $table, array $where): ?object
    {
        return $this->db->table($table)->where($where)->get()->getRow();
    }

    /**
     * @param array<string, mixed> $where
     * @return array<string, mixed>|null
     */
    public function getWhereRowArray(string $table, array $where): ?array
    {
        $row = $this->db->table($table)->where($where)->get()->getRowArray();

        return is_array($row) ? $row : null;
    }

    /**
     * @param array<string, mixed> $where
     * @return list<object>
     */
    public function getWhereObjects(string $table, array $where): array
    {
        return $this->db->table($table)->where($where)->get()->getResult();
    }

    /**
     * @return list<object>
     */
    public function saldoInvestasi(string $tahun): array
    {
        return $this->db->table('v_transaksi_thn')
            ->where('tahun', $tahun)
            ->get()
            ->getResult();
    }

    /**
     * @return list<object>
     */
    public function getSaldoId(string $tahun, string $noRekening): array
    {
        $start = "$tahun-01-01";
        $end   = "$tahun-12-31";

        return $this->db->table('v_saldo_investasi')
            ->where('no_rekening', $noRekening)
            ->where('tanggal >=', $start)
            ->where('tanggal <=', $end)
            ->get()
            ->getResult();
    }

    public function jumlahRekening(): int
    {
        return (int) $this->db->table('client_account')->countAllResults();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function rekeningListLimit(int $limit = 100, int $offset = 0): array
    {
        return $this->db->table('client_account')
            ->select('no_rekening')
            ->limit($limit, $offset)
            ->get()
            ->getResultArray();
    }

    /**
     * Hitung selisih hari untuk kalkulasi saldo harian & saldo tahunan (identik dengan logika CI3).
     *
     * @return array{0: float, 1: float}
     */
    public function getHariTransaksi(string $tanggal, ?string $today = null): array
    {
        $tglSkrg         = strtotime($today ?? date('Y-m-d'));
        $tahunIni        = date('Y', $tglSkrg);
        $tglAwal         = date('Y-m-d', strtotime($tahunIni . '-01-01 -1 days'));
        $tglTransaksi    = date('Y-m-d', strtotime($tanggal . ' -1 day'));
        $hariSaldoHarian = abs($tglSkrg - strtotime($tglTransaksi)) / 86400;
        $hariSaldoTahun  = max(1, ($tglSkrg - strtotime($tglAwal)) / 86400);

        return [$hariSaldoHarian, $hariSaldoTahun];
    }

    /**
     * Hitung akumulasi saldo harian user dari 1 Jan tahun berjalan hingga $tglTransaksi.
     */
    public function updateSaldoUser(string $noRekening, string $tglTransaksi, string $thn): float
    {
        $transaksiAll = $this->cekTransaksiTahun($noRekening, $thn);
        $penarikanAll = $this->cekPenarikanTahun($noRekening, $thn);

        $transaksiIndex = [];
        foreach ($transaksiAll as $row) {
            $transaksiIndex[$row['tgl_transaksi']][] = $row;
        }

        $penarikanIndex = [];
        foreach ($penarikanAll as $row) {
            $penarikanIndex[$row['tgl_penarikan']][] = $row;
        }

        $saldoSkr        = 0.0;
        $penambahanSaldo = 0.0;

        $from = date('Y-m-d', strtotime($thn . '-01-01 -1 days'));
        $to   = $tglTransaksi;

        while (strtotime($from) < strtotime($to)) {
            $from = date('Y-m-d', strtotime($from . ' +1 day'));

            $cek  = $transaksiIndex[$from] ?? [];
            $cek2 = $penarikanIndex[$from] ?? [];

            if ($cek !== []) {
                foreach ($cek as $transaksi) {
                    $jml              = (float) $transaksi['jml_transaksi'];
                    $saldoSkr         = $saldoSkr + $jml + $penambahanSaldo;
                    $penambahanSaldo  = $penambahanSaldo + $jml;
                }
            } elseif ($cek2 !== []) {
                foreach ($cek2 as $penarikan) {
                    $jml              = (float) $penarikan['jml_penarikan'];
                    $saldoSkr         = $saldoSkr - $jml + $penambahanSaldo;
                    $penambahanSaldo  = $penambahanSaldo - $jml;
                }
            } else {
                $saldoSkr = $saldoSkr + $penambahanSaldo;
            }
        }

        return $saldoSkr;
    }

    /**
     * Recalculate dan simpan rata_rata_harian & rata_rata_tahun ke saldo_harian.
     */
    public function recalculateSaldoHarian(string $noRekening, string $tahun, string $userId): void
    {
        $tgl  = $tahun . date('-m-d');
        $hari = $this->getHariTransaksi($tgl);

        $rataHarian = $this->updateSaldoUser($noRekening, $tgl, $tahun);
        $rataTahun  = $rataHarian / $hari[1];

        $this->editData(
            'saldo_harian',
            [
                'rata_rata_harian' => $rataHarian,
                'rata_rata_tahun'  => $rataTahun,
                'updated_at'       => date('Y-m-d H:i:s'),
                'updated_by'       => $userId,
            ],
            [
                'no_rekening' => $noRekening,
                'tahun'       => $tahun,
            ]
        );
    }
}

