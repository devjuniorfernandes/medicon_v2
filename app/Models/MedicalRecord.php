<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    protected $fillable = [
        'user_id',
        'blood_type',
        'allergies',
        'chronic_conditions',
        'current_medication',
        'height',
        'weight',
        'emergency_contact',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
