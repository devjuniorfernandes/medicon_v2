<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hospital;
use App\Http\Resources\HospitalResource;
use Illuminate\Http\Request;

class HospitalController extends Controller
{
    public function index()
    {
        $hospitals = Hospital::with(['specialties', 'galleries'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->paginate(15);
        return HospitalResource::collection($hospitals);
    }

    public function show($slug)
    {
        $hospital = Hospital::with(['specialties', 'galleries', 'reviews.user'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->where('slug', $slug)
            ->firstOrFail();
            
        // Order reviews by latest if loaded
        if ($hospital->relationLoaded('reviews')) {
            $hospital->setRelation('reviews', $hospital->reviews->sortByDesc('created_at')->values());
        }

        return new HospitalResource($hospital);
    }
}
