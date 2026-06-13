<?php

namespace App\Models;

use CodeIgniter\Model;

class User extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $allowedFields = ['name', 'mobile', 'email', 'password', 'profile_photo', 'status', 'email_verified_at', 'mobile_verified_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    protected $validationRules = [
        'name' => 'required|string|max_length[100]',
        'mobile' => 'required|numeric|is_unique[users.mobile]',
        'email' => 'permit_empty|valid_email|is_unique[users.email]',
        'password' => 'permit_empty|min_length[8]',
        'status' => 'in_list[active,inactive,blocked]',
    ];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Relationships
    public function getAddresses()
    {
        return $this->hasMany(UserAddress::class, 'user_id', 'id');
    }

    public function getOrders()
    {
        return $this->hasMany(Order::class, 'user_id', 'id');
    }

    public function getPets()
    {
        return $this->hasMany(Pet::class, 'user_id', 'id');
    }

    public function getQrTags()
    {
        return $this->hasMany(QrTag::class, 'assigned_user_id', 'id');
    }
}
