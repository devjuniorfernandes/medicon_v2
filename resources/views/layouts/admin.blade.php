<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MEDICON Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 antialiased font-sans flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <aside class="bg-blue-900 w-64 h-full flex flex-col text-white shadow-lg">
        <div class="p-6 text-2xl font-bold border-b border-blue-800">
            MEDICON Admin
        </div>
        <nav class="flex-1 p-4 space-y-2">
            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 rounded bg-blue-800 hover:bg-blue-700">Dashboard</a>
            <a href="{{ route('admin.hospitals.index') }}" class="block px-4 py-2 rounded hover:bg-blue-700">Hospitais</a>
            <a href="{{ route('admin.specialties.index') }}" class="block px-4 py-2 rounded hover:bg-blue-700">Especialidades</a>
            <a href="{{ route('admin.users.index') }}" class="block px-4 py-2 rounded hover:bg-blue-700">Utilizadores</a>
            <a href="{{ route('admin.messages.index') }}" class="block px-4 py-2 rounded hover:bg-blue-700">Mensagens</a>
        </nav>
        <div class="p-4 border-t border-blue-800">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-2 hover:bg-blue-700 rounded">Sair</button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-full">
        <!-- Header -->
        <header class="bg-white shadow px-6 py-4 flex justify-between items-center">
            <h1 class="text-xl font-semibold text-gray-800">@yield('title')</h1>
            <div class="text-sm text-gray-600">{{ auth()->user()->name }}</div>
        </header>

        <!-- Content -->
        <div class="flex-1 overflow-auto p-6">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @yield('content')
        </div>
    </main>
</body>
</html>
