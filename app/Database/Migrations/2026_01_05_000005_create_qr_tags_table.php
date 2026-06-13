<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateQrTagsTable extends Migration
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
            'serial_number' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'unique' => true,
            ],
            'qr_code' => [
                'type' => 'LONGTEXT',
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['available', 'reserved', 'sold', 'activated', 'blocked'],
                'default' => 'available',
            ],
            'assigned_order_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'assigned_user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'pet_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'activated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'),
                'update' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            ],
        ]);

        $this->forge->addKey('id', false, false, 'PRIMARY');
        $this->forge->addKey('serial_number');
        $this->forge->addKey('status');
        $this->forge->addKey('assigned_user_id');
        $this->forge->createTable('qr_tags');
    }

    public function down()
    {
        $this->forge->dropTable('qr_tags');
    }
}
