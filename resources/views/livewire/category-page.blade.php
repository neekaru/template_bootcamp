<div class="dark:bg-gray-800 min-h-screen py-8">
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
                        ['icon' => 'fa-solid fa-couch', 'name' => 'Perabotan<br>rumah tangga', 'color' => 'bg-green-500'],
                        ['icon' => 'fa-solid fa-house-chimney-window', 'name' => 'Dekorasi<br>rumah', 'color' => 'bg-sky-600'],
                        ['icon' => 'fa-solid fa-gem', 'name' => 'Aksesoris', 'color' => 'bg-purple-500'],
                        // Ensure you only have 3 items here if you want to match the Figma desktop image exactly
                        // If you have more, the grid-cols-3 will cause them to wrap.
                    ];
                @endphp

                @foreach ($categories as $category)
                    <div class="flex flex-col items-center text-center">
                        {{-- Icon Circle --}}
                        <div class="{{ $category['color'] }} rounded-full w-20 h-20 lg:w-[88px] lg:h-[88px] flex items-center justify-center shadow-md dark:bg-opacity-100">
                            <i class="{{ $category['icon'] }} !text-black dark:!text-white text-3xl lg:text-[36px]"></i>
                        </div>
                        {{-- Text below icon --}}
                        <span class="text-xs font-semibold text-gray-700 dark:text-gray-300 mt-2 leading-tight block w-[100px] lg:w-[120px]">
                            {!! $category['name'] !!}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
