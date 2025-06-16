<div class="bg-base-200 py-12">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
            @foreach ($produks as $produk)
                <div class="bg-base-100 rounded-lg shadow-lg overflow-hidden">
                    <div class="p-6 text-center">
                        <div class="flex justify-center items-center mb-2">
                            @php
                                $avgRating = $produk->ratings()->avg('rating');
                                $reviewCount = $produk->ratings()->count();
                            @endphp
                            <div class="flex items-center">
                                <div class="flex text-yellow-400">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="{{ $avgRating >= $i ? 'fas' : 'far' }} fa-star {{ $avgRating >= $i ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                    @endfor
                                </div>
                                @if($reviewCount > 0)
                                    <span class="ml-1 text-sm text-gray-500">({{ $reviewCount }})</span>
                                @endif
                            </div>
                        </div>
                        <h3 class="text-lg font-semibold text-base-content mb-1">{{ $produk->nama_produk }}</h3>
                        <p class="text-base-content mb-4">Rp.{{ number_format($produk->harga, 0, ',', '.') }}</p>
                        <a href="/produk/detail/{{ $produk->id }}">
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
            <a href="/category" class="text-orange-600 font-semibold text-lg hover:text-orange-700">
                Lihat barang lainnya >>>
            </a>
        </div>
    </div>
</div>
