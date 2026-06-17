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
        'date_of_birth',
        'resume_path',
        'additional_document_path',
        'categories',
        'years_of_experience',
        'is_young_professional',
        'applicant_type',
        'application_fy',
    ];

    protected $casts = [
        'categories' => 'array',
        'date_of_birth' => 'date',
        'is_young_professional' => 'boolean',
        'applicant_type' => 'string',
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
     * Calculate age as of a given date (default: today)
     * @param \Carbon\Carbon|null $asOfDate
     * @return int|null
     */
    public function getAgeAsOf($asOfDate = null)
    {
        if (!$this->date_of_birth) {
            return null;
        }

        $asOfDate = $asOfDate ?? \Carbon\Carbon::now();
        return $this->date_of_birth->diffInYears($asOfDate);
    }

    /**
     * Check if applicant is within age limit on a specific date
     * @param int $maxAge
     * @param \Carbon\Carbon|null $asOfDate
     * @return bool
     */
    public function isWithinAgeLimit($maxAge = 35, $asOfDate = null)
    {
        $age = $this->getAgeAsOf($asOfDate);
        return $age !== null && $age <= $maxAge;
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
    }

    /**
     * Scope: Filter only young professional applicants (new form)
     */
    public function scopeYoungProfessional($query)
    {
        return $query->where('is_young_professional', true);
    }

    /**
     * Scope: Filter only legacy applicants
     */
    public function scopeLegacy($query)
    {
        return $query->where('is_young_professional', false);
    }

    /**
     * Get all education documents for this applicant
     */
    public function getEducationDocuments()
    {
        return ApplicantEducationDocument::whereIn('applicant_education_id', 
            $this->educations()->pluck('id')
        )->get();
    }

    /**
     * Get all experience documents for this applicant
     */
    public function getExperienceDocuments()
    {
        return ApplicantExperienceDocument::whereIn('applicant_experience_id', 
            $this->experiences()->pluck('id')
        )->get();
    }

    /**
     * Get count of all documents (resume + additional + education + experience documents)
     */
    public function getTotalDocumentCount()
    {
        $count = 0;
        
        if ($this->resume_path) $count++;
        if ($this->additional_document_path) $count++;
        
        $count += $this->getEducationDocuments()->count();
        $count += $this->getExperienceDocuments()->count();
        
        return $count;
    }

    /**
     * Check if applicant is a consultant
     */
    public function isConsultant()
    {
        return $this->applicant_type === 'consultant';
    }

    /**
     * Check if applicant is a young professional
     */
    public function isYoungProfessional()
    {
        return $this->applicant_type === 'young_professional';
    }

    /**
     * Check if applicant is from a startup
     */
    public function isStartup()
    {
        return $this->applicant_type === 'startups';
    }

    /**
     * Scope: Filter by applicant type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('applicant_type', $type);
    }

    /**
     * Scope: Filter consultants
     */
    public function scopeConsultants($query)
    {
        return $query->where('applicant_type', 'consultant');
    }

    /**
     * Scope: Filter young professionals
     */
    public function scopeYoungProfessionals($query)
    {
        return $query->where('applicant_type', 'young_professional');
    }

    /**
     * Scope: Filter startups
     */
    public function scopeStartups($query)
    {
        return $query->where('applicant_type', 'startups');
    }

    /**
     * Get applicant type label
     */
    public function getApplicantTypeLabel()
    {
        $types = config('applicant_types.types', []);
        return $types[$this->applicant_type]['label'] ?? $this->applicant_type;
    }

    /**
     * Compute current financial year (1 April - 31 March next year)
     * Format: 2025-2026
     */
    public static function getCurrentFinancialYear()
    {
        $now = \Carbon\Carbon::now();
        if ($now->month >= 4) {
            return $now->year . '-' . ($now->year + 1);
        }
        return ($now->year - 1) . '-' . $now->year;
    }

    /**
     * Get FY label for this applicant
     */
    public function getFinancialYearLabel()
    {
        return $this->application_fy ?? 'N/A';
    }
}