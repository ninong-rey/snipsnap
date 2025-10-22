<?php
// app/Services/OTPService.php

namespace App\Services;

use App\Models\OTP;
use Illuminate\Support\Facades\Mail;
use App\Mail\OTPMail;
use Illuminate\Support\Facades\Log;

class OTPService
{
    /**
     * Send OTP to email
     */
    public function sendOTP($email)
    {
        try {
            // Generate OTP
            $otpCode = OTP::generateCode();
            $token = OTP::generateToken();
            $expiresAt = now()->addMinutes(10); // OTP valid for 10 minutes

            // Create OTP record
            $otp = OTP::create([
                'email' => $email,
                'otp_code' => $otpCode,
                'token' => $token,
                'expires_at' => $expiresAt,
            ]);

            // Send email
            Mail::to($email)->send(new OTPMail($otpCode));

            return [
                'success' => true,
                'token' => $token,
                'message' => 'OTP sent successfully to your email!'
            ];

        } catch (\Exception $e) {
            Log::error('OTP Send Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to send OTP. Please try again.'
            ];
        }
    }

    /**
     * Verify OTP
     */
    public function verifyOTP($email, $otpCode, $token)
    {
        try {
            $otp = OTP::where('email', $email)
                      ->where('otp_code', $otpCode)
                      ->where('token', $token)
                      ->where('used', false)
                      ->first();

            if (!$otp) {
                return [
                    'success' => false,
                    'message' => 'Invalid OTP code'
                ];
            }

            if ($otp->isExpired()) {
                return [
                    'success' => false,
                    'message' => 'OTP has expired. Please request a new one.'
                ];
            }

            // Mark OTP as used
            $otp->markAsUsed();

            return [
                'success' => true,
                'message' => 'OTP verified successfully'
            ];

        } catch (\Exception $e) {
            Log::error('OTP Verify Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to verify OTP'
            ];
        }
    }
}