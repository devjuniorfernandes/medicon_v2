<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'hospital_id',
        'image',
        'caption',
        'sort_order',
    ];

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }
}
