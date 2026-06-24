@extends('layouts.hospital')

@section('title', 'Ficha Médica do Paciente')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-4">
            <div class="w-16 h-16 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 text-2xl font-bold">
                {{ substr($user->name, 0, 1) }}
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h2>
                <p class="text-gray-500">{{ $user->email }}</p>
            </div>
        </div>
        <a href="{{ route('hospital.appointments.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
            &larr; Voltar às Marcações
        </a>
    </div>

    @if(!$record)
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 rounded-r-lg shadow-sm flex items-start">
            <svg class="w-6 h-6 text-yellow-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            <div>
                <h3 class="text-lg font-medium text-yellow-800">Ficha Médica Incompleta</h3>
                <p class="mt-2 text-sm text-yellow-700">O paciente <strong>{{ $user->name }}</strong> ainda não preencheu a sua ficha médica através da aplicação móvel ou portal.</p>
            </div>
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Informação Clínica
                </h3>
            </div>
            
            <div class="p-6">
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-8">
                    
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Tipo Sanguíneo</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900">
                            @if($record->blood_type)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    {{ $record->blood_type }}
                                </span>
                            @else
                                <span class="text-gray-400 font-normal">Não informado</span>
                            @endif
                        </dd>
                    </div>

                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Peso & Altura</dt>
                        <dd class="mt-1 text-md text-gray-900">
                            {{ $record->weight ? $record->weight . ' kg' : '---' }}
                            <span class="mx-2 text-gray-300">|</span>
                            {{ $record->height ? $record->height . ' cm' : '---' }}
                        </dd>
                    </div>

                    <div class="sm:col-span-2 border-t border-gray-100 pt-6">
                        <dt class="text-sm font-medium text-gray-500 flex items-center">
                            <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            Alergias
                        </dt>
                        <dd class="mt-2 text-md text-gray-900">
                            @if($record->allergies)
                                <p class="whitespace-pre-wrap bg-red-50 p-4 rounded-lg border border-red-100">{{ $record->allergies }}</p>
                            @else
                                <span class="text-gray-400 italic">Sem registos de alergias.</span>
                            @endif
                        </dd>
                    </div>

                    <div class="sm:col-span-2 border-t border-gray-100 pt-6">
                        <dt class="text-sm font-medium text-gray-500">Doenças Crónicas</dt>
                        <dd class="mt-2 text-md text-gray-900">
                            @if($record->chronic_conditions)
                                <p class="whitespace-pre-wrap bg-blue-50 p-4 rounded-lg border border-blue-100">{{ $record->chronic_conditions }}</p>
                            @else
                                <span class="text-gray-400 italic">Sem registos de doenças crónicas.</span>
                            @endif
                        </dd>
                    </div>

                    <div class="sm:col-span-2 border-t border-gray-100 pt-6">
                        <dt class="text-sm font-medium text-gray-500 flex items-center">
                            <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                            Medicação Atual
                        </dt>
                        <dd class="mt-2 text-md text-gray-900">
                            @if($record->current_medication)
                                <p class="whitespace-pre-wrap bg-gray-50 p-4 rounded-lg border border-gray-200">{{ $record->current_medication }}</p>
                            @else
                                <span class="text-gray-400 italic">Sem registos de medicação em curso.</span>
                            @endif
                        </dd>
                    </div>

                    <div class="sm:col-span-2 border-t border-gray-100 pt-6">
                        <dt class="text-sm font-medium text-gray-500">Contactos de Emergência</dt>
                        <dd class="mt-2 text-md text-gray-900">
                            @if($record->emergency_contact)
                                <p class="whitespace-pre-wrap font-semibold">{{ $record->emergency_contact }}</p>
                            @else
                                <span class="text-gray-400 italic">Nenhum contacto fornecido.</span>
                            @endif
                        </dd>
                    </div>

                </dl>
            </div>
            
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 text-xs text-gray-400 text-right">
                Última atualização: {{ $record->updated_at->format('d/m/Y H:i') }}
            </div>
        </div>
    @endif
</div>
@endsection
