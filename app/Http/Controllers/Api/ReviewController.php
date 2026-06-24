<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hospital;
use App\Models\Review;
use App\Http\Resources\ReviewResource;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, Hospital $hospital)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        if (Auth::user()->isHospital()) {
            return response()->json([
                'message' => 'Contas de hospital não podem deixar avaliações.'
            ], 403);
        }

        $review = new Review();
        $review->user_id = Auth::id();
        $review->hospital_id = $hospital->id;
        $review->rating = $validated['rating'];
        $review->comment = $validated['comment'] ?? null;
        $review->save();

        // Load user to return in resource
        $review->load('user');

        return response()->json([
            'message' => 'Avaliação submetida com sucesso.',
            'review' => new ReviewResource($review)
        ], 201);
    }
}
