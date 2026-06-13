<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminUser extends Model
{
    protected $table = 'admin_users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['name', 'email', 'password', 'role', 'status', 'last_login'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $validationRules = [
        'name' => 'required|string|max_length[100]',
        'email' => 'required|valid_email|is_unique[admin_users.email]',
        'password' => 'required|min_length[8]',
        'role' => 'required|in_list[admin,manager,staff]',
        'status' => 'in_list[active,inactive,blocked]',
    ];
    protected $validationMessages = [
        'email' => [
            'is_unique' => 'Email already exists',
        ],
    ];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Relationships
    public function getAuditLogs()
    {
        return $this->hasMany(AuditLog::class, 'admin_user_id', 'id');
    }
}
