<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderItem extends Model
{
    protected $table = 'order_items';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['order_id', 'product_id', 'assigned_qr_tag_id', 'qty', 'price', 'total'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $validationRules = [
        'order_id' => 'required|numeric',
        'product_id' => 'required|numeric',
        'qty' => 'required|numeric|greater_than[0]',
        'price' => 'required|numeric',
    ];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Relationships
    public function getOrder()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function getProduct()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function getQrTag()
    {
        return $this->belongsTo(QrTag::class, 'assigned_qr_tag_id', 'id');
    }
}
