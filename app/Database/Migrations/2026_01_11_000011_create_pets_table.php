<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePetsTable extends Migration
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
            'qr_tag_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'pet_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'pet_type' => [
                'type' => 'ENUM',
                'constraint' => ['dog', 'cat', 'bird', 'rabbit', 'other'],
            ],
            'breed' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'gender' => [
                'type' => 'ENUM',
                'constraint' => ['male', 'female'],
            ],
            'dob' => [
                'type' => 'DATE',
            ],
            'color' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'weight' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => true,
            ],
            'sterilized' => [
                'type' => 'BOOLEAN',
                'default' => false,
            ],
            'profile_photo' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['active', 'lost', 'deceased', 'inactive'],
                'default' => 'active',
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
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('qr_tag_id', 'qr_tags', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('pets');
    }

    public function down()
    {
        $this->forge->dropTable('pets');
    }
}
