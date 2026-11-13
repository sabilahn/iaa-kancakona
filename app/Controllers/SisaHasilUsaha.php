<?php

namespace App\Controllers;

use App\Models\SisaHasilUsahaModel;
use App\Models\UserModel;

class SisaHasilUsaha extends BaseController
{
    private const PORSI_DISPEN = [82.5, 17.5];
    private const URAIAN_DISPEN = ['Dividen Pesero', 'IAA Pusat, PPA, dan Menejemen'];
    private const PORSI_DISDA = [30, 20, 50];
    private const URAIAN_DISDA = ['IAA Pusat', 'PP. Annuqayah', 'Menejemen IAA'];

    protected SisaHasilUsahaModel $shuModel;
    protected UserModel $userModel;

    public function __construct()
    {
        $this->shuModel  = new SisaHasilUsahaModel();
        $this->userModel = new UserModel();
    }

    public function index(): string
    {
        return $this->renderPage('sisa_hasil_usaha', [
            'title'    => 'IAA Investment | Sisa Hasil Usaha',
            'data_shu' => $this->shuModel->shuList(),
        ]);
    }

    public function tambahDataShu()
    {
        $tahun  = (string) $this->request->getPost('tahun_add');
        $jmlShu = (float) $this->request->getPost('jml_shu');
        $userId = (string) $this->session->get('user_id');

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            $cekSaldo = $this->shuModel->cekSaldoTahun($tahun);
            if ($cekSaldo === []) {
                $db->transRollback();
                $this->session->setFlashdata('error', 'Gagal! Belum ada data transaksi.');

                return redirect()->back();
            }

            $cekShu = $this->shuModel->cekShuTahun($tahun);
            if ($cekShu !== []) {
                $db->transRollback();
                $this->session->setFlashdata('error', 'Gagal! Data SHU sudah ada');

                return redirect()->back();
            }

            $now = date('Y-m-d H:i:s');
            $this->shuModel->tambahData([
                'tahun'      => $tahun,
                'jumlah'     => $jmlShu,
                'created_at' => $now,
                'created_by' => $userId,
            ], 'sisa_hasil_usaha');

            foreach (self::PORSI_DISPEN as $i => $porsi) {
                $this->shuModel->tambahData([
                    'porsi'      => $porsi,
                    'uraian'     => self::URAIAN_DISPEN[$i],
                    'shu'        => $jmlShu,
                    'jumlah'     => $porsi / 100 * $jmlShu,
                    'tahun'      => $tahun,
                    'created_at' => $now,
                    'created_by' => $userId,
                ], 'porsi_dispen');
            }

            $dispenManajemen = self::PORSI_DISPEN[1] / 100 * $jmlShu;
            foreach (self::PORSI_DISDA as $i => $porsi) {
                $this->shuModel->tambahData([
                    'porsi'      => $porsi,
                    'uraian'     => self::URAIAN_DISDA[$i],
                    'menejemen'  => $dispenManajemen,
                    'jumlah'     => $porsi / 100 * $dispenManajemen,
                    'tahun'      => $tahun,
                    'created_at' => $now,
                    'created_by' => $userId,
                ], 'porsi_disda');
            }

            $this->userModel->logActivity($userId, 'Tambah Data SHU Berhasil');

            $db->transCommit();
            $this->session->setFlashdata('success', 'Sukses! Data berhasil disimpan.');
        } catch (\Throwable $e) {
            $db->transRollback();
            log_message('error', 'tambahDataShu error: ' . $e->getMessage());
            $this->session->setFlashdata('error', 'Gagal menyimpan data!');
        }

        return redirect()->back();
    }

    public function ubahDataShu()
    {
        $idShu  = $this->request->getPost('id_shu');
        $tahun  = (string) $this->request->getPost('tahun_edt');
        $jmlShu = (float) $this->request->getPost('jml_shu_edt');
        $userId = (string) $this->session->get('user_id');

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            $now = date('Y-m-d H:i:s');
            $this->shuModel->editData('sisa_hasil_usaha', [
                'jumlah'     => $jmlShu,
                'updated_at' => $now,
                'updated_by' => $userId,
            ], ['id_shu' => $idShu]);

            foreach (self::PORSI_DISPEN as $porsi) {
                $this->shuModel->editData('porsi_dispen', [
                    'shu'        => $jmlShu,
                    'jumlah'     => $porsi / 100 * $jmlShu,
                    'updated_at' => $now,
                    'updated_by' => $userId,
                ], ['porsi' => $porsi, 'tahun' => $tahun]);
            }

            $dispenManajemen = self::PORSI_DISPEN[1] / 100 * $jmlShu;
            foreach (self::PORSI_DISDA as $porsi) {
                $this->shuModel->editData('porsi_disda', [
                    'menejemen'  => $dispenManajemen,
                    'jumlah'     => $porsi / 100 * $dispenManajemen,
                    'updated_at' => $now,
                    'updated_by' => $userId,
                ], ['porsi' => $porsi, 'tahun' => $tahun]);
            }

            $this->userModel->logActivity($userId, 'Ubah Data SHU Berhasil');

            $db->transCommit();
            $this->session->setFlashdata('success', 'Sukses! Data berhasil disimpan.');
        } catch (\Throwable $e) {
            $db->transRollback();
            log_message('error', 'ubahDataShu error: ' . $e->getMessage());
            $this->session->setFlashdata('error', 'Gagal! Pastikan input anda benar!');
        }

        return redirect()->back();
    }

    public function hapusDataShu()
    {
        $idShu  = $this->request->getPost('id_shu');
        $tahun  = (string) $this->request->getPost('tahun_hps');
        $userId = (string) $this->session->get('user_id');

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            $this->shuModel->hapusData(['id_shu' => $idShu], 'sisa_hasil_usaha');
            $this->shuModel->hapusData(['tahun' => $tahun], 'porsi_dispen');
            $this->shuModel->hapusData(['tahun' => $tahun], 'porsi_disda');

            $this->userModel->logActivity($userId, 'Hapus Data SHU Berhasil');

            $db->transCommit();
            $this->session->setFlashdata('success', 'Sukses! Data berhasil dihapus.');
        } catch (\Throwable $e) {
            $db->transRollback();
            log_message('error', 'hapusDataShu error: ' . $e->getMessage());
            $this->session->setFlashdata('error', 'Gagal menghapus data!');
        }

        return redirect()->back();
    }
}

