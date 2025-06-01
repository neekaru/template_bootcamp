<div class="min-h-screen flex flex-col items-center justify-center pt-0 pb-12 px-4 sm:px-6 lg:px-8">
    <!-- Dashboard Pembeli on Top -->
    <div class="w-full max-w-xl">
        <div class="bg-white shadow-md rounded-lg px-6 py-4 mb-6 flex">
            <h1 class="text-xl font-bold text-gray-800">Dashboard Pembeli</h1>
        </div>
    </div>
    <div class="max-w-xl w-full space-y-8 bg-white p-10 rounded-xl shadow-2xl flex-grow">
        <div class="mt-0">
            <h2 class="text-3xl font-extrabold text-gray-900 text-center">
                Ubah Password
            </h2>
        </div>

        @if (session()->has('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        
        <form wire:submit.prevent="save" class="mt-8 space-y-6">
            <div class="space-y-6">
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700">Password Lama</label>
                    <input id="current_password" wire:model.lazy="current_password" type="password" required
                           class="mt-1 appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm bg-gray-100">
                    @error('current_password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="new_password" class="block text-sm font-medium text-gray-700">Password Baru</label>
                    <input id="new_password" wire:model.lazy="new_password" type="password" required
                           class="mt-1 appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm bg-gray-100">
                    @error('new_password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
                    <input id="new_password_confirmation" wire:model.lazy="new_password_confirmation" type="password" required
                           class="mt-1 appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm bg-gray-100">
                    @error('new_password_confirmation') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="flex space-x-4 pt-4">
                    <button type="submit"
                            wire:loading.attr="disabled"
                            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-green-500 hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <span wire:loading wire:target="save" class="animate-spin rounded-full h-5 w-5 border-t-2 border-b-2 border-white mr-2"></span>
                        Yes
                    </button>
                    <a href="{{ route('dashboard') }}" wire:navigate
                            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        No
                    </a>
                </div>
            </div>
        </form>
    </div>
</div> 