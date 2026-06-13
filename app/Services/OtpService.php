<?php

namespace App\Services;

use App\Models\OtpVerification;
use Exception;

class OtpService
{
    protected $otpModel;
    protected $expiryMinutes = 5;
    protected $otpLength = 6;

    public function __construct()
    {
        $this->otpModel = new OtpVerification();
    }

    public function generateOtp($mobile)
    {
        // Generate random OTP
        $otp = str_pad(mt_rand(0, pow(10, $this->otpLength) - 1), $this->otpLength, '0', STR_PAD_LEFT);

        // Calculate expiry
        $expiresAt = date('Y-m-d H:i:s', strtotime("+{$this->expiryMinutes} minutes"));

        // Store OTP
        $data = [
            'mobile' => $mobile,
            'otp' => $otp,
            'attempts' => 0,
            'verified' => false,
            'expires_at' => $expiresAt,
        ];

        $this->otpModel->insert($data);

        return $otp;
    }

    public function verifyOtp($mobile, $otp)
    {
        $record = $this->otpModel
            ->where('mobile', $mobile)
            ->where('verified', false)
            ->orderBy('id', 'DESC')
            ->first();

        if (!$record) {
            return ['success' => false, 'message' => 'No OTP found for this mobile'];
        }

        if ($this->otpModel->isExpired()) {
            return ['success' => false, 'message' => 'OTP has expired'];
        }

        if ($record['attempts'] >= 3) {
            return ['success' => false, 'message' => 'Maximum attempts exceeded'];
        }

        if ($record['otp'] !== $otp) {
            $this->otpModel->update($record['id'], ['attempts' => $record['attempts'] + 1]);
            return ['success' => false, 'message' => 'Invalid OTP'];
        }

        // Mark as verified
        $this->otpModel->update($record['id'], [
            'verified' => true,
            'verified_at' => date('Y-m-d H:i:s'),
        ]);

        return ['success' => true, 'message' => 'OTP verified successfully'];
    }

    public function resendOtp($mobile)
    {
        // Delete existing unverified OTPs
        $this->otpModel->where('mobile', $mobile)->where('verified', false)->delete();

        // Generate new OTP
        return $this->generateOtp($mobile);
    }
}
