<?php

namespace App\Services;

use App\Models\QrTag;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class QrCodeService
{
    protected $qrModel;

    public function __construct()
    {
        $this->qrModel = new QrTag();
    }

    public function generateQrCode($serialNumber, $baseUrl = null)
    {
        if (!$baseUrl) {
            $baseUrl = config('App')->baseURL;
        }

        $qrUrl = $baseUrl . 'pet/' . $serialNumber;

        try {
            $qrCode = QrCode::create($qrUrl);
            $writer = new PngWriter();
            $result = $writer->write($qrCode);

            return $result->getString();
        } catch (Exception $e) {
            throw new Exception('Failed to generate QR code: ' . $e->getMessage());
        }
    }

    public function createQrTag($serialNumber, $quantity = 1)
    {
        $tags = [];
        for ($i = 0; $i < $quantity; $i++) {
            $qrCode = $this->generateQrCode($serialNumber . '-' . ($i + 1));
            $tags[] = [
                'serial_number' => $serialNumber . '-' . ($i + 1),
                'qr_code' => base64_encode($qrCode),
                'status' => 'available',
            ];
        }

        return $this->qrModel->insertBatch($tags);
    }

    public function getQrTagsByStatus($status)
    {
        return $this->qrModel->where('status', $status)->findAll();
    }

    public function reserveQrTag($serialNumber, $orderId)
    {
        return $this->qrModel
            ->where('serial_number', $serialNumber)
            ->update(null, [
                'status' => 'reserved',
                'assigned_order_id' => $orderId,
            ]);
    }

    public function activateQrTag($serialNumber, $userId, $petId)
    {
        return $this->qrModel
            ->where('serial_number', $serialNumber)
            ->update(null, [
                'status' => 'activated',
                'assigned_user_id' => $userId,
                'pet_id' => $petId,
                'activated_at' => date('Y-m-d H:i:s'),
            ]);
    }
}
