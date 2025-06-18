<div class="min-h-screen flex flex-col items-center justify-center pt-0 pb-12 px-4 sm:px-6 lg:px-8">
    <!-- Dashboard Pembeli on Top -->
    <div class="w-full max-w-3xl">
        <div class="bg-white shadow-md rounded-lg px-6 py-4 mb-6 flex">
            <h1 class="text-xl font-bold text-gray-800">Dashboard Pembeli</h1>
        </div>
    </div>
    <div class="max-w-3xl w-full space-y-8 bg-white p-10 rounded-xl shadow-2xl flex-grow">
        <div class="mt-0">
            <h2 class="text-3xl font-extrabold text-gray-900 text-center">
                Ubah Profile
            </h2>
        </div>

        @if (session()->has('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        
        <form wire:submit.prevent="save" class="mt-8 space-y-6">
            <div class="flex flex-col md:flex-row md:space-x-12 items-start">
                <!-- Profile Image Section -->
                <div class="w-full md:w-1/3 flex flex-col items-center mb-6 md:mb-0">
                    <div class="relative group">
                        <label for="avatarUpload" class="cursor-pointer">
                            <div class="w-40 h-40 bg-gray-200 rounded-md flex items-center justify-center overflow-hidden">
                                @if ($newAvatar)
                                    <img src="{{ $newAvatar->temporaryUrl() }}" alt="New Avatar Preview" class="w-full h-full object-cover">
                                @elseif ($currentAvatarUrl)
                                    <img src="{{ $currentAvatarUrl }}" alt="Current Avatar" class="w-full h-full object-cover">
                                @else
                                    {{-- Fallback if $currentAvatarUrl is somehow also null, though mount() should prevent this --}}
                                    <i class="fas fa-pencil-alt fa-4x text-gray-400"></i>
                                @endif
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 flex items-center justify-center rounded-md transition-opacity duration-300">
                                    <i class="fas fa-pencil-alt fa-3x text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300"></i>
                                </div>
                            </div>
                        </label>
                        <input type="file" id="avatarUpload" wire:model="newAvatar" class="hidden">
                    </div>
                    @error('newAvatar') <span class="text-red-500 text-sm mt-2">{{ $message }}</span> @enderror
                    @if($newAvatar)
                        <div wire:loading wire:target="newAvatar" class="mt-2 text-sm text-gray-500">Uploading...</div>
                    @endif
                </div>

                <!-- Form Fields Section -->
                <div class="w-full md:w-2/3 space-y-6">
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700">Masukan Nama</label>
                        <input id="username" wire:model.lazy="username" type="text" required
                               class="mt-1 appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm bg-gray-100">
                        @error('username') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Masukan Email</label>
                        <input id="email" wire:model.lazy="email" type="email" autocomplete="email" required
                               class="mt-1 appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm bg-gray-100">
                        @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex space-x-4 pt-4">
                        <button type="submit"
                                class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-green-500 hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Yes
                        </button>
                        <a href="{{ route('dashboard') }}" wire:navigate
                                class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            No
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div> 