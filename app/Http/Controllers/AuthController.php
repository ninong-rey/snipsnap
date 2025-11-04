<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\OTP;

class AuthController extends Controller
{
    /**
     * Show login page
     */
    public function loginView()
    {
        return view('login');
    }

    /**
     * Handle login submission
     */
    public function loginPerform(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginInput = $request->login;
        $field = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone_number';

        $user = User::where($field, $loginInput)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['login' => 'Invalid credentials provided.']);
        }

        Auth::login($user, $request->filled('remember'));
        return redirect()->route('my-web')->with('success', 'Logged in successfully!');
    }

    /**
     * Show signup page
     */
    public function signupView()
    {
        return view('signup');
    }

    /**
     * Handle signup/registration
     */
    public function signupPerform(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|email|max:255|unique:users,email',
            'phone_number' => 'nullable|string|max:20|unique:users,phone_number',
            'password' => 'required|string|confirmed|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => bcrypt($request->password),
        ]);

        Auth::login($user);
        return redirect()->route('my-web')->with('success', 'Registration successful!');
    }

    /**
     * Show forgot password (OTP request) page
     */
    public function forgotPasswordView()
    {
        return view('reset'); // matches reset.blade.php
    }

    /**
     * Handle OTP request (forgot password)
     */
    public function otpRequest(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
        ]);

        $loginInput = $request->login;

        // Identify if it's email or phone number
        if (filter_var($loginInput, FILTER_VALIDATE_EMAIL)) {
            $user = User::where('email', $loginInput)->first();
            $email = $loginInput;
        } else {
            $user = User::where('phone_number', $loginInput)->first();
            $email = $user ? $user->email : null;
        }

        if (!$user || !$email) {
            return back()->withErrors(['login' => 'No account found with this email or phone number.']);
        }

        // Generate OTP
        $otpCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $expiresAt = now()->addMinutes(10);

        // Remove old OTPs for this email
        OTP::where('email', $email)->delete();

        // Save new OTP
        OTP::create([
            'email' => $email,
            'otp_code' => $otpCode,
            'expires_at' => $expiresAt,
            'used' => false,
        ]);

        try {
            // Send OTP via email
            Mail::raw(
                "Your SnipSnap OTP Code is: {$otpCode}\n\nThis code expires in 10 minutes.\n\nIf you didn’t request this, ignore this email.",
                function ($message) use ($email) {
                    $message->to($email)->subject('Your SnipSnap OTP Code');
                }
            );

            // Store email in session
            $request->session()->put('otp_email', $email);

            return redirect()->route('password.reset')
                ->with('success', 'OTP sent successfully to your email!');
        } catch (\Exception $e) {
    return back()->withErrors(['login' => 'Mail error: ' . $e->getMessage()]);
}

    }

    /**
     * Show reset password page (after OTP sent)
     */
    public function resetPasswordView(Request $request)
    {
        if (!$request->session()->has('otp_email')) {
            return redirect()->route('forgot.password.view')
                ->withErrors(['error' => 'Please request an OTP first.']);
        }

        return view('reset-password'); // matches reset-password.blade.php
    }

    /**
     * Handle OTP verification + new password submission
     */
    public function otpVerify(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
            'password' => 'required|min:8|confirmed',
        ]);

        $email = $request->session()->get('otp_email');

        if (!$email) {
            return back()->withErrors(['otp' => 'Session expired. Please request a new OTP.']);
        }

        $otp = OTP::where('email', $email)
            ->where('otp_code', $request->otp)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->first();

        if (!$otp) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP code.']);
        }

        // Update password
        $user = User::where('email', $email)->first();
        if ($user) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);

            // Mark OTP as used and clear session
            $otp->update(['used' => true]);
            $request->session()->forget('otp_email');

            Auth::login($user);
            return redirect()->route('my-web')->with('success', 'Password reset successfully!');
        }

        return back()->withErrors(['otp' => 'User not found.']);
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
