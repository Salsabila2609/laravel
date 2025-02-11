<template>
  <div class="w-full py-8 bg-gray-100">
    <!-- Judul dan Garis dengan Icon di Tengah -->
    <h2 class="text-4xl font-bold text-center text-[#396C6D]">Layanan Masyarakat</h2>
    <div class="flex items-center justify-center my-2">
      <div class="w-24 h-1 bg-[#D4A017]"></div>
      <img src="/image/madiun_silat.png" alt="Silat Icon" class="w-12 h-12 mx-2">
      <div class="w-24 h-1 bg-[#D4A017]"></div>
    </div>

<!-- Kontainer Card -->
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-10 px-8 max-w-8xl mx-auto">
  <a
    v-for="(layanan, index) in layananList"
    :key="index"
    :href="layanan.url"
    target="_blank"
    rel="noopener noreferrer"
    class="bg-white shadow-lg hover:shadow-xl transition-transform duration-300 ease-in-out transform hover:scale-105 flex flex-col items-center text-center rounded-lg overflow-hidden relative w-full h-96 p-6">
    
    <!-- Logo dengan latar putih -->
    <div class="bg-gray-100 p-6 w-full h-64 flex justify-center items-center">
      <img :src="layanan.img" alt="Icon" class="object-contain"
      :style="{ width: layanan.width, height: layanan.height }" />
    </div>

    <!-- Judul dengan latar hijau full kanan-kiri & bawah -->
    <div class="bg-[#396C6D] w-full absolute bottom-0 left-0 py-5 flex justify-center items-center min-h-[70px]">
      <h3 class="text-xl font-semibold text-white px-6">{{ layanan.title }}</h3>
    </div>
  </a>
</div>
  </div>
</template>

<script setup>
const layananList = [
  { img: '/images/jdih.png', title: 'Jaringan Dokumentasi dan Informasi Hukum', url: 'https://jdih.go.id/', width: '210px', height: '300px' },
  { img: '/images/kpk.png', title: 'KPK Whistleblower’s System', url: 'https://kws.kpk.go.id/', width: '600px', height: '600px' },
  { img: '/images/cettar-jatim.png', title: 'Cettar Jatim', url: 'https://jatimprov.go.id/', width: '400px', height: '600px' },
  { img: '/images/lapor.png', title: 'Layanan Aspirasi dan Pengaduan Online', url: 'https://www.lapor.go.id/', width: '220px', height: '450px' },
  { img: '/images/sirup.png', title: 'Sistem Informasi Rencana Umum Pengadaan', url: 'https://sirup.lkpp.go.id/', width: '300px', height: '700px' },
  { img: '/images/satudata.png', title: 'Satu Data Indonesia', url: 'https://data.go.id/', width: '240px', height: '500px' },
  { img: '/images/gratifikasi.png', title: 'Mekanisme Pelaporan Gratifikasi', url: 'https://www.kpk.go.id/id/layanan-publik/gratifikasi', width: '500px', height: '500px' },
  { img: '/images/lpse.png', title: 'Layanan Pengadaan Secara Elektronik', url: 'https://lpse.lkpp.go.id/', width: '500px', height: '360px' },
  { img: '/images/opdmadiun.png', title: 'OPD Kabupaten Madiun', url: 'https://madiunkab.go.id/', width: '340px', height: '400px' }
];
</script>
