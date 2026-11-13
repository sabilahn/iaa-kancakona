<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\HTTP\RedirectResponse;

class Auth extends BaseController
{
    private UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index(): string|RedirectResponse
    {
        if ($this->session->get('status') === 'login') {
            return redirect()->to(base_url('dashboard'));
        }

        return view('users/login', [
            'title'     => 'IAA Investment | Login Page',
            'user_list' => $this->userModel->getAdminUsers(),
        ]);
    }

    public function login(): RedirectResponse
    {
        $rules = [
            'username' => 'required',
            'userpass' => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to(base_url('auth'));
        }

        $username  = trim((string) $this->request->getPost('username'));
        $plainPass = (string) $this->request->getPost('userpass');

        $user = $this->userModel->findByUsername($username);

        if ($user !== null && $this->userModel->verifyAndUpgradePassword($user, $plainPass)) {
            $this->session->regenerate(true);
            $this->session->set([
                'user_id'  => $user['user_id'],
                'username' => $user['username'],
                'role'     => $user['role'],
                'status'   => 'login',
            ]);

            $this->userModel->logActivity((string) $user['user_id'], 'Login Berhasil');

            return redirect()->to(base_url('dashboard'));
        }

        $this->session->setFlashdata(
            'gagal',
            '<div class="text-center"><i class="fa fa-exclamation-circle"></i> Username atau password salah</div>',
        );

        return redirect()->to(base_url('auth'));
    }

    public function ubahPassword(): string
    {
        return view('users/ubah_pass');
    }

    public function updatePassword(): string|RedirectResponse
    {
        $rules = [
            'o_userpass' => 'required',
            'n_userpass' => 'required|matches[c_userpass]',
            'c_userpass' => 'required|matches[n_userpass]',
        ];

        if (! $this->validate($rules)) {
            $this->session->setFlashdata('error', 'Terjadi kesalahan! Pastikan input anda benar!');

            return view('users/ubah_pass');
        }

        $userId    = (string) $this->session->get('user_id');
        $oldPass   = trim((string) $this->request->getPost('o_userpass'));
        $newPass   = trim((string) $this->request->getPost('n_userpass'));
        $user      = $this->userModel->findById($userId);

        if ($user === null || ! $this->userModel->verifyAndUpgradePassword($user, $oldPass)) {
            $this->session->setFlashdata('error1', 'Password lama salah!');

            return view('users/ubah_pass');
        }

        $db = db_connect();
        $db->transBegin();

        try {
            $this->userModel->updatePassword($userId, password_hash($newPass, PASSWORD_DEFAULT));
            $this->userModel->logActivity($userId, 'Ubah Password Berhasil');

            if ($db->transStatus() === false) {
                $db->transRollback();

                return redirect()->to(base_url('dashboard'));
            }

            $db->transCommit();

            return redirect()->to(base_url('auth/logout'));
        } catch (\Throwable) {
            $db->transRollback();

            return redirect()->to(base_url('dashboard'));
        }
    }

    public function logout(): RedirectResponse
    {
        $userId = (string) $this->session->get('user_id');
        if ($userId !== '') {
            $this->userModel->logActivity($userId, 'Logout');
        }

        $this->session->destroy();

        return redirect()->to(base_url('auth'));
    }
}

