<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;

use App\Mail\AppointmentStatusUpdated;
use Illuminate\Support\Facades\Mail;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        // Get the current logged in hospital user's hospital
        $hospital = $request->user()->hospital;
        
        if (!$hospital) {
            abort(404, 'Hospital not found');
        }

        $appointments = $hospital->appointments()->with(['user', 'specialty'])->latest()->get();

        return view('hospital.appointments.index', compact('appointments'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $hospital = $request->user()->hospital;
        
        if (!$hospital || $appointment->hospital_id !== $hospital->id) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed'
        ]);

        $appointment->update([
            'status' => $validated['status']
        ]);

        // Send email notification to user
        Mail::to($appointment->user->email)->send(new AppointmentStatusUpdated($appointment));

        // Send Push Notification via Firebase (FCM) if user has token
        if ($appointment->user->fcm_token && in_array($validated['status'], ['confirmed', 'cancelled'])) {
            $statusText = $validated['status'] === 'confirmed' ? 'Confirmada' : 'Cancelada';
            $message = "A sua marcação no hospital {$hospital->name} foi $statusText.";
            
            try {
                // Here we would normally use kreait/firebase-php or Google Client to get the Bearer token.
                // For demonstration, this is the HTTP v1 payload structure:
                \Illuminate\Support\Facades\Http::withToken(env('FCM_SERVER_KEY', 'dummy-token'))
                    ->post('https://fcm.googleapis.com/v1/projects/'.env('FIREBASE_PROJECT_ID', 'medicon').'/messages:send', [
                        'message' => [
                            'token' => $appointment->user->fcm_token,
                            'notification' => [
                                'title' => 'Atualização de Consulta',
                                'body' => $message,
                            ],
                            'data' => [
                                'appointment_id' => (string) $appointment->id,
                                'status' => $validated['status']
                            ]
                        ]
                    ]);
            } catch (\Exception $e) {
                // Log error but don't stop the request
                \Illuminate\Support\Facades\Log::error('FCM Error: ' . $e->getMessage());
            }
        }

        return back()->with('status', 'Appointment status updated successfully!');
    }
}
