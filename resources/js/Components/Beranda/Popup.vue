<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
  popup: Object,
});

// Konversi array gambar dari props
const images = computed(() => props.popup?.image_popup || []);
const showPopup = ref(true);
const currentIndex = ref(0);

// Fungsi untuk navigasi gambar manual dengan efek looping
const nextImage = () => {
  if (images.value.length > 1) {
    currentIndex.value = (currentIndex.value + 1) % images.value.length;
  }
};

const prevImage = () => {
  if (images.value.length > 1) {
    currentIndex.value = (currentIndex.value - 1 + images.value.length) % images.value.length;
  }
};
</script>

<template>
  <div v-if="showPopup" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-70 z-50">
    <div class="bg-white p-3 rounded-lg shadow-xl relative w-[80%] sm:w-[60%] md:w-[50%] z-50 max-w-[900px] flex items-center">
      
      <!-- Tombol Panah Kiri (Muncul jika gambar > 1) -->
      <button
        v-if="images.length > 1"
        @click="prevImage"
        class="absolute left-[-50px] bg-gray-800 text-white p-3 rounded-full shadow-lg hover:bg-gray-700 transition duration-200 z-10 flex items-center justify-center w-10 h-10"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
        </svg>
      </button>

      <!-- Slide Show Gambar -->
      <div class="relative w-full">
        <!-- Tombol Close -->
<button @click="showPopup = false" class="absolute top-2 right-2 z-50 custom-close-button">
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-8 h-8 close-button">
    <circle cx="12" cy="12" r="10" fill="#DB1B1B"/>
    <path d="M10.28 9.22a.75.75 0 1 0-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 1 0 1.06 1.06L12 13.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L13.06 12l1.72-1.72a.75.75 0 1 0-1.06-1.06L12 10.94l-1.72-1.72Z" fill="white"/>
  </svg>
</button>
        <img
        v-if="images.length > 0"
        :src="`/storage/${images[currentIndex]}`"
        alt="Popup Image"
        class="w-full h-auto rounded-lg transition-all duration-500 ease-in-out"
        style="max-width:100%; max-height: 400px;"
        />
        <p v-else class="text-center text-gray-500">Tidak ada gambar</p>
      </div>

      <!-- Tombol Panah Kanan (Muncul jika gambar > 1) -->
      <button
        v-if="images.length > 1"
        @click="nextImage"
        class="absolute right-[-50px] bg-gray-800 text-white p-3 rounded-full shadow-lg hover:bg-gray-700 transition duration-200 z-10 flex items-center justify-center w-10 h-10"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
        </svg>
      </button>

    </div>
  </div>
</template>

<style scoped>
button {
  cursor: pointer;
  transition: background-color 0.3s;
}
button:hover {
  background-color: rgba(0, 0, 0, 0.7);
}

  .custom-close-button {
    background-color: transparent !important;
    border: none !important;
    outline: none !important;
    box-shadow: none !important;
  }
  .custom-close-button:hover, .custom-close-button:focus {
    background-color: transparent !important;
    outline: none !important;
  }
</style>
