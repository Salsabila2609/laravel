<template>
  <div 
    class="relative w-full max-w-[1920px] mx-auto px-4 mb-5 mt-5"
    @mouseenter="handleMouseEnter"
    @mouseleave="handleMouseLeave"
    @touchstart="handleMouseEnter"
    @touchend="handleMouseLeave"
  >
    <!-- Slide container -->
    <div class="relative w-full h-[150px] sm:h-[200px] md:h-[250px] lg:h-[450px] xl:h-[400px] overflow-hidden rounded-lg bg-white">
      <template v-for="(item, index) in media" :key="item.id || index">
        <div
          class="absolute inset-0 w-full h-full transition-all duration-500 ease-in-out transform"
          :class="[
            hasMultipleSlides 
              ? (index === currentIndex 
                  ? 'translate-x-0 opacity-100' 
                  : 'translate-x-full opacity-0')
              : 'translate-x-0 opacity-100'
          ]"
        >
          <a 
            v-bind:href="safeUrl(item.url)" 
            target="_blank"
            rel="noopener noreferrer"
            class="block w-full h-full group relative"
          >
            <div class="w-full h-full relative overflow-hidden">
              <img
                v-bind:src="safeImageUrl(item.image)"
                :alt="item.title || 'Slide image'"
                class="absolute w-full h-full object-contain transition-transform duration-300 group-hover:scale-105"
                loading="lazy"
              />
            </div>
            <!-- Hover overlay -->
            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-opacity duration-300 flex items-center justify-center">
              <span class="text-white text-lg md:text-xl font-bold opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                View
              </span>
            </div>
          </a>
        </div>
      </template>

      <!-- Counter overlay -->
      <div v-if="hasMultipleSlides" class="absolute bottom-2 md:bottom-4 right-2 md:right-4 bg-black/50 text-white px-2 md:px-3 py-1 rounded-full text-xs md:text-sm font-medium">
        {{ currentCount }}
      </div>
    </div>

    <!-- Navigation buttons -->
    <template v-if="hasMultipleSlides">
      <button
        @click="prevSlide"
        class="absolute left-4 md:left-6 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white p-2 md:p-3 rounded-full shadow-lg transition-colors duration-200"
        aria-label="Previous slide"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-6 md:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
      </button>

      <button
        @click="nextSlide"
        class="absolute right-4 md:right-6 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white p-2 md:p-3 rounded-full shadow-lg transition-colors duration-200"
        aria-label="Next slide"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-6 md:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </button>
    </template>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';

const props = defineProps({
  media: {
    type: Array,
    required: true,
    default: () => []
  },
  autoplayInterval: {
    type: Number,
    default: 3000,
    validator: (value) => Number.isInteger(value) && value > 500 && value < 10000
  }
});

const currentIndex = ref(0);
const autoplayTimer = ref(null);
const isHovered = ref(false);

const currentCount = computed(() => `${currentIndex.value + 1} / ${props.media.length}`);
const hasMultipleSlides = computed(() => props.media.length > 1);

const startAutoplay = () => {
  if (hasMultipleSlides.value && !isHovered.value) {
    autoplayTimer.value = setInterval(() => {
      nextSlide();
    }, props.autoplayInterval);
  }
};

const stopAutoplay = () => {
  if (autoplayTimer.value) {
    clearInterval(autoplayTimer.value);
    autoplayTimer.value = null;
  }
};

const handleMouseEnter = () => {
  isHovered.value = true;
  stopAutoplay();
};

const handleMouseLeave = () => {
  isHovered.value = false;
  startAutoplay();
};

const nextSlide = () => {
  currentIndex.value = (currentIndex.value + 1) % props.media.length;
};

const prevSlide = () => {
  currentIndex.value = (currentIndex.value - 1 + props.media.length) % props.media.length;
};

onMounted(() => startAutoplay());
onUnmounted(() => stopAutoplay());

/**
 * Validasi URL untuk mencegah XSS & eksploitasi URL
 */
const safeUrl = (url) => {
  try {
    const parsedUrl = new URL(url, window.location.origin);
    return parsedUrl.protocol === "https:" ? parsedUrl.href : "#";
  } catch {
    return "#";
  }
};

/**
 * Validasi path gambar untuk mencegah directory traversal
 */
const safeImageUrl = (path) => {
  if (typeof path !== "string") return "/images/default.png";

  // Cegah karakter ".." atau "//"
  const sanitizedPath = path.replace(/(\.\.\/|\/\/)/g, "");

  // Hanya izinkan format gambar tertentu
  if (!/\.(jpg|jpeg|png|webp|svg)$/i.test(sanitizedPath)) {
    return "/images/default.png";
  }

  return `/storage/${sanitizedPath}`;
};
</script>
