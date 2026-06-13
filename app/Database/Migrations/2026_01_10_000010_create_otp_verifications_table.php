<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOtpVerificationsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'mobile' => [
                'type' => 'VARCHAR',
                'constraint' => 15,
            ],
            'otp' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
            ],
            'attempts' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'verified' => [
                'type' => 'BOOLEAN',
                'default' => false,
            ],
            'expires_at' => [
                'type' => 'DATETIME',
            ],
            'verified_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);

        $this->forge->addKey('id', false, false, 'PRIMARY');
        $this->forge->addKey('mobile');
        $this->forge->createTable('otp_verifications');
    }

    public function down()
    {
        $this->forge->dropTable('otp_verifications');
    }
}
