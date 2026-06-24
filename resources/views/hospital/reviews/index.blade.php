@extends('layouts.hospital')

@section('title', 'Avaliações dos Pacientes')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-bold mb-6">Avaliações dos Pacientes</h2>

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @forelse($reviews as $review)
                    <div class="mb-8 border rounded-lg p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="font-bold text-lg">{{ $review->user->name }}</h3>
                                <div class="flex items-center mt-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-5 h-5 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    @endfor
                                    <span class="ml-2 text-sm text-gray-500">{{ $review->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                            </div>
                        </div>

                        <p class="text-gray-700 mb-6">{{ $review->comment ?: 'Sem comentário.' }}</p>

                        @if($review->hospital_response)
                            <div class="bg-blue-50 p-4 rounded-lg ml-8 border-l-4 border-blue-500">
                                <p class="text-sm font-bold text-blue-800 mb-1">A nossa resposta ({{ $review->responded_at->format('d/m/Y H:i') }}):</p>
                                <p class="text-gray-700">{{ $review->hospital_response }}</p>
                            </div>
                        @else
                            <form action="{{ route('hospital.reviews.update', $review) }}" method="POST" class="ml-8 mt-4">
                                @csrf
                                @method('PUT')
                                <div class="mb-4">
                                    <label for="hospital_response_{{ $review->id }}" class="block text-sm font-medium text-gray-700 mb-2">Responder a esta avaliação</label>
                                    <textarea id="hospital_response_{{ $review->id }}" name="hospital_response" rows="3" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md p-2" placeholder="Escreva aqui a sua resposta..."></textarea>
                                </div>
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Enviar Resposta
                                </button>
                            </form>
                        @endif
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-8">Ainda não recebeu nenhuma avaliação.</p>
                @endforelse

                <div class="mt-6">
                    {{ $reviews->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
