<div class="min-h-screen py-8 dark:bg-base-200">
    <div class="container mx-auto px-4">
        {{-- This outer flex container is responsible for centering the icon group (the inline-grid) --}}
        <div class="flex justify-center">
            {{--
                inline-grid: Makes the grid container only as wide as its content.
                grid-cols-3: We'll use 3 columns across all sizes for these specific items to match the Figma.
                             If you have other sections with more items, they would need their own grid setup.
                gap-x-*: Responsive horizontal gaps. Increased significantly for lg screens.
                gap-y-6: Vertical gap if items were to wrap (not expected here with 3 items).
            --}}
            <div class="inline-grid grid-cols-3 gap-y-6
                        gap-x-4 sm:gap-x-6 md:gap-x-8 lg:gap-x-12 xl:gap-x-16">
                @php
                    $categories = [
                        [
                            'icon' => 'fa-solid fa-couch',
                            'name' => 'Perabotan<br>Rumah Tangga',
                            'color' => 'bg-[#9F6444]',
                            'link' => route('category.products', ['categoryName' => 'perabotan-rumah-tangga']),
                        ],
                        [
                            'icon' => 'fa-solid fa-house',
                            'name' => 'Dekorasi<br>Rumah',
                            'color' => 'bg-[#9F6444]',
                            'link' => route('category.products', ['categoryName' => 'dekorasi-rumah']),
                        ],
                        [
                            'icon' => 'fa-solid fa-table-cells-large',
                            'name' => 'Aksesoris',
                            'color' => 'bg-[#9F6444]',
                            'link' => route('category.products', ['categoryName' => 'aksesoris']),
                        ],
                    ];
                @endphp

                @foreach ($categories as $category)
                    <a href="{{ $category['link'] }}" class="flex flex-col items-center text-center group focus:outline-none">
                        {{-- Icon Circle --}}
                        <div class="{{ $category['color'] }} rounded-full w-20 h-20 lg:w-[88px] lg:h-[88px] flex items-center justify-center shadow-md group-hover:scale-105 transition-transform">
                            <i class="{{ $category['icon'] }} !text-white text-3xl lg:text-[36px]"></i>
                        </div>
                        {{-- Text below icon --}}
                        <span class="text-xs font-semibold dark:text-light text-black mt-2 leading-tight block w-[100px] lg:w-[120px]">
                            {!! $category['name'] !!}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>

        <hr class="mt-12 border-gray-300">

       {{-- Display Product Categories with Items --}}
       @livewire('category.product-category-display')
    </div>
</div>
