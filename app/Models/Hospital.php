<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hospital extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
        'phone',
        'email',
        'website',
        'address',
        'province',
        'municipality',
        'latitude',
        'longitude',
        'opening_hours',
        'logo',
        'cover_image',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schedules()
    {
        return $this->hasMany(HospitalSchedule::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function averageRating()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    public function specialties()
    {
        return $this->belongsToMany(Specialty::class);
    }

    public function galleries()
    {
        return $this->hasMany(Gallery::class)->orderBy('sort_order');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
