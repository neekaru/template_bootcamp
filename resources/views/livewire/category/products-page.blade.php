<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold dark:text-white text-black">{{ $categoryDisplayName }}</h1>
        <hr class="my-4 border-gray-300 dark:border-gray-600">
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success mb-4">
            {{ session('message') }}
        </div>
    @endif

    @if ($paginatedProducts->isEmpty())
        <p class="text-center text-gray-500 dark:text-gray-400">Tidak ada produk dalam kategori ini.</p>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-x-6 gap-y-8">
            @foreach($paginatedProducts as $product)
                <div class="card bg-base-100 shadow-xl rounded-lg overflow-hidden transform hover:scale-105 transition-transform duration-300">
                    <figure class="h-48 sm:h-56 flex items-center justify-center p-4 bg-white"> {{-- Increased height and padding --}}
                        <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" class="max-h-full max-w-full object-contain" />
                    </figure>
                    <div class="p-4 text-center"> {{-- Increased padding --}}
                        <div class="flex justify-center mb-2"> {{-- Increased margin-bottom --}}
                            @for ($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= $product['rating'] ? 'text-orange-400' : 'text-gray-300 dark:text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            @endfor
                        </div>
                        <h3 class="text-sm font-semibold text-black dark:text-white truncate mb-1" title="{{ $product['name'] }}">{{ $product['name'] }}</h3> {{-- Increased font-size and margin-bottom --}}
                        <p class="text-xs text-gray-600 dark:text-gray-400 mb-3">{{ $product['price'] }}</p> {{-- Increased font-size and margin-bottom --}}
                        <button wire:click="addToCart('{{ $product['name'] }}')" class="btn btn-sm text-white normal-case tracking-normal font-medium" style="background-color: #F97316; border-color: #F97316;">
                            Tambah ke keranjang
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-12">
            {{ $paginatedProducts->links() }}
        </div>
    @endif
</div>
