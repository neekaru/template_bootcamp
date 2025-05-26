<div>
    <!-- Main Livewire component wrapper -->
    <div class="py-3 sm:py-4">
        <!-- Desktop Header -->
        <header class="hidden sm:block">
            <div class="container mx-auto px-4">
                <nav class="bg-[#9F6444] shadow-xl rounded-lg p-3 flex items-center justify-between">
                    <!-- Logo -->
                    <a href="/" class="flex items-center space-x-2.5 text-white">
                        <div class="bg-white p-1.5 rounded-md shadow">
                            <svg class="h-7 w-auto text-[#A0522D]" viewBox="0 0 28 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4 4h3v6.5L12 4h3.5L8.5 12l7 8H12l-5-6.5V20H4V4z"/>
                                <path d="M21 12l-5-5.5v3.5h-2v4h2v3.5l5-5.5z"/>
                            </svg>
                        </div>
                        <span class="font-bold text-xl tracking-wide">KREASI KITA</span>
                    </a>

                    <!-- Navigation Links -->
                    <ul class="flex space-x-5 lg:space-x-7 items-center text-sm font-medium text-white">
                        <li><a href="{{ url('/') }}" class="px-2 py-1 hover:text-amber-200 transition duration-150">Home</a></li>
                        <li><a href="#" class="px-2 py-1 hover:text-amber-200 transition duration-150">Kategori</a></li>
                        <li><a href="#" class="px-2 py-1 hover:text-amber-200 transition duration-150">Keranjang</a></li>
                        <li><a href="#" class="px-2 py-1 hover:text-amber-200 transition duration-150">Tentang kami</a></li>
                    </ul>

                    <!-- Search and Auth -->
                    <div class="flex items-center space-x-4">
                        <!-- Search Icon -->
                        <button class="text-white hover:text-amber-200 transition duration-150 p-1.5 rounded-full hover:bg-white/10">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>

                        @guest
                            <a href="{{ route('login') }}" class="px-5 py-2 text-sm bg-gradient-to-r from-[#E5A04B] to-[#BD6711] hover:from-[#D0903F] hover:to-[#A95C0F] text-white font-semibold rounded-md shadow-sm transition duration-150 ease-in-out border border-white/20">
                                Log In
                            </a>
                        @else
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="px-5 py-2 text-sm bg-red-600 hover:bg-red-500 text-white font-semibold rounded-md shadow-sm transition duration-150 ease-in-out">
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