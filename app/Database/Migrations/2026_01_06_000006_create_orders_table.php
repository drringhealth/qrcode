<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOrdersTable extends Migration
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
            'order_no' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'unique' => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'customer_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'mobile' => [
                'type' => 'VARCHAR',
                'constraint' => 15,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'billing_address' => [
                'type' => 'TEXT',
            ],
            'shipping_address' => [
                'type' => 'TEXT',
            ],
            'amount' => [
                'type' => 'DECIMAL',
                'constraint' => '12,2',
            ],
            'tax' => [
                'type' => 'DECIMAL',
                'constraint' => '12,2',
                'default' => 0,
            ],
            'shipping_charge' => [
                'type' => 'DECIMAL',
                'constraint' => '12,2',
                'default' => 0,
            ],
            'discount' => [
                'type' => 'DECIMAL',
                'constraint' => '12,2',
                'default' => 0,
            ],
            'total_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '12,2',
            ],
            'payment_status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'completed', 'failed', 'refunded'],
                'default' => 'pending',
            ],
            'order_status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'confirmed', 'packed', 'shipped', 'delivered', 'cancelled'],
                'default' => 'pending',
            ],
            'notes' => [
                'type' => 'TEXT',
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
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', false, false, 'PRIMARY');
        $this->forge->addKey('order_no');
        $this->forge->addKey('user_id');
        $this->forge->createTable('orders');
    }

    public function down()
    {
        $this->forge->dropTable('orders');
    }
}
