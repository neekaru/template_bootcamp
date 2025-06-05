<div class="container mx-auto px-4 py-8 font-sans">
    <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Daftar Transaksi</h1>

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <input
            wire:model.live.debounce.300ms="search"
            type="text"
            placeholder="Cari Transaksi anda disini"
            class="input input-bordered w-full sm:max-w-xs dark:bg-gray-700 dark:border-gray-600 dark:text-white"
        />
        <select wire:model.live="selectedCategory" class="select select-bordered w-full sm:max-w-xs dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            <option value="">Semua Produk</option>
            @foreach($categories as $category)
                <option value="{{ $category }}">{{ $category }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-6">
        <span class="text-sm font-medium text-gray-700 dark:text-gray-300 mr-3">Status:</span>
        <div class="inline-flex rounded-md shadow-sm" role="group">
            <button wire:click="setStatusFilter('berhasil')" type="button"
                    class="px-4 py-2 text-sm font-medium rounded-l-lg
                           {{ $statusFilter === 'berhasil' ? 'bg-green-500 text-white z-10 ring-2 ring-green-500' : 'bg-white text-gray-900 border border-gray-200 hover:bg-gray-100 hover:text-green-700 focus:text-green-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 dark:focus:text-white' }}">
                Berhasil
            </button>
            <button wire:click="setStatusFilter('diproses')" type="button"
                    class="px-4 py-2 text-sm font-medium
                           {{ $statusFilter === 'diproses' ? 'bg-yellow-400 text-white z-10 ring-2 ring-yellow-400' : 'bg-white text-gray-900 border-t border-b border-gray-200 hover:bg-gray-100 hover:text-yellow-700 focus:text-yellow-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 dark:focus:text-white' }}">
                Diproses
            </button>
            <button wire:click="setStatusFilter('gagal')" type="button"
                    class="px-4 py-2 text-sm font-medium rounded-r-lg
                           {{ $statusFilter === 'gagal' ? 'bg-red-500 text-white z-10 ring-2 ring-red-500' : 'bg-white text-gray-900 border border-gray-200 hover:bg-gray-100 hover:text-red-700 focus:text-red-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 dark:focus:text-white' }}">
                Gagal
            </button>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success mb-4">
            {{ session('success') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="alert alert-error mb-4">
            {{ session('error') }}
        </div>
    @endif
    @if (session()->has('info'))
        <div class="alert alert-info mb-4">
            {{ session('info') }}
        </div>
    @endif

    <div class="space-y-6">
        @forelse ($transactions as $transaction)
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-4 sm:p-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-3 pb-3 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400 mb-2 sm:mb-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500 dark:text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 2a2 2 0 00-2 2v1H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2V4a2 2 0 00-2-2zm0 2V4h2v1H8V4h2zm-2 3V6h4v2H6z" clip-rule="evenodd" />
                            <path d="M3 9h14v8a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                        </svg>
                        <span>Belanja</span>
                        <span class="mx-2">•</span>
                        <span>{{ $transaction->created_at->format('j M Y') }}</span>
                    </div>
                    <div class="flex items-center gap-x-2">
                        @php
                            $statusClass = '';
                            $statusText = '';
                            switch ($transaction->status) {
                                case 'success':
                                    $statusClass = 'bg-green-100 text-green-700 dark:bg-green-700 dark:text-green-100';
                                    $statusText = 'Berhasil';
                                    break;
                                case 'pending':
                                    $statusClass = 'bg-yellow-100 text-yellow-700 dark:bg-yellow-600 dark:text-yellow-100';
                                    $statusText = 'Diproses';
                                    break;
                                default: // failed, expired, cancelled, deny
                                    $statusClass = 'bg-red-100 text-red-700 dark:bg-red-700 dark:text-red-100';
                                    $statusText = 'Gagal';
                                    break;
                            }
                        @endphp
                        <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $statusClass }}">{{ $statusText }}</span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $transaction->invoice }}</span>
                    </div>
                </div>

                @foreach ($transaction->transactionDetails as $detail)
                    <div class="flex flex-col sm:flex-row items-start sm:items-center mb-4 last:mb-0">
                        <img src="{{ isset($detail->product->foto[0]) ? asset('storage/' . $detail->product->foto[0]) : asset('images/default-product.png') }}"
                             alt="{{ $detail->product->nama_produk ?? '' }}"
                             class="w-20 h-20 sm:w-24 sm:h-24 object-cover rounded-md mr-0 sm:mr-4 mb-3 sm:mb-0 flex-shrink-0">
                        <div class="flex-grow">
                            <h3 class="text-md font-semibold text-gray-800 dark:text-white">{{ $detail->product->nama_produk }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $detail->quantity }} barang x Rp {{ number_format($detail->price, 0, ',', '.') }}
                            </p>
                        </div>
                        @if ($loop->first)
                        <div class="sm:ml-auto mt-3 sm:mt-0 text-left sm:text-right flex-shrink-0">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Belanja</p>
                            <p class="text-lg font-bold text-gray-800 dark:text-white">Rp {{ number_format($transaction->total, 0, ',', '.') }}</p>
                        </div>
                        @endif
                    </div>
                @endforeach
                
                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700 flex flex-wrap gap-2 justify-end">
                    @if($transaction->status == 'pending' || $transaction->status == 'diproses')
                        <button wire:click="bayarSekarang({{ $transaction->id }})" class="btn btn-primary btn-sm">Bayar Sekarang</button>
                    @endif
                    <button wire:click="lihatDetailPesanan('{{ $transaction->invoice }}')" class="btn btn-sm btn-outline btn-info normal-case">Lihat Detail Pesanan</button>
                    @if($transaction->status == 'success')
                        <button wire:click="reviewProduk({{ $detail->product->id }}, {{ $transaction->id }})" class="btn btn-sm btn-neutral normal-case">Review</button>
                    @endif
                    <button wire:click="beliLagi({{ $transaction->id }})" class="btn btn-sm btn-warning normal-case">Beli lagi</button>
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Tidak ada transaksi</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Anda belum melakukan transaksi apapun.</p>
                <div class="mt-6">
                    <a href="{{ url('/') }}" wire:navigate class="btn btn-primary normal-case">
                        Mulai Belanja
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $transactions->links() }}
    </div>
</div>

@push('scripts')
<script>
    window.addEventListener('pay-with-snap', function(e) {
        if (e.detail.snapToken) {
            payWithSnap(e.detail.snapToken);
        }
    });
</script>
<script>
    window.addEventListener('redirect-to-snap', function(e) {
        if (e.detail.url) {
            console.log('sigma')
            window.open(e.detail.url, '_blank');
        }
    });
</script>
@endpush
