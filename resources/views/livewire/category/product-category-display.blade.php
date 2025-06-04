<div class="pt-8"> {{-- Removed container mx-auto px-4, as parent likely has it. Added pt-8 for spacing after hr --}}
    @if (session()->has('message'))
        <div class="alert alert-success mb-4">
            {{ session('message') }}
        </div>
    @endif

    @foreach($categoriesWithProducts as $category)
        <div class="mb-10">
            <div class="flex justify-between items-center mb-5">
                <h2 class="text-xl font-bold dark:text-white text-black">{{ $category['name'] }}</h2>
                <a href="{{ route('category.products', ['categoryName' => Str::slug($category['name'])]) }}" wire:navigate class="text-sm font-semibold text-orange-500 hover:text-orange-600 dark:text-orange-400 dark:hover:text-orange-300">
                    Lainnya <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-x-4 gap-y-6">
                @foreach($category['products'] as $product)
                    <div class="card bg-base-100 shadow-md rounded-lg overflow-hidden">
                        <figure class="h-36 flex items-center justify-center p-3 bg-white">
                            <a href="/produk/{{ $product['id'] }}">
                                <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" class="max-h-full max-w-full object-contain" />
                            </a>
                        </figure>
                        <div class="p-3 text-center">
                            <div class="flex justify-center mb-1.5">
                                @for ($i = 1; $i <= 5; $i++)
                                    <svg class="w-3.5 h-3.5 {{ $i <= $product['rating'] ? 'text-orange-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                @endfor
                            </div>
                            <h3 class="text-xs font-medium text-black dark:text-white truncate" title="{{ $product['name'] }}">{{ $product['name'] }}</h3>
                            <p class="text-2xs text-gray-500 dark:text-gray-300 mb-2">{{ $product['price'] }}</p>
                            <button wire:click="addToCart({{ $product['id'] }})" class="btn btn-xs text-white normal-case tracking-normal font-normal" style="background-color: #F97316; border-color: #F97316; min-height: 1.8rem; height: 1.8rem; font-size: 0.65rem; padding-left: 0.75rem; padding-right: 0.75rem;">
                                Tambah ke keranjang
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>