<?php

namespace App\Models;

use CodeIgniter\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['category_id', 'name', 'slug', 'description', 'price', 'sale_price', 'stock', 'image', 'status'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $validationRules = [
        'name' => 'required|string|max_length[255]',
        'slug' => 'required|string|max_length[255]|is_unique[products.slug]',
        'description' => 'required|string',
        'price' => 'required|numeric',
        'sale_price' => 'permit_empty|numeric',
        'stock' => 'numeric',
        'status' => 'in_list[active,inactive]',
    ];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Relationships
    public function getOrderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_id', 'id');
    }

    public function generateSlug($name)
    {
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));
    }
}
