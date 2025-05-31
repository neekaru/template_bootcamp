<script>
    function carouselData(sectionsJson) {
        const originalSections = JSON.parse(sectionsJson);

        return {
            activeIndex: 1,
            autoplay: true,
            interval: null,
            originalSections: originalSections,
            slidesToDisplay: [],
            transitionEnabled: true,
            numOriginalSlides: 0,
            isSnapping: false,
            isVisible: true,
            intersectionObserver: null,
            visibilityHandler: null,
            animatingText: false,
            currentTextIndex: 0,

            init() {
                this.numOriginalSlides = this.originalSections.length;

                if (this.numOriginalSlides === 0) {
                    this.slidesToDisplay = [];
                    this.autoplay = false;
                    return;
                }

                if (this.numOriginalSlides === 1) {
                    this.slidesToDisplay = [...this.originalSections];
                    this.activeIndex = 0;
                    this.currentTextIndex = 0;
                    this.autoplay = false;
                } else {
                    const firstClone = { ...this.originalSections[0], _cloneId: 'first_clone_' + Math.random() };
                    const lastClone = { ...this.originalSections[this.numOriginalSlides - 1], _cloneId: 'last_clone_' + Math.random() };
                    this.slidesToDisplay = [lastClone, ...this.originalSections, firstClone];
                    this.activeIndex = 1;
                    this.currentTextIndex = 0;
                }

                this.setupVisibilityTracking();

                if (this.autoplay && this.numOriginalSlides > 1) {
                    this.startAutoplay();
                }

                this.$nextTick(() => {
                    setTimeout(() => {
                        if (this.numOriginalSlides > 0) {
                            this.animateTextIn();
                        }
                    }, 100);
                });
            },

            setupVisibilityTracking() {
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
                clearInterval(this.interval);
                this.interval = setInterval(() => {
                    if (!this.isSnapping && this.isVisible && !document.hidden && !this.animatingText) {
                        this.next();
                    }
                }, 4000);
            },

            stopAutoplay() {
                if (this.interval) {
                    clearInterval(this.interval);
                    this.interval = null;
                }
            },

            next() {
                if (this.numOriginalSlides <= 1 || this.isSnapping || this.animatingText) return;
                this.animateTextOut(() => {
                    this.transitionEnabled = true;
                    this.activeIndex++;
                    this.updateTextIndex();
                });
            },

            prev() {
                if (this.numOriginalSlides <= 1 || this.isSnapping || this.animatingText) return;
                this.animateTextOut(() => {
                    this.transitionEnabled = true;
                    this.activeIndex--;
                    this.updateTextIndex();
                });
            },

            goTo(originalIndex) {
                if (this.numOriginalSlides === 0 || this.isSnapping || this.animatingText) return;
                if (this.currentDotIndex === originalIndex) return;

                if (this.numOriginalSlides === 1) {
                    this.activeIndex = 0;
                    this.currentTextIndex = 0;
                    return;
                }
                this.animateTextOut(() => {
                    this.transitionEnabled = true;
                    this.activeIndex = originalIndex + 1;
                    this.updateTextIndex();
                });
            },

            updateTextIndex() {
                if (this.numOriginalSlides <= 1) {
                    this.currentTextIndex = 0;
                    return;
                }

                let newTextIdx = this.activeIndex - 1;
                if (this.activeIndex === 0) {
                    newTextIdx = this.numOriginalSlides - 1;
                } else if (this.activeIndex === this.slidesToDisplay.length - 1) {
                    newTextIdx = 0;
                }
                this.currentTextIndex = newTextIdx;
            },

            animateTextOut(callback) {
                this.animatingText = true;
                const textElement = this.$refs.categoryText;
                const imageElement = this.$refs.productImage;

                if (textElement) {
                    textElement.classList.remove('text-slide-up');
                    textElement.offsetHeight;
                    textElement.classList.add('text-slide-down');
                }

                if (imageElement) {
                    imageElement.classList.remove('image-fade-in');
                    imageElement.offsetHeight;
                    imageElement.classList.add('image-fade-out');
                }

                setTimeout(() => {
                    callback();
                    this.$nextTick(() => {
                        this.animateTextIn();
                    });
                }, 400);
            },

            animateTextIn() {
                this.$nextTick(() => {
                    const textElement = this.$refs.categoryText;
                    const imageElement = this.$refs.productImage;

                    if (textElement) {
                        textElement.classList.remove('text-slide-down', 'text-slide-up');
                        textElement.offsetHeight;
                        textElement.classList.add('text-slide-up');
                    }

                    if (imageElement) {
                        imageElement.classList.remove('image-fade-out');
                        imageElement.offsetHeight;
                        imageElement.classList.add('image-fade-in');
                    }

                    setTimeout(() => {
                        this.animatingText = false;
                    }, 800);
                });
            },

            handleTransitionEnd() {
                if (this.numOriginalSlides <= 1 || this.isSnapping) return;

                const landedOnFirstClone = this.activeIndex === this.slidesToDisplay.length - 1;
                const landedOnLastClone = this.activeIndex === 0;

                if (landedOnFirstClone || landedOnLastClone) {
                    this.isSnapping = true;

                    requestAnimationFrame(() => {
                        this.transitionEnabled = false;

                        if (landedOnFirstClone) {
                            this.activeIndex = 1;
                        } else {
                            this.activeIndex = this.numOriginalSlides;
                        }

                        this.updateTextIndex();

                        requestAnimationFrame(() => {
                            requestAnimationFrame(() => {
                                this.transitionEnabled = true;
                                this.isSnapping = false;
                            });
                        });
                    });
                }
            },

            get currentDotIndex() {
                if (this.numOriginalSlides === 0) return -1;
                if (this.numOriginalSlides === 1) return 0;

                if (this.activeIndex === 0) return this.numOriginalSlides - 1;
                if (this.activeIndex === this.slidesToDisplay.length - 1) return 0;
                return this.activeIndex - 1;
            },

            get currentSection() {
                if (this.numOriginalSlides === 0 || this.currentTextIndex < 0 || this.currentTextIndex >= this.numOriginalSlides) {
                    return null;
                }
                return this.originalSections[this.currentTextIndex];
            }
        }
    }
