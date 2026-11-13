<?php

namespace App\Controllers;

use App\Models\ProfilPemegangSahamModel;
use App\Models\UserModel;

class ProfilPemegangSaham extends BaseController
{
    protected ProfilPemegangSahamModel $profilModel;
    protected UserModel $userModel;

    public function __construct()
    {
        $this->profilModel = new ProfilPemegangSahamModel();
        $this->userModel   = new UserModel();
    }

    public function index(): string
    {
        return $this->renderPage('profil_pemegang_saham/profil_pemegang_saham', [
            'title'    => 'IAA Investment | Profil Pemegang Saham',
            'profiles' => $this->profilModel->profiles(),
        ]);
    }

    public function show()
    {
        return $this->response->setJSON($this->profilModel->profiles());
    }

    public function getBank()
    {
        return $this->response->setJSON($this->profilModel->daftarBank());
    }

    public function tambahDataAnggota(): string
    {
        return $this->renderPage('profil_pemegang_saham/create', [
            'title' => 'IAA Investment | Profil Pemegang Saham',
        ]);
    }

    public function tambahData()
    {
        $rules = [
            'rekening' => 'required|trim|exact_length[9]',
            'nama'     => 'required',
        ];

        if (! $this->validate($rules)) {
            $this->session->setFlashdata('error', 'Gagal! Pastikan input anda benar!');

            return redirect()->to(site_url('profil_pemegang_saham'));
        }

        $noRekening = trim((string) $this->request->getPost('rekening'));
        $cek        = $this->profilModel->cekRekening('client_account', $noRekening);

        if ($cek !== null) {
            $this->session->setFlashdata('error', 'Gagal! No rekening sudah ada.');

            return redirect()->to(site_url('profil_pemegang_saham'));
        }

        $userId = (string) $this->session->get('user_id');
        $data   = [
            'no_rekening' => $noRekening,
            'nik'         => (string) $this->request->getPost('nik'),
            'nama'        => strtolower((string) $this->request->getPost('nama')),
            'tmp_lahir'   => strtolower((string) $this->request->getPost('tmp_lahir')),
            'tgl_lahir'   => (string) $this->request->getPost('tgl_lahir'),
            'dsn'         => (string) $this->request->getPost('dsn'),
            'ds'          => (string) $this->request->getPost('ds'),
            'rt'          => (string) $this->request->getPost('rt'),
            'rw'          => (string) $this->request->getPost('rw'),
            'kec'         => (string) $this->request->getPost('kec'),
            'kab'         => (string) $this->request->getPost('kab'),
            'nama_ibu'    => (string) $this->request->getPost('nama_ibu'),
            'pekerjaan'   => (string) $this->request->getPost('pekerjaan'),
            'gaji'        => (string) $this->request->getPost('gaji'),
            'usaha'       => (string) $this->request->getPost('usaha'),
            'omzet'       => (string) $this->request->getPost('omzet'),
            'tgl_daftar'  => (string) $this->request->getPost('tgl_daftar'),
            'no_hp'       => (string) $this->request->getPost('no_hp'),
            'id_bank'     => $this->request->getPost('id_bank'),
            'rek_bank'    => (string) $this->request->getPost('rek_bank'),
            'created_at'  => date('Y-m-d H:i:s'),
            'created_by'  => $userId,
        ];

        $this->profilModel->tambahData('client_account', $data);
        $this->userModel->logActivity($userId, 'Tambah Data Anggota Berhasil');
        $this->session->setFlashdata('success', 'Sukses! Data berhasil disimpan.');

        return redirect()->to(site_url('profil_pemegang_saham'));
    }

    public function ubahDataAnggota(string $noRekening): string
    {
        $profile = $this->profilModel->findProfile($noRekening);

        return $this->renderPage('profil_pemegang_saham/update', [
            'title'    => 'IAA Investment | Profil Pemegang Saham',
            'profiles' => $profile !== null ? [$profile] : [],
        ]);
    }

    public function ubahData()
    {
        if (! $this->validate(['nama_edt' => 'required'])) {
            $this->session->setFlashdata('error', 'Gagal! Pastikan input anda benar!');

            return redirect()->to(site_url('profil_pemegang_saham'));
        }

        $noRekening   = (string) $this->request->getPost('rekening_edt');
        $idBankField  = 'id_bank_edt_' . $noRekening;
        $idBank       = $this->request->getPost($idBankField);
        $userId       = (string) $this->session->get('user_id');

        $data = [
            'nik'        => (string) $this->request->getPost('nik_edt'),
            'nama'       => strtolower((string) $this->request->getPost('nama_edt')),
            'tmp_lahir'  => strtolower((string) $this->request->getPost('tmp_lahir_edt')),
            'tgl_lahir'  => (string) $this->request->getPost('tgl_lahir_edt'),
            'dsn'        => (string) $this->request->getPost('dsn_edt'),
            'ds'         => (string) $this->request->getPost('ds_edt'),
            'rt'         => (string) $this->request->getPost('rt_edt'),
            'rw'         => (string) $this->request->getPost('rw_edt'),
            'kec'        => (string) $this->request->getPost('kec_edt'),
            'kab'        => (string) $this->request->getPost('kab_edt'),
            'nama_ibu'   => (string) $this->request->getPost('nama_ibu_edt'),
            'pekerjaan'  => (string) $this->request->getPost('pekerjaan_edt'),
            'gaji'       => (string) $this->request->getPost('gaji_edt'),
            'usaha'      => (string) $this->request->getPost('usaha_edt'),
            'omzet'      => (string) $this->request->getPost('omzet_edt'),
            'tgl_daftar' => (string) $this->request->getPost('tgl_daftar_edt'),
            'no_hp'      => (string) $this->request->getPost('no_hp_edt'),
            'id_bank'    => $idBank !== '' ? $idBank : null,
            'rek_bank'   => (string) $this->request->getPost('rek_bank_edt'),
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $userId,
        ];

        $this->profilModel->ubahAnggota(['no_rekening' => $noRekening], $data);
        $this->userModel->logActivity($userId, 'Ubah Data Anggota Berhasil');
        $this->session->setFlashdata('success', 'Sukses! Data berhasil disimpan.');

        return redirect()->to(site_url('profil_pemegang_saham'));
    }

    public function lihatDataAnggota(string $noRekening): string
    {
        $profile = $this->profilModel->findProfile($noRekening);

        return $this->renderPage('profil_pemegang_saham/read', [
            'title'    => 'IAA Investment | Profil Pemegang Saham',
            'profiles' => $profile !== null ? [$profile] : [],
        ]);
    }

    public function hapusAnggota()
    {
        $noRekening = (string) $this->request->getPost('rekening');
        $userId     = (string) $this->session->get('user_id');

        $cek = $this->profilModel->cekRekening('transaksi_investasi', $noRekening);
        if ($cek === null) {
            $this->profilModel->hapusData(['no_rekening' => $noRekening], 'client_account');
            $this->userModel->logActivity($userId, 'Hapus Data Anggota Berhasil');
            $this->session->setFlashdata('success', 'Sukses! Data berhasil dihapus.');
        } else {
            $this->userModel->logActivity($userId, 'Hapus Data Anggota Gagal');
            $this->session->setFlashdata('error', 'Gagal! Data tidak dapat dihapus.');
        }

        return redirect()->to(site_url('profil_pemegang_saham'));
    }
}

