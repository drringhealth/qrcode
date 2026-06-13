<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserAddressesTable extends Migration
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
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'address_type' => [
                'type' => 'ENUM',
                'constraint' => ['billing', 'shipping', 'both'],
                'default' => 'both',
            ],
            'address' => [
                'type' => 'TEXT',
            ],
            'city' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'state' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'country' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'pincode' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
            ],
            'is_default' => [
                'type' => 'BOOLEAN',
                'default' => false,
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
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('user_addresses');
    }

    public function down()
    {
        $this->forge->dropTable('user_addresses');
    }
}
