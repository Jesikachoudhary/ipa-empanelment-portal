<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Notifications\AdminRegistrationNotification;
use Illuminate\Support\Str;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Log attempt (do NOT log password)
        Log::info('Admin login attempt', ['email' => $data['email'], 'remember' => $request->filled('remember')]);

        // Prevent login for unverified accounts
        $admin = Admin::where('email', $data['email'])->first();
        if ($admin && is_null($admin->email_verified_at)) {
            return back()->withErrors(['email' => 'Please verify your email before logging in. Enter the code sent to your email via the Verify page.'])->with('show_verify', true);
        }

        $attempt = Auth::guard('admin')->attempt($data, $request->filled('remember'));
        Log::info('Admin login result', ['email' => $data['email'], 'success' => $attempt]);

        if ($attempt) {
            $request->session()->regenerate();

            // determine default redirect: super-admin goes to index; otherwise check if has application
            $loggedAdmin = Auth::guard('admin')->user();
            $defaultRedirect = route('admin.applicants.create');
            if ($loggedAdmin && $loggedAdmin->is_super) {
                $defaultRedirect = route('admin.applicants.index');
            } elseif ($loggedAdmin && $loggedAdmin->applicant) {
                $defaultRedirect = route('admin.applicants.edit', $loggedAdmin->applicant);
            }

            return redirect()->intended($defaultRedirect);
        }

        return back()->withErrors(['email' => 'Invalid credentials'])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('admin.register');
    }

    public function showVerify(\Illuminate\Http\Request $request)
    {
        // Pass optional query params `email` and `code` to the view so the form can be prefilled
        return view('admin.verify', [
            'email' => $request->query('email'),
            'code' => $request->query('code'),
        ]);
    }

    public function verify(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'code' => 'required|string',
        ]);

        $admin = Admin::where('email', $data['email'])->first();
        if (! $admin) {
            return back()->withErrors(['email' => 'No account found with that email.']);
        }

        if ($admin->registration_code !== $data['code']) {
            return back()->withErrors(['code' => 'The verification code is invalid.']);
        }

        // optional: expire codes older than 48 hours
        if ($admin->registration_code_sent_at && $admin->registration_code_sent_at->diffInHours(now()) > 48) {
            return back()->withErrors(['code' => 'The verification code has expired. Please request a new one by registering again or contacting an administrator.']);
        }

        $admin->email_verified_at = now();
        $admin->registration_code = null;
        $admin->registration_code_sent_at = null;
        $admin->save();

        return redirect()->route('admin.login')->with('status', 'Your email has been verified. You can now login.');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|confirmed|min:6',
        ]);

        $admin = Admin::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // generate 6-digit random code, store and send via email with login link
        $code = random_int(100000, 999999);
        $admin->registration_code = (string) $code;
        $admin->registration_code_sent_at = now();
        $admin->save();

        // send notification via mailer
        $admin->notify(new AdminRegistrationNotification($code));

        return redirect()->route('admin.login')->with('status', 'Registration successful. Check your email for a confirmation code and login link.');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
