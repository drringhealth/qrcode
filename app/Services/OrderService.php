<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\QrTag;

class OrderService
{
    protected $orderModel;
    protected $orderItemModel;
    protected $qrModel;

    public function __construct()
    {
        $this->orderModel = new Order();
        $this->orderItemModel = new OrderItem();
        $this->qrModel = new QrTag();
    }

    public function createOrder($data)
    {
        $data['order_no'] = $this->orderModel->generateOrderNo();
        $data['payment_status'] = 'pending';
        $data['order_status'] = 'pending';

        return $this->orderModel->insert($data);
    }

    public function addOrderItem($orderId, $productId, $qty, $price)
    {
        $total = $qty * $price;

        return $this->orderItemModel->insert([
            'order_id' => $orderId,
            'product_id' => $productId,
            'qty' => $qty,
            'price' => $price,
            'total' => $total,
        ]);
    }

    public function assignQrTagToOrder($orderId, $serialNumber)
    {
        $qrTag = $this->qrModel->where('serial_number', $serialNumber)->first();

        if (!$qrTag || $qrTag['status'] !== 'available') {
            return false;
        }

        return $this->qrModel->update($qrTag['id'], [
            'status' => 'reserved',
            'assigned_order_id' => $orderId,
        ]);
    }

    public function updateOrderStatus($orderId, $status)
    {
        return $this->orderModel->update($orderId, [
            'order_status' => $status,
        ]);
    }

    public function updatePaymentStatus($orderId, $status)
    {
        return $this->orderModel->update($orderId, [
            'payment_status' => $status,
        ]);
    }

    public function getOrderDetails($orderId)
    {
        $order = $this->orderModel->find($orderId);
        if (!$order) return null;

        $order['items'] = $this->orderItemModel->where('order_id', $orderId)->findAll();
        $order['qr_tags'] = $this->qrModel->where('assigned_order_id', $orderId)->findAll();

        return $order;
    }
}
