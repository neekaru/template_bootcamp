<div>
    <div class="py-3 sm:py-4">
        <!-- Desktop Header -->
        <header class="hidden sm:block">
            <div class="container mx-auto px-4">
                <nav class="bg-neutral-800 shadow-xl rounded-lg p-3 flex items-center justify-between">
                    <!-- Logo -->
                    <a href="/" class="flex items-center space-x-2.5 text-gray-100">
                        <img src="{{ asset('assets/icon/logo-1.png') }}" alt="Kreasi Kita Logo" class="h-10 w-auto">
                        <span class="font-bold text-xl tracking-wide">KREASI KITA</span>
                    </a>

                    <!-- Navigation Links -->
                    <ul class="flex space-x-5 lg:space-x-7 items-center text-sm font-medium text-gray-200">
                        <li><a href="{{ url('/') }}" wire:navigate class="px-2 py-1 hover:text-sky-300 transition duration-150">Home</a></li>
                        <li><a href="#" wire:navigate class="px-2 py-1 hover:text-sky-300 transition duration-150">Kategori</a></li>
                        <li><a href="/cart" wire:navigate class="px-2 py-1 hover:text-sky-300 transition duration-150">Keranjang</a></li>
                        <li><a href="/about" wire:navigate class="px-2 py-1 hover:text-sky-300 transition duration-150">Tentang kami</a></li>
                    </ul>

                    <!-- Search, Theme Toggle and Auth -->
                    <div class="flex items-center space-x-4">
                        <!-- Search Icon -->
                        <button class="text-gray-200 hover:text-sky-300 transition duration-150 p-1.5 rounded-full hover:bg-white/5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>

                        <!-- Theme Toggle Button (Desktop Only) -->
                        <button
                            x-on:click="toggleTheme()"
                            class="hidden sm:block text-gray-200 hover:text-sky-300 transition duration-150 p-1.5 rounded-full hover:bg-white/5"
                            title="Toggle Light Mode"
                        >
                            <span x-show="theme === 'light'">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                                </svg>
                            </span>
                            <span x-show="theme === 'dark'">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </span>
                        </button>

                        @if(!$isAuthenticated)
                            <a wire:navigate href="{{ route('login') }}" class="px-5 py-2 text-sm bg-neutral-600 hover:bg-neutral-500 text-white font-semibold rounded-md shadow-sm transition duration-150 ease-in-out border border-neutral-600">
                                Log In
                            </a>
                        @else
                            @livewire('auth.logout')
                        @endif
                    </div>
                </nav>
            </div>
        </header>
    </div>
</div>