<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;

class PatientController extends Controller
{
    public function showMedicalRecord(Request $request, User $user)
    {
        $hospital = $request->user()->hospital;
        
        if (!$hospital) {
            abort(404, 'Hospital not found');
        }

        // Security check: ensure the patient has at least one appointment with this hospital
        $hasAppointment = $hospital->appointments()->where('user_id', $user->id)->exists();
        
        if (!$hasAppointment) {
            abort(403, 'Acesso negado. O paciente não tem consultas marcadas neste hospital.');
        }

        $record = $user->medicalRecord;

        return view('hospital.patients.medical-record', compact('user', 'record'));
    }
}
