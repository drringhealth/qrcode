<?php

namespace App\Models;

use CodeIgniter\Model;

class PetLostReport extends Model
{
    protected $table = 'pet_lost_reports';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['pet_id', 'reward_amount', 'description', 'last_seen_location', 'last_seen_latitude', 'last_seen_longitude', 'lost_date', 'status'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $validationRules = [
        'pet_id' => 'required|numeric',
        'description' => 'required|string',
        'last_seen_location' => 'required|string|max_length[255]',
        'lost_date' => 'required|valid_date[Y-m-d]',
        'status' => 'in_list[active,found,cancelled]',
    ];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    public function getPet()
    {
        return $this->belongsTo(Pet::class, 'pet_id', 'id');
    }
}
