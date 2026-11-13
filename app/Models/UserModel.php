<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table          = 'user_account';
    protected $primaryKey     = 'user_id';
    protected $useAutoIncrement = false;
    protected $returnType     = 'array';
    protected $allowedFields  = ['user_id', 'username', 'userpass', 'role'];

    public function findByUsername(string $username): ?array
    {
        $row = $this->where('username', $username)->first();

        return is_array($row) ? $row : null;
    }

    public function findById(string $userId): ?array
    {
        $row = $this->where('user_id', $userId)->first();

        return is_array($row) ? $row : null;
    }

    /**
     * Verifikasi password (mendukung password_hash modern maupun hash MD5 lama).
     * Jika masih memakai MD5 dan cocok, otomatis di-upgrade ke password_hash().
     */
    public function verifyAndUpgradePassword(array $user, string $plainPassword): bool
    {
        $storedHash = (string) ($user['userpass'] ?? '');
        if ($storedHash === '') {
            return false;
        }

        if (password_verify($plainPassword, $storedHash)) {
            if (password_needs_rehash($storedHash, PASSWORD_DEFAULT)) {
                $this->updatePassword((string) $user['user_id'], password_hash($plainPassword, PASSWORD_DEFAULT));
            }

            return true;
        }

        // Dukungan transisi hash MD5 32-karakter dari CI3 -> lazy rehash ke bcrypt/argon2
        if (strlen($storedHash) === 32 && ctype_xdigit($storedHash)) {
            if (hash_equals(strtolower($storedHash), md5($plainPassword))) {
                $this->updatePassword((string) $user['user_id'], password_hash($plainPassword, PASSWORD_DEFAULT));

                return true;
            }
        }

        return false;
    }

    public function updatePassword(string $userId, string $newHash): bool
    {
        return (bool) $this->db->table($this->table)
            ->where('user_id', $userId)
            ->update(['userpass' => $newHash]);
    }

    public function logActivity(string $userId, string $activity): bool
    {
        return (bool) $this->db->table('activity_log')->insert([
            'user_id'  => $userId,
            'activity' => $activity,
            'times'    => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Daftar akun non-user untuk dropdown login.
     *
     * @return list<array<string, mixed>>
     */
    public function getAdminUsers(): array
    {
        return $this->where('role !=', 'user')->findAll();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getAllUsers(): array
    {
        return $this->findAll();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getActivityLogs(): array
    {
        return $this->db->table('v_activity')->get()->getResultArray();
    }

    public function createUser(array $data): bool
    {
        return (bool) $this->db->table($this->table)->insert($data);
    }

    public function deleteUser(string $userId): bool
    {
        return (bool) $this->db->table($this->table)
            ->where('user_id', $userId)
            ->delete();
    }
}

