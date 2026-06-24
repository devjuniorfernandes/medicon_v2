<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hospital;
use App\Http\Resources\HospitalResource;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = Hospital::with(['specialties', 'galleries'])
            ->withAvg('reviews', 'rating')
            ->orderByDesc('reviews_avg_rating');

        if ($request->filled('q')) {
            $searchTerm = $request->q;
            $query->where('name', 'like', "%{$searchTerm}%");
        }

        if ($request->filled('province')) {
            $query->where('province', $request->province);
        }

        if ($request->filled('specialty')) {
            $query->whereHas('specialties', function ($q) use ($request) {
                $q->where('specialties.id', $request->specialty);
            });
        }

        $hospitals = $query->paginate(15);

        return HospitalResource::collection($hospitals);
    }
}
