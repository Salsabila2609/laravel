<template>
  <div class="w-full py-12 bg-white">
    <!-- Judul dan Garis dengan Icon di Tengah -->
    <h2 class="text-2xl sm:text-3xl font-bold text-center text-[#396C6D]">Layanan Masyarakat</h2>
    <div class="flex items-center justify-center my-4">
      <div class="w-16 sm:w-24 h-1 bg-[#D4A017] rounded"></div>
      <img :src="silat" alt="Silat Icon" class="w-8 h-8 sm:w-12 sm:h-12 mx-2">
      <div class="w-16 sm:w-24 h-1 bg-[#D4A017] rounded"></div>
    </div>

    <!-- Kontainer Card -->
    <div class="relative w-full max-w-7xl mx-auto">
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6 px-4 sm:px-6 md:px-8">
        <a
          v-for="(layanan, index) in currentSlideData"
          :key="index"
          :href="layanan.url"
          target="_blank"
          rel="noopener noreferrer"
          class="bg-white p-4 sm:p-6 shadow-lg hover:shadow-2xl transition transform hover:scale-105 card-container flex flex-col items-center text-center rounded-lg cursor-pointer">
          <!-- Logo di Atas -->
          <img :src="layanan.img" alt="Icon" class="h-20 w-20 sm:h-20 sm:w-20 object-contain mb-4" />

          <!-- Judul -->
          <h3 class="text-base sm:text-lg font-semibold text-gray-700 mb-2">{{ layanan.title }}</h3>

          <!-- Deskripsi -->
          <p class="text-gray-500 text-sm sm:text-base">{{ layanan.description }}</p>
        </a>
      </div>

      <!-- Tombol Geser Kiri -->
      <button
        @click="prevSlide"
        :disabled="currentSlide === 0"
        class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-white p-2 sm:p-3 rounded-full shadow-md hover:bg-gray-100 disabled:opacity-50 flex items-center justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6 text-[#396C6D]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
      </button>

      <!-- Tombol Geser Kanan -->
      <button
        @click="nextSlide"
        :disabled="currentSlide === totalSlides - 1"
        class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-white p-2 sm:p-3 rounded-full shadow-md hover:bg-gray-100 disabled:opacity-50 flex items-center justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6 text-[#396C6D]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </button>
    </div>

    <!-- Indikator Slide -->
    <div class="flex justify-center mt-6">
      <span
        v-for="(dot, index) in totalSlides"
        :key="index"
        @click="goToSlide(index)"
        :class="[
          'w-2.5 h-2.5 sm:w-3 sm:h-3 mx-1 rounded-full cursor-pointer',
          currentSlide === index ? 'bg-[#396C6D]' : 'bg-gray-400'
        ]">
      </span>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import silat from "../../../public/images/madiun_silat.png"; // Path gambar

// Data Layanan
const layananList = [
  {
    img: '/images/jdih.png',
    title: 'Jaringan Dokumentasi dan Informasi Hukum',
    description: 'Akses cepat dan akurat ke produk hukum daerah untuk mendukung transparansi dan kemudahan informasi hukum'
  },
  {
    img: '/images/kpk.png',
    title: 'KPK Whistleblower’s System',
    description: 'Anda dapat melaporkan tindak pidana korupsi yang dilakukan oleh seseorang kepada bagian Pengawasan Internal di tempat Anda bekerja.'
  },
  {
    img: '/images/cettar-jatim.png',
    title: 'Cettar Jatim',
    description: 'Layanan unggulan Jawa Timur yang Cepat, Efektif & efisien, Tanggap, Transparan, Akuntabel dan Responsif untuk meningkatkan kualitas pelayanan publik dan kesejahteraan masyarakat.'
  },
  {
    img: '/images/lapor.png',
    title: 'Layanan Aspirasi dan Pengaduan Online',
    description: 'Sampaikan pengaduan dan aspirasi masyarakat dengan mudah melalui layanan kami. Solusi cepat untuk menjawab kebutuhan Anda.',
    url : 'https://www.lapor.go.id/'
  },
  {
    img: '/images/sirup.png',
    title: 'Sistem Informasi Rencana Umum Pengadaan',
    description: 'Akses informasi rencana pengadaan barang dan jasa secara transparan dan akurat. Mudah digunakan untuk mendukung proses pengadaan yang efisien.'
  },
  {
    img: '/images/satudata.png',
    title: 'Satu Data Indonesia',
    description: 'Platform terpadu untuk menyediakan data yang akurat, terbuka, dan terintegrasi sebagai acuan pengambilan keputusan. Mendukung transparansi dan pembangunan berbasis data.'
  },
  {
    img: '/images/gratifikasi.png',
    title: 'Mekanisme Pelaporan Gratifikasi',
    description: 'Gratifikasi wajib dilaporkan kepada UPG Kabupaten Madiun paling lambat 7 (tujuh) hari sejak terjadinya peristiwa gratifikasi tersebut.'
  },
  {
    img: '/images/lpse.png',
    title: 'Layanan Pengadaan Secara Elektronik',
    description: 'Permudah proses pengadaan barang dan jasa melalui sistem elektronik yang transparan, efisien, dan akuntabel. Mendukung tata kelola yang lebih baik.'
  },
  {
    img: '/images/opdmadiun.png',
    title: 'Organisasi Perangkat Daerah Kabupaten Madiun',
    description: 'Informasi lengkap tentang struktur, tugas, dan layanan dari Organisasi Perangkat Daerah Kabupaten Madiun untuk mendukung pelayanan publik yang optimal.'
  }
];

const itemsPerSlide = 3; 
const currentSlide = ref(0);

// Hitung total slide berdasarkan jumlah layanan
const totalSlides = computed(() => Math.ceil(layananList.length / itemsPerSlide));

// Data yang ditampilkan per slide
const currentSlideData = computed(() =>
  layananList.slice(currentSlide.value * itemsPerSlide, (currentSlide.value + 1) * itemsPerSlide)
);

// Fungsi geser ke slide sebelumnya
const prevSlide = () => {
  if (currentSlide.value > 0) {
    currentSlide.value--;
  }
};

// Fungsi geser ke slide berikutnya
const nextSlide = () => {
  if (currentSlide.value < totalSlides.value - 1) {
    currentSlide.value++;
  }
};

// Fungsi pindah ke slide tertentu
const goToSlide = (index) => {
  currentSlide.value = index;
};
</script>

<style>
/* Hilangkan scrollbar */
.scrollbar-hide::-webkit-scrollbar {
  display: none;
}
.scrollbar-hide {
  -ms-overflow-style: none;
  scrollbar-width: none;
}

.card-container {
  border-radius: 0; 
  border-bottom-left-radius: 12px; 
  border-bottom-right-radius: 12px; 
}
</style>
