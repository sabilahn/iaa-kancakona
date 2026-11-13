<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\HTTP\RedirectResponse;

class Users extends BaseController
{
    private UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function list(): string
    {
        $users = $this->userModel->getAllUsers();
        foreach ($users as $i => $user) {
            $uid = esc($user['user_id']);
            $users[$i]['action'] = '<span data-toggle="modal" data-target="#resetModal' . $uid . '"><button class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Kembalikan password"><i class="fas fa-sync"></i></button></span> '
                . '<button class="btn btn-danger" data-toggle="modal" data-target="#hapusModal' . $uid . '"><i class="fa fa-trash"></i></button>';
        }

        return $this->renderPage('users/list', [
            'title'     => 'IAA Investment | Setting',
            'user_list' => $users,
        ]);
    }

    public function tambah(): RedirectResponse
    {
        $rules = [
            'id_user'  => 'required|max_length[10]',
            'username' => 'required|max_length[20]',
            'userpass' => 'required',
        ];

        if (! $this->validate($rules)) {
            $this->session->setFlashdata('error', 'Pastikan seluruh input terisi dengan benar.');

            return redirect()->back();
        }

        $db = db_connect();
        $db->transBegin();

        try {
            $ok = $this->userModel->createUser([
                'user_id'  => trim((string) $this->request->getPost('id_user')),
                'username' => trim((string) $this->request->getPost('username')),
                'userpass' => password_hash((string) $this->request->getPost('userpass'), PASSWORD_DEFAULT),
                'role'     => 'user',
            ]);

            if (! $ok || $db->transStatus() === false) {
                $db->transRollback();
                $this->session->setFlashdata('error', 'Gagal menambahkan user.');

                return redirect()->back();
            }

            $db->transCommit();
            $this->session->setFlashdata('success', 'Sukses! Data berhasil disimpan.');
        } catch (\Throwable) {
            $db->transRollback();
            $this->session->setFlashdata('error', 'Terjadi kesalahan saat menyimpan user.');
        }

        return redirect()->back();
    }

    public function hapus(): RedirectResponse
    {
        $userId = trim((string) $this->request->getPost('id_hapus'));
        if ($userId !== '') {
            $this->userModel->deleteUser($userId);
            $this->session->setFlashdata('success', 'Sukses! Data berhasil dihapus.');
        }

        return redirect()->back();
    }

    public function reset(): RedirectResponse
    {
        $userId = trim((string) $this->request->getPost('id_reset'));
        if ($userId === '') {
            return redirect()->back();
        }

        $db = db_connect();
        $db->transBegin();

        try {
            $ok = $this->userModel->updatePassword($userId, password_hash('123', PASSWORD_DEFAULT));
            if (! $ok || $db->transStatus() === false) {
                $db->transRollback();

                return redirect()->back();
            }

            $db->transCommit();
            $this->session->setFlashdata('success', 'Sukses! Password berhasil dikembalikan menjadi 123');
        } catch (\Throwable) {
            $db->transRollback();
        }

        return redirect()->back();
    }

    public function activity(): string
    {
        return $this->renderPage('users/activity', [
            'title'      => 'IAA Investment | Setting',
            'activities' => $this->userModel->getActivityLogs(),
        ]);
    }
}

