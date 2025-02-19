<template>
  <Navbar />
  <div class="min-h-screen flex flex-col">
    <div class="flex-grow">
        <!-- Background Hijau dengan Breadcrumb -->
        <div :style="{ backgroundImage: 'url(' + greenBgUrl + ')' }" class="h-1/4 bg-cover bg-center relative">
        <div class="text-sm text-[#99CBC0] font-bold py-4 px-6 lg:ml-20 md:ml-10 sm:ml-5 ml-2">
          <Link href="/" class="text-[#D4A017] no-underline">Beranda</Link> > Daftar Nama Kecamatan Se-kabupaten
        </div>
          <div class="relative z-10 px-5 py-10 text-gray-800">
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-center text-[#D4A017] mb-10">KONTAK</h1>
          </div>
        </div>

        <!-- Background Putih -->
        <div class="h-3/4 bg-white flex justify-center mt-10">
          <!-- Card dengan lebar 90% dari viewport -->
          <div class="bg-[#F9F6EE] shadow-lg rounded-xl p-4 sm:p-6 w-[90vw] max-w-[1200px] border border-gray-200">
            <div class="flex justify-center items-center mb-4">
              <!-- SVG Icon -->
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#396C6D" class="w-6 h-6 sm:w-8 sm:h-8 mr-2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" />
              </svg>

              <!-- Teks Hubungi Kami -->
              <h2 class="text-xl sm:text-2xl font-bold text-gray-800 text-center">Hubungi Kami</h2>
            </div>

            <hr class="border-t-1 border-black mb-4">
            <p class="text-sm sm:text-base text-gray-600 mb-2 text-center">Jika ada pertanyaan, kritik, saran, atau informasi lainnya, silahkan hubungi kami.</p>

            <!-- Tabel 2 Kolom -->
            <div class="flex flex-col lg:flex-row gap-4 mt-10 text-gray-800 mb-4">
              <div class="w-full lg:w-1/2 p-2">
                <div class="text-center font-semibold text-sm sm:text-base">Dinas Komunikasi dan Informatika Kabupaten Madiun</div>
                <hr class="border-t-1 border-gray-800 mt-6">
                <div class="flex items-center justify-left mt-2 ml-5">
                  <!-- Icon untuk Alamat -->
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 sm:w-5 sm:h-5 text-gray-800 mr-2">
                    <path fill-rule="evenodd" d="m11.54 22.351.07.04.028.016a.76.76 0 0 0 .723 0l.028-.015.071-.041a16.975 16.975 0 0 0 1.144-.742 19.58 19.58 0 0 0 2.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 0 0-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 0 0 2.682 2.282 16.975 16.975 0 0 0 1.145.742ZM12 13.5a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" clip-rule="evenodd" />
                  </svg>
                  Jl. Mastrip No.23 Kelurahan Klegen Kecamatan Kartoharjo Kota Madiun
                </div>

                <div class="flex items-center justify-left mt-2 ml-5">
                  <!-- Icon untuk Email -->
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 sm:w-5 sm:h-5 text-gray-800 mr-2">
                    <path fill-rule="evenodd" d="M19 5H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2zM5 7h14a1 1 0 0 1 1 1v1.293l-7 5.298-7-5.298V8a1 1 0 0 1 1-1zM5 9.707l7 5.298 7-5.298V17H5V9.707z" clip-rule="evenodd" />
                  </svg>
                  kominfo@madiunkab.go.id
                </div>
              </div>

              <div class="w-full lg:w-1/2 p-2">
                <div class="text-center font-semibold text-sm sm:text-base">Bagian Humas dan Protokol Kabupaten Madiun</div>
                <hr class="border-t-1 border-gray-800 mt-6">
                <div class="flex items-center justify-left mt-2 ml-5">
                  <!-- Icon untuk Alamat -->
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 sm:w-5 sm:h-5 text-gray-800 mr-2">
                    <path fill-rule="evenodd" d="m11.54 22.351.07.04.028.016a.76.76 0 0 0 .723 0l.028-.015.071-.041a16.975 16.975 0 0 0 1.144-.742 19.58 19.58 0 0 0 2.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 0 0-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 0 0 2.682 2.282 16.975 16.975 0 0 0 1.145.742ZM12 13.5a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" clip-rule="evenodd" />
                  </svg>
                  Jalan Alun – alun Timur No. 1 Mejayan
                </div>

                <div class="flex items-center justify-left mt-2 ml-5">
                  <!-- Icon untuk Email -->
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 sm:w-5 sm:h-5 text-gray-800 mr-2">
                    <path fill-rule="evenodd" d="M19 5H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2zM5 7h14a1 1 0 0 1 1 1v1.293l-7 5.298-7-5.298V8a1 1 0 0 1 1-1zM5 9.707l7 5.298 7-5.298V17H5V9.707z" clip-rule="evenodd" />
                  </svg>
                  humas@madiunkab.go.id
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <Footer />
  
</template>

<script>
import { Link } from '@inertiajs/vue3';
import Navbar from '@/Components/Layout/Navbar.vue';
import Footer from '@/Components/Layout/Footer.vue';
export default {
  name: 'Background',
  components: {
    Link,
    Navbar,
    Footer,
  },
  data() {
    return {
      greenBgUrl: '/image/bgn.png',
    };
  },
};
</script>