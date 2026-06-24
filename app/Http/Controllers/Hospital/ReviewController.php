<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $hospital = auth()->user()->hospital;
        $reviews = $hospital->reviews()->with('user')->latest()->paginate(10);
        return view('hospital.reviews.index', compact('reviews'));
    }

    public function update(Request $request, Review $review)
    {
        $hospital = auth()->user()->hospital;
        
        if ($review->hospital_id !== $hospital->id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'hospital_response' => 'required|string|max:1000',
        ]);

        $review->update([
            'hospital_response' => $request->hospital_response,
            'responded_at' => now(),
        ]);

        return back()->with('success', 'Resposta submetida com sucesso.');
    }
}
