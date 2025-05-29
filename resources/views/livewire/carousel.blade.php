<div x-data="{
    activeIndex: 1, // Start at the first actual slide (index 1 in slidesToDisplay)
    autoplay: true,
    interval: null,
    originalSections: {{ json_encode($sections) }},
    slidesToDisplay: [],
    transitionEnabled: true,
    numOriginalSlides: 0,
    isSnapping: false, // Flag to indicate a snap (teleport) is in progress
    isVisible: true, // Track if page/component is visible
    intersectionObserver: null, // For viewport visibility
    visibilityHandler: null, // Store visibility change handler

    init() {
        this.numOriginalSlides = this.originalSections.length;

        if (this.numOriginalSlides === 0) {
            this.slidesToDisplay = [];
            this.autoplay = false;
            return;
        }

        if (this.numOriginalSlides === 1) {
            this.slidesToDisplay = [...this.originalSections];
            this.activeIndex = 0; // Only one slide, its index is 0
            this.autoplay = false; // No need to autoplay or loop one slide
        } else {
            // Create clones for infinite looping
            const firstClone = { ...this.originalSections[0], _cloneId: 'first_clone_' + Math.random() };
            const lastClone = { ...this.originalSections[this.numOriginalSlides - 1], _cloneId: 'last_clone_' + Math.random() };
            this.slidesToDisplay = [lastClone, ...this.originalSections, firstClone];
            this.activeIndex = 1; // Start on the first actual slide
        }

        // Setup visibility tracking
        this.setupVisibilityTracking();

        if (this.autoplay && this.numOriginalSlides > 1) {
            this.startAutoplay();
        }
    },

    setupVisibilityTracking() {
        // Page Visibility API - pause when tab is not active
        this.visibilityHandler = () => {
            this.isVisible = !document.hidden;
            if (this.isVisible) {
                if (this.autoplay && this.numOriginalSlides > 1) {
                    this.startAutoplay();
                }
            } else {
                this.stopAutoplay();
            }
        };
        document.addEventListener('visibilitychange', this.visibilityHandler);

        // Intersection Observer - pause when carousel is not in viewport
        if ('IntersectionObserver' in window) {
            this.intersectionObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting && this.isVisible) {
                        if (this.autoplay && this.numOriginalSlides > 1) {
                            this.startAutoplay();
                        }
                    } else {
                        this.stopAutoplay();
                    }
                });
            }, { threshold: 0.1 });

            this.intersectionObserver.observe(this.$el);
        }
    },

    destroy() {
        // Cleanup when component is destroyed
        this.stopAutoplay();
        if (this.visibilityHandler) {
            document.removeEventListener('visibilitychange', this.visibilityHandler);
        }
        if (this.intersectionObserver) {
            this.intersectionObserver.disconnect();
        }
    },

    startAutoplay() {
        if (!this.autoplay || this.numOriginalSlides <= 1 || !this.isVisible) return;
        clearInterval(this.interval); // Clear existing interval
        this.interval = setInterval(() => {
            if (!this.isSnapping && this.isVisible && !document.hidden) { // Don't advance if a snap is in progress or page is hidden
                this.next();
            }
        }, 3000); // Change slide every 3 seconds
    },

    stopAutoplay() {
        if (this.interval) {
            clearInterval(this.interval);
            this.interval = null;
        }
    },

    next() {
        if (this.numOriginalSlides <= 1 || this.isSnapping) return;
        this.transitionEnabled = true;
        this.activeIndex++;
        // Snap logic will be handled by handleTransitionEnd
    },

    prev() {
        if (this.numOriginalSlides <= 1 || this.isSnapping) return;
        this.transitionEnabled = true;
        this.activeIndex--;
        // Snap logic will be handled by handleTransitionEnd
    },

    goTo(originalIndex) { // originalIndex is 0 to numOriginalSlides - 1
        if (this.numOriginalSlides === 0 || this.isSnapping) return;
        if (this.numOriginalSlides === 1) {
            this.activeIndex = 0;
            return;
        }
        this.transitionEnabled = true;
        this.activeIndex = originalIndex + 1; // Map to index in slidesToDisplay
    },

    handleTransitionEnd() {
        if (this.numOriginalSlides <= 1 || this.isSnapping) return;

        // Check if we landed on a clone
        const landedOnFirstClone = this.activeIndex === this.slidesToDisplay.length - 1;
        const landedOnLastClone = this.activeIndex === 0;

        if (landedOnFirstClone || landedOnLastClone) {
            this.isSnapping = true;
            this.transitionEnabled = false; // Disable transition for the snap
            if (landedOnFirstClone) {
                this.activeIndex = 1; // Snap to the real first slide
            } else { // landedOnLastClone
                this.activeIndex = this.numOriginalSlides; // Snap to the real last slide
            }
            // Force DOM update before re-enabling transitions
            this.$nextTick(() => {
                this.transitionEnabled = true;
                this.isSnapping = false; // Snap complete
            });
        }
    },

    get currentDotIndex() {
        if (this.numOriginalSlides === 0) return -1;
        if (this.numOriginalSlides === 1) return 0;

        if (this.activeIndex === 0) return this.numOriginalSlides - 1;
        if (this.activeIndex === this.slidesToDisplay.length - 1) return 0;
        return this.activeIndex - 1;
    }
}"
x-init="init()"
x-on:destroy="destroy()"
{{-- MODIFIED CLASS ATTRIBUTE: Added mt-8 for top margin and improved overflow handling --}}
class="relative w-full sm:w-[90%] max-w-5xl mx-auto overflow-hidden rounded-lg shadow-xl mt-8 contain-layout group"
style="contain: layout style; backface-visibility: hidden;"
>
<!-- Fixed Text Section -->
<div class="absolute top-0 left-0 w-full sm:w-1/2 h-full p-4 sm:p-8 flex-col justify-center z-20 hidden sm:flex">
    <h2 class="text-2xl sm:text-4xl font-bold text-gray-800">{{ $fixed_title }}</h2>
    <h3 class="text-2xl sm:text-4xl font-bold text-gray-800 mb-2 sm:mb-4">{{ $fixed_subtitle }}</h3>
    <div class="w-24 h-1 bg-orange-500 mb-4"></div>
    <p class="text-xs sm:text-sm text-gray-600">{{ $fixed_description }}</p>
