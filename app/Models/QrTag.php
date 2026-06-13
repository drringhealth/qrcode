<?php

namespace App\Models;

use CodeIgniter\Model;

class QrTag extends Model
{
    protected $table = 'qr_tags';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['serial_number', 'qr_code', 'status', 'assigned_order_id', 'assigned_user_id', 'pet_id', 'activated_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $validationRules = [
        'serial_number' => 'required|string|max_length[50]|is_unique[qr_tags.serial_number]',
        'qr_code' => 'required|string',
        'status' => 'in_list[available,reserved,sold,activated,blocked]',
    ];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Relationships
    public function getOrder()
    {
        return $this->belongsTo(Order::class, 'assigned_order_id', 'id');
    }

    public function getUser()
    {
        return $this->belongsTo(User::class, 'assigned_user_id', 'id');
    }

    public function getPet()
    {
        return $this->belongsTo(Pet::class, 'pet_id', 'id');
    }

    public function getStatusBadge()
    {
        $statuses = [
            'available' => 'badge-success',
            'reserved' => 'badge-info',
            'sold' => 'badge-warning',
            'activated' => 'badge-primary',
            'blocked' => 'badge-danger',
        ];
        return $statuses[$this->data['status']] ?? 'badge-secondary';
    }
}
