<?php

namespace App\Models;

use CodeIgniter\Model;

class PetPhoto extends Model
{
    protected $table = 'pet_photos';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['pet_id', 'photo'];
    protected $useTimestamps = true;
    protected $createdField = 'uploaded_at';
    protected $validationRules = [
        'pet_id' => 'required|numeric',
        'photo' => 'required|string',
    ];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Relationships
    public function getPet()
    {
        return $this->belongsTo(Pet::class, 'pet_id', 'id');
    }
}
