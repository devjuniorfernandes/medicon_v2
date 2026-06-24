@extends('layouts.admin')

@section('title', 'Editar Hospital')

@section('content')
<div class="bg-white shadow rounded-lg p-6 border border-gray-200">
    <form action="{{ route('admin.hospitals.update', $hospital) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Nome do Hospital</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring" id="name" name="name" type="text" value="{{ $hospital->name }}" required>
        </div>
        <div class="flex items-center justify-between">
            <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                Actualizar
            </button>
        </div>
    </form>
</div>
@endsection
