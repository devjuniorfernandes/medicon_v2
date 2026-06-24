<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MedicalRecordController extends Controller
{
    public function show(Request $request)
    {
        $record = $request->user()->medicalRecord;
        return response()->json($record ?: (object)[]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'blood_type' => 'nullable|string|max:5',
            'allergies' => 'nullable|string',
            'chronic_conditions' => 'nullable|string',
            'current_medication' => 'nullable|string',
            'height' => 'nullable|numeric|min:0',
            'weight' => 'nullable|numeric|min:0',
            'emergency_contact' => 'nullable|string|max:255',
        ]);

        $record = $request->user()->medicalRecord()->updateOrCreate(
            ['user_id' => $request->user()->id],
            $validated
        );

        return response()->json([
            'message' => 'Ficha médica atualizada com sucesso',
            'medical_record' => $record
        ]);
    }
}
