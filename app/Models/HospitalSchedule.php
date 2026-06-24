<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HospitalSchedule extends Model
{
    protected $fillable = [
        'hospital_id',
        'day_of_week',
        'start_time',
        'end_time',
        'is_closed',
    ];

    protected $casts = [
        'is_closed' => 'boolean',
    ];

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }
}
