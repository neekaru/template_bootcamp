<div>
    <!-- Desktop Header -->
    <header class="bg-base-100 dark:bg-neutral text-base-content dark:text-neutral-content shadow-lg hidden sm:block rounded-xl mx-auto max-w-7xl mt-4 mb-2 px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="/" class="flex items-center">
                    <span class="bg-primary dark:bg-primary-focus text-primary-content dark:text-primary-content p-1 px-3 rounded-md font-bold text-lg shadow">KK</span>
                    <span class="ml-3 text-xl font-bold tracking-tight">KREASI KITA</span>
                </a>
            </div>

            <!-- Centered Navigation Links & Search -->
            <div class="hidden md:flex md:items-center md:space-x-4 lg:space-x-6">
                <a href="/" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-base-200 dark:hover:bg-neutral-focus hover:bg-opacity-75 transition-colors">Home</a>
                <a href="/kategori" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-base-200 dark:hover:bg-neutral-focus hover:bg-opacity-75 transition-colors">Kategori</a>
                <a href="/keranjang" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-base-200 dark:hover:bg-neutral-focus hover:bg-opacity-75 transition-colors">Keranjang</a>
                <a href="/tentang-kami" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-base-200 dark:hover:bg-neutral-focus hover:bg-opacity-75 transition-colors">Tentang kami</a>
                <button aria-label="Search" class="p-2 rounded-md hover:bg-base-200 dark:hover:bg-neutral-focus hover:bg-opacity-75 transition-colors focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </div>

            <!-- Login/Logout Buttons -->
            <div class="hidden md:flex md:items-center">
                @guest
                    <a href="{{ route('login') }}" class="btn btn-primary btn-sm">
                        Log In
                    </a>
                    {{-- Register button is intentionally omitted as per user request for desktop --}}
                @else
                    {{-- Logged-in state: Simple Logout Button --}}
                    <form method="POST" action="{{ route('logout') }}" class="inline-block">
                        @csrf
                        <button type="submit" class="btn btn-error btn-sm">
                            Logout
                        </button>
                    </form>
                @endguest
            </div>
        </div>
    </header>

    <!-- Mobile Bottom Navigation will be a separate component -->
    {{-- This comment is kept from the original file for clarity, mobile navigation is handled elsewhere. --}}
</div>