<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $appointments = $request->user()->appointments()->with(['hospital', 'specialty'])->get();
        return response()->json($appointments);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'hospital_id' => 'required|exists:hospitals,id',
            'specialty_id' => 'nullable|exists:specialties,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'notes' => 'nullable|string',
        ]);

        if ($request->user()->isHospital()) {
            return response()->json([
                'message' => 'Contas de hospital não podem agendar consultas.'
            ], 403);
        }

        $requestedDate = \Carbon\Carbon::parse($validated['appointment_date']);
        $dayOfWeek = $requestedDate->dayOfWeek;
        $time = $requestedDate->format('H:i:s');

        $schedule = \App\Models\HospitalSchedule::where('hospital_id', $validated['hospital_id'])
            ->where('day_of_week', $dayOfWeek)
            ->first();

        if ($schedule) {
            if ($schedule->is_closed) {
                return response()->json([
                    'message' => 'O hospital encontra-se fechado neste dia da semana.'
                ], 422);
            }

            if ($time < $schedule->start_time || $time > $schedule->end_time) {
                return response()->json([
                    'message' => 'O horário de funcionamento neste dia é das ' . \Carbon\Carbon::parse($schedule->start_time)->format('H:i') . ' às ' . \Carbon\Carbon::parse($schedule->end_time)->format('H:i') . '.'
                ], 422);
            }
        }

        $exists = Appointment::where('hospital_id', $validated['hospital_id'])
            ->where('appointment_date', $validated['appointment_date'])
            ->where('status', '!=', 'cancelled')
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Este horário já se encontra preenchido neste hospital. Por favor, selecione outro.'
            ], 422);
        }

        $userConflict = $request->user()->appointments()
            ->where('appointment_date', $validated['appointment_date'])
            ->where('status', '!=', 'cancelled')
            ->exists();

        if ($userConflict) {
            return response()->json([
                'message' => 'Você já tem uma consulta agendada para esta mesma hora.'
            ], 422);
        }

        $appointment = $request->user()->appointments()->create([
            'hospital_id' => $validated['hospital_id'],
            'specialty_id' => $validated['specialty_id'] ?? null,
            'appointment_date' => $validated['appointment_date'],
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Appointment created successfully',
            'appointment' => $appointment->load(['hospital', 'specialty'])
        ], 201);
    }

    public function cancel(Request $request, $id)
    {
        $appointment = $request->user()->appointments()->findOrFail($id);

        if ($appointment->status === 'completed') {
            return response()->json([
                'message' => 'Consultas concluídas não podem ser canceladas.'
            ], 422);
        }

        if ($appointment->status === 'cancelled') {
            return response()->json([
                'message' => 'Esta consulta já se encontra cancelada.'
            ], 422);
        }

        $appointment->status = 'cancelled';
        $appointment->save();

        return response()->json([
            'message' => 'Consulta cancelada com sucesso.',
            'appointment' => $appointment
        ]);
    }
}
