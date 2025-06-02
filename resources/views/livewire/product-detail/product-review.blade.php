<div class="font-sans">
    <hr class="my-6 border-gray-300">
    <div class="bg-white p-4 sm:p-6 rounded-lg">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Left Column: Overall Rating -->
            <div class="md:col-span-1">
                <h2 class="text-xl font-semibold mb-3 text-gray-800">Ulasan pembeli</h2>
                <div class="flex items-center mb-1">
                    <svg class="w-7 h-7 text-orange-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <span class="text-3xl font-bold ml-2 text-gray-800">{{ number_format($overallRating, 1) }}</span>
                    <span class="text-xl text-gray-500 ml-1">/ {{ number_format($maxRating, 1) }}</span>
                </div>
                <p class="text-sm text-gray-600">{{ $satisfactionPercentage }}% customer merasa puas dengan produk ini</p>
            </div>

            <!-- Right Column: Photos and Specific Review -->
            <div class="md:col-span-2">
                <h3 class="text-lg font-semibold mb-3 text-gray-800">Foto & Video Pembeli</h3>
                <div class="flex space-x-2 mb-6 overflow-x-auto pb-2">
                    @foreach(array_slice($generalReviewPhotos, 0, 5) as $photoUrl)
                        <img src="{{ $photoUrl }}" alt="Review photo" class="w-16 h-16 object-cover rounded flex-shrink-0">
                    @endforeach
                    @if(isset($generalReviewPhotos[5]))
                    <div class="relative w-16 h-16 flex-shrink-0">
                        <img src="{{ $generalReviewPhotos[5] }}" alt="More review photos" class="w-full h-full object-cover rounded filter brightness-75">
                        <div class="absolute inset-0 bg-black bg-opacity-30 flex items-center justify-center rounded">
                            <span class="text-white font-bold text-base">{{ $countOverlayText }}</span>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="flex items-start space-x-3">
                    <!-- User Avatar -->
                    <div class="w-10 h-10 rounded-full bg-gray-200 flex-shrink-0">
                        {{-- Placeholder circle for avatar --}}
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-800">{{ $userReview['name'] }}</p>
                        <p class="text-sm text-gray-700 my-2 leading-relaxed">
                            {{ $userReview['text'] }}
                        </p>
                        <div class="flex space-x-2 mt-2 overflow-x-auto pb-2">
                            @foreach($userReview['photos'] as $photo)
                                <img src="{{ $photo }}" alt="User review photo" class="w-20 h-20 object-cover rounded border border-gray-200 flex-shrink-0">
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
