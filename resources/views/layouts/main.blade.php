<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen">
        <nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
            <!-- Primary Navigation Menu -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <a href="{{ route('home') }}">
                                <svg height="32" viewBox="0 0 32 32" width="32" class="h-8 w-auto" xmlns="http://www.w3.org/2000/svg"><g id="Layer_2" data-name="Layer 2"><path d="m28.5 7.7-1.5-1.2-13 10.2-13-10.2-1.5 1.2 14.5 11.3z"/><path d="m15.9 25.8 14.6-11.3 1.5 1.2-16.1 12.5-16.1-12.5 1.5-1.2z"/></g></svg>
                            </a>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            <a href="{{ route('home') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-indigo-700 transition">
                                Главная
                            </a>
                            @auth
                            <a href="{{ route('liked') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition">
                                Понравившиеся
                            </a>
                            @if(in_array(Auth::user()->role, ['admin', 'moderator']))
                            <a href="{{ route('moderation.page') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition">
                                Модерация
                            </a>
                            @endif
                            @endauth
                        </div>
                    </div>

                    <!-- Auth Links -->
                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        @guest
                            <a href="{{ route('login') }}" class="text-sm text-gray-700 underline">Вход</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 underline">Регистрация</a>
                            @endif
                        @else
                            <div class="ml-3 relative">
                                <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition">
                                    <div>{{ Auth::user()->name }}</div>
                                </a>
                            </div>
                             <!-- Logout -->
                            <form method="POST" action="{{ route('logout') }}" class="ml-4">
                                @csrf
                                <a href="{{ route('logout') }}"
                                         onclick="event.preventDefault();
                                                    this.closest('form').submit();" class="text-sm text-gray-700 underline">
                                    Выход
                                </a>
                            </form>
                        @endguest
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main>
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>
</body>
</html>
