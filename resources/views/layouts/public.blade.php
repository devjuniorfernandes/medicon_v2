<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - MEDICON Angola</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans antialiased text-gray-900 flex flex-col min-h-screen">
    
    <!-- Navbar -->
    <nav class="bg-white shadow-sm border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('public.home') }}" class="flex-shrink-0 flex items-center gap-2">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        <span class="font-bold text-xl tracking-tight text-blue-900">MEDICON</span>
                    </a>
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a href="{{ route('public.home') }}" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Início</a>
                        <a href="{{ route('public.search') }}" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Directório</a>
                        <a href="{{ route('public.contact') }}" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Contactos</a>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                        @if(auth()->user()->role === \App\Enums\UserRole::SUPER_ADMIN->value)
                            <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-blue-600 font-medium text-sm">Painel Admin</a>
                        @elseif(auth()->user()->role === \App\Enums\UserRole::HOSPITAL->value)
                            <a href="{{ route('hospital.dashboard') }}" class="text-gray-600 hover:text-blue-600 font-medium text-sm">Meu Hospital</a>
                        @else
                            <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-blue-600 font-medium text-sm">Minha Conta</a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-blue-600 font-medium text-sm">Login</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white mt-12">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <span class="font-bold text-2xl flex items-center gap-2 mb-4">
                    <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    MEDICON
                </span>
                <p class="text-gray-400 text-sm">A maior plataforma de saúde digital de Angola. Encontre rapidamente os melhores profissionais e instituições.</p>
            </div>
            <div>
                <h4 class="text-lg font-semibold mb-4">Links Úteis</h4>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li><a href="{{ route('public.home') }}" class="hover:text-white">Início</a></li>
                    <li><a href="{{ route('public.search') }}" class="hover:text-white">Procurar Hospitais</a></li>
                    <li><a href="{{ route('public.contact') }}" class="hover:text-white">Contactos</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-lg font-semibold mb-4">Para Instituições</h4>
                <p class="text-sm text-gray-400 mb-4">Registe a sua clínica ou hospital na nossa plataforma e aumente a sua visibilidade.</p>
                <a href="{{ route('public.contact') }}" class="inline-block bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded text-sm font-medium transition">Fale Connosco</a>
            </div>
        </div>
        <div class="border-t border-gray-800 py-6 text-center text-sm text-gray-500">
            &copy; {{ date('Y') }} MEDICON / Kubico Digital. Todos os direitos reservados.
        </div>
    </footer>
    @yield('scripts')
</body>
</html>
