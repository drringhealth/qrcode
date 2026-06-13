<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePetPhotosTable extends Migration
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
            'photo' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'uploaded_at' => [
                'type' => 'DATETIME',
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);

        $this->forge->addKey('id', false, false, 'PRIMARY');
        $this->forge->addForeignKey('pet_id', 'pets', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('pet_photos');
    }

    public function down()
    {
        $this->forge->dropTable('pet_photos');
    }
}
