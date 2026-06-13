<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePetPrivacySettingsTable extends Migration
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
            'pet_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'show_pet_name' => [
                'type' => 'BOOLEAN',
                'default' => true,
            ],
            'show_photo' => [
                'type' => 'BOOLEAN',
                'default' => true,
            ],
            'show_breed' => [
                'type' => 'BOOLEAN',
                'default' => true,
            ],
            'show_age' => [
                'type' => 'BOOLEAN',
                'default' => true,
            ],
            'show_vaccination' => [
                'type' => 'BOOLEAN',
                'default' => false,
            ],
            'show_owner_name' => [
                'type' => 'BOOLEAN',
                'default' => false,
            ],
            'show_phone' => [
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
        $this->forge->addForeignKey('pet_id', 'pets', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('pet_privacy_settings');
    }

    public function down()
    {
        $this->forge->dropTable('pet_privacy_settings');
    }
}
