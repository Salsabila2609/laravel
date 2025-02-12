<template>
  <footer class="bg-[#396C6D] text-white py-4">
    <div class="container mx-auto px-6 lg:px-12">
      
      <!-- Bungkus hanya untuk judul "Kontak Kami" dan garis -->
      <div class="w-full flex justify-center mb-7">
        <div class="w-[450px] text-center">
          <h2 class="text-2xl font-bold">Kontak Kami</h2>
          <div class="border-t-4 border-[#EDBC3B] mt-3 w-75 mx-auto"></div> 
        </div>
      </div>

      <!-- Flex Container untuk semua bagian sejajar tinggi -->
      <div class="flex flex-col lg:flex-row lg:justify-between lg:items-stretch gap-8">
        
        <!-- Bagian Logo Diskominfo + Sosial Media -->
        <div class="flex flex-col items-center lg:items-start lg:w-1/3 lg:self-stretch">
          <div class="flex flex-col justify-center items-center lg:items-start lg:h-full">
            <div class="bg-white shadow-lg rounded-2xl p-4 mb-4 flex justify-center items-center">
              <img :src="diskominfoImage" alt="Logo Diskominfo Kabupaten Madiun" class="w-34 h-26">
            </div>
            <h3 class="text-2xl font-semibold">Sosial Media</h3>
            <p class="text-sm mt-2 mb-4 text-center lg:text-left">
              Bergabunglah dengan sosial media kami untuk menerima berita dan informasi terbaru.
            </p>
            <div class="flex space-x-3">
              <a href="https://www.youtube.com/channel/UCv2HWvm0mF1gHJ327SMhn1Q" target="_blank" class="transition-transform transform hover:scale-110">
                <img :src="youtubeImage" alt="YouTube" class="w-8 h-8">
              </a>
              <a href="https://www.instagram.com/pemkabmadiun/" target="_blank" class="transition-transform transform hover:scale-110">
                <img :src="instagramImage" alt="Instagram" class="w-8 h-8">
              </a>
              <a href="https://twitter.com/pemkab_madiun" target="_blank" class="transition-transform transform hover:scale-110">
                <img :src="twitterImage" alt="Twitter" class="w-8 h-8">
              </a>
              <a href="https://www.facebook.com/pemkab.madiun/" target="_blank" class="transition-transform transform hover:scale-110">
                <img :src="facebookImage" alt="Facebook" class="w-8 h-8">
              </a>
            </div>
          </div>
        </div>

        <!-- Bagian Kontak Kami di tengah -->
        <div class="flex flex-col items-center lg:items-start lg:w-1/3 lg:self-stretch">
          <div class="bg-white shadow-lg p-6 w-full rounded-tl-[30px] rounded-br-[30px] h-full flex flex-col justify-center">
            <div class="grid grid-cols-1 gap-4">
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

        <!-- Bagian Map sejajar tinggi dengan card kontak -->
        <div class="flex justify-center lg:w-1/3 lg:self-stretch">
          <iframe
            class="w-full h-full rounded-tl-[30px] rounded-br-[30px] shadow-lg"
            src="https://www.google.com/maps?q=Pusat+Pemerintahan+Kabupaten+Madiun&ll=-7.540698,111.654557&z=18&output=embed"
            style="border:0; min-height: 100%;"
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade">
          </iframe>
        </div>

      </div>

      <!-- Footer Copyright -->
      <div class="mt-8 border-t border-[#EDBC3B] pt-4 text-center">
        <p class="text-sm text-gray-400">&copy; 2025 Pemerintah Kabupaten Madiun. All Rights Reserved.</p>
      </div>
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
  
  <style scoped>
  footer {
    font-family: 'Poppins', sans-serif;
  }
  </style>
