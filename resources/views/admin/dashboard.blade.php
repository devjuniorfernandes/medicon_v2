@extends('layouts.admin')

@section('title', 'Painel Geral de Controlo')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">Visão Global da Plataforma</h1>
    <p class="text-gray-600 mt-1">Bem-vindo ao centro de comando da Medicon.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-6 gap-6 mb-8">
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col items-center justify-center">
        <h3 class="text-gray-500 text-xs font-semibold uppercase tracking-wider mb-2">Hospitais</h3>
        <p class="text-4xl font-extrabold text-indigo-600">{{ $stats['total_hospitals'] }}</p>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col items-center justify-center">
        <h3 class="text-gray-500 text-xs font-semibold uppercase tracking-wider mb-2">Especialidades</h3>
        <p class="text-4xl font-extrabold text-indigo-600">{{ $stats['total_specialties'] }}</p>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col items-center justify-center">
        <h3 class="text-gray-500 text-xs font-semibold uppercase tracking-wider mb-2">Pacientes</h3>
        <p class="text-4xl font-extrabold text-blue-600">{{ $stats['total_users'] }}</p>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col items-center justify-center">
        <h3 class="text-gray-500 text-xs font-semibold uppercase tracking-wider mb-2">Consultas Hoje</h3>
        <p class="text-4xl font-extrabold text-emerald-600">{{ $stats['total_appointments_today'] }}</p>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col items-center justify-center">
        <h3 class="text-gray-500 text-xs font-semibold uppercase tracking-wider mb-2">Cons. Concluídas</h3>
        <p class="text-4xl font-extrabold text-emerald-600">{{ $stats['total_appointments_completed'] }}</p>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col items-center justify-center">
        <h3 class="text-gray-500 text-xs font-semibold uppercase tracking-wider mb-2">Avaliações</h3>
        <p class="text-4xl font-extrabold text-amber-500">{{ $stats['total_reviews'] }}</p>
    </div>
</div>

<div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
    <h3 class="text-lg font-bold text-gray-800 mb-6">Crescimento de Pacientes (Registos)</h3>
    <div class="relative h-72 w-full">
        <canvas id="usersChart"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('usersChart').getContext('2d');
        const data = @json($usersPerMonth);
        
        const labels = Object.keys(data);
        const values = Object.values(data);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Novos Pacientes',
                    data: values,
                    borderColor: '#4f46e5', // indigo-600
                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
    });
</script>
@endsection
