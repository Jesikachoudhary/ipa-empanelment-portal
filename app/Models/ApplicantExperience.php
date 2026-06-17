<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ApplicantExperience extends Model
{
    use HasFactory;

    /**
     * Explicit table name to avoid pluralization issues.
     */
    protected $table = 'applicant_experiences';

    protected $fillable = [
        'applicant_id',
        'organization',
        'role',
        'from_year',
        'from_month',
        'to_year',
        'to_month',
        'details',
    ];

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }

    /**
     * Boot the model - attach listeners for after save/delete
     */
    protected static function boot()
    {
        parent::boot();

        // After saving an experience record, update parent applicant's years_of_experience
        static::saved(function ($experience) {
            $experience->applicant->updateYearsOfExperience();
        });

        // After deleting an experience record, update parent applicant's years_of_experience
        static::deleted(function ($experience) {
            $experience->applicant->updateYearsOfExperience();
        });
    }
}
