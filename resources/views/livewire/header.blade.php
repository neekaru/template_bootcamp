<header class="bg-base-100 shadow-md">
    <div class="container mx-auto navbar">
        <div class="flex-1">
            <a href="/" class="btn btn-ghost normal-case text-xl">KerajinanTangan</a>
        </div>
        <div class="flex-none">
            <ul class="menu menu-horizontal px-1">
                                @guest
                    <li><a href="{{ route('login') }}" class="btn btn-ghost">Login</a></li>
                    <li><a href="{{ route('register') }}" class="btn btn-ghost">Register</a></li>
                @else
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-ghost">Logout</button>
                        </form>
                    </li>
                @endguest
                <li>
                    <a href="#" class="btn btn-ghost"> <!-- Replace # with actual cart route later -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        Cart
                    </a>
                </li>
            </ul>
        </div>
    </div>
</header>