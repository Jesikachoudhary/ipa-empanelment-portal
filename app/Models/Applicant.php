<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Crypt;

class Applicant extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'name',
        'address',
        'email',
        'contact_number',
        'resume_path',
        'additional_document_path',
        'categories',
        'years_of_experience',
    ];

    protected $casts = [
        'categories' => 'array',
    ];

    /**
     * Get the route key for the model using encrypted ID
     */
    public function getRouteKey()
    {
        try {
            return Crypt::encrypt($this->getKey());
        } catch (\Exception $e) {
            return $this->getKey();
        }
    }

    /**
     * Retrieve the model from the encrypted route key
     */
    public function resolveRouteBinding($value, $field = null)
    {
        try {
            $decrypted = Crypt::decrypt($value);
            return $this->where($this->getRouteKeyName(), $decrypted)->firstOrFail();
        } catch (\Exception $e) {
            return $this->where($field ?? $this->getRouteKeyName(), $value)->firstOrFail();
        }
    }

    public function educations()
    {
        return $this->hasMany(ApplicantEducation::class);
    }

    public function experiences()
    {
        return $this->hasMany(ApplicantExperience::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    /**
     * Calculate total years of experience from all experience entries
     */
    public function calculateYearsOfExperience()
    {
        $totalMonths = 0;
        
        foreach ($this->experiences as $exp) {
            if ($exp->from_year && $exp->to_year) {
                $fromMonth = (int)($exp->from_month ?? 12);
                $toMonth = (int)($exp->to_month ?? 12);
                
                $fromYear = (int)$exp->from_year;
                $toYear = (int)$exp->to_year;
                
                // Calculate months difference (inclusive of both start and end months)
                $monthsDiff = (($toYear - $fromYear) * 12) + ($toMonth - $fromMonth) + 1;
                
                if ($monthsDiff > 0) {
                    $totalMonths += $monthsDiff;
                }
            }
        }
        
        // Convert total months to years and months format
        $years = intdiv($totalMonths, 12);
        $months = $totalMonths % 12;
        
        // Return as decimal (e.g., 2.5 for 2 years 6 months)
        return round($years + ($months / 12), 2);
    }

    /**
     * Update years of experience calculation
     */
    public function updateYearsOfExperience()
    {
        $this->years_of_experience = $this->calculateYearsOfExperience();
        $this->save();
        
        return $this;
    }}