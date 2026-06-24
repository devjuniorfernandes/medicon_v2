<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hospital;
use Carbon\Carbon;

class SlotController extends Controller
{
    public function index(Request $request, Hospital $hospital)
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today',
        ]);

        $date = Carbon::parse($request->date);
        $dayOfWeek = $date->dayOfWeek; // 0 = Sunday, 1 = Monday, etc.

        // Get hospital schedule for the specific day
        $schedule = $hospital->schedules()->where('day_of_week', $dayOfWeek)->first();

        if (!$schedule || $schedule->is_closed) {
            return response()->json([]); // Closed on this day
        }

        // Get booked appointments for this date (ignoring cancelled or completed if you want, but for now just avoid 'cancelled')
        $appointments = $hospital->appointments()
            ->whereDate('appointment_date', $date->toDateString())
            ->where('status', '!=', 'cancelled')
            ->get();

        $bookedTimes = $appointments->map(function ($app) {
            return Carbon::parse($app->appointment_date)->format('H:i');
        })->toArray();

        // Generate 30 min slots
        $slots = [];
        $startTime = Carbon::parse($schedule->start_time);
        $endTime = Carbon::parse($schedule->end_time);
        
        $currentSlot = $startTime->copy();

        while ($currentSlot->copy()->addMinutes(30)->lessThanOrEqualTo($endTime)) {
            $timeString = $currentSlot->format('H:i');
            
            // If the date is today, ignore past times
            if ($date->isToday() && $currentSlot->format('H:i') < now()->format('H:i')) {
                $currentSlot->addMinutes(30);
                continue;
            }

            if (!in_array($timeString, $bookedTimes)) {
                $slots[] = $timeString;
            }
            $currentSlot->addMinutes(30);
        }

        return response()->json($slots);
    }
}
