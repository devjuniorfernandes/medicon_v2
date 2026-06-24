<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hospital;
use App\Models\Specialty;
use App\Models\ContactMessage;

class PublicController extends Controller
{
    public function home()
    {
        $stats = [
            'hospitals' => Hospital::count(),
            'specialties' => Specialty::count(),
        ];
        
        $featuredHospitals = Hospital::inRandomOrder()->take(4)->get();
        $specialties = Specialty::orderBy('name')->get();

        return view('public.home', compact('stats', 'featuredHospitals', 'specialties'));
    }

    public function search(Request $request)
    {
        $query = Hospital::withAvg('reviews', 'rating')->orderByDesc('reviews_avg_rating');

        if ($request->filled('q')) {
            $query->where('name', 'like', '%' . $request->q . '%');
        }

        if ($request->filled('province')) {
            $query->where('province', $request->province);
        }

        if ($request->filled('specialty')) {
            $query->whereHas('specialties', function($q) use ($request) {
                $q->where('specialties.id', $request->specialty);
            });
        }

        $hospitals = $query->paginate(12);
        $specialties = Specialty::orderBy('name')->get();

        return view('public.search', compact('hospitals', 'specialties'));
    }

    public function hospital(Hospital $hospital)
    {
        $hospital->load(['specialties', 'galleries']);
        return view('public.hospital', compact('hospital'));
    }

    public function contact()
    {
        return view('public.contact');
    }

    public function submitContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
        ]);

        ContactMessage::create($request->all());

        return redirect()->route('public.contact')->with('success', 'Mensagem enviada com sucesso! Iremos responder em breve.');
    }
}
