<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\User;
use App\Services\OtpService;
use App\Services\UserService;

class AuthController extends ResourceController
{
    protected $modelName = 'App\Models\User';
    protected $format = 'json';
    protected $otpService;
    protected $userService;

    public function __construct()
    {
        $this->otpService = new OtpService();
        $this->userService = new UserService();
    }

    public function sendOtp()
    {
        $rules = [
            'mobile' => 'required|numeric|max_length[15]',
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $mobile = $this->request->getVar('mobile');
        $otp = $this->otpService->generateOtp($mobile);

        // TODO: Send OTP via SMS
        
        return $this->respondCreated([
            'message' => 'OTP sent successfully',
            'mobile' => $mobile,
        ]);
    }

    public function verifyOtp()
    {
        $rules = [
            'mobile' => 'required|numeric|max_length[15]',
            'otp' => 'required|numeric|max_length[10]',
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $mobile = $this->request->getVar('mobile');
        $otp = $this->request->getVar('otp');

        $result = $this->otpService->verifyOtp($mobile, $otp);

        if ($result['success']) {
            // Check if user exists
            $user = $this->userService->getUserByMobile($mobile);
            if (!$user) {
                // Create new user
                $user = [
                    'mobile' => $mobile,
                    'name' => '',
                    'status' => 'active',
                    'mobile_verified_at' => date('Y-m-d H:i:s'),
                ];
                $userId = $this->userService->createUser($user);
                $user['id'] = $userId;
            }

            $token = $this->generateToken($user);

            return $this->respond([
                'message' => 'OTP verified successfully',
                'token' => $token,
                'user' => $user,
            ]);
        }

        return $this->failUnauthorized($result['message']);
    }

    public function register()
    {
        $rules = [
            'mobile' => 'required|numeric|max_length[15]|is_unique[users.mobile]',
            'email' => 'permit_empty|valid_email|is_unique[users.email]',
            'name' => 'required|string|max_length[100]',
            'password' => 'required|min_length[8]',
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $data = [
            'mobile' => $this->request->getVar('mobile'),
            'email' => $this->request->getVar('email'),
            'name' => $this->request->getVar('name'),
            'password' => $this->request->getVar('password'),
            'mobile_verified_at' => date('Y-m-d H:i:s'),
        ];

        $userId = $this->userService->createUser($data);
        $user = $this->model->find($userId);

        $token = $this->generateToken($user);

        return $this->respondCreated([
            'message' => 'User registered successfully',
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function login()
    {
        $rules = [
            'mobile' => 'required|numeric',
            'password' => 'required|min_length[8]',
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $mobile = $this->request->getVar('mobile');
        $password = $this->request->getVar('password');

        $user = $this->userService->getUserByMobile($mobile);
        if (!$user || !password_verify($password, $user['password'])) {
            return $this->failUnauthorized('Invalid mobile or password');
        }

        $token = $this->generateToken($user);

        return $this->respond([
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user,
        ]);
    }

    private function generateToken($user)
    {
        $key = config('App')->encryptionKey;
        $payload = [
            'iat' => time(),
            'exp' => time() + (30 * 24 * 60 * 60), // 30 days
            'userId' => $user['id'],
            'mobile' => $user['mobile'],
        ];

        return \Firebase\JWT\JWT::encode($payload, $key, 'HS256');
    }
}
