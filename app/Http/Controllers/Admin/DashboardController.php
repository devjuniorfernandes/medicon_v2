<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hospital;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_hospitals' => Hospital::count(),
            'total_specialties' => Specialty::count(),
            'total_users' => User::where('role', 'patient')->count(),
            'total_appointments_today' => \App\Models\Appointment::whereDate('appointment_date', today())->count(),
            'total_appointments_completed' => \App\Models\Appointment::where('status', 'completed')->count(),
            'total_reviews' => \App\Models\Review::count(),
        ];

        // Let's get registered users per month for the chart
        $usersPerMonth = User::where('role', 'patient')
            ->selectRaw('COUNT(*) as count, DATE_FORMAT(created_at, "%b") as month')
            ->groupBy('month')
            ->orderByRaw('MIN(created_at)')
            ->pluck('count', 'month')
            ->toArray();

        return view('admin.dashboard', compact('stats', 'usersPerMonth'));
    }
}
