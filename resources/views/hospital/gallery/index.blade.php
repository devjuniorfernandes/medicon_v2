@extends('layouts.hospital')

@section('title', 'Galeria de Imagens')

@section('content')
<div class="bg-white shadow rounded-lg p-6 border border-gray-200 mb-6">
    <h2 class="text-lg font-semibold text-gray-800 mb-4">Adicionar Nova Imagem</h2>
    <form action="{{ route('hospital.gallery.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col md:flex-row gap-4 items-end">
        @csrf
        <div class="flex-1">
            <label class="block text-gray-700 text-sm font-bold mb-2">Ficheiro de Imagem</label>
            <input type="file" name="image" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring" required accept="image/*">
        </div>
        <div class="flex-1">
            <label class="block text-gray-700 text-sm font-bold mb-2">Legenda (Opcional)</label>
            <input type="text" name="caption" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring">
        </div>
        <div>
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Upload
            </button>
        </div>
    </form>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
    @forelse($galleries as $gallery)
        <div class="bg-white shadow rounded-lg overflow-hidden border border-gray-200 relative">
            <img src="{{ Storage::url($gallery->image) }}" class="w-full h-48 object-cover" alt="{{ $gallery->caption }}">
            @if($gallery->caption)
                <div class="p-3 bg-gray-50 border-t border-gray-200">
                    <p class="text-sm text-gray-600 truncate">{{ $gallery->caption }}</p>
                </div>
            @endif
            <form action="{{ route('hospital.gallery.destroy', $gallery) }}" method="POST" class="absolute top-2 right-2">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white text-xs font-bold py-1 px-2 rounded" onclick="return confirm('Apagar imagem?')">X</button>
            </form>
        </div>
    @empty
        <div class="col-span-full p-6 text-center text-gray-500 bg-white shadow rounded-lg border border-gray-200">
            Nenhuma imagem na galeria.
        </div>
    @endforelse
</div>
@endsection
