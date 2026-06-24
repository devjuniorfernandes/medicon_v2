@extends('layouts.hospital')

@section('title', 'Gerir Especialidades')

@section('content')
<div class="bg-white shadow rounded-lg p-6 border border-gray-200">
    <form action="{{ route('hospital.specialties.sync') }}" method="POST">
        @csrf
        <p class="mb-4 text-gray-600">Seleccione as especialidades médicas que o seu hospital disponibiliza:</p>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-6">
            @foreach($allSpecialties as $specialty)
                <label class="inline-flex items-center bg-gray-50 border border-gray-200 rounded p-3 cursor-pointer hover:bg-gray-100 transition">
                    <input type="checkbox" name="specialties[]" value="{{ $specialty->id }}" class="form-checkbox h-5 w-5 text-indigo-600 rounded" {{ in_array($specialty->id, $hospitalSpecialties) ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">{{ $specialty->name }}</span>
                </label>
            @endforeach
        </div>

        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            Guardar Alterações
        </button>
    </form>
</div>
@endsection
