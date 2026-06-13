<?php

namespace App\Models;

use CodeIgniter\Model;

class PetVaccination extends Model
{
    protected $table = 'pet_vaccinations';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['pet_id', 'vaccine_name', 'vaccination_date', 'next_due_date', 'notes'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $validationRules = [
        'pet_id' => 'required|numeric',
        'vaccine_name' => 'required|string|max_length[100]',
        'vaccination_date' => 'required|valid_date[Y-m-d]',
        'next_due_date' => 'permit_empty|valid_date[Y-m-d]',
    ];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    public function getPet()
    {
        return $this->belongsTo(Pet::class, 'pet_id', 'id');
    }

    public function isDue()
    {
        if (empty($this->data['next_due_date'])) return false;
        return strtotime($this->data['next_due_date']) <= time();
    }
}
