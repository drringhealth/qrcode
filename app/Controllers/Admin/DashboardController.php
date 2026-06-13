<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use App\Models\AdminUser;
use App\Models\User;
use App\Models\Pet;
use App\Models\Order;
use App\Models\QrTag;
use App\Models\AuditLog;
use App\Services\AuditService;

class DashboardController extends Controller
{
    protected $adminUserModel;
    protected $userModel;
    protected $petModel;
    protected $orderModel;
    protected $qrTagModel;
    protected $auditLogModel;
    protected $auditService;

    public function __construct()
    {
        $this->adminUserModel = new AdminUser();
        $this->userModel = new User();
        $this->petModel = new Pet();
        $this->orderModel = new Order();
        $this->qrTagModel = new QrTag();
        $this->auditLogModel = new AuditLog();
        $this->auditService = new AuditService();
    }

    public function index()
    {
        if (!$this->isAdminAuthenticated()) {
            return redirect()->to('/admin/login');
        }

        $data = [
            'title' => 'Dashboard',
            'totalUsers' => $this->userModel->countAll(),
            'totalPets' => $this->petModel->countAll(),
            'totalOrders' => $this->orderModel->countAll(),
            'totalQrTags' => $this->qrTagModel->countAll(),
            'activeQrTags' => $this->qrTagModel->where('status', 'activated')->countAllResults(),
            'recentOrders' => $this->orderModel->orderBy('created_at', 'DESC')->limit(10)->findAll(),
            'recentAuditLogs' => $this->auditLogModel->orderBy('created_at', 'DESC')->limit(20)->findAll(),
        ];

        return view('admin/dashboard', $data);
    }

    public function users()
    {
        if (!$this->isAdminAuthenticated()) {
            return redirect()->to('/admin/login');
        }

        $page = $this->request->getVar('page') ?? 1;
        $perPage = 20;

        $data = [
            'title' => 'Users',
            'users' => $this->userModel->paginate($perPage, 'users'),
            'pager' => $this->userModel->pager,
        ];

        return view('admin/users/index', $data);
    }

    public function pets()
    {
        if (!$this->isAdminAuthenticated()) {
            return redirect()->to('/admin/login');
        }

        $page = $this->request->getVar('page') ?? 1;
        $perPage = 20;

        $data = [
            'title' => 'Pets',
            'pets' => $this->petModel->paginate($perPage, 'pets'),
            'pager' => $this->petModel->pager,
        ];

        return view('admin/pets/index', $data);
    }

    public function orders()
    {
        if (!$this->isAdminAuthenticated()) {
            return redirect()->to('/admin/login');
        }

        $page = $this->request->getVar('page') ?? 1;
        $perPage = 20;
        $status = $this->request->getVar('status') ?? null;

        $query = $this->orderModel;
        if ($status) {
            $query = $query->where('order_status', $status);
        }

        $data = [
            'title' => 'Orders',
            'orders' => $query->paginate($perPage, 'orders'),
            'pager' => $query->pager,
            'statuses' => ['pending', 'confirmed', 'packed', 'shipped', 'delivered', 'cancelled'],
        ];

        return view('admin/orders/index', $data);
    }

    public function qrTags()
    {
        if (!$this->isAdminAuthenticated()) {
            return redirect()->to('/admin/login');
        }

        $page = $this->request->getVar('page') ?? 1;
        $perPage = 20;
        $status = $this->request->getVar('status') ?? null;

        $query = $this->qrTagModel;
        if ($status) {
            $query = $query->where('status', $status);
        }

        $data = [
            'title' => 'QR Tags',
            'qrTags' => $query->paginate($perPage, 'qrtags'),
            'pager' => $query->pager,
            'statuses' => ['available', 'reserved', 'sold', 'activated', 'blocked'],
        ];

        return view('admin/qrtags/index', $data);
    }

    public function auditLogs()
    {
        if (!$this->isAdminAuthenticated()) {
            return redirect()->to('/admin/login');
        }

        $page = $this->request->getVar('page') ?? 1;
        $perPage = 50;
        $module = $this->request->getVar('module') ?? null;

        $query = $this->auditLogModel;
        if ($module) {
            $query = $query->where('module', $module);
        }

        $data = [
            'title' => 'Audit Logs',
            'logs' => $query->orderBy('created_at', 'DESC')->paginate($perPage, 'logs'),
            'pager' => $query->pager,
        ];

        return view('admin/audit/logs', $data);
    }

    private function isAdminAuthenticated()
    {
        $session = session();
        return $session->get('adminId') !== null;
    }
}
