<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\Specialty;
use Illuminate\Http\Request;

class SpecialtyController extends Controller
{
    public function index()
    {
        $hospital = auth()->user()->hospital;
        $hospitalSpecialties = $hospital->specialties->pluck('id')->toArray();
        $allSpecialties = Specialty::orderBy('name')->get();

        return view('hospital.specialties.index', compact('hospital', 'hospitalSpecialties', 'allSpecialties'));
    }

    public function sync(Request $request)
    {
        $request->validate([
            'specialties' => 'array',
            'specialties.*' => 'exists:specialties,id',
        ]);

        $hospital = auth()->user()->hospital;
        $hospital->specialties()->sync($request->input('specialties', []));

        return redirect()->route('hospital.specialties.index')->with('success', 'Especialidades actualizadas.');
    }
}
