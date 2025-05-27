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
                        <li><a href="{{ url('/') }}" class="px-2 py-1 hover:text-sky-300 transition duration-150">Home</a></li>
                        <li><a href="#" class="px-2 py-1 hover:text-sky-300 transition duration-150">Kategori</a></li>
                        <li><a href="#" class="px-2 py-1 hover:text-sky-300 transition duration-150">Keranjang</a></li>
                        <li><a href="#" class="px-2 py-1 hover:text-sky-300 transition duration-150">Tentang kami</a></li>
                    </ul>

                    <!-- Search and Auth -->
                    <div class="flex items-center space-x-4">
                        <!-- Search Icon -->
                        <button class="text-gray-200 hover:text-sky-300 transition duration-150 p-1.5 rounded-full hover:bg-white/5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>

                        @guest
                            <a href="{{ route('login') }}" class="px-5 py-2 text-sm bg-neutral-600 hover:bg-neutral-500 text-white font-semibold rounded-md shadow-sm transition duration-150 ease-in-out border border-neutral-600">
                                Log In
                            </a>
                        @else
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="px-5 py-2 text-sm bg-red-700 hover:bg-red-600 text-white font-semibold rounded-md shadow-sm transition duration-150 ease-in-out">
                                    Logout
                                </button>
                            </form>
                        @endguest
                    </div>
                </nav>
            </div>
        </header>
    </div>
</div>