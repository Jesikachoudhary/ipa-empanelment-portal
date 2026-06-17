<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminForgotPasswordController;
use App\Http\Controllers\AdminResetPasswordController;

Route::get('/', function () {
    return view('welcome');
});

// Admin routes
Route::prefix('admin')->group(function () {
    Route::get('login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
    Route::post('login', [AdminAuthController::class, 'login'])->name('admin.login.post');

    Route::get('register', [AdminAuthController::class, 'showRegister'])->name('admin.register');
    Route::post('register', [AdminAuthController::class, 'register'])->name('admin.register.post');
    Route::get('verify', [AdminAuthController::class, 'showVerify'])->name('admin.verify');
    Route::post('verify', [AdminAuthController::class, 'verify'])->name('admin.verify.post');

    Route::get('password/forgot', [AdminForgotPasswordController::class, 'showLinkRequestForm'])->name('admin.password.request');
    Route::post('password/email', [AdminForgotPasswordController::class, 'sendResetLinkEmail'])->name('admin.password.email');

    Route::get('password/reset/{token}', [AdminResetPasswordController::class, 'showResetForm'])->name('admin.password.reset');
    Route::post('password/reset', [AdminResetPasswordController::class, 'reset'])->name('admin.password.update');

    // Home landing page (public)
    Route::get('/', function () {
        return view('admin.home');
    })->name('admin.home');

    Route::middleware('auth:admin')->group(function () {
        // Applicant routes (create, store, edit, update, show)
        Route::get('applicants/create', [\App\Http\Controllers\AdminApplicantController::class, 'create'])->name('admin.applicants.create');
        Route::post('applicants', [\App\Http\Controllers\AdminApplicantController::class, 'store'])->name('admin.applicants.store');
        // Super-admin only routes for examination (middleware enforced)
        Route::middleware('admin.super')->group(function () {
            Route::get('applicants', [\App\Http\Controllers\AdminApplicantController::class, 'index'])->name('admin.applicants.index');
            Route::get('applicants/export/csv', [\App\Http\Controllers\AdminApplicantController::class, 'export'])->name('admin.applicants.export');
            Route::get('applicants/{applicant}/edit', [\App\Http\Controllers\AdminApplicantController::class, 'edit'])->name('admin.applicants.edit');
            Route::put('applicants/{applicant}', [\App\Http\Controllers\AdminApplicantController::class, 'update'])->name('admin.applicants.update');
            Route::delete('applicants/{applicant}', [\App\Http\Controllers\AdminApplicantController::class, 'destroy'])->name('admin.applicants.destroy');
            Route::get('applicants/{applicant}', [\App\Http\Controllers\AdminApplicantController::class, 'show'])->name('admin.applicants.show');
        });

        Route::get('dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::post('logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

        // Admin management (super admin only - controller enforces check)
        Route::get('admins', [AdminController::class, 'index'])->name('admin.admins.index');
        Route::get('admins/{admin}', [AdminController::class, 'show'])->name('admin.admins.show');

        Route::get('password/change', [AdminController::class, 'showChangePasswordForm'])->name('admin.password.change');
        Route::post('password/change', [AdminController::class, 'changePassword'])->name('admin.password.change.post');
    });
});
