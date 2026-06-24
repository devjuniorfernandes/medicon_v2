@extends('layouts.hospital')

@section('title', 'Meu Perfil')

@section('content')
<div class="bg-white shadow rounded-lg p-6 border border-gray-200">
    <form action="{{ route('hospital.profile.update') }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Telefone</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring" name="phone" type="text" value="{{ $hospital->phone ?? '' }}">
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Website</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring" name="website" type="url" value="{{ $hospital->website ?? '' }}">
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Província</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring" name="province" type="text" value="{{ $hospital->province ?? '' }}">
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Município</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring" name="municipality" type="text" value="{{ $hospital->municipality ?? '' }}">
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Morada Completa</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring" name="address" type="text" value="{{ $hospital->address ?? '' }}">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Horário de Funcionamento</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring" name="opening_hours" type="text" value="{{ $hospital->opening_hours ?? '' }}" placeholder="Ex: Seg-Sexta: 8h-18h | Urgências 24h">
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">Descrição</label>
            <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring" name="description" rows="4">{{ $hospital->description ?? '' }}</textarea>
        </div>

        <div class="flex items-center">
            <button class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                Actualizar Perfil
            </button>
        </div>
    </form>
</div>
@endsection
