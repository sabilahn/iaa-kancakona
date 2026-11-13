<?php

namespace App\Libraries;

/**
 * Loader kredensial & environment sesuai STANDAR_SECURITY_ENV.md.
 * Membaca file PHP (berguard FCPATH/BASEPATH) di luar public_html,
 * dengan fallback ke .env.php di root project untuk development lokal.
 */
class EnvConfig
{
    public const PROJECT_KEY = 'kancakona';

    /** @var array<string, mixed>|null */
    private static ?array $cache = null;

    /**
     * Ambil seluruh array konfigurasi dari file env PHP yang ditemukan.
     *
     * @return array<string, mixed>
     */
    public static function all(): array
    {
        if (self::$cache !== null) {
            return self::$cache;
        }

        $key = self::PROJECT_KEY;
        $home = ! empty($_SERVER['HOME']) ? rtrim((string) $_SERVER['HOME'], '/\\') : '';

        $candidates = [
            // 1. Project di subfolder public_html/<project>/public -> /home/user/.env_config/
            dirname(FCPATH, 3) . DIRECTORY_SEPARATOR . '.env_config' . DIRECTORY_SEPARATOR . $key . '.php',
            // 2. Project langsung di public_html/public atau root/<project>/public
            dirname(FCPATH, 2) . DIRECTORY_SEPARATOR . '.env_config' . DIRECTORY_SEPARATOR . $key . '.php',
            // 3. Fallback berdasarkan HOME di cPanel (/home/username/.env_config/...)
            $home !== '' ? $home . DIRECTORY_SEPARATOR . '.env_config' . DIRECTORY_SEPARATOR . $key . '.php' : '',
            // 4. Fallback di root project sendiri (development lokal)
            ROOTPATH . '.env.php',
        ];

        self::$cache = [];
        foreach ($candidates as $path) {
            if ($path !== '' && is_file($path) && is_readable($path)) {
                $loaded = include $path;
                if (is_array($loaded)) {
                    self::$cache = $loaded;
                    break;
                }
            }
        }

        return self::$cache;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $all = self::all();

        return array_key_exists($key, $all) ? $all[$key] : $default;
    }
}

