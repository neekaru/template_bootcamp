<x-layouts.app>
    {{-- Font Awesome CDN Link --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <div class="min-h-screen py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            {{-- Header --}}
            <div class="bg-white shadow-md rounded-lg px-6 py-4 mb-6">
                <h1 class="text-xl font-bold text-gray-800">Dashboard Pembeli</h1>
            </div>

            {{-- Main Content Card --}}
            <div class="bg-white shadow-xl rounded-lg p-6 sm:p-8">
                <h2 class="text-2xl font-semibold text-gray-800 mb-1">Welcome Back, {{ auth('pembeli')->user()->username ?? 'Rojabi' }}</h2>
                <hr class="border-gray-300 my-4">

                <div class="flex flex-col md:flex-row gap-6">
                    {{-- Left Column: Profile Picture and Delete Button (Desktop Only) --}}
                    <div class="flex flex-col items-center md:w-1/3">
                        <div class="w-48 h-48 mb-6 bg-gray-200 rounded-md overflow-hidden">
                            {{-- Placeholder for actual image, replace src with dynamic path if available --}}
                            <img src="{{ auth('pembeli')->user()->avatar ? (filter_var(auth('pembeli')->user()->avatar, FILTER_VALIDATE_URL) ? auth('pembeli')->user()->avatar : Illuminate\Support\Facades\Storage::url(auth('pembeli')->user()->avatar)) : 'https://ui-avatars.com/api/?name=' . urlencode(auth('pembeli')->user()->username) . '&color=7F9CF5&background=EBF4FF' }}" alt="Profile Picture" class="w-full h-full object-cover">
                        </div>
                        {{-- Delete Account Button - Hidden on mobile, shown on desktop --}}
                        <button class="btn btn-error text-white w-full max-w-xs hidden md:block">
                            Hapus Akun
                        </button>
                    </div>

                    {{-- Right Column: User Details and Action Buttons --}}
                    <div class="md:w-2/3 space-y-5">
                        <div>
                            <p class="text-lg text-gray-700"><span class="font-semibold">Nama :</span> {{ auth('pembeli')->user()->username ?? auth('pembeli')->user()->nama_lengkap ?? 'Rojabi Nur Ibrahim' }}</p>
                        </div>
                        <div>
                            <p class="text-lg text-gray-700"><span class="font-semibold">Tanggal Bergabung :</span> {{ auth('pembeli')->user()->created_at ? auth('pembeli')->user()->created_at->format('F Y') : 'Januari 2025' }}</p>
                        </div>
                        <div>
                            <p class="text-lg text-gray-700"><span class="font-semibold">Email :</span> {{ auth('pembeli')->user()->email ?? 'nsa.2014@gmail.com' }}</p>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4 mt-6 pt-4">
                            <a href="{{ route('pembeli.edit-profile') }}" wire:navigate class="btn btn-ghost bg-gray-200 hover:bg-gray-300 text-gray-700 flex-1 py-3 text-center flex justify-center items-center">
                                <i class="fas fa-edit mr-2"></i>
                                Edit Profile
                            </a>
                            <a href="{{ route('pembeli.edit-password') }}" wire:navigate class="btn btn-ghost bg-gray-200 hover:bg-gray-300 text-gray-700 flex-1 py-3 text-center flex justify-center items-center">
                                <i class="fas fa-key mr-2"></i>
                                Edit Password
                            </a>
                            <button class="btn btn-ghost bg-gray-200 hover:bg-gray-300 text-gray-700 flex-1 py-3">
                                <i class="fas fa-history mr-2"></i>
                                History Order
                            </button>
                        </div>

                        {{-- Delete Account Button - Shown on mobile only, positioned after History Order --}}
                        <div class="md:hidden mt-6 pt-4 border-t border-gray-200">
                            <button class="btn btn-error text-white w-full">
                                <i class="fas fa-trash mr-2"></i>
                                Hapus Akun
                            </button>
                        </div>

                        {{-- Logout Button - Shown on mobile only --}}
                        <div class="md:hidden mt-4">
                            @livewire('auth.logout')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>