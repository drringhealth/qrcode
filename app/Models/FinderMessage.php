<?php

namespace App\Models;

use CodeIgniter\Model;

class FinderMessage extends Model
{
    protected $table = 'finder_messages';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['pet_id', 'finder_name', 'finder_phone', 'finder_email', 'finder_message', 'finder_photo', 'finder_location', 'finder_latitude', 'finder_longitude', 'status'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $validationRules = [
        'pet_id' => 'required|numeric',
        'finder_name' => 'required|string|max_length[100]',
        'finder_phone' => 'required|numeric|max_length[15]',
        'finder_email' => 'permit_empty|valid_email',
        'finder_message' => 'required|string',
        'status' => 'in_list[new,contacted,resolved,spam]',
    ];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    public function getPet()
    {
        return $this->belongsTo(Pet::class, 'pet_id', 'id');
    }
}
