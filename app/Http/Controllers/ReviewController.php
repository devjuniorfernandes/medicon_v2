<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hospital;

class ReviewController extends Controller
{
    public function store(Request $request, Hospital $hospital)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $request->user()->reviews()->create([
            'hospital_id' => $hospital->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        return back()->with('success', 'Obrigado pela sua avaliação!');
    }
}
