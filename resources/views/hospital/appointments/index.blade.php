@extends('layouts.hospital')

@section('title', 'Gestão de Marcações')

@section('content')
<div class="py-12" x-data="{ view: 'lista' }">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        @if(session('status'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('status') }}</span>
            </div>
        @endif

        <div class="mb-6 flex space-x-4">
            <button @click="view = 'lista'" :class="{ 'bg-indigo-600 text-white': view === 'lista', 'bg-white text-gray-700 hover:bg-gray-50': view !== 'lista' }" class="px-4 py-2 rounded-lg shadow-sm border border-gray-200 font-medium text-sm transition-colors">
                <svg class="w-4 h-4 inline-block mr-1 -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                Vista de Tabela
            </button>
            <button @click="view = 'calendario'" :class="{ 'bg-indigo-600 text-white': view === 'calendario', 'bg-white text-gray-700 hover:bg-gray-50': view !== 'calendario' }" class="px-4 py-2 rounded-lg shadow-sm border border-gray-200 font-medium text-sm transition-colors">
                <svg class="w-4 h-4 inline-block mr-1 -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Vista de Calendário
            </button>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                
                @if($appointments->isEmpty())
                    <p class="text-gray-500 text-center py-8">Ainda não existem marcações para o seu hospital.</p>
                @else
                    <!-- VISTA DE TABELA -->
                    <div x-show="view === 'lista'" class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data / Hora</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paciente</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Especialidade</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($appointments as $appointment)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}</div>
                                            <div class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('H:i') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('hospital.patients.medical-record', $appointment->user) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-900 underline flex items-center">
                                                {{ $appointment->user->name }}
                                                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                            </a>
                                            <div class="text-sm text-gray-500 mt-1">{{ $appointment->user->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $appointment->specialty ? $appointment->specialty->name : 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($appointment->status === 'pending')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pendente</span>
                                            @elseif($appointment->status === 'confirmed')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Confirmada</span>
                                            @elseif($appointment->status === 'completed')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Concluída</span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Cancelada</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            @if($appointment->status === 'pending')
                                                <form action="{{ route('hospital.appointments.update', $appointment) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="confirmed">
                                                    <button type="submit" class="text-blue-600 hover:text-blue-900 mr-3">Confirmar</button>
                                                </form>
                                                <form action="{{ route('hospital.appointments.update', $appointment) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="cancelled">
                                                    <button type="submit" class="text-red-600 hover:text-red-900">Cancelar</button>
                                                </form>
                                            @elseif($appointment->status === 'confirmed')
                                                <form action="{{ route('hospital.appointments.update', $appointment) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="completed">
                                                    <button type="submit" class="text-green-600 hover:text-green-900 mr-3">Marcar Concluída</button>
                                                </form>
                                                <form action="{{ route('hospital.appointments.update', $appointment) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="cancelled">
                                                    <button type="submit" class="text-red-600 hover:text-red-900">Cancelar</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- VISTA DE CALENDÁRIO -->
                    <div x-show="view === 'calendario'" style="display: none;" class="w-full">
                        <div id="calendar" class="w-full h-[600px]"></div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        if (calendarEl) {
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                locale: 'pt',
                buttonText: {
                    today: 'Hoje',
                    month: 'Mês',
                    week: 'Semana',
                    day: 'Dia',
                    list: 'Lista'
                },
                slotMinTime: '06:00:00',
                slotMaxTime: '22:00:00',
                allDaySlot: false,
                events: [
                    @foreach($appointments as $appointment)
                    @php
                        $color = '#f59e0b'; // yellow (pending)
                        if($appointment->status === 'confirmed') $color = '#3b82f6'; // blue
                        if($appointment->status === 'completed') $color = '#10b981'; // green
                        if($appointment->status === 'cancelled') $color = '#ef4444'; // red
                    @endphp
                    {
                        title: '{{ $appointment->user->name }} ({{ $appointment->specialty ? $appointment->specialty->name : "Geral" }})',
                        start: '{{ \Carbon\Carbon::parse($appointment->appointment_date)->format("Y-m-d\TH:i:s") }}',
                        color: '{{ $color }}',
                        url: '{{ route('hospital.patients.medical-record', $appointment->user) }}',
                        extendedProps: {
                            status: '{{ $appointment->status }}'
                        }
                    },
                    @endforeach
                ],
                eventClick: function(info) {
                    // Prevent default URL opening if we want to do something custom, 
                    // but here we just let it open the URL (Ficha Médica).
                }
            });

            // Need to render calendar only when the tab is shown
            // Since Alpine changes display:none to block, FullCalendar might not size correctly
            // We use a MutationObserver to detect when the calendar div becomes visible
            var observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.attributeName === "style") {
                        if (calendarEl.parentElement.style.display !== 'none') {
                            calendar.render();
                        }
                    }
                });
            });
            observer.observe(calendarEl.parentElement, { attributes: true });
            
            // Initial render if it starts visible
            calendar.render();
        }
    });
</script>
@endsection
