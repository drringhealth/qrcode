<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use App\Models\AdminUser;
use App\Services\AuditService;

class AuthController extends Controller
{
    protected $adminUserModel;
    protected $auditService;

    public function __construct()
    {
        $this->adminUserModel = new AdminUser();
        $this->auditService = new AuditService();
    }

    public function login()
    {
        if ($this->request->getMethod() === 'post') {
            $rules = [
                'email' => 'required|valid_email',
                'password' => 'required|min_length[8]',
            ];

            if (!$this->validate($rules)) {
                return view('admin/auth/login', ['errors' => $this->validator->getErrors()]);
            }

            $email = $this->request->getVar('email');
            $password = $this->request->getVar('password');

            $admin = $this->adminUserModel->where('email', $email)->first();
            if (!$admin || !password_verify($password, $admin['password'])) {
                return view('admin/auth/login', ['error' => 'Invalid email or password']);
            }

            if ($admin['status'] !== 'active') {
                return view('admin/auth/login', ['error' => 'Your account is not active']);
            }

            // Update last login
            $this->adminUserModel->update($admin['id'], ['last_login' => date('Y-m-d H:i:s')]);

            // Log audit
            $request = service('request');
            $this->auditService->log(
                $admin['id'],
                'admin',
                'login',
                $admin['id'],
                null,
                null,
                'Admin login'
            );

            // Set session
            $session = session();
            $session->set([
                'adminId' => $admin['id'],
                'adminEmail' => $admin['email'],
                'adminName' => $admin['name'],
                'adminRole' => $admin['role'],
            ]);

            return redirect()->to('/admin/dashboard');
        }

        return view('admin/auth/login');
    }

    public function logout()
    {
        $session = session();
        $adminId = $session->get('adminId');

        // Log audit
        $this->auditService->log(
            $adminId,
            'admin',
            'logout',
            $adminId,
            null,
            null,
            'Admin logout'
        );

        $session->destroy();
        return redirect()->to('/admin/login');
    }
}
