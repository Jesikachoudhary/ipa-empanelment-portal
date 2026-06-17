<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ApplicantEducation extends Model
{
    use HasFactory;

    /**
     * Explicit table name because "education" is uncountable
     * and Eloquent pluralization produced a different table name.
     */
    protected $table = 'applicant_educations';

    protected $fillable = [
        'applicant_id',
        'qualification',
        'institution',
        'year_of_passing',
    ];

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }
}
