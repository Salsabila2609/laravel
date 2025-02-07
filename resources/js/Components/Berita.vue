<template>
    <div class="bg-[#396C6D] p-8">
      <!-- Judul Berita Terkini dengan Garis Kuning -->
      <div class="flex items-center mx-15 mb-4">
        <div class="text-white text-4xl font-bold">Berita Terkini</div>
        <div class="w-[82%] h-1 bg-[#D4A017] ml-5 rounded"></div>
 
      </div>
  
      <div class="grid grid-cols-3 gap-4 mx-15 my-15">
        <div v-if="mainNews" class="bg-white p-4 rounded-lg shadow-md col-span-2">
          <div>
            <h3 
              class="text-xl font-semibold mb-2 cursor-pointer hover:underline" 
              @click="goToNewsDetail(mainNews.id)"
            >
              {{ mainNews.judul }}
            </h3>
  
            <div class="flex items-center text-sm text-gray-500 mb-4">
              <div class="flex items-center mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#268B79" class="size-4 mr-1">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
                <p>{{ mainNews.penulis }}</p>
              </div>
              <div class="flex items-center mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#268B79" class="size-4 mr-1">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                </svg>
                <p>{{ mainNews.created_at }}</p>
              </div>
            </div>

            <!-- Kategori (pindah ke kanan pada tampilan mobile) -->
            <div class="flex items-center mb-2 sm:mb-0 sm:order-3 order-2">
              <div class="bg-[#EBE3D8] px-2 py-1 rounded-full text-black flex items-center">
                <span class="w-3 h-3 sm:w-4 sm:h-4 bg-yellow-500 rounded-full mr-2 flex-shrink-0"></span>
                {{ mainNews.kategori.join(', ') }}
              </div>
            </div>
          </div>

          <div class="flex flex-col sm:flex-row">
            <div class="w-full sm:w-1/2 pr-1">
              <img 
                v-if="mainNews.gambar_utama" 
                :src="mainNews.gambar_utama" 
                class="w-full h-60 object-cover rounded-md cursor-pointer" 
                @click="goToNewsDetail(mainNews.id)" 
                alt="Gambar Berita Utama" 
              />
            </div>
            <div class="w-full sm:w-1/2 pl-3 mt-3 sm:mt-0">
              <p class="text-gray-700" v-html="getShortDescription(mainNews.isi_berita)"></p>
            </div>
          </div>

          <!-- Carousel Berita -->
          <div v-if="newsCards.length" class="mt-6 relative col-span-3">
            <div class="overflow-hidden">
              <div class="flex transition-transform duration-500" :style="carouselStyle">
                <div v-for="news in newsCards" :key="news.id" class="bg-[#F9F6EE] rounded-lg shadow-md w-full sm:w-1/2 min-w-[49.4%] cursor-pointer mr-1.5 mb-1 overflow-hidden">
                  <img 
                    v-if="news.gambar_utama" 
                    :src="news.gambar_utama" 
                    class="rounded-md w-full h-32 object-cover mb-2" 
                    alt="Gambar Berita" 
                  />
                  <!-- Kategori dengan responsivitas mobile -->
                  <div class="bg-[#EBE3D8] px-2 py-1 rounded-full text-black flex items-center mb-2"> 
                    <span class="w-3 h-3 sm:w-4 sm:h-4 bg-yellow-500 rounded-full mr-2 flex-shrink-0"></span>
                    <span class="block sm:hidden">
                      {{ Array.isArray(news.kategori) ? news.kategori[0] + (news.kategori.length > 1 ? "..." : "") : news.kategori }}
                    </span>
                    <span class="hidden sm:block">
                      {{ Array.isArray(news.kategori) ? news.kategori.join(', ') : news.kategori }}
                    </span>
                  </div>

                  <!-- Judul Berita -->
                  <h3 class="text-base font-semibold mb-3 ml-4 text-[#396C6D] truncate-line">
                    {{ news.judul }}
                  </h3>

                <!-- Penulis dan Tanggal -->
                <div class="text-sm text-gray-500 mb-4 ml-4 flex sm:flex-row flex-col sm:items-center">
                  <!-- Penulis -->
                  <div class="flex items-center text-[#396C6D] mb-1 sm:mb-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#268B79" class="size-4 mr-1">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                    <p>{{ news.penulis }}</p>
                  </div>

                  <!-- Tanggal -->
                  <div class="flex items-center text-[#396C6D] sm:ml-10"> <!-- Tambah margin kiri untuk mode desktop -->
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#268B79" class="size-4 mr-1">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                    </svg>
                    <p>{{ news.created_at }}</p>
                  </div>
                </div>
                </div>
              </div>
            </div>

            <!-- Tombol Navigasi -->
            <button 
              v-if="currentIndex > 0" 
              @click="prevSlide" 
              class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-gray-800 text-white px-3 py-2 rounded-md"
            >
              ❮
            </button>
            <button 
              v-if="currentIndex < this.maxIndex" 
              @click="nextSlide" 
              class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-gray-800 text-white px-3 py-2 rounded-md"
            >
              ❯
            </button>
          </div>
        </div>
      </div>

      <!-- Widget Kominfo -->
      <div id="gpr-kominfo-widget-container" class="bg-white p-4 rounded-lg shadow-md"></div>
    </div>
</template>


<script>
export default {
  props: {
    mainNews: Object,
    newsCards: Array,
  },
  data() {
    return {
      currentIndex: 0,
      autoSlideInterval: 3000, // Interval dalam milidetik (3 detik)
      autoSlideTimer: null, // Timer untuk auto slide
    };
  },
  computed: {
  maxIndex() {
    return Math.max(0, this.newsCards.length - 2);
  },
  carouselStyle() {
    return `transform: translateX(-${this.currentIndex * 50}%)`;
  },
},
methods: {
  getShortDescription(isiBerita) {
    const maxLength = 550; // Jumlah karakter yang akan ditampilkan
    if (isiBerita.length > maxLength) {
      return isiBerita.substring(0, maxLength) + '...'; // Potong dan tambahkan '...'
    }
    return isiBerita;
  },
    prevSlide() {
      if (this.currentIndex > 0) {
        this.currentIndex--;
      }
    },
    nextSlide() {
      if (this.currentIndex < this.maxIndex) {
        this.currentIndex++;
      } else {
        this.currentIndex = 0; // Jika sudah mencapai slide terakhir, kembali ke awal
      }
    },
    startAutoSlide() {
      this.autoSlideTimer = setInterval(() => {
        this.nextSlide();
      }, this.autoSlideInterval);
    },
    stopAutoSlide() {
      if (this.autoSlideTimer) {
        clearInterval(this.autoSlideTimer);
        this.autoSlideTimer = null;
      }
    },
  },
  mounted() {
    const script = document.createElement('script');
    script.type = 'text/javascript';
    script.src = 'https://widget.kominfo.go.id/gpr-widget-kominfo.min.js';
    document.head.appendChild(script);

    this.startAutoSlide(); // Mulai auto-slide saat komponen dimuat
  },
  beforeDestroy() {
    this.stopAutoSlide(); // Bersihkan interval saat komponen dihancurkan
  },
};
</script>

<style>
  .truncate-line {
    display: -webkit-box;
    -webkit-line-clamp: 2; /* Batasi menjadi 3 baris */
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis; /* Menambahkan titik-titik */
  }
</style>