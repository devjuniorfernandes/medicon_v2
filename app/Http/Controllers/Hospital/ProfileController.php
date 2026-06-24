<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit()
    {
        $hospital = auth()->user()->hospital;
        return view('hospital.profile.edit', compact('hospital'));
    }

    public function update(Request $request)
    {
        $hospital = auth()->user()->hospital;
        
        $request->validate([
            'description' => 'nullable|string',
            'phone' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string',
            'province' => 'nullable|string|max:255',
            'municipality' => 'nullable|string|max:255',
            'opening_hours' => 'nullable|string|max:255',
        ]);

        $hospital->update($request->only([
            'description', 'phone', 'website', 'address', 'province', 'municipality', 'opening_hours'
        ]));

        return redirect()->route('hospital.profile.edit')->with('success', 'Perfil actualizado com sucesso.');
    }
}
