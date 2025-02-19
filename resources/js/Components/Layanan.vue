<template>
  <div class="w-full py-8 bg-gray-100">
    <!-- Judul dan Garis dengan Icon di Tengah -->
    <h2 class="text-4xl font-bold text-center text-[#396C6D]">Layanan Masyarakat</h2>
    <div class="flex items-center justify-center my-3">
      <div class="w-32 h-1 bg-[#D4A017]"></div>
      <img src="/image/madiun_silat.png" alt="Silat Icon" class="w-14 h-14 mx-1">
      <div class="w-32 h-1 bg-[#D4A017]"></div>
    </div>

    <!-- Kontainer Card -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-10 px-8 max-w-8xl mx-auto">
      <a
        v-for="layanan in layanans"
        :key="layanan.id"
        :href="layanan.url"
        target="_blank"
        rel="noopener noreferrer"
        class="bg-white shadow-lg hover:shadow-xl transition-transform duration-300 ease-in-out transform hover:scale-105 flex flex-col items-center text-center rounded-lg overflow-hidden relative w-full h-96 p-6">
        
        <!-- Logo dengan latar putih -->
        <div class="bg-gray-100 p-6 w-full h-64 flex justify-center items-center">
          <img :src="`/storage/${layanan.icon}`" :alt="layanan.title" class="object-contain w-54 h-44" />
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
import { defineProps } from "vue";

defineProps({ layanans: Array });
</script>
