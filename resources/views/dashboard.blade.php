<x-layouts.app>
    <div class="container mx-auto py-8">
        <h1 class="text-2xl font-bold">Dashboard</h1>
        <p>Welcome to your dashboard!</p>

        <form method="POST" action="{{ route('logout') }}" class="mt-4">
            @csrf
            <button type="submit" class="btn btn-error">Logout</button>
        </form>
    </div>
</x-layouts.app>