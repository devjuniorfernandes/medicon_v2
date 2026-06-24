@extends('layouts.public')

@section('title', $hospital->name)

@section('content')
<!-- Hospital Header -->
<div class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
        <div class="md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <h1 class="text-3xl font-bold text-gray-900 sm:text-4xl sm:truncate">{{ $hospital->name }}</h1>
                <div class="mt-2 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-6">
                    @if($hospital->province)
                        <div class="mt-2 flex items-center text-sm text-gray-500">
                            <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            {{ $hospital->province }}, {{ $hospital->municipality }}
                        </div>
                    @endif
                    @if($hospital->phone)
                        <div class="mt-2 flex items-center text-sm text-gray-500">
                            <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            {{ $hospital->phone }}
                        </div>
                    @endif
                </div>
            </div>
            @if($hospital->website)
                <div class="mt-4 flex md:mt-0 md:ml-4">
                    <a href="{{ $hospital->website }}" target="_blank" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Visitar Website
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-8">
            @if($hospital->description)
                <section>
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Sobre Nós</h2>
                    <div class="prose prose-blue text-gray-600">
                        {!! nl2br(e($hospital->description)) !!}
                    </div>
                </section>
            @endif

            <section>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Especialidades Médicas</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @forelse($hospital->specialties as $specialty)
                        <div class="flex items-start">
                            <svg class="flex-shrink-0 h-6 w-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span class="ml-3 text-gray-700">{{ $specialty->name }}</span>
                        </div>
                    @empty
                        <p class="text-gray-500">Ainda sem especialidades registadas.</p>
                    @endforelse
                </div>
            </section>

            @if($hospital->galleries->count() > 0)
                <section>
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Galeria</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($hospital->galleries as $gallery)
                            <div class="relative rounded-lg overflow-hidden h-40 bg-gray-200">
                                <img src="{{ Storage::url($gallery->image) }}" alt="{{ $gallery->caption }}" class="w-full h-full object-cover hover:scale-110 transition-transform duration-300">
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>

        <section class="mt-8 border-t border-gray-200 pt-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Avaliações dos Pacientes</h2>
                <div class="flex items-center">
                    <span class="text-yellow-400 flex items-center">
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <span class="ml-1 text-xl font-bold text-gray-900">{{ number_format($hospital->averageRating(), 1) }}</span>
                    </span>
                    <span class="ml-2 text-sm text-gray-500">({{ $hospital->reviews->count() }} avaliações)</span>
                </div>
            </div>

            @if(auth()->check() && !auth()->user()->isHospital() && !auth()->user()->isSuperAdmin())
                <div class="bg-gray-50 p-4 rounded-lg mb-8 border border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Deixar uma Avaliação</h3>
                    <form action="{{ route('reviews.store', $hospital) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Classificação (1 a 5 estrelas)</label>
                            <select name="rating" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md" required>
                                <option value="5">5 - Excelente</option>
                                <option value="4">4 - Muito Bom</option>
                                <option value="3">3 - Bom</option>
                                <option value="2">2 - Razoável</option>
                                <option value="1">1 - Mau</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Comentário (Opcional)</label>
                            <textarea name="comment" rows="2" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Como foi a sua experiência?"></textarea>
                        </div>
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">Submeter Avaliação</button>
                    </form>
                </div>
            @endif

            <div class="space-y-6">
                @forelse($hospital->reviews()->latest()->get() as $review)
                    <div class="border-b border-gray-200 pb-6">
                        <div class="flex items-center mb-2">
                            <div class="shrink-0 mr-3">
                                @if($review->user->avatar)
                                    <img class="h-8 w-8 object-cover rounded-full" src="{{ Storage::url($review->user->avatar) }}" alt="{{ $review->user->name }}">
                                @else
                                    <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-800 text-sm font-bold">
                                        {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <span class="font-medium text-gray-900 mr-2">{{ $review->user->name }}</span>
                            <div class="flex text-yellow-400">
                                @for($i = 0; $i < $review->rating; $i++)
                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                @endfor
                            </div>
                            <span class="text-sm text-gray-500 ml-auto">{{ $review->created_at->diffForHumans() }}</span>
                        </div>
                        @if($review->comment)
                            <p class="text-gray-600 text-sm">{{ $review->comment }}</p>
                        @endif
                        @if($review->hospital_response)
                            <div class="mt-3 bg-blue-50 p-3 rounded-lg border-l-4 border-blue-500">
                                <p class="text-xs font-bold text-blue-800 mb-1">Resposta do Hospital ({{ $review->responded_at->format('d/m/Y') }}):</p>
                                <p class="text-sm text-gray-700">{{ $review->hospital_response }}</p>
                            </div>
                        @endif
                    </div>
                @empty
                    <p class="text-gray-500 text-sm">Este hospital ainda não tem avaliações. Seja o primeiro a avaliar!</p>
                @endforelse
            </div>
        </section>
    </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Informação de Contacto</h3>
                
                <ul class="space-y-4">
                    <li class="flex items-start">
                        <svg class="flex-shrink-0 h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span class="ml-3 text-gray-600">{{ $hospital->address ?? 'Morada não disponível' }}</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="flex-shrink-0 h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        <span class="ml-3 text-gray-600">{{ $hospital->email }}</span>
                    </li>
                    @if($hospital->phone)
                        <li class="flex items-start">
                            <svg class="flex-shrink-0 h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            <span class="ml-3 text-gray-600">{{ $hospital->phone }}</span>
                        </li>
                    @endif
                </ul>
            </div>

        </div>

            @if($hospital->opening_hours)
                <div class="bg-blue-50 rounded-xl shadow-sm border border-blue-100 p-6">
                    <h3 class="text-lg font-bold text-blue-900 mb-2">Horário de Funcionamento</h3>
                    <p class="text-blue-800">{{ $hospital->opening_hours }}</p>
                </div>
            @endif
            
            <!-- Appointment Booking Form -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Agendar Consulta</h3>
                
                @if(session('success'))
                    <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif
                
                @if($errors->any())
                    <div class="mb-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(auth()->check() && !auth()->user()->isHospital() && !auth()->user()->isSuperAdmin())
                    <form action="{{ route('appointments.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="hospital_id" value="{{ $hospital->id }}">
                        
                        <div>
                            <label for="specialty_id" class="block text-sm font-medium text-gray-700">Especialidade</label>
                            <select id="specialty_id" name="specialty_id" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                <option value="">Selecione uma especialidade...</option>
                                @foreach($hospital->specialties as $specialty)
                                    <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="appointment_day" class="block text-sm font-medium text-gray-700">Data da Consulta</label>
                            <input type="date" id="appointment_day" required min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>

                        <div id="slots_container" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Horas Disponíveis</label>
                            <div id="slots_grid" class="grid grid-cols-4 gap-2">
                                <!-- Slots injected via JS -->
                            </div>
                            <p id="no_slots_msg" class="text-sm text-red-500 hidden mt-1">Não existem vagas para este dia.</p>
                        </div>
                        
                        <input type="hidden" name="appointment_date" id="appointment_date" required>

                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700">Notas (Opcional)</label>
                            <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Alguma observação importante para o médico?"></textarea>
                        </div>

                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Confirmar Agendamento
                        </button>
                    </form>
                @elseif(!auth()->check())
                    <div class="text-center py-6 bg-gray-50 rounded-lg border border-gray-200">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Autenticação Necessária</h3>
                        <p class="mt-1 text-sm text-gray-500">Inicie sessão ou crie uma conta para agendar uma consulta.</p>
                        <div class="mt-6">
                            <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                Iniciar Sessão
                            </a>
                        </div>
                    </div>
                @else
                    <div class="text-center py-6 bg-gray-50 rounded-lg border border-gray-200">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Conta Institucional</h3>
                        <p class="mt-1 text-sm text-gray-500">A sua conta atual não permite efetuar marcações de consultas como paciente.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dateInput = document.getElementById('appointment_day');
        const slotsContainer = document.getElementById('slots_container');
        const slotsGrid = document.getElementById('slots_grid');
        const noSlotsMsg = document.getElementById('no_slots_msg');
        const finalDateInput = document.getElementById('appointment_date');
        const hospitalId = {{ $hospital->id }};

        if (dateInput) {
            dateInput.addEventListener('change', async function() {
                const date = this.value;
                if (!date) {
                    slotsContainer.classList.add('hidden');
                    return;
                }

                slotsGrid.innerHTML = '<div class="col-span-4 text-sm text-gray-500 text-center py-2">A carregar...</div>';
                slotsContainer.classList.remove('hidden');
                noSlotsMsg.classList.add('hidden');
                finalDateInput.value = '';

                try {
                    const response = await fetch(`/api/hospitals/${hospitalId}/available-slots?date=${date}`);
                    const slots = await response.json();

                    slotsGrid.innerHTML = '';

                    if (slots.length === 0) {
                        noSlotsMsg.classList.remove('hidden');
                    } else {
                        slots.forEach(time => {
                            const btn = document.createElement('button');
                            btn.type = 'button';
                            btn.className = 'slot-btn py-2 px-3 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-blue-50 hover:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors';
                            btn.textContent = time;
                            
                            btn.addEventListener('click', () => {
                                // Reset all buttons
                                document.querySelectorAll('.slot-btn').forEach(b => {
                                    b.classList.remove('bg-blue-600', 'text-white', 'border-blue-600');
                                    b.classList.add('bg-white', 'text-gray-700', 'border-gray-300');
                                });
                                // Select this one
                                btn.classList.remove('bg-white', 'text-gray-700', 'border-gray-300');
                                btn.classList.add('bg-blue-600', 'text-white', 'border-blue-600');
                                
                                // Set final hidden input
                                finalDateInput.value = `${date} ${time}:00`;
                            });

                            slotsGrid.appendChild(btn);
                        });
                    }
                } catch (error) {
                    slotsGrid.innerHTML = '<div class="col-span-4 text-sm text-red-500 text-center py-2">Erro ao carregar vagas.</div>';
                }
            });
        }
    });
</script>
@endsection
