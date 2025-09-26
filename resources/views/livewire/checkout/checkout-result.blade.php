<div class="min-h-screen flex flex-col items-center justify-center p-4 sm:p-6">
    <div class="w-full max-w-md">
        <!-- Top Section (Status) -->
        <div class="bg-[#DDBE9D] rounded-xl shadow-lg p-6 sm:p-8 mb-6 text-center">
            @php
                $statusType = 'failed'; // Default to failed
                if ($transaction->status == 'success') {
                    $statusType = 'success';
                } elseif ($transaction->status == 'pending') {
                    $statusType = 'pending';
                }
            @endphp

            @if ($statusType == 'success')
                <div class="mx-auto bg-green-500 rounded-full h-20 w-20 sm:h-24 sm:w-24 flex items-center justify-center mb-4">
                    <svg class="h-10 w-10 sm:h-12 sm:w-12 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h1 class="text-xl sm:text-2xl font-semibold text-[#5C4B32] mb-2">Pembayaran Sukses!</h1>
            @elseif ($statusType == 'pending')
                <div class="mx-auto bg-yellow-500 rounded-full h-20 w-20 sm:h-24 sm:w-24 flex items-center justify-center mb-4">
                    <svg class="h-10 w-10 sm:h-12 sm:w-12 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h1 class="text-xl sm:text-2xl font-semibold text-[#5C4B32] mb-2">Pembayaran Tertunda</h1>
            @else {{-- failed, expired, cancelled, deny etc. --}}
                <div class="mx-auto bg-red-500 rounded-full h-20 w-20 sm:h-24 sm:w-24 flex items-center justify-center mb-4">
                    <svg class="h-10 w-10 sm:h-12 sm:w-12 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <h1 class="text-xl sm:text-2xl font-semibold text-[#5C4B32] mb-2">Pembayaran Gagal!</h1>
            @endif
            <p class="text-lg sm:text-xl text-[#5C4B32] font-medium">Rp {{ number_format($transaction->total, 0, ',', '.') }},00</p>
        </div>

        <!-- Bottom Section (Details) -->
        <div class="bg-[#DDBE9D] rounded-xl shadow-lg p-6 sm:p-8 text-[#5C4B32]">
            <h2 class="text-lg sm:text-xl font-semibold mb-6">Detil Pembayaran</h2>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm">Nomor Ref</span>
                    <span class="text-sm font-semibold text-right">{{ $transaction->invoice }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm">Status Pembayaran</span>
                    @if ($statusType == 'success')
                        <span class="text-sm font-semibold text-green-700 flex items-center">
                            <svg class="h-4 w-4 sm:h-5 sm:w-5 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            Sukses
                        </span>
                    @elseif ($statusType == 'pending')
                        <span class="text-sm font-semibold text-yellow-600 flex items-center">
                            <svg class="h-4 w-4 sm:h-5 sm:w-5 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                            </svg>
                            Tertunda
                        </span>
                    @else
                        <span class="text-sm font-semibold text-red-600 flex items-center">
                           <svg class="h-4 w-4 sm:h-5 sm:w-5 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                               <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                           </svg>
                            Gagal
                        </span>
                    @endif
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm">Waktu Pembayaran</span>
                    <span class="text-sm font-semibold text-right">{{ $transaction->updated_at->format('Y-m-d, H:i:s') }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm">Total Pembayaran</span>
                    <span class="text-sm font-semibold text-right">Rp {{ number_format($transaction->total, 0, ',', '.') }},00</span>
                </div>
            </div>

            <div class="mt-8 text-center">
                <a href="{{ route('dashboard') }}" wire:navigate
                   class="inline-block bg-[#5C4B32] text-white font-semibold py-2 px-6 rounded-lg hover:bg-opacity-90 transition duration-150">
                    Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
