<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\Applicant;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    /**
     * Show category selection page before applicant form.
     */
    public function selectCategory()
    {
        // If admin already has an application, send them to edit it directly
        $existing = Applicant::where('admin_id', auth('admin')->id())->first();
        if ($existing) {
            return redirect()->route('admin.applicants.edit', $existing)
                ->with('info', 'You already submitted an application. You can view or edit it here.');
        }

        return view('admin.applicants.select_category');
    }

    /**
     * Show list of admins (super admin only).
     */
    public function index()
    {
        $user = Auth::guard('admin')->user();
        if (! $user || ! $user->is_super) {
            abort(403);
        }

        $admins = Admin::orderBy('id', 'asc')->get();
        return view('admin.admins.index', compact('admins'));
    }

    /**
     * Show single admin details (super admin only).
     */
    public function show(Admin $admin)
    {
        $user = Auth::guard('admin')->user();
        if (! $user || ! $user->is_super) {
            abort(403);
        }

        return view('admin.admins.show', compact('admin'));
    }

    public function showChangePasswordForm()
    {
        return view('admin.change_password');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|confirmed|min:8',
        ]);

        $admin = Auth::guard('admin')->user();

        if (! Hash::check($request->input('current_password'), $admin->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        $admin->password = Hash::make($request->input('password'));
        $admin->save();

        return back()->with('status', 'Password changed successfully.');
    }
}
