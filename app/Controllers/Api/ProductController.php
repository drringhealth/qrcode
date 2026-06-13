<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\Product;

class ProductController extends ResourceController
{
    protected $modelName = 'App\Models\Product';
    protected $format = 'json';

    public function index()
    {
        $products = $this->model->where('status', 'active')->findAll();
        return $this->respond(['products' => $products]);
    }

    public function show($id = null)
    {
        $product = $this->model->find($id);
        if (!$product) {
            return $this->failNotFound('Product not found');
        }

        return $this->respond(['product' => $product]);
    }

    public function byCategory($categoryId = null)
    {
        $products = $this->model
            ->where('category_id', $categoryId)
            ->where('status', 'active')
            ->findAll();

        return $this->respond(['products' => $products]);
    }
}
