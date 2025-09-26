<div class="container mx-auto p-4 sm:p-6 lg:p-8 font-sans">
    <div class="max-w-2xl mx-auto bg-base-100 shadow-xl rounded-lg p-6">
        
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

        <div class="flex items-start space-x-4 mb-6">
            <img src="{{ $product->image_url ?? 'https://via.placeholder.com/100' }}" alt="{{ $product->nama_produk }}" class="w-24 h-24 object-cover rounded-md border">
            <div>
                <h1 class="text-xl font-semibold text-gray-800 dark:text-white">{{ $product->nama_produk }}</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Bagaimana penilaian anda tentang barang ini??</p>
            </div>
        </div>

        <form wire:submit.prevent="submitReview">
            <div class="mb-6">
                <div class="flex items-center space-x-1">
                    @for ($i = 1; $i <= 5; $i++)
                        <button type="button" wire:click="setRating({{ $i }})" wire:key="rating-star-{{ $i }}" class="p-0 m-0 bg-transparent border-none focus:outline-none">
                            <svg class="w-10 h-10 cursor-pointer {{ $rating >= $i ? 'text-yellow-400' : 'text-gray-300' }}"
                                 fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        </button>
                    @endfor
                    <span class="ml-3 font-medium {{ $rating > 0 ? 'text-gray-700 dark:text-gray-300' : 'text-gray-500 dark:text-gray-400' }}">
                        {{ $rating > 0 ? $this->rating_text : 'Sangat baik?' }}
                    </span>
                </div>
                @error('rating') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="mb-6">
                <label for="review_text" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Berikan ulasan anda untuk produk ini</label>
                <textarea wire:model.defer="review_text" id="review_text" rows="4" 
                          class="textarea textarea-bordered w-full dark:bg-gray-700 dark:text-white"
                          placeholder="Ketikkan deskripsi anda tentang produk..."></textarea>
                @error('review_text') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>            
            <div class="mb-8">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Bagikan foto-foto dari produk yang anda beli
                </label>
                <div wire:ignore>                
                    <input type="file" 
                        x-data="{ pond: null }"
                        x-init="$nextTick(() => {
                            if (typeof FilePond === 'undefined') {
                                console.error('FilePond is not loaded');
                                return;
                            }
                            try {
                                pond = FilePond.create($el, {
                                    credits: false,
                                    allowMultiple: true,
                                    maxFiles: 5,
                                    acceptedFileTypes: ['image/*'],
                                    maxFileSize: '3MB',
                                    server: {
                                        process: (fieldName, file, metadata, load, error, progress, abort, transfer, options) => {
                                            @this.upload('photos', file, load, error, progress)
                                        },
                                        revert: (filename, load) => {
                                            @this.removeUpload('photos', filename, load)
                                        },
                                    },
                                    labelIdle: 'Seret & Lepas foto Anda atau <span class=&quot;filepond--label-action&quot;>Jelajahi</span>',
                                    labelFileProcessingComplete: 'Upload selesai',                                       
                                    labelTapToUndo: 'ketuk untuk membatalkan',
                                    labelTapToCancel: 'ketuk untuk membatalkan',
                                    labelFileWaitingForSize: 'Menunggu ukuran',
                                    labelFileSizeNotAvailable: 'Ukuran tidak tersedia',
                                    labelFileLoading: 'Memuat',
                                    labelFileLoadError: 'Gagal memuat',
                                    labelFileProcessing: 'Mengunggah',
                                    labelFileProcessingError: 'Gagal mengunggah',
                                    labelTapToRetry: 'ketuk untuk mencoba lagi',
                                    stylePanelLayout: 'compact',
                                    imagePreviewHeight: 100,
                                    labelButtonRemoveItem: 'Hapus',
                                    labelButtonProcessItem: 'Unggah',
                                    labelMaxFileSizeExceeded: 'File terlalu besar',
                                    labelMaxFileSize: 'Ukuran maksimal file adalah 3MB'
                                });
                            } catch (error) {
                                console.error('Error initializing FilePond:', error);
                            }
                        })"
                        name="photos" 
                        multiple>
                </div>
                @error('photos') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                @error('photos.*') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>            
            @if(!empty($photos))
                <div wire:loading wire:target="photos" class="text-sm text-gray-500 mb-4">
                    <span class="inline-flex items-center">
                        <span class="loading loading-spinner loading-xs mr-2"></span>
                        Mengunggah foto...
                    </span>
                </div>
                <div wire:loading.remove wire:target="photos" class="text-sm text-green-600 mb-4">
                    <span class="inline-flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Upload foto selesai!
                    </span>
                </div>
            @endif
            <div class="flex justify-end space-x-3">
                <button type="button" wire:click="cancel" class="btn btn-outline normal-case">Batal</button>                
                <div class="relative inline-flex">
                    <button type="submit" 
                        class="btn btn-primary normal-case bg-orange-500 hover:bg-orange-600 border-orange-500 hover:border-orange-600 text-white" 
                        wire:loading.class="opacity-50 cursor-wait"
                        wire:loading.attr="disabled"
                        wire:target="submitReview">
                        <span wire:loading.flex wire:target="submitReview" class="flex items-center hidden">
                            <span class="loading loading-spinner loading-xs mr-2"></span>
                            Mengirim...
                        </span>
                        <span wire:loading.remove wire:target="submitReview">Kirim</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

