<template>
  <div class="w-full py-12 bg-white">
    <!-- Judul dan Garis dengan Icon di Tengah -->
    <h2 class="text-2xl sm:text-3xl font-bold text-center text-[#396C6D]">Layanan Masyarakat</h2>
   <div class="flex items-center justify-center my-4">
    <div class="w-16 sm:w-24 h-1 bg-[#D4A017] rounded"></div>
      <img src="/image/madiun_silat.png" alt="Silat Icon" class="w-12 h-12 mx-2">
      <div class="w-16 sm:w-24 h-1 bg-[#D4A017] rounded"></div>
    </div>

    <!-- Kontainer Card -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 px-6 max-w-6xl mx-auto">
      <a
        v-for="(layanan, index) in layananList"
        :key="index"
        :href="layanan.url"
        target="_blank"
        rel="noopener noreferrer"
        class="bg-white p-6 shadow-lg hover:shadow-2xl transition transform hover:scale-105 flex flex-col items-center text-center rounded-lg cursor-pointer">
        <!-- Logo di Atas -->
        <img :src="layanan.img" alt="Icon" class="h-16 w-26 object-cover mb-1" />

        <!-- Judul -->
        <h3 class="text-1xl font-semibold text-gray-700 mb-1">{{ layanan.title }}</h3>
      </a>
    </div>
  </div>
</template>

<script setup>
const layananList = [
{
  img: '/images/jdih.png',
  title: 'Jaringan Dokumentasi dan Informasi Hukum',
  url: 'https://jdih.go.id/'
},
{
  img: '/images/kpk.png',
  title: 'KPK Whistleblower’s System',
  url: 'https://kws.kpk.go.id/'
},
{
  img: '/images/cettar-jatim.png',
  title: 'Cettar Jatim',
  url: 'https://jatimprov.go.id/'
},
{
  img: '/images/lapor.png',
  title: 'Layanan Aspirasi dan Pengaduan Online',
  url: 'https://www.lapor.go.id/'
},
{
  img: '/images/sirup.png',
  title: 'Sistem Informasi Rencana Umum Pengadaan',
  url: 'https://sirup.lkpp.go.id/'
},
{
  img: '/images/satudata.png',
  title: 'Satu Data Indonesia',
  url: 'https://data.go.id/'
},
{
  img: '/images/gratifikasi.png',
  title: 'Mekanisme Pelaporan Gratifikasi',
  url: 'https://www.kpk.go.id/id/layanan-publik/gratifikasi'
},
{
  img: '/images/lpse.jpg',
  title: 'Layanan Pengadaan Secara Elektronik',
  url: 'https://lpse.lkpp.go.id/'
},
{
  img: '/images/opdmadiun.png',
  title: 'Organisasi Perangkat Daerah Kabupaten Madiun',
  url: 'https://madiunkab.go.id/'
}
];
</script>

<style>
.card-container {
border-radius: 0; 
border-bottom-left-radius: 12px; 
border-bottom-right-radius: 12px; 
}
</style>
