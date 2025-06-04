<div class="bg-base-200 py-12">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
            @foreach ($produks as $produk)
                <div class="bg-base-100 rounded-lg shadow-lg overflow-hidden">
                    <div class="p-6 text-center">
                        <div class="flex justify-center mb-2">
                            @php
                                $avgRating = $produk->ratings_avg_rating ?? 0;
                            @endphp
                            @for ($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $avgRating >= $i ? 'text-orange-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            @endfor
                            @if($produk->ratings_count > 0)
                                <span class="ml-1 text-sm text-gray-500">({{ $produk->ratings_count }})</span>
                            @endif
                        </div>
                        <h3 class="text-lg font-semibold text-base-content mb-1">{{ $produk->nama_produk }}</h3>
                        <p class="text-base-content mb-4">Rp.{{ number_format($produk->harga, 0, ',', '.') }}</p>
                        <a href="/produk/{{ $produk->id }}">
                            <img src="{{ $produk->image_url ?? 'https://via.placeholder.com/300x300.png?text=Produk+Kerajinan' }}" alt="{{ $produk->nama_produk }}" class="w-full h-48 object-contain mb-4">
                        </a>
                        <button wire:click="addToCart({{ $produk->id }})" class="bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 px-6 rounded-md transition duration-300 ease-in-out transform hover:scale-105">
                            Tambah ke keranjang
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="text-center mt-10">
            <a href="#" class="text-orange-600 font-semibold text-lg hover:text-orange-700">
                Lihat barang lainnya >>>
            </a>
        </div>
    </div>
</div>
