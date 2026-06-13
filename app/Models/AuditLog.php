<?php

namespace App\Models;

use CodeIgniter\Model;

class AuditLog extends Model
{
    protected $table = 'audit_logs';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['admin_user_id', 'module', 'action', 'record_id', 'old_values', 'new_values', 'description', 'ip_address', 'user_agent'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $casts = [
        'old_values' => 'json',
        'new_values' => 'json',
    ];

    public function getAdminUser()
    {
        return $this->belongsTo(AdminUser::class, 'admin_user_id', 'id');
    }
}
