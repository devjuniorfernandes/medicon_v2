<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;

class AppointmentController extends Controller
{
    /**
     * Store a newly created appointment in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'hospital_id' => 'required|exists:hospitals,id',
            'specialty_id' => 'required|exists:specialties,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'notes' => 'nullable|string',
        ]);

        $requestedDate = \Carbon\Carbon::parse($validated['appointment_date']);
        $dayOfWeek = $requestedDate->dayOfWeek;
        $time = $requestedDate->format('H:i:s');

        $schedule = \App\Models\HospitalSchedule::where('hospital_id', $validated['hospital_id'])
            ->where('day_of_week', $dayOfWeek)
            ->first();

        if ($schedule) {
            if ($schedule->is_closed) {
                return back()->withErrors(['appointment_date' => 'O hospital encontra-se fechado neste dia da semana.'])->withInput();
            }

            if ($time < $schedule->start_time || $time > $schedule->end_time) {
                return back()->withErrors(['appointment_date' => 'O horário de funcionamento neste dia é das ' . \Carbon\Carbon::parse($schedule->start_time)->format('H:i') . ' às ' . \Carbon\Carbon::parse($schedule->end_time)->format('H:i') . '.'])->withInput();
            }
        }

        $exists = Appointment::where('hospital_id', $validated['hospital_id'])
            ->where('appointment_date', $validated['appointment_date'])
            ->where('status', '!=', 'cancelled')
            ->exists();

        if ($exists) {
            return back()->withErrors(['appointment_date' => 'Este horário já se encontra preenchido neste hospital. Por favor, selecione outro.'])->withInput();
        }

        $userConflict = $request->user()->appointments()
            ->where('appointment_date', $validated['appointment_date'])
            ->where('status', '!=', 'cancelled')
            ->exists();

        if ($userConflict) {
            return back()->withErrors(['appointment_date' => 'Você já tem uma consulta agendada para esta mesma hora.'])->withInput();
        }

        $request->user()->appointments()->create([
            'hospital_id' => $validated['hospital_id'],
            'specialty_id' => $validated['specialty_id'],
            'appointment_date' => $validated['appointment_date'],
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Consulta agendada com sucesso! Poderá acompanhar o estado na sua Dashboard.');
    }
}
