<div class="container mx-auto p-4 font-sans">
    <div class="max-w-4xl mx-auto bg-white shadow-lg rounded-lg overflow-hidden md:flex">
        <div class="md:w-1/2 p-5">
            <div class="border border-gray-300 rounded-lg p-2 mb-3">
                <img src="{{ isset($product->foto[0]) ? asset('storage/' . $product->foto[0]) : 'https://via.placeholder.com/400x300.png?text=Product+Image' }}" alt="{{ $product->nama_produk }}" class="w-full h-auto rounded-md">
            </div>
            <div class="flex space-x-2">
                @foreach(array_slice($product->foto ?? [], 0, 3) as $i => $thumb)
                    <div class="w-1/3 border border-gray-300 rounded-lg p-1">
                        <img src="{{ asset('storage/' . $thumb) }}" alt="Thumbnail {{ $i+1 }}" class="w-full h-auto rounded-md">
                    </div>
                @endforeach
            </div>
        </div>
        <div class="md:w-1/2 p-5">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $product->nama_produk }}</h1>
            <div class="flex items-center mb-3">
                <div class="flex text-yellow-400">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="far fa-star text-gray-400"></i>
                </div>
                <span class="ml-2 text-sm text-gray-600">(79 review)</span>
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
</div>