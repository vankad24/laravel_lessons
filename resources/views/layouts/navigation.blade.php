<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="px-4 shrink-0 flex items-center">
                    <a href="{{ route('home') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" viewBox="0 0 32 32" height="32">
                            <text y="26" font-size="24">🔮</text>
                        </svg>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                        Главная
                    </x-nav-link>
                    @auth
                    <x-nav-link :href="route('liked')" :active="request()->routeIs('liked')">
                        Понравившиеся
                    </x-nav-link>
                    @if(in_array(Auth::user()->role, ['admin', 'moderator']))
                    <x-nav-link :href="route('moderation.page')" :active="request()->routeIs('moderation.page')">
                        Модерация
                    </x-nav-link>
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
