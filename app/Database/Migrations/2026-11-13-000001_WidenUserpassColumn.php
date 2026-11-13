<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class WidenUserpassColumn extends Migration
{
    public function up()
    {
        // Perlebar kolom userpass dari VARCHAR(50) ke VARCHAR(255) agar mendukung bcrypt/argon2 password_hash()
        $this->forge->modifyColumn('user_account', [
            'userpass' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->modifyColumn('user_account', [
            'userpass' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false,
            ],
        ]);
    }
}

