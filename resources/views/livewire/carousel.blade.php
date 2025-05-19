<div class="bg-green-900 text-white min-h-screen flex flex-col items-center justify-center p-4 sm:p-8 font-sans">
    <div class="w-full max-w-5xl text-center">
        <h1 class="text-4xl sm:text-5xl font-bold mb-8 sm:mb-12 text-gray-100">{{ $mainHeading }}</h1>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 mb-8 sm:mb-12">
            @foreach($sections as $section)
                <div class="rounded-lg overflow-hidden shadow-xl border-2 border-green-700 hover:border-green-500 transition-all duration-300 transform hover:scale-105">
                    <img src="{{ $section['preview_image'] }}" alt="{{ $section['tab_title'] }} preview" class="w-full h-40 sm:h-48 object-cover">
                </div>
            @endforeach
        </div>

        <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-8 mb-6 sm:mb-8">
            @foreach($sections as $index => $section)
                <button
                    wire:click="selectSection({{ $index }})"
                    class="text-xl sm:text-2xl font-semibold pb-2 group transition-colors duration-300 ease-in-out flex items-center
                           {{ $activeIndex === $index ? 'text-yellow-400 border-b-2 border-yellow-400' : 'text-gray-400 hover:text-yellow-300' }}"
                >
                    <span>{{ $section['tab_title'] }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" 
                         class="w-5 h-5 ml-2 transition-opacity duration-300 {{ $activeIndex === $index ? 'opacity-100' : 'opacity-0 group-hover:opacity-100' }}">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0L13.5 19.5M21 12H3" />
                    </svg>
                </button>
            @endforeach
        </div>

        <div class="bg-green-800 p-6 sm:p-8 rounded-lg shadow-2xl min-h-[120px] sm:min-h-[150px] flex items-center justify-center transition-all duration-500 ease-in-out">
            <p class="text-base sm:text-lg text-gray-200 leading-relaxed">
                {{ $sections[$activeIndex]['description'] }}
            </p>
        </div>
    </div>
</div>
