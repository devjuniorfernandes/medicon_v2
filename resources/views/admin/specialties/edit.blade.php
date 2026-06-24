@extends('layouts.admin')

@section('title', 'Editar Especialidade')

@section('content')
<div class="bg-white shadow rounded-lg p-6 border border-gray-200">
    <form action="{{ route('admin.specialties.update', $specialty) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Nome da Especialidade</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring" id="name" name="name" type="text" value="{{ $specialty->name }}" required>
        </div>
        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="description">Descrição (Opcional)</label>
            <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring" id="description" name="description" rows="4">{{ $specialty->description }}</textarea>
        </div>
        <div class="flex items-center justify-between">
            <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                Actualizar
            </button>
        </div>
    </form>
</div>
@endsection
