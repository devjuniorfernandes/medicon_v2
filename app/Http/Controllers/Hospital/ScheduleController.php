<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HospitalSchedule;

class ScheduleController extends Controller
{
    public function index()
    {
        $hospital = auth()->user()->hospital;
        
        // Ensure 7 days exist
        for ($i = 0; $i < 7; $i++) {
            HospitalSchedule::firstOrCreate(
                ['hospital_id' => $hospital->id, 'day_of_week' => $i],
                ['start_time' => '08:00:00', 'end_time' => '18:00:00', 'is_closed' => false]
            );
        }

        $schedules = $hospital->schedules()->orderBy('day_of_week')->get();

        return view('hospital.schedules.index', compact('schedules'));
    }

    public function store(Request $request)
    {
        $hospital = auth()->user()->hospital;
        $schedules = $request->input('schedules', []);

        foreach ($schedules as $day => $data) {
            HospitalSchedule::updateOrCreate(
                ['hospital_id' => $hospital->id, 'day_of_week' => $day],
                [
                    'start_time' => $data['start_time'],
                    'end_time' => $data['end_time'],
                    'is_closed' => !isset($data['is_open']),
                ]
            );
        }

        return redirect()->back()->with('status', 'Horários atualizados com sucesso!');
    }
}
