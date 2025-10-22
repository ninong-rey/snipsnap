<?php

namespace App\Http\Controllers\Auth;
/** @var \Illuminate\Support\Facades\Log $Log */
/** @var \Illuminate\Support\Facades\Hash $Hash */


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log; // Imported facade
use Illuminate\Support\Facades\Hash; // Imported facade
use Illuminate\Support\Facades\Auth;
use App\Mail\OtpMail;
use App\Models\User;
use Twilio\Rest\Client;

class OtpController extends Controller
{
    /**
     * Handle sending OTP via email or phone
     */
    public function requestOtp(Request $request)
    {
        $request->validate([
            'login' => 'required'
        ]);

        $login = $request->input('login');

        $user = User::where('email', $login)
                    ->orWhere('phone_number', $login)
                    ->first();

        if (!$user) {
            return back()->with('error', "No account found with that email or phone number.");
        }

        // Store login identifier in session
        Session::put('reset_identifier', $login);

        // Generate OTP
        $otp = rand(100000, 999999);
        $user->otp_code = $otp;
        $user->otp_expires_at = now()->addMinutes(5);
        $user->save();

        // Send OTP via Email
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            Mail::to($user->email)->send(new OtpMail($otp));
            return redirect()->route('reset.password.view')
                ->with('success', 'OTP sent! Check your email.');
        }

        // Send OTP via Twilio SMS
        if (preg_match('/^09\d{9}$/', $login)) {
            $phone = '+63' . substr($login, 1);
            $twilioFrom = env('TWILIO_PHONE');

            if ($phone === $twilioFrom) {
                return back()->with('error', 'Cannot send SMS to your Twilio number.');
            }

            try {
                $twilio = new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));
                $verifySid = env('TWILIO_VERIFY_SID');

                $twilio->verify->v2->services($verifySid)
                    ->verifications
                    ->create($phone, "sms");

                return redirect()->route('reset.password.view')
                    ->with('success', 'OTP sent to your phone via SMS.');
            } catch (\Exception $e) {
                // FIX: Use imported Log facade
                Log::error("Twilio Verify failed: " . $e->getMessage());
                return back()->with('error', 'Failed to send SMS. Check logs.');
            }
        }

        return back()->with('error', 'Enter a valid email or phone number.');
    }

    /**
     * Handle OTP verification + password reset in a single form
     */
    public function resetPassword(Request $request)
{
    $request->validate([
        'otp' => 'required|numeric',
        'password' => 'required|string|min:8|confirmed',
    ]);

    $login = Session::get('reset_identifier');

    if (!$login) {
        return back()->with('error', 'Please request a new OTP first.');
    }

    // Find user
    $user = User::where('email', $login)
                ->orWhere('phone_number', $login)
                ->first();

    if (!$user) {
        return back()->with('error', 'No account found.');
    }

    // Verify OTP for email
    if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
        if ($user->otp_code != $request->otp || now()->gt($user->otp_expires_at)) {
            return back()->with('error', 'Invalid or expired OTP.');
        }

        // Clear OTP
        $user->otp_code = null;
        $user->otp_expires_at = null;
    }

    // Verify OTP for phone via Twilio
    if (preg_match('/^09\d{9}$/', $login)) {
        $phone = '+63' . substr($login, 1);
        try {
            $twilio = new \Twilio\Rest\Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));
            $verifySid = env('TWILIO_VERIFY_SID');

            $verificationCheck = $twilio->verify->v2->services($verifySid)
                ->verificationChecks
                ->create([
                    'to' => $phone,
                    'code' => $request->otp
                ]);

            if ($verificationCheck->status !== "approved") {
                return back()->with('error', 'Invalid or expired OTP.');
            }
        } catch (\Exception $e) {
            // FIX: Use imported Log facade
            Log::error("Twilio Verify failed: " . $e->getMessage());
            return back()->with('error', 'Failed to verify OTP.');
        }
    }

    // Save new password
    // FIX: Use imported Hash facade
    $user->password = Hash::make($request->password);
    $user->save();

    // Clear session
    Session::forget('otp_verified_id');
    Session::forget('reset_identifier');

    return redirect()->route('login.view')
        ->with('success', 'Password successfully reset!');
}

    /**
     * Show Reset Password Form
     */
    public function showResetPasswordForm()
    {
        if (!Session::has('reset_identifier')) {
            return redirect()->route('forgot.password.view')
                ->with('error', 'Please request an OTP first.');
        }

        return view('reset-password');
    }
}