</script>

<div
    x-data="carouselData('{{ addslashes(json_encode($sections)) }}')"
    x-init="init()"
    x-on:destroy="destroy()"
    class="relative w-full sm:w-[90%] max-w-5xl mx-auto overflow-hidden rounded-lg shadow-xl mt-8 carousel-container group"
>
    <!-- Fixed Text Section -->
    <div class="absolute top-0 left-0 w-full sm:w-1/2 h-full p-4 sm:p-8 flex-col justify-center z-20 hidden sm:flex bg-gradient-to-r from-white/95 to-white/80 backdrop-blur-sm">
        <h2 class="text-2xl sm:text-4xl font-bold text-gray-800">{{ $fixed_title }}</h2>
        <h3 class="text-2xl sm:text-4xl font-bold text-gray-800 mb-2 sm:mb-4">{{ $fixed_subtitle }}</h3>
        <div class="w-24 h-1 bg-orange-500 mb-4"></div>
        <p class="text-xs sm:text-sm text-gray-600">{{ $fixed_description }}</p>
    </div>

    <!-- Carousel Track -->
    <div class="h-64 sm:h-96 flex carousel-track"
        :style="`transform: translateX(-${activeIndex * 100}%);`"
        :class="{ 'smooth-transition': transitionEnabled }"
        @transitionend="handleTransitionEnd()">
        
        <template x-for="(section, idx) in slidesToDisplay" :key="section._cloneId || idx">
            <div class="w-full h-full flex-shrink-0 relative carousel-slide flex items-center justify-end">
                <div class="w-full sm:w-1/2 h-full flex flex-col items-center justify-center p-4 relative overflow-hidden carousel-content bg-white/60 dark:bg-gray-800/80">
                    <!-- Image -->
                    <div class="w-full h-3/4 flex items-center justify-center">
                        <img x-ref="productImage"
                            :src="currentSection?.preview_image || ''" 
                            alt="Product Image"
                            class="max-w-full max-h-full object-contain rounded-lg shadow-lg">
                    </div>
                    <!-- Category Title -->
                    <div class="w-full h-1/4 flex items-center justify-center text-center">
                        <h3 x-ref="categoryText"
                            class="text-xl sm:text-2xl font-semibold text-gray-700 will-change-transform"
                            x-text="currentSection?.category_title || ''"></h3>
                    </div>
                </div>
            </div>
        </template>

        <!-- Optional fallback if no slides -->
        <template x-if="numOriginalSlides === 0">
            <div class="w-full h-full flex-shrink-0 relative carousel-slide flex items-center justify-end">
                <div class="w-full sm:w-1/2 h-full flex flex-col items-center justify-center p-4 relative overflow-hidden carousel-content">
                    <p class="text-gray-500">No items to display.</p>
                </div>
            </div>
        </template>
    </div>

    <!-- Navigation Buttons -->
    <button @click="prev()" x-show="numOriginalSlides > 1"
        class="absolute top-1/2 left-2 sm:left-4 transform -translate-y-1/2 bg-white bg-opacity-30 hover:bg-opacity-50 text-gray-800 p-1 sm:p-2 rounded-full focus:outline-none z-10 opacity-0 group-hover:opacity-100 transition-opacity duration-300 hover:scale-110">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 sm:w-6 sm:h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
        </svg>
    </button>
    <button @click="next()" x-show="numOriginalSlides > 1"
        class="absolute top-1/2 right-2 sm:right-4 transform -translate-y-1/2 bg-white bg-opacity-30 hover:bg-opacity-50 text-gray-800 p-1 sm:p-2 rounded-full focus:outline-none z-10 opacity-0 group-hover:opacity-100 transition-opacity duration-300 hover:scale-110">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 sm:w-6 sm:h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
        </svg>
    </button>

    <!-- Dot Indicators -->
    <div x-show="numOriginalSlides > 1"
        class="absolute carousel-dots bottom-2 sm:bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-1.5 sm:space-x-2 z-10">
        <template x-for="(section, originalIndex) in originalSections" :key="originalIndex">
            <button @click="goTo(originalIndex)"
                :class="{ 'bg-white scale-125': currentDotIndex === originalIndex, 'bg-gray-400 hover:bg-gray-300': currentDotIndex !== originalIndex }"
                class="w-2 h-2 sm:w-3 sm:h-3 rounded-full focus:outline-none transition-all duration-300 hover:scale-110"></button>
        </template>
    </div>
</div>
