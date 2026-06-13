<?php

namespace App\Models;

use CodeIgniter\Model;

class UserAddress extends Model
{
    protected $table = 'user_addresses';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['user_id', 'address_type', 'address', 'city', 'state', 'country', 'pincode', 'is_default'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $validationRules = [
        'user_id' => 'required|numeric',
        'address' => 'required|string',
        'city' => 'required|string|max_length[50]',
        'state' => 'required|string|max_length[50]',
        'country' => 'required|string|max_length[50]',
        'pincode' => 'required|numeric|max_length[10]',
        'address_type' => 'in_list[billing,shipping,both]',
    ];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Relationships
    public function getUser()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
