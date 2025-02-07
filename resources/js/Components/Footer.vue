<template>
    <footer class="bg-[#396C6D] text-white py-6">
     <div class="container mx-auto px-6 lg:px-12">
         <div class="flex flex-col lg:flex-col justify-center items-center">
             <!-- Kontak Kami -->
             <div class="lg:w-3/4 mb-6 lg:mb-0">
                 <!-- Judul Kontak Kami dan Garis -->
                 <h2 class="text-2xl font-bold text-white text-center mb-2">Kontak Kami</h2>
                 <hr class="border-t-2 border-[#EDBC3B] mb-3 mx-auto w-1/2">

                 <!-- Card Kontak Kami -->
                 <div class="bg-white shadow-lg p-4 w-3/4 mx-auto rounded-tl-[20px] rounded-br-[20px] rounded-tr-none rounded-bl-none">
                     <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                         <!-- Kolom Kiri -->
                         <div class="flex items-start">
                             <span class="mr-3 text-xl text-teal-600">📍</span>
                             <p class="text-gray-700 text-sm">
                                 Jl. Mastrip No.23 Kel. Klegen Kec. Kartoharjo Kota Madiun – Jawa Timur 63117, Indonesia
                             </p>
                         </div>
                         <div class="flex items-start">
                             <span class="mr-3 text-xl text-teal-600">📠</span>
                             <p class="text-gray-700 text-sm">Fax. 0351-4487016, 4487017</p>
                         </div>

                         <!-- Kolom Kanan -->
                         <div class="flex items-start">
                             <span class="mr-3 text-xl text-teal-600">📞</span>
                             <p class="text-gray-700 text-sm">0351-462927</p>
                         </div>
                         <div class="flex items-start">
                             <span class="mr-3 text-xl text-teal-600">✉️</span>
                             <p class="text-gray-700 text-sm">helpdesk@madiunkab.go.id</p>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </div>
     <!-- Sosial Media dan Map -->
     <div class="lg:w-full flex flex-col lg:flex-row justify-between items-center mt-8 space-y-8 lg:space-y-0">
         <!-- Sosial Media -->
         <div class="lg:w-1/2 flex flex-col items-start text-left ml-48">
             <!-- Logo Diskominfo -->
             <img :src="diskominfoImage" alt="Logo Diskominfo Kabupaten Madiun" class="w-25 h-20 mb-2">

             <!-- Kalimat Sosial Media -->
             <h3 class="text-2xl font-semibold">Sosial Media</h3>
             <p class="text-sm mt-2 mb-4">Bergabunglah dengan sosial media kami untuk menerima berita dan informasi terbaru.</p>

             <!-- Ikon Sosial Media -->
             <div class="flex justify-start space-x-3">
                 <a href="https://www.youtube.com/channel/UCv2HWvm0mF1gHJ327SMhn1Q" target="_blank" class="text-3xl">
                     <img :src="youtubeImage" alt="YouTube" class="w-8 h-8 mx-1">
                 </a>
                 <a href="https://www.instagram.com/pemkabmadiun/" target="_blank" class="text-3xl">
                     <img :src="instagramImage" alt="Instagram" class="w-8 h-8 mx-1">
                 </a>
                 <a href="https://twitter.com/pemkab_madiun" target="_blank" class="text-3xl">
                     <img :src="twitterImage" alt="Twitter" class="w-8 h-8 mx-1">
                 </a>
                 <a href="https://www.facebook.com/pemkab.madiun/" target="_blank" class="text-3xl">
                     <img :src="facebookImage" alt="Facebook" class="w-8 h-8 mx-1">
                 </a>
             </div>
         </div>

         <!-- Map -->
         <div class="flex justify-center lg:w-1/2 mr-32">
             <iframe
                 class="w-3/4 h-[200px] rounded-tl-lg rounded-br-lg border-tl-none border-br-none border-tr-[25px] border-bl-[25px] shadow-lg"
                 src="https://www.google.com/maps?q=Pusat+Pemerintahan+Kabupaten+Madiun&ll=-7.540698,111.654557&z=18&output=embed"
                 width="400" 
                 height="200" 
                 style="border:0;"
                 allowfullscreen=""
                 loading="lazy"
                 referrerpolicy="no-referrer-when-downgrade">
             </iframe>
         </div>
     </div>
     <div class="mt-8 border-t border-[#EDBC3B] w-3/4 mx-auto pt-1">
       <p class="text-sm text-gray-400 text-center">&copy; 2025 Pemerintah Kabupaten Madiun. All Rights Reserved.</p>
     </div>
 </footer>
</template>

<script>
import diskominfoImage from '../../../public/images/diskominfo.png';
import youtubeImage from '../../../public/images/youtube.png';
import instagramImage from '../../../public/images/instagram.png';
import twitterImage from '../../../public/images/twitter.png';
import facebookImage from '../../../public/images/facebook.png';

export default {
  data() {
    return {
      diskominfoImage,
      youtubeImage,
      instagramImage,
      twitterImage,
      facebookImage,
    };
  },
};
</script>
