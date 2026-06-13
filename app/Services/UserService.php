<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserAddress;

class UserService
{
    protected $userModel;
    protected $addressModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->addressModel = new UserAddress();
    }

    public function createUser($data)
    {
        // Hash password if provided
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }

        return $this->userModel->insert($data);
    }

    public function updateUser($userId, $data)
    {
        // Hash password if provided
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }

        return $this->userModel->update($userId, $data);
    }

    public function getUserByMobile($mobile)
    {
        return $this->userModel->where('mobile', $mobile)->first();
    }

    public function getUserByEmail($email)
    {
        return $this->userModel->where('email', $email)->first();
    }

    public function addAddress($userId, $addressData)
    {
        $addressData['user_id'] = $userId;

        // If marked as default, unset others
        if ($addressData['is_default'] ?? false) {
            $this->addressModel->where('user_id', $userId)->update(null, ['is_default' => false]);
        }

        return $this->addressModel->insert($addressData);
    }

    public function getUserAddresses($userId)
    {
        return $this->addressModel->where('user_id', $userId)->findAll();
    }

    public function getDefaultAddress($userId)
    {
        return $this->addressModel->where('user_id', $userId)->where('is_default', true)->first();
    }

    public function verifyPassword($userId, $password)
    {
        $user = $this->userModel->find($userId);
        if (!$user) return false;

        return password_verify($password, $user['password']);
    }
}
