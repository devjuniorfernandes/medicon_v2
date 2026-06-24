<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Painel do Hospital - MEDICON</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 antialiased font-sans flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <aside class="bg-indigo-900 w-64 h-full flex flex-col text-white shadow-lg">
        <div class="p-6 text-xl font-bold border-b border-indigo-800">
            Painel do Hospital
        </div>
        <nav class="flex-1 p-4 space-y-2">
            <a href="{{ route('hospital.dashboard') }}" class="block px-4 py-2 rounded hover:bg-indigo-800">Dashboard</a>
            <a href="{{ route('hospital.appointments.index') }}" class="block px-4 py-2 rounded hover:bg-indigo-800">Marcações</a>
            <a href="{{ route('hospital.reviews.index') }}" class="block px-4 py-2 rounded hover:bg-indigo-800">Avaliações</a>
            <a href="{{ route('hospital.profile.edit') }}" class="block px-4 py-2 rounded hover:bg-indigo-800">Meu Perfil</a>
            <a href="{{ route('hospital.schedules.index') }}" class="block px-4 py-2 rounded hover:bg-indigo-800">Horários</a>
            <a href="{{ route('hospital.specialties.index') }}" class="block px-4 py-2 rounded hover:bg-indigo-800">Especialidades</a>
            <a href="{{ route('hospital.gallery.index') }}" class="block px-4 py-2 rounded hover:bg-indigo-800">Galeria</a>
        </nav>
        <div class="p-4 border-t border-indigo-800">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-2 hover:bg-indigo-700 rounded">Sair</button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-full">
        <!-- Header -->
        <header class="bg-white shadow px-6 py-4 flex justify-between items-center">
            <h1 class="text-xl font-semibold text-gray-800">@yield('title')</h1>
            <div class="text-sm text-gray-600">{{ auth()->user()->hospital->name ?? auth()->user()->name }}</div>
        </header>

        <!-- Content -->
        <div class="flex-1 overflow-auto p-6">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </main>
</body>
</html>
