<div class="min-h-screen py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        {{-- Flash Messages --}}
        @if (session()->has('success'))
            <div class="alert alert-success mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="alert alert-error mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                {{ session('error') }}
            </div>
        @endif

        {{-- Cart Items --}}
        <div class="mb-8">
            <div class="grid grid-cols-4 gap-x-4 items-center font-semibold text-gray-700 px-4 py-2 mb-2">
                <div>Detail Produk</div>
                <div class="text-center">Kuantitas</div>
                <div class="text-right">Harga</div>
                <div class="text-right">Total</div>
            </div>

            @forelse($cartItems as $item)
                <div class="bg-gray-200 rounded-lg p-4 flex items-center mb-4">
                    <div class="w-1/4 flex items-center">
                        <img src="{{ $item->produk->image_url ?? 'https://via.placeholder.com/80x80.png?text=' . urlencode($item->produk->nama_produk) }}"
                             alt="{{ $item->produk->nama_produk }}"
                             class="w-20 h-20 object-cover rounded-md mr-4">
                        <div>
                            <h3 class="font-semibold text-gray-800">{{ $item->produk->nama_produk }}</h3>
                            <p class="text-xs text-gray-600">Detail Produk:</p>
                            <div class="text-xs text-gray-600">
                                <p>{{ Str::limit($item->produk->deskripsi_produk, 50) }}</p>
                                <p>Kategori: {{ $item->produk->kategori_produk }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="w-1/4 flex items-center justify-center">
                        <button wire:click="decrementQuantity({{ $item->id }})"
                                class="btn btn-sm btn-ghost hover:bg-gray-300 px-3 py-1 rounded">-</button>
                        <span class="mx-2 font-semibold">{{ $item->qty }}</span>
                        <button wire:click="incrementQuantity({{ $item->id }})"
                                class="btn btn-sm btn-ghost hover:bg-gray-300 px-3 py-1 rounded">+</button>
                    </div>
                    <div class="w-1/4 text-right font-semibold text-gray-800">
                        Rp {{ number_format($item->produk->harga, 0, ',', '.') }}
                    </div>
                    <div class="w-1/4 text-right font-semibold text-gray-800">
                        Rp {{ number_format($item->qty * $item->produk->harga, 0, ',', '.') }}
                    </div>
                </div>
            @empty
                <div class="bg-gray-200 rounded-lg p-8 text-center flex flex-col items-center">
                    <p class="text-gray-600 text-lg">Keranjang Anda kosong</p>
                    <a href="/" wire:navigate class="btn bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-6 rounded-lg mt-4">
                        Mulai Belanja
                    </a>
                </div>
            @endforelse

            @if($cartItems->count() > 0)
                <div class="text-right mt-6">
                    <a href="/" wire:navigate class="btn bg-gray-400 hover:bg-gray-500 text-white font-semibold py-2 px-6 rounded-lg">
                        Tambah Barang
                    </a>
                </div>
            @endif
        </div>

        @if($cartItems->count() > 0)
            <hr class="border-gray-400 my-8">

            {{-- Order Summary --}}
            <div class="bg-gray-300 rounded-lg p-6 sm:p-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-6">Detail Keranjang</h2>

                <div class="flex justify-between items-center mb-4">
                    <span class="text-gray-700 font-medium">BARANG : {{ $totalItems }}</span>
                    <span class="text-gray-800 font-semibold">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>

                <div class="mb-4">
                    <label for="shipping" class="block text-gray-700 font-medium mb-1">SHIPPING</label>
                    <select wire:model.live="shipping_method" id="shipping" class="select select-bordered w-full bg-white">
                        <option value="standard">Standar Delivery (Rp {{ number_format(15000, 0, ',', '.') }})</option>
                        <option value="express">Express Delivery (Rp {{ number_format(25000, 0, ',', '.') }})</option>
                    </select>
                </div>

                <div class="mb-6">
                    <label for="promo-code" class="block text-gray-700 font-medium mb-1">Kode Promo</label>
                    <input wire:model="promo_code" type="text" id="promo-code" placeholder="Enter your code"
                           class="input input-bordered w-full bg-white"
                           @if($promo_applied) disabled @endif>
                    @if($promo_applied)
                        <p class="text-green-600 text-sm mt-1">✓ Promo code applied successfully!</p>
                    @endif
                </div>

                <button wire:click="applyPromoCode"
                        class="btn bg-orange-700 hover:bg-orange-800 text-white w-full sm:w-auto py-3 px-6 rounded-md mb-6"
                        @if($promo_applied || empty($promo_code)) disabled @endif>
                    @if($promo_applied) APPLIED @else APPLY @endif
                </button>

                @if($discount > 0)
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-700 font-medium">Subtotal</span>
                        <span class="text-gray-800 font-semibold">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-700 font-medium">Shipping</span>
                        <span class="text-gray-800 font-semibold">Rp {{ number_format($shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-green-600 font-medium">Discount</span>
                        <span class="text-green-600 font-semibold">- Rp {{ number_format($discount, 0, ',', '.') }}</span>
                    </div>
                @endif

                <hr class="border-gray-400 my-6">

                <div class="flex justify-between items-center mb-6">
                    <span class="text-gray-700 font-medium">TOTAL COST</span>
                    <span class="text-2xl text-gray-800 font-bold">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                </div>

                <button wire:click="checkout"
                        class="btn bg-orange-700 hover:bg-orange-800 text-white w-full py-3 rounded-md font-semibold">
                    CHECKOUT
                </button>
            </div>
        @endif
    </div>
</div>