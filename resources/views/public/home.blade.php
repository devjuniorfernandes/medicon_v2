@extends('layouts.public')

@section('title', 'A Maior Rede de Saúde de Angola')

@section('content')
<!-- Hero Section -->
<div class="relative bg-blue-900 overflow-hidden">
    <div class="absolute inset-0">
        <img src="https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80" alt="Hospital background" class="w-full h-full object-cover opacity-20">
    </div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 text-center lg:text-left lg:py-32 flex flex-col lg:flex-row items-center">
        <div class="lg:w-1/2">
            <h1 class="text-4xl tracking-tight font-extrabold text-white sm:text-5xl md:text-6xl">
                <span class="block">A Saúde à distância</span>
                <span class="block text-blue-400">de um clique</span>
            </h1>
            <p class="mt-3 text-base text-blue-100 sm:mt-5 sm:text-lg sm:max-w-xl md:mt-5 md:text-xl lg:mx-0">
                A MEDICON é a plataforma nacional de Saúde Digital. Encontre clínicas, hospitais, especialidades e agende a sua consulta rapidamente em toda Angola.
            </p>
        </div>
        
        <!-- Search Card -->
        <div class="mt-10 lg:mt-0 lg:w-1/2 lg:ml-8 w-full max-w-md">
            <div class="bg-white rounded-xl shadow-2xl p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Encontre o que precisa</h3>
                <form action="{{ route('public.search') }}" method="GET">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nome do Hospital/Clínica</label>
                            <input type="text" name="q" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm p-2 border" placeholder="Ex: Clínica Sagrada Esperança">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Especialidade</label>
                            <select name="specialty" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm p-2 border">
                                <option value="">Todas as especialidades</option>
                                @foreach($specialties as $specialty)
                                    <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="w-full flex justify-center py-3 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                            Pesquisar Agora
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Stats Section -->
<div class="bg-blue-600">
    <div class="max-w-7xl mx-auto py-12 px-4 sm:py-16 sm:px-6 lg:px-8 lg:py-20">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-3xl font-extrabold text-white sm:text-4xl">A rede que não pára de crescer</h2>
            <p class="mt-3 text-xl text-blue-200 sm:mt-4">Juntamos as melhores instituições para garantir o seu bem-estar.</p>
        </div>
        <dl class="mt-10 text-center sm:max-w-3xl sm:mx-auto sm:grid sm:grid-cols-2 sm:gap-8">
            <div class="flex flex-col">
                <dt class="order-2 mt-2 text-lg leading-6 font-medium text-blue-200">Hospitais & Clínicas Registadas</dt>
                <dd class="order-1 text-5xl font-extrabold text-white">{{ $stats['hospitals'] }}</dd>
            </div>
            <div class="flex flex-col mt-10 sm:mt-0">
                <dt class="order-2 mt-2 text-lg leading-6 font-medium text-blue-200">Especialidades Médicas</dt>
                <dd class="order-1 text-5xl font-extrabold text-white">{{ $stats['specialties'] }}</dd>
            </div>
        </dl>
    </div>
</div>

<!-- Featured Hospitals -->
<div class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-extrabold text-gray-900 text-center mb-12">Hospitais em Destaque</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($featuredHospitals as $hospital)
                <a href="{{ route('public.hospital', $hospital->slug) }}" class="group bg-white rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 flex flex-col">
                    <div class="h-40 bg-gray-200 relative">
                        @if($hospital->galleries->count() > 0)
                            <img src="{{ Storage::url($hospital->galleries->first()->image) }}" alt="{{ $hospital->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-blue-50 text-blue-200">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </div>
                        @endif
                    </div>
                    <div class="p-6 flex-grow flex flex-col justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 group-hover:text-blue-600 transition">{{ $hospital->name }}</h3>
                            <p class="text-sm text-gray-500 mt-2 flex items-start gap-1">
                                <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                {{ $hospital->province ? $hospital->province . ' - ' . $hospital->municipality : 'Localização sob consulta' }}
                            </p>
                            <div class="mt-2 flex items-center text-sm">
                                <span class="text-yellow-400 flex items-center">
                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    <span class="ml-1 font-medium text-gray-700">{{ number_format($hospital->averageRating(), 1) }}</span>
                                </span>
                                <span class="ml-1 text-gray-500">({{ $hospital->reviews->count() }} avaliações)</span>
                            </div>
                        </div>
                        <div class="mt-4 pt-4 border-t border-gray-100 text-sm text-blue-600 font-medium flex items-center justify-between">
                            Ver detalhes <svg class="w-4 h-4 transform group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
        <div class="text-center mt-12">
            <a href="{{ route('public.search') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 transition">
                Ver Directório Completo
            </a>
        </div>
    </div>
</div>
@endsection
