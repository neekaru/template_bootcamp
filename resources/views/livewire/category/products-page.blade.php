<div class="w-full dark:bg-base-200">
    <div class="container mx-auto px-4 py-8">
        <nav class="mb-4 text-sm text-gray-500" aria-label="Breadcrumb">
            <ol class="list-none p-0 inline-flex">
                {{-- <li class="flex items-center">
                    <a href="/" class="hover:underline">Home</a>
                    <svg class="fill-current w-3 h-3 mx-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"/></svg>
                </li> --}}
                <li class="flex items-center">
                    <a href="{{ route('category.index') }}" class="hover:underline">Category</a>
                    <svg class="fill-current w-3 h-3 mx-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"/></svg>
                </li>
                <li>
                    <span class="text-gray-700">{{ $categoryDisplayName }}</span>
                </li>
            </ol>
        </nav>

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
                        <figure class="h-48 sm:h-56 flex items-center justify-center p-4 bg-white">
                            <a href="/produk/detail/{{ $product->id }}">
                                <img src="{{ $product->image_url ?? 'https://via.placeholder.com/300x300.png?text=Produk+Kerajinan' }}" alt="{{ $product->nama_produk }}" class="max-h-full max-w-full object-contain" />
                            </a>
                        </figure>
                        <div class="p-4 text-center">
                            <div class="flex justify-center mb-2">
                                @php
                                    $avgRating = $product->ratings_avg_rating ?? 0;
                                @endphp
                                @for ($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $avgRating >= $i ? 'text-orange-400' : 'text-gray-300 dark:text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                @endfor
                                @if($product->ratings_count > 0)
                                    <span class="ml-1 text-xs text-gray-500">({{ $product->ratings_count }})</span>
                                @endif
                            </div>
                            <h3 class="text-sm font-semibold text-black dark:text-white truncate mb-1" title="{{ $product->nama_produk }}">{{ $product->nama_produk }}</h3>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mb-3">Rp.{{ number_format($product->harga, 0, ',', '.') }}</p>
                            <button wire:click="addToCart({{ $product->id }})" class="btn btn-sm text-white normal-case tracking-normal font-medium" style="background-color: #F97316; border-color: #F97316;">
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
</div>
