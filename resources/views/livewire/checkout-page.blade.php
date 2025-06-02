<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Checkout') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">
                    
                    @if (session()->has('success_toast'))
                        <div class="mb-4 p-3 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800 fixed top-5 right-5 z-50 shadow-lg" 
                             x-data="{ showToast: true }" x-show="showToast" x-init="setTimeout(() => showToast = false, 3000)" x-transition>
                            {{ session('success_toast') }}
                        </div>
                    @endif
                    @if (session()->has('error'))
                        <div class="mb-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800" role="alert">
                            <span class="font-medium">Error!</span> {{ session('error') }}
                            @if (session()->has('error_details') && app()->environment('local'))
                                <pre class="mt-2 text-xs whitespace-pre-wrap">{{ session('error_details') }}</pre>
                            @endif
                        </div>
                    @endif
                    @if (session()->has('info'))
                        <div class="mb-4 p-4 text-sm text-blue-700 bg-blue-100 rounded-lg dark:bg-blue-200 dark:text-blue-800" role="alert">
                            <span class="font-medium">Info!</span> {{ session('info') }}
                        </div>
                    @endif

                    @if ($currentTransaction)
                        <h1 class="text-2xl font-medium text-gray-900 dark:text-white">
                            Ringkasan Pesanan (Invoice: {{ $currentTransaction->invoice }})
                        </h1>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400 mb-6">
                            Silahkan periksa dan konfirmasi alamat pengiriman Anda sebelum melanjutkan.
                        </p>

                        {{-- Address Input Section --}}
                        <div class="mb-6 bg-gray-50 dark:bg-gray-700/50 p-4 rounded-md shadow">
                            <label for="inputAlamat" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Alamat Pengiriman Anda</label>
                            <textarea id="inputAlamat" wire:model.lazy="inputAlamat" rows="3" 
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:focus:ring-indigo-600 dark:focus:border-indigo-600 @error('inputAlamat') border-red-500 @enderror"
                                      placeholder="Masukkan alamat lengkap Anda di sini..."></textarea>
                            @error('inputAlamat') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>

                        <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Detail Pesanan:</h3>
                             <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Alamat Tujuan: <span class="font-semibold text-gray-800 dark:text-gray-100">{{ $currentTransaction->alamat ?: 'Mohon isi alamat pengiriman di atas.' }}</span>
                            </p>
                        </div>

                        <div class="mt-4 flow-root">
                            <ul role="list" class="-my-6 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($currentTransaction->transactionDetails as $item)
                                    <li class="flex py-6">
                                        <div class="h-24 w-24 flex-shrink-0 overflow-hidden rounded-md border border-gray-200 dark:border-gray-700">
                                            <img src="{{ $item->product->image_url ?? asset('images/default-product.png') }}" 
                                                 alt="{{ $item->product->name ?? 'Nama Produk' }}" 
                                                 class="h-full w-full object-cover object-center">
                                        </div>
                                        <div class="ml-4 flex flex-1 flex-col">
                                            <div>
                                                <div class="flex justify-between text-base font-medium text-gray-900 dark:text-white">
                                                    <h3>
                                                        <a href="#" class="hover:underline">{{ $item->product->name ?? 'Nama Produk' }}</a>
                                                    </h3>
                                                    <p class="ml-4">Rp {{ number_format(($item->price ?? $item->product->price) * $item->qty, 0, ',', '.') }}</p>
                                                </div>
                                            </div>
                                            <div class="flex flex-1 items-end justify-between text-sm">
                                                <p class="text-gray-500 dark:text-gray-400">Qty {{ $item->qty }}</p>
                                            </div>
                                        </div>
                                    </li>
                                @empty
                                    <p class="text-gray-500 dark:text-gray-400 py-4">Tidak ada item dalam transaksi ini.</p>
                                @endforelse
                            </ul>
                        </div>

                        <div class="border-t border-gray-200 dark:border-gray-700 px-0 sm:px-4 py-6 sm:px-6 mt-6">
                            <div class="flex justify-between text-base font-medium text-gray-900 dark:text-white">
                                <p>Subtotal</p>
                                <p>Rp {{ number_format($currentTransaction->total - $shipping_cost, 0, ',', '.') }}</p>
                            </div>
                            <div class="flex justify-between text-base font-medium text-gray-900 dark:text-white mt-2">
                                <p>Biaya Pengiriman</p>
                                <p>Rp {{ number_format($shipping_cost, 0, ',', '.') }}</p>
                            </div>
                            <div class="flex justify-between text-lg font-bold text-gray-900 dark:text-white mt-4 border-t border-gray-300 dark:border-gray-600 pt-4">
                                <p>Total Pembayaran</p>
                                <p>Rp {{ number_format($currentTransaction->total, 0, ',', '.') }}</p>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="mt-8">
                                @if ($showPaymentButton && $snapRedirectUrl)
                                    <a href="{{ $snapRedirectUrl }}" 
                                       class="w-full flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-6 py-3.5 text-base font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                        <i class="fas fa-credit-card mr-2"></i> Lanjutkan ke Pembayaran (Midtrans)
                                    </a>
                                @else
                                    <button wire:click="reviewOrderAndProceed" wire:loading.attr="disabled" wire:target="reviewOrderAndProceed"
                                            class="w-full flex items-center justify-center rounded-md border border-transparent bg-green-600 px-6 py-3.5 text-base font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-60 disabled:cursor-not-allowed transition ease-in-out duration-150">
                                        <svg wire:loading wire:target="reviewOrderAndProceed" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span wire:loading.remove wire:target="reviewOrderAndProceed"><i class="fas fa-check-circle mr-2"></i>Simpan Alamat & Lanjutkan Pesanan</span>
                                        <span wire:loading wire:target="reviewOrderAndProceed">Memproses Pesanan...</span>
                                    </button>
                                    @if ($errors->any() && !$errors->has('inputAlamat'))
                                        <p class="mt-3 text-sm text-red-600 dark:text-red-400 text-center">Gagal memproses. Silahkan periksa alamat atau pesan error di atas, lalu coba lagi.</p>
                                    @endif
                                @endif
                            </div>

                            <div class="mt-6 flex justify-center text-center text-sm text-gray-500 dark:text-gray-400">
                                <p>
                                    atau
                                    <a href="{{ route('cart.index') }}" wire:navigate class="font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300">
                                        Kembali ke Keranjang
                                        <span aria-hidden="true"> &rarr;</span>
                                    </a>
                                </p>
                            </div>
                        </div>
                    @else
                         <p class="text-gray-600 dark:text-gray-400 text-center py-10">Sedang memuat detail checkout atau keranjang Anda kosong.</p>
                         <div wire:loading class="text-center text-gray-500 dark:text-gray-400">
                            <svg class="animate-spin mx-auto h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Memproses...
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div> 