</div>

<!-- Carousel items -->
<div class="h-64 sm:h-96 flex"
     :style="`transform: translateX(-${activeIndex * 100}%); will-change: transform;`"
     :class="{ 'transition-transform duration-700 ease-in-out': transitionEnabled }"
     style="backface-visibility: hidden; transform-style: preserve-3d;"
     @transitionend="handleTransitionEnd()">
    <template x-for="(section, idx) in slidesToDisplay" :key="section._cloneId || idx">
        <div class="w-full h-full flex-shrink-0 relative carousel-slide flex items-center justify-end">
            <!-- Image and Category Title Container -->
            <div class="w-full sm:w-1/2 h-full flex flex-col items-center justify-center p-4 relative overflow-hidden">
                <!-- Image -->
                <div class="w-full h-3/4 flex items-center justify-center">
                    <img :src="section.preview_image" alt="Product Image"
                         class="max-w-full max-h-full object-contain rounded-lg shadow-lg transition-transform duration-700 ease-in-out"
                         :style="(activeIndex === idx || (activeIndex === 0 && idx === slidesToDisplay.length -1) || (activeIndex === slidesToDisplay.length -1 && idx === 0) ) ? 'transform: translateX(0); opacity: 1;' : 'transform: translateX(100%); opacity: 0;'">
                </div>
                <!-- Category Title -->
                <div class="w-full h-1/4 flex items-center justify-center text-center">
                     <h3 class="text-xl sm:text-2xl font-semibold text-gray-700 transition-all duration-500 ease-in-out transform delay-300"
                        :style="(activeIndex === idx || (activeIndex === 0 && idx === slidesToDisplay.length -1) || (activeIndex === slidesToDisplay.length -1 && idx === 0) ) ? 'opacity: 1; transform: translateY(0);' : 'opacity: 0; transform: translateY(20px);'"
                        x-text="section.category_title"></h3>
                </div>
            </div>
        </div>
    </template>
</div>

<!-- Navigation Buttons (conditionally shown if more than one slide and on hover) -->
<button @click="prev()" x-show="numOriginalSlides > 1"
        class="absolute top-1/2 left-2 sm:left-4 transform -translate-y-1/2 bg-white bg-opacity-30 hover:bg-opacity-50 text-gray-800 p-1 sm:p-2 rounded-full focus:outline-none z-10 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 sm:w-6 sm:h-6">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
    </svg>
</button>
<button @click="next()" x-show="numOriginalSlides > 1"
        class="absolute top-1/2 right-2 sm:right-4 transform -translate-y-1/2 bg-white bg-opacity-30 hover:bg-opacity-50 text-gray-800 p-1 sm:p-2 rounded-full focus:outline-none z-10 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 sm:w-6 sm:h-6">
        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
    </svg>
</button>

<!-- Dot indicators (conditionally shown if more than one slide) -->
<div x-show="numOriginalSlides > 1"
     class="absolute bottom-2 sm:bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-1.5 sm:space-x-2 z-10">
    <template x-for="(section, originalIndex) in originalSections" :key="originalIndex">
        <button @click="goTo(originalIndex)"
                :class="{ 'bg-white': currentDotIndex === originalIndex, 'bg-gray-400 hover:bg-gray-300': currentDotIndex !== originalIndex }"
                class="w-2 h-2 sm:w-3 sm:h-3 rounded-full focus:outline-none"></button>
    </template>
</div>
</div>