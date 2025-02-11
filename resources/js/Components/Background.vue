<template>
    <div class="absolute w-full h-screen z-[-1]">
      <!-- Background Hijau -->
      <div :style="{ backgroundImage: 'url(' + greenBgUrl + ')' }" class="h-2/5 bg-cover bg-center"></div>
    </div>
  </template>
  
  <script>
  export default {
    name: 'Background',
    data() {
      return {
        // Memanggil gambar latar belakang hijau dari folder 'public/image'
        greenBgUrl: '/image/bgn.png',
      };
    },
  };
  </script>
  
  