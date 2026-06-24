<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hospital;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class HospitalController extends Controller
{
    public function index()
    {
        $hospitals = Hospital::with('user')->paginate(15);
        return view('admin.hospitals.index', compact('hospitals'));
    }

    public function create()
    {
        return view('admin.hospitals.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => UserRole::HOSPITAL->value,
            ]);

            Hospital::create([
                'user_id' => $user->id,
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'email' => $request->email,
            ]);
        });

        return redirect()->route('admin.hospitals.index')->with('success', 'Hospital created successfully.');
    }

    public function show(Hospital $hospital)
    {
        return view('admin.hospitals.show', compact('hospital'));
    }

    public function edit(Hospital $hospital)
    {
        return view('admin.hospitals.edit', compact('hospital'));
    }

    public function update(Request $request, Hospital $hospital)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $hospital->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('admin.hospitals.index')->with('success', 'Hospital atualizado com sucesso.');
    }

    public function toggleActive(Hospital $hospital)
    {
        $hospital->update([
            'is_active' => !$hospital->is_active
        ]);

        $status = $hospital->is_active ? 'ativado' : 'suspenso';
        return back()->with('success', "Hospital $status com sucesso.");
    }

    public function destroy(Hospital $hospital)
    {
        $hospital->user()->delete(); // Soft delete user
        $hospital->delete(); // Soft delete hospital

        return redirect()->route('admin.hospitals.index')->with('success', 'Hospital deleted successfully.');
    }
}
