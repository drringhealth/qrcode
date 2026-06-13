<?php

namespace App\Models;

use CodeIgniter\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $allowedFields = ['order_no', 'user_id', 'customer_name', 'mobile', 'email', 'billing_address', 'shipping_address', 'amount', 'tax', 'shipping_charge', 'discount', 'total_amount', 'payment_status', 'order_status', 'notes'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    protected $validationRules = [
        'order_no' => 'required|string|max_length[50]|is_unique[orders.order_no]',
        'customer_name' => 'required|string|max_length[100]',
        'mobile' => 'required|numeric|max_length[15]',
        'email' => 'required|valid_email',
        'billing_address' => 'required|string',
        'shipping_address' => 'required|string',
        'total_amount' => 'required|numeric',
        'payment_status' => 'in_list[pending,completed,failed,refunded]',
        'order_status' => 'in_list[pending,confirmed,packed,shipped,delivered,cancelled]',
    ];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Relationships
    public function getUser()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function getOrderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }

    public function getPayment()
    {
        return $this->hasOne(Payment::class, 'order_id', 'id');
    }

    public function getShipment()
    {
        return $this->hasOne(CourierShipment::class, 'order_id', 'id');
    }

    public function getQrTags()
    {
        return $this->hasMany(QrTag::class, 'assigned_order_id', 'id');
    }

    public function generateOrderNo()
    {
        return 'ORD' . date('Ymd') . mt_rand(1000, 9999);
    }
}
