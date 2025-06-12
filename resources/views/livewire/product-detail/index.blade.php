<div class="container mx-auto p-4 font-sans">
    <x-breadcrumbs :crumbs="$breadcrumbs" />
    <div class="max-w-4xl mx-auto bg-white shadow-lg rounded-lg overflow-hidden md:flex">
        <div class="md:w-1/2 p-5">
            <div class="border border-gray-300 rounded-lg p-2 mb-3">
                <a href="{{ isset($product->foto[0]) ? asset('storage/' . $product->foto[0]) : 'https://via.placeholder.com/400x300.png?text=Product+Image' }}" class="glightbox" data-gallery="product-images">
                    <img src="{{ isset($product->foto[0]) ? asset('storage/' . $product->foto[0]) : 'https://via.placeholder.com/400x300.png?text=Product+Image' }}" alt="{{ $product->nama_produk }}" class="w-full h-auto rounded-md">
                </a>
            </div>
            <div class="flex space-x-2">
                @foreach(array_slice($product->foto ?? [], 0, 3) as $i => $thumb)
                    <div class="w-1/3 border border-gray-300 rounded-lg p-1">
                        <a href="{{ asset('storage/' . $thumb) }}" class="glightbox" data-gallery="product-images">
                            <img src="{{ asset('storage/' . $thumb) }}" alt="Thumbnail {{ $i+1 }}" class="w-full h-auto rounded-md">
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="md:w-1/2 p-5">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $product->nama_produk }}</h1>
            <div class="flex items-center mb-3">
                @php
                    $avgRating = $product->ratings()->avg('rating');
                    $reviewCount = $product->ratings()->count();
                @endphp
                <div class="flex text-yellow-400">
                    @for ($i = 1; $i <= 5; $i++)
                        <i class="{{ $avgRating >= $i ? 'fas' : 'far' }} fa-star {{ $avgRating >= $i ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                    @endfor
                </div>
                <span class="ml-2 text-sm text-gray-600">({{ $reviewCount }} review)</span>
            </div>
            <h2 class="text-xl font-semibold text-gray-700 mb-2">Detail produk</h2>
            <p class="text-gray-600 text-sm mb-4">
                {{ $product->deskripsi_produk }}
            </p>
            <h3 class="text-lg font-semibold text-gray-700 mb-1">Spesifikasi:</h3>
            <ul class="list-disc list-inside text-sm text-gray-600 space-y-1 mb-4">
                <li>Jenis Produk: Lampu Tidur Hias</li>
                <li>Bahan: Akrilik + ABS</li>
                <li>Sumber Daya: USB / Baterai (opsional)</li>
                <li>Warna Cahaya: Warm White / RGB (warna-warni)</li>
                <li>Daya: 5W</li>
                <li>Ukuran: &plusmn; 15cm x 10cm x 10cm</li>
                <li>Berat: &plusmn; 300 gram</li>
            </ul>
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center border border-gray-300 rounded-md">
                    <button wire:click="decrementQuantity" class="px-3 py-1 text-gray-700 hover:bg-gray-100 rounded-l-md">
                        <i class="fas fa-minus"></i>
                    </button>
                    <span class="px-4 py-1 text-gray-700 border-l border-r border-gray-300">{{ $quantity }}</span>
                    <button wire:click="incrementQuantity" class="px-3 py-1 text-gray-700 hover:bg-gray-100 rounded-r-md">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
                <span class="text-2xl font-bold text-gray-800">Rp.{{ number_format($product->harga, 0, ',', '.') }}</span>
            </div>
            <div class="flex space-x-4">
                <button wire:click="addToCart"
                    class="flex-1 text-white font-semibold py-3 rounded-md text-center hover:brightness-110"
                    style="background-color: #f97316;">
                    Tambah ke keranjang
                </button>
                <button wire:click="buyNow"
                    class="flex-1 text-white font-semibold py-3 rounded-md text-center hover:brightness-110"
                    style="background-color: #FAA748;">
                    Beli Sekarang
                </button>
            </div>
        </div>
    </div>

    {{-- Product Reviews Section --}}
    <div class="max-w-4xl mx-auto">
        @if($reviewCount > 0)
            <script>
                // Initialize GLightbox on page load and after Livewire updates
                function initGLightbox() {
                    if (typeof GLightbox !== 'undefined') {
                        const lightbox = GLightbox({
                            touchNavigation: true,
                            loop: true,
                            autoplayVideos: true
                        });
                    }
                }

                // Initial load
                document.addEventListener('DOMContentLoaded', initGLightbox);

                // After Livewire updates
                document.addEventListener('livewire:navigated', initGLightbox);
                document.addEventListener('livewire:initialized', initGLightbox);
            </script>
            <h2 class="text-lg font-semibold text-gray-800 mt-4 mb-4">Ulasan Pembeli</h2>
            <div class="space-y-6">
                @foreach($product->ratings()->latest()->take(5)->get() as $rating)
                    <div class="bg-gray-50 rounded-lg p-4 shadow flex flex-col sm:flex-row gap-4">
                        <div class="flex-shrink-0">
                            <img src="{{ $rating->customer && $rating->customer->image ? asset('storage/avatars/' . $rating->customer->image) : 'https://ui-avatars.com/api/?name=' . urlencode($rating->customer->username ?? 'User') }}" alt="{{ $rating->customer->username ?? 'User' }}" class="w-12 h-12 rounded-full border">
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center mb-1">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="{{ $rating->rating >= $i ? 'fas' : 'far' }} fa-star {{ $rating->rating >= $i ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                @endfor
                                <span class="ml-2 text-xs text-gray-500">{{ $rating->created_at->format('d M Y') }}</span>
                            </div>
                            <p class="text-gray-700 text-sm mb-1">{{ $rating->review }}</p>
                            @if($rating->foto_review)
                                @php
                                    $fotoReviewArray = is_array($rating->foto_review)
                                        ? $rating->foto_review
                                        : json_decode($rating->foto_review, true);
                                @endphp
                                @if(is_array($fotoReviewArray) && count($fotoReviewArray) > 0)
                                    <div class="flex gap-2 mt-2">
                                        @foreach($fotoReviewArray as $foto)
                                            <a href="{{ asset('storage/' . ltrim($foto, '/')) }}" class="glightbox" data-gallery="review-{{$rating->id}}">
                                                <img src="{{ asset('storage/' . ltrim($foto, '/')) }}" alt="Foto Review" class="w-16 h-16 object-cover rounded hover:opacity-75 transition-opacity">
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500">Belum ada ulasan untuk produk ini.</p>
        @endif
    </div>
</div>