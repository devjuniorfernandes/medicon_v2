<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Specialty extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    public function hospitals()
    {
        return $this->belongsToMany(Hospital::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
