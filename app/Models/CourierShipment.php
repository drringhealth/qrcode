<?php

namespace App\Models;

use CodeIgniter\Model;

class CourierShipment extends Model
{
    protected $table = 'courier_shipments';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['order_id', 'courier_name', 'tracking_number', 'shipping_status', 'shipped_at', 'delivered_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $validationRules = [
        'order_id' => 'required|numeric',
        'courier_name' => 'required|string|max_length[100]',
        'tracking_number' => 'required|string|max_length[100]|is_unique[courier_shipments.tracking_number]',
        'shipping_status' => 'in_list[pending,shipped,in_transit,delivered,cancelled]',
    ];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Relationships
    public function getOrder()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}
