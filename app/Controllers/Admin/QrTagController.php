<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use App\Models\QrTag;
use App\Services\QrCodeService;
use App\Services\AuditService;

class QrTagController extends Controller
{
    protected $qrTagModel;
    protected $qrCodeService;
    protected $auditService;

    public function __construct()
    {
        $this->qrTagModel = new QrTag();
        $this->qrCodeService = new QrCodeService();
        $this->auditService = new AuditService();
    }

    public function generate()
    {
        if (!$this->isAdminAuthenticated()) {
            return redirect()->to('/admin/login');
        }

        if ($this->request->getMethod() === 'post') {
            $rules = [
                'quantity' => 'required|numeric|greater_than[0]|less_than_equal_to[1000]',
            ];

            if (!$this->validate($rules)) {
                return view('admin/qrtags/generate', ['errors' => $this->validator->getErrors()]);
            }

            $quantity = $this->request->getVar('quantity');
            $serialPrefix = 'QR-' . date('Ymd') . '-';

            $inserted = $this->qrCodeService->createQrTag($serialPrefix, $quantity);

            $adminId = session()->get('adminId');
            $this->auditService->log(
                $adminId,
                'qr_tags',
                'generate',
                null,
                null,
                ['quantity' => $quantity],
                'Generated ' . $quantity . ' QR tags'
            );

            return redirect()->to('/admin/qrtags')->with('success', 'QR tags generated successfully');
        }

        return view('admin/qrtags/generate');
    }

    public function export()
    {
        if (!$this->isAdminAuthenticated()) {
            return redirect()->to('/admin/login');
        }

        $status = $this->request->getVar('status') ?? 'available';
        $qrTags = $this->qrTagModel->where('status', $status)->findAll();

        $csv = "Serial Number,Status,Created At\n";
        foreach ($qrTags as $tag) {
            $csv .= "{$tag['serial_number']},{$tag['status']},{$tag['created_at']}\n";
        }

        $filename = 'qr-tags-' . $status . '-' . date('Y-m-d-His') . '.csv';

        return $this->response
            ->setHeader('Content-Type', 'text/csv')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($csv);
    }

    private function isAdminAuthenticated()
    {
        $session = session();
        return $session->get('adminId') !== null;
    }
}
