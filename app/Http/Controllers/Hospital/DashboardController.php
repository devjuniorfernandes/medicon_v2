<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $hospital = auth()->user()->hospital;
        
        $stats = [
            'specialties_count' => $hospital ? $hospital->specialties()->count() : 0,
            'gallery_count' => $hospital ? $hospital->galleries()->count() : 0,
            'total_appointments' => $hospital ? $hospital->appointments()->count() : 0,
            'total_reviews' => $hospital ? $hospital->reviews()->count() : 0,
            'avg_rating' => $hospital ? number_format((float)$hospital->reviews()->avg('rating'), 1) : 0,
        ];

        // Monthly Appointments for Chart
        $appointmentsChart = array_fill(1, 12, 0);
        if ($hospital) {
            $monthlyAppointments = $hospital->appointments()
                ->selectRaw('MONTH(created_at) as month, COUNT(*) as total')
                ->whereYear('created_at', date('Y'))
                ->groupBy('month')
                ->pluck('total', 'month')
                ->toArray();
            foreach ($monthlyAppointments as $month => $total) {
                $appointmentsChart[$month] = $total;
            }
        }

        // Monthly Reviews Rating for Chart
        $reviewsChart = array_fill(1, 12, 0);
        if ($hospital) {
            $monthlyReviews = $hospital->reviews()
                ->selectRaw('MONTH(created_at) as month, AVG(rating) as avg_rating')
                ->whereYear('created_at', date('Y'))
                ->groupBy('month')
                ->pluck('avg_rating', 'month')
                ->toArray();
            foreach ($monthlyReviews as $month => $avg) {
                $reviewsChart[$month] = round($avg, 1);
            }
        }

        return view('hospital.dashboard', compact('hospital', 'stats', 'appointmentsChart', 'reviewsChart'));
    }
}
