<?php

namespace App\Models;

use CodeIgniter\Model;

class Payment extends Model
{
    protected $table = 'payments';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['order_id', 'gateway', 'transaction_id', 'amount', 'status', 'response'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $casts = [
        'response' => 'json',
    ];
    protected $validationRules = [
        'order_id' => 'required|numeric',
        'gateway' => 'required|string|max_length[50]',
        'transaction_id' => 'required|string|max_length[100]',
        'amount' => 'required|numeric',
        'status' => 'in_list[pending,completed,failed,refunded]',
    ];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Relationships
    public function getOrder()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}
