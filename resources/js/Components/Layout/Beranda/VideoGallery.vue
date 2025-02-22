<template>
  <div class="p-4 mb-5">
    <div class="flex items-center space-x-2">
      <h1 class="text-2xl font-bold text-teal-700 ml-5 whitespace-nowrap">Berita Video</h1>
      <div class="flex-grow">
        <div class="h-1 bg-[#D4A017] ml-5 mr-5 rounded"></div>
      </div>
    </div>

    <!-- Video Slider Container -->
    <div class="overflow-hidden relative">
      <div
        class="flex gap-4 transition-transform duration-300"
        :style="{ transform: `translateX(-${currentPage * 100}%)` }"
      >
        <!-- Wrapper untuk setiap slide -->
        <div
          v-for="(chunk, index) in videoChunks"
          :key="index"
          class="flex w-full gap-4 flex-none"
        >
          <!-- Video Card -->
          <div
            v-for="video in chunk"
            :key="video.id.videoId"
            class="w-1/2 bg-white shadow rounded-lg relative overflow-hidden group"
          >
            <a
              :href="getSafeYouTubeUrl(video.id.videoId)"
              target="_blank"
              rel="noopener noreferrer"
              class="block"
            >
              <!-- Gambar dengan overlay gelap -->
              <div class="relative w-full h-48">
                <img
                  :src="getSafeThumbnailUrl(video.snippet.thumbnails.medium.url)"
                  :alt="sanitizeText(video.snippet.title)"
                  class="w-full h-full object-cover transition-transform duration-300"
                />
                <!-- Overlay gelap -->
                <div class="absolute inset-0 bg-black opacity-40 transition-opacity duration-300 group-hover:opacity-20"></div>
                <!-- Judul di bawah gambar -->
                <div
                  class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black text-white p-2 transition-transform duration-300 group-hover:translate-y-10 group-hover:opacity-0"
                >
                  <h2 class="text-lg font-semibold">{{ sanitizeText(video.snippet.title) }}</h2>
                </div>
                <!-- Tampilan Viewers -->
                <div
                  v-if="video.statistics"
                  class="absolute top-2 right-2 bg-black text-white px-2 py-1 rounded-md opacity-80"
                >
                  <span>{{ formatViews(video.statistics.viewCount) }} Views</span>
                </div>
              </div>
            </a>
          </div>
        </div>
      </div>

      <!-- Navigation Arrows -->
      <button
        class="absolute top-1/2 -translate-y-1/2 left-4 bg-gray-100 p-2 rounded-full shadow-md hover:bg-gray-300 z-10"
        :disabled="currentPage === 0"
        @click="prevPage"
      >
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
        </svg>
      </button>
      <button
        class="absolute top-1/2 -translate-y-1/2 right-4 bg-gray-100 p-2 rounded-full shadow-md hover:bg-gray-300 z-10"
        :disabled="currentPage === maxPage"
        @click="nextPage"
      >
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20" stroke-width="2" stroke="currentColor" class="w-6 h-6">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
        </svg>
      </button>
    </div>

    <!-- Pagination Dots -->
    <div class="flex justify-center mt-4 gap-2">
      <span
        v-for="n in maxPage + 1"
        :key="n"
        class="w-3 h-3 rounded-full bg-gray-300 cursor-pointer"
        :class="{ 'bg-gray-600': currentPage === n - 1 }"
        @click="currentPage = n - 1"
      ></span>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, onBeforeUnmount } from "vue";
import DOMPurify from 'dompurify'; // Library untuk sanitasi HTML

export default {
  name: "VideoGallery",
  setup() {
    const videos = ref([]); // Menyimpan data video
    const currentPage = ref(0); // Indeks halaman saat ini
    const maxPage = ref(0); // Indeks maksimum halaman
    const videoChunks = ref([]); // Menyimpan video dalam grup
    let autoUpdateInterval = null; // Untuk menyimpan ID interval

    // Fungsi untuk mengambil video terbaru dan statistik
    const fetchVideos = async () => {
      const apiKey = ""; 
      const channelId = "UCv2HWvm0mF1gHJ327SMhn1Q";
      const url = `https://www.googleapis.com/youtube/v3/search?key=${apiKey}&channelId=${channelId}&order=date&part=snippet&type=video&maxResults=6`;

      try {
        const response = await fetch(url);
        if (!response.ok) {
          throw new Error(`HTTP error! Status: ${response.status}`);
        }
        const data = await response.json();
        videos.value = await Promise.all(data.items.map(async (item) => {
          // Mengambil statistik (jumlah view)
          const statsResponse = await fetch(`https://www.googleapis.com/youtube/v3/videos?key=${apiKey}&id=${item.id.videoId}&part=statistics`);
          const statsData = await statsResponse.json();
          const statistics = statsData.items[0].statistics;
          return { ...item, statistics };
        }));
        videoChunks.value = chunkArray(videos.value, 2); // Grupkan video
        maxPage.value = videoChunks.value.length - 1; // Hitung jumlah halaman
      } catch (error) {
        console.error("Error fetching videos:", error);
      }
    };

    // Fungsi untuk membagi array menjadi grup
    const chunkArray = (array, size) => {
      const result = [];
      for (let i = 0; i < array.length; i += size) {
        result.push(array.slice(i, i + size));
      }
      return result;
    };

    // Format jumlah viewers
    const formatViews = (views) => {
      return new Intl.NumberFormat().format(views);
    };

    // Navigasi ke halaman sebelumnya
    const prevPage = () => {
      if (currentPage.value > 0) currentPage.value--;
    };

    // Navigasi ke halaman berikutnya
    const nextPage = () => {
      if (currentPage.value < maxPage.value) currentPage.value++;
    };

    // Mengatur interval untuk memperbarui video secara otomatis
    const startAutoUpdate = () => {
      autoUpdateInterval = setInterval(fetchVideos, 60000); // Perbarui setiap 60 detik
    };

    // Hentikan pembaruan otomatis (jika diperlukan)
    const stopAutoUpdate = () => {
      if (autoUpdateInterval) {
        clearInterval(autoUpdateInterval);
        autoUpdateInterval = null;
      }
    };

    // Validasi URL YouTube
    const getSafeYouTubeUrl = (videoId) => {
      const youtubeUrl = `https://www.youtube.com/watch?v=${videoId}`;
      if (/^https:\/\/www\.youtube\.com\/watch\?v=[\w-]+$/.test(youtubeUrl)) {
        return youtubeUrl;
      }
      return '#';
    };

    // Sanitasi URL thumbnail
    const getSafeThumbnailUrl = (url) => {
      return DOMPurify.sanitize(url);
    };

    // Sanitasi teks
    const sanitizeText = (text) => {
      return DOMPurify.sanitize(text);
    };

    // Lifecycle hooks
    onMounted(() => {
      fetchVideos(); // Mengambil video saat komponen dimuat
      startAutoUpdate(); // Memulai pembaruan otomatis
    });

    onBeforeUnmount(() => {
      stopAutoUpdate(); // Membersihkan interval saat komponen dilepas
    });

    return {
      videos,
      videoChunks,
      currentPage,
      maxPage,
      prevPage,
      nextPage,
      formatViews,
      getSafeYouTubeUrl,
      getSafeThumbnailUrl,
      sanitizeText,
    };
  },
};
</script>
