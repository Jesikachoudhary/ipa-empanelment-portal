<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\AdminResetPasswordNotification;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $guard = 'admin';

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_super',
        'super_admin_id',
        'registration_code',
        'registration_code_sent_at',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'registration_code_sent_at' => 'datetime',
        'is_super' => 'boolean',
    ];
    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new AdminResetPasswordNotification($token));
    }

    /**
     * Admin has one applicant (the application they submitted).
     */
    public function applicant()
    {
        return $this->hasOne(Applicant::class);
    }

    /**
     * A non-super admin belongs to a super admin
     */
    public function superAdmin()
    {
        return $this->belongsTo(Admin::class, 'super_admin_id');
    }

    /**
     * A super admin has many sub-admins
     */
    public function subAdmins()
    {
        return $this->hasMany(Admin::class, 'super_admin_id');
    }

    /**
     * Get all applicants submitted by this admin's team (if superadmin) or just this admin's applicant
     */
    public function teamApplicants()
    {
        if ($this->is_super) {
            // Get applicants from this superadmin and all their sub-admins
            $subAdminIds = $this->subAdmins()->pluck('id')->toArray();
            return Applicant::whereIn('admin_id', array_merge([$this->id], $subAdminIds));
        } else {
            // Regular admin only sees their own applicant
            return Applicant::where('admin_id', $this->id);
        }
    }

}
