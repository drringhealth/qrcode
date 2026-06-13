<?php

namespace App\Models;

use CodeIgniter\Model;

class OtpVerification extends Model
{
    protected $table = 'otp_verifications';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['mobile', 'otp', 'attempts', 'verified', 'expires_at', 'verified_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $validationRules = [
        'mobile' => 'required|numeric|max_length[15]',
        'otp' => 'required|numeric|max_length[10]',
    ];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    public function isExpired()
    {
        return strtotime($this->data['expires_at']) < time();
    }

    public function isVerified()
    {
        return $this->data['verified'] == true;
    }

    public function getRemainingAttempts()
    {
        return 3 - (int)$this->data['attempts'];
    }
}
