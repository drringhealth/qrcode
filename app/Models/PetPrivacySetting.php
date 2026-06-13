<?php

namespace App\Models;

use CodeIgniter\Model;

class PetPrivacySetting extends Model
{
    protected $table = 'pet_privacy_settings';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['pet_id', 'show_pet_name', 'show_photo', 'show_breed', 'show_age', 'show_vaccination', 'show_owner_name', 'show_phone'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $validationRules = [
        'pet_id' => 'required|numeric',
    ];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    public function getPet()
    {
        return $this->belongsTo(Pet::class, 'pet_id', 'id');
    }
}
