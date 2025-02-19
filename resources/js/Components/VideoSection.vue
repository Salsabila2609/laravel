<template>
    <section class="relative w-full h-screen overflow-hidden">
      <!-- Video Background -->
      <video
        autoplay
        muted
        loop
        id="myVideo"
        :src="videoSource"
        class="absolute top-0 left-0 w-full h-full object-cover"
      ></video>
  
      <!-- Overlay Content -->
      <div class="absolute top-0 left-0 w-full h-full flex flex-col justify-center items-center bg-gradient-to-b from-black/50 to-transparent text-white text-center z-10">
      <h1 class="text-2xl font-bold">Selamat Datang di Portal Resmi</h1>
      <h2 class="text-5xl font-black mt-2">Pemerintah Kabupaten Madiun</h2>
      <!-- Search Bar -->
      <!-- <div class="flex w-full mt-6 justify-center relative">
  <div class="w-[90%] md:w-[80%] lg:w-[50rem] relative">
    <input
      v-model="searchQuery"
      type="text"
      placeholder="Cari berita..."
      class="w-full px-4 py-3 border rounded-full focus:outline-none focus:ring-2 text-lg"
      :class="{'focus:ring-[#396C6D] border-[#396C6D]': true}"
    />
    <div class="absolute right-3 top-1/2 transform -translate-y-1/2">
      <svg 
        xmlns="http://www.w3.org/2000/svg" 
        viewBox="0 0 24 24" 
        fill="#396C6D" 
        class="size-9 cursor-pointer"
        @click="searchNews"
      >
        <path d="M8.25 10.875a2.625 2.625 0 1 1 5.25 0 2.625 2.625 0 0 1-5.25 0Z" />
        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm-1.125 4.5a4.125 4.125 0 1 0 2.338 7.524l2.007 2.006a.75.75 0 1 0 1.06-1.06l-2.006-2.007a4.125 4.125 0 0 0-3.399-6.463Z" clip-rule="evenodd" />
      </svg>
    </div>
  </div>
</div> -->

    </div>
  
 </section>
  </template>
  
  <script>
  export default {
    props: {
      videoSource: {
        type: String,
        required: true,
      },
    },
  };
  </script>
  
  <style scoped>
  /* Smooth Video Rendering */
  #myVideo {
    -webkit-transform: translate3d(0, 0, 0);
    transform: translate3d(0, 0, 0);
    will-change: transform, opacity;
    object-fit: cover;
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
  }
  .video-section {
  position: relative;
  overflow: hidden;
  margin-top: 60px; /* Sesuaikan dengan tinggi navbar */
  z-index: 10; /* Pastikan tetap di bawah navbar */
}

  </style>
  