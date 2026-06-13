<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\Order;
use App\Services\OrderService;

class OrderController extends ResourceController
{
    protected $modelName = 'App\Models\Order';
    protected $format = 'json';
    protected $orderService;

    public function __construct()
    {
        $this->orderService = new OrderService();
    }

    public function index()
    {
        $userId = $this->getUserIdFromToken();
        if (!$userId) {
            return $this->failUnauthorized('Unauthorized');
        }

        $orders = $this->model->where('user_id', $userId)->findAll();
        return $this->respond(['orders' => $orders]);
    }

    public function show($id = null)
    {
        $userId = $this->getUserIdFromToken();
        if (!$userId) {
            return $this->failUnauthorized('Unauthorized');
        }

        $order = $this->model->find($id);
        if (!$order || $order['user_id'] != $userId) {
            return $this->failNotFound('Order not found');
        }

        $order = $this->orderService->getOrderDetails($id);
        return $this->respond(['order' => $order]);
    }

    public function create()
    {
        $userId = $this->getUserIdFromToken();
        if (!$userId) {
            return $this->failUnauthorized('Unauthorized');
        }

        $rules = [
            'customer_name' => 'required|string|max_length[100]',
            'mobile' => 'required|numeric|max_length[15]',
            'email' => 'required|valid_email',
            'billing_address' => 'required|string',
            'shipping_address' => 'required|string',
            'total_amount' => 'required|numeric',
            'items' => 'required|array',
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $data = $this->request->getJSON(true);
        $data['user_id'] = $userId;

        $orderId = $this->orderService->createOrder($data);

        // Add order items
        foreach ($data['items'] as $item) {
            $this->orderService->addOrderItem(
                $orderId,
                $item['product_id'],
                $item['qty'],
                $item['price']
            );
        }

        return $this->respondCreated([
            'message' => 'Order created successfully',
            'order_id' => $orderId,
        ]);
    }

    private function getUserIdFromToken()
    {
        $token = $this->request->getHeader('Authorization');
        if (!$token) {
            return null;
        }

        $token = str_replace('Bearer ', '', $token->getValue());

        try {
            $key = config('App')->encryptionKey;
            $decoded = \Firebase\JWT\JWT::decode($token, new \Firebase\JWT\Key($key, 'HS256'));
            return $decoded->userId;
        } catch (\Exception $e) {
            return null;
        }
    }
}
