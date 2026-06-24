@extends('layouts.hospital')

@section('title', 'Resumo do Hospital')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
        <h3 class="text-gray-500 text-sm font-medium uppercase tracking-wide">Especialidades</h3>
        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['specialties_count'] }}</p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
        <h3 class="text-gray-500 text-sm font-medium uppercase tracking-wide">Marcações Totais</h3>
        <p class="text-3xl font-bold text-blue-600 mt-2">{{ $stats['total_appointments'] }}</p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
        <h3 class="text-gray-500 text-sm font-medium uppercase tracking-wide">Avaliação Média</h3>
        <p class="text-3xl font-bold text-yellow-500 mt-2">{{ $stats['avg_rating'] }} / 5.0</p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
        <h3 class="text-gray-500 text-sm font-medium uppercase tracking-wide">Total Avaliações</h3>
        <p class="text-3xl font-bold text-indigo-600 mt-2">{{ $stats['total_reviews'] }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Appointments Chart -->
    <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Marcações por Mês ({{ date('Y') }})</h3>
        <canvas id="appointmentsChart" height="250"></canvas>
    </div>

    <!-- Reviews Chart -->
    <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Média de Avaliações por Mês ({{ date('Y') }})</h3>
        <canvas id="reviewsChart" height="250"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const months = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
    
    const appointmentsData = {!! json_encode(array_values($appointmentsChart)) !!};
    const reviewsData = {!! json_encode(array_values($reviewsChart)) !!};

    const ctxAppt = document.getElementById('appointmentsChart').getContext('2d');
    new Chart(ctxAppt, {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                label: 'Marcações',
                data: appointmentsData,
                backgroundColor: 'rgba(59, 130, 246, 0.5)',
                borderColor: 'rgb(59, 130, 246)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } }
            }
        }
    });

    const ctxRev = document.getElementById('reviewsChart').getContext('2d');
    new Chart(ctxRev, {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Avaliação Média',
                data: reviewsData,
                backgroundColor: 'rgba(234, 179, 8, 0.2)',
                borderColor: 'rgb(234, 179, 8)',
                borderWidth: 2,
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true, max: 5 }
            }
        }
    });
});
</script>
@endsection
