@extends('layouts.public')

@section('title', 'Directório de Hospitais')

@section('content')
<div class="bg-blue-900 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-white mb-6">Pesquisar Directório</h1>
        <form action="{{ route('public.search') }}" method="GET" class="bg-white p-4 rounded-lg shadow flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <label class="sr-only">Nome</label>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Nome do Hospital/Clínica..." class="w-full border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 p-2 border">
            </div>
            <div class="md:w-64">
                <label class="sr-only">Província</label>
                <select name="province" class="w-full border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 p-2 border">
                    <option value="">Todas as Províncias</option>
                    <option value="Luanda" {{ request('province') == 'Luanda' ? 'selected' : '' }}>Luanda</option>
                    <option value="Benguela" {{ request('province') == 'Benguela' ? 'selected' : '' }}>Benguela</option>
                    <option value="Huíla" {{ request('province') == 'Huíla' ? 'selected' : '' }}>Huíla</option>
                    <!-- other provinces can be added here -->
                </select>
            </div>
            <div class="md:w-64">
                <label class="sr-only">Especialidade</label>
                <select name="specialty" class="w-full border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 p-2 border">
                    <option value="">Todas as Especialidades</option>
                    @foreach($specialties as $specialty)
                        <option value="{{ $specialty->id }}" {{ request('specialty') == $specialty->id ? 'selected' : '' }}>{{ $specialty->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-md">
                Pesquisar
            </button>
        </form>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-6 text-gray-600">
        Encontrados {{ $hospitals->total() }} resultados
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($hospitals as $hospital)
            <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-shadow border border-gray-100 flex flex-col overflow-hidden">
                <div class="h-48 bg-gray-200 relative">
                    @if($hospital->galleries->count() > 0)
                        <img src="{{ Storage::url($hospital->galleries->first()->image) }}" alt="{{ $hospital->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-blue-50 text-blue-200">
                            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                    @endif
                </div>
                <div class="p-6 flex-grow flex flex-col justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $hospital->name }}</h3>
                        <p class="text-sm text-gray-500 flex items-start gap-1 mb-2">
                            <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            {{ $hospital->province ? $hospital->province . ' - ' . $hospital->municipality : 'Localização não definida' }}
                        </p>
                        <div class="mb-3 flex items-center text-sm">
                            <span class="text-yellow-400 flex items-center">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                <span class="ml-1 font-medium text-gray-700">{{ number_format($hospital->averageRating(), 1) }}</span>
                            </span>
                            <span class="ml-1 text-gray-500">({{ $hospital->reviews->count() }} avaliações)</span>
                        </div>
                        <div class="flex flex-wrap gap-1 mt-3">
                            @foreach($hospital->specialties->take(3) as $spec)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $spec->name }}
                                </span>
                            @endforeach
                            @if($hospital->specialties->count() > 3)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                    +{{ $hospital->specialties->count() - 3 }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="mt-6">
                        <a href="{{ route('public.hospital', $hospital->slug) }}" class="w-full flex justify-center py-2 px-4 border border-blue-600 rounded-md shadow-sm text-sm font-medium text-blue-600 bg-white hover:bg-blue-50 transition">
                            Ver Perfil Completo
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-gray-500">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Não foram encontrados hospitais com os critérios seleccionados.
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $hospitals->links() }}
    </div>
</div>
@endsection
