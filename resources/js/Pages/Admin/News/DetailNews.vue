<script setup>
import { usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import Navbar from '@/Components/Layout/Navbar.vue';


// Ambil props dari Inertia
const { props } = usePage();
const news = props.news;
const categoriesWithCount = props.categoriesWithCount;
const relatedNews = props.related;

// Format kategori berita utama
const formattedCategories = computed(() => {
  return news.kategori?.join(', ') || '';
});

// Berita terkait
const relatedNewsFiltered = computed(() => {
  return relatedNews.filter(related =>
    related.kategori.some(kategori => news.kategori.includes(kategori))
  );
});

// State untuk modal
const isModalOpen = ref(false);
const modalImage = ref('');
const modalCaption = ref('');

// Fungsi membuka modal
const openModal = (image, caption) => {
  modalImage.value = `/storage/${image}`;
  modalCaption.value = caption;
  isModalOpen.value = true;
};

// Fungsi menutup modal
const closeModal = () => {
  isModalOpen.value = false;
};

</script>

<script>
export default {
  props: {
    news: Object,
  },
    methods: {
  formatContent(content) {
    const parser = new DOMParser();
    const doc = parser.parseFromString(content, 'text/html');

    // Mengatur ukuran gambar dan membuatnya center
    doc.querySelectorAll('img').forEach((img) => {
      img.style.maxWidth = '80%'; // Gambar tidak melebihi lebar container
      img.style.height = 'auto';   // Menjaga aspect ratio
      img.style.display = 'block'; // Agar elemen <img> jadi block
      img.style.margin = '0 auto'; // Membuat gambar berada di tengah
      img.style.borderRadius = '12px'; // Membuat gambar rounded
      img.classList.add('responsive-image'); // Menambahkan class untuk styling lebih lanjut
    });

    // Membuat semua iframe dan video menjadi responsif dan center, serta rounded
      doc.querySelectorAll('iframe, video').forEach((element) => {
        const wrapper = document.createElement('div');
        wrapper.classList.add('video-container');
        element.style.borderRadius = '12px';
        element.style.overflow = 'hidden';
        element.parentNode.insertBefore(wrapper, element);
        wrapper.appendChild(element);
      });


    return doc.body.innerHTML;
  }
}

}
</script>


<template>
  <Navbar />

  <div class="min-h-screen bg-[#396C6D]">
    <div class="text-sm text-[#99cbc0] font-bold p-5">
  <Link href="/dashboard" class="text-[#D4A017] no-underline">Beranda > </Link>
  <Link href="/news" class="text-[#D4A017] no-underline">Berita</Link> > 
  Detail Berita
</div>

    <main class="container mx-auto mt-10 p-4 md:p-6 bg-white shadow rounded flex flex-col md:flex-row gap-6">
      <!-- Konten Utama (Kiri) -->
      <div class="flex-1 flex flex-col">
        <!-- Card Cream -->
        <div class="w-full bg-[#F9F6EE] p-4 rounded-2xl mb-4">
          <h1 class="text-2xl md:text-3xl font-bold mb-2 text-gray-800">{{ news.judul }}</h1>

          <!-- Kategori dan Info -->
          <div class="flex flex-col md:flex-row justify-between items-center text-gray-600 text-sm">
            <!-- Kategori (Kiri) -->
            <div class="flex items-center mb-2 md:mb-0">
              <div class="bg-[#EBE3D8] px-2 py-1 rounded-full text-black font-semibold flex items-center">
                <span class="w-3 h-3 sm:w-4 sm:h-4 bg-yellow-500 rounded-full mr-2 flex-shrink-0"></span>
                {{ formattedCategories }}
              </div>
            </div>

            <!-- Penulis dan Tanggal (Kanan) -->
            <div class="flex items-center space-x-4">
              <!-- Penulis -->
              <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#268B79" class="size-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
                <div class="ml-2"><span>{{ news.penulis }}</span></div>
              </div>

              <!-- Tanggal -->
              <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#268B79" class="size-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                </svg>
                <div class="ml-2"><span>{{ news.tanggal_terbit }}</span></div>
              </div>
              <!-- view counter -->
              <div class="flex items-center">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#268B79" class="w-5 h-5 mr-2 text-[#396C6D]">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                  </svg>
                  <span class="font-medium">{{ news.views.toLocaleString() }}</span>
                  <span class="ml-1">views</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Gambar Utama -->
        <div class="flex flex-col mb-4 items-center">
          <img
            :src="`/storage/${news.gambar_utama}`"
            alt="Main News Image"
            class="w-4/5 h-auto rounded-2xl object-cover"
          />
          <p class="text-gray-500 text-sm mt-2 text-center">
            {{ news.gambar_utama_keterangan }}
          </p>
        </div>


        <!-- Isi Berita -->
        <div class="prose">
          <div class="text-gray-700 text-base md:text-lg leading-relaxed">
            <div ref="newsContent" v-html="formatContent(news.isi_berita)"></div>
          </div>
        </div>

        <!-- Carousel Gambar Lampiran -->
        <div v-if="news.gambar_lampiran?.length > 0" class="mt-4">
          <h3 class="text-lg font-bold mb-4 text-gray-800">Gambar Lampiran:</h3>
          <div class="flex overflow-x-scroll space-x-4">
            <div
              v-for="(lampiran, index) in news.gambar_lampiran"
              :key="index"
              class="min-w-[200px] rounded overflow-hidden shadow cursor-pointer"
              @click="openModal(lampiran, news.gambar_lampiran_keterangan[index])"
            >
              <img :src="`/storage/${lampiran}`" :alt="`Attachment Image ${index + 1}`" class="w-full h-40 object-cover" />
              <p class="text-gray-500 text-xs text-center mt-2">{{ news.gambar_lampiran_keterangan[index] }}</p>
            </div>
          </div>
        </div>

        <!-- Modal -->
        <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75" @click="closeModal">
          <div class="relative bg-white p-4 rounded shadow-lg" @click.stop>
            <img :src="modalImage" alt="Modal Image" class="max-w-full max-h-[80vh] object-contain" />
            <p class="text-gray-500 text-sm mt-2 text-center">{{ modalCaption }}</p>
            <button class="absolute top-2 right-2" @click="closeModal">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6 close-button">
                <circle cx="12" cy="12" r="10" fill="#396C6D"/>
                <path d="M10.28 9.22a.75.75 0 1 0-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 1 0 1.06 1.06L12 13.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L13.06 12l1.72-1.72a.75.75 0 1 0-1.06-1.06L12 10.94l-1.72-1.72Z" fill="white"/>
              </svg>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar (Kanan) -->
      <aside class="w-full md:w-1/4 flex flex-col space-y-4">
        <!-- Kategori Berita -->
        <div class="bg-white rounded shadow-md p-4">
          <h3 class="text-lg font-bold mb-4 text-gray-800">Kategori Berita:</h3>
          <div class="space-y-2">
            <Link
              v-for="(category, index) in categoriesWithCount"
              :key="index"
              :href="`/news/${category.category}`"
              class="block text-sm text-white px-4 py-2 mb-2 rounded-md bg-[#396C6D] hover:bg-[#99CBC0] transition"
            >
              {{ category.category }} ({{ category.count }})
            </Link>
          </div>
        </div>

        <!-- Berita Serupa -->
        <div class="bg-white rounded shadow-md p-4">
          <h3 class="text-lg font-bold mb-4 text-gray-800">Berita Serupa:</h3>
          <div v-for="berita in relatedNews" :key="berita.id" class="bg-[#F9F6EE] rounded shadow-md overflow-hidden mb-4">
            <Link :href="`/news/${berita.slug}`" class="block">
              <img :src="`/storage/${berita.gambar_utama}`" alt="Berita Image" class="w-full h-32 object-cover" />
              <div class="p-4">
                <div class="bg-[#EBE3D8] px-2 py-1 rounded-full text-black font-semibold flex items-center text-sm lg:text-s mb-2">
                  <span class="w-3 h-3 sm:w-4 sm:h-4 bg-yellow-500 rounded-full mr-2 flex-shrink-0"></span>
                  <span class="truncate">{{ berita.kategori.join(', ') }}</span>
                </div>
                <h4 class="font-bold text-gray-800 text-sm">
                  {{ berita.judul }}
                </h4>
                <div class="flex items-center justify-between flex-wrap">
                  <!-- Icon SVG untuk penulis -->
                  <div class="flex items-center sm:flex-row flex-col">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#268B79" class="w-4 h-4 mr-1 sm:mr-1 mb-1 sm:mb-0">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                    <p class="text-sm text-gray-600 mt-1 text-center sm:text-left">
                      {{ berita.penulis }} 
                    </p>
                  </div>

                  <!-- Penulis dan Tanggal dengan icon di sebelah kiri tanggal -->
                  <div class="flex items-center sm:flex-row flex-col ml-auto">
                    <!-- Icon kalender -->
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#268B79" class="w-4 h-4 ml-2 sm:ml-2 mb-1 sm:mb-0">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                    </svg>
                    <p class="text-sm text-gray-600 mt-1 text-center sm:text-left">
                      {{ berita.tanggal_terbit }}
                    </p>
                  </div>
                </div>
              </div>
            </Link>
          </div>
        </div>
      </aside>
    </main>
  </div>
</template>

<style>
ul, ol {
padding: 0 1rem;

list-style-position: outside;
}

ul {
  list-style-type: disc; 
}

ol {
  list-style-type: decimal; 
}

h1 {
  font-size: 1.4rem;
}

h2 {
  font-size: 1.2rem;
}

h3 {
  font-size: 1.1rem;
}

h4,
h5,
h6 {
  font-size: 1rem;
}

/* Code and preformatted text styles */
code {
  background-color: var(--purple-light);
  border-radius: 0.4rem;
  color: var(--black);
  font-size: 0.85rem;
  padding: 0.25em 0.3em;
}

pre {
  background: var(--black);
  border-radius: 0.5rem;
  color: var(--white);
  font-family: 'JetBrainsMono', monospace;
  margin: 1.5rem 0;
  padding: 0.75rem 1rem;
}

  code {
    background: none;
    color: inherit;
    font-size: 0.8rem;
    padding: 0;
  }

blockquote {
  border-left: 3px solid var(--gray-3);
  margin: 1.5rem 0;
  padding-left: 1rem;
}

button .close-button:hover circle {
  fill: #99CBC0; 
}

hr {
  border: none;
  border-top: 1px solid var(--gray-2);
  margin: 2rem 0;
}

.responsive-image {
 max-width: 100%;
height: auto;
}

.video-container {
  display: flex;
  justify-content: center;
  align-items: center;
  margin: 20px 0;
}

.video-container iframe,
.video-container video {
  width: 80%; 
  height: 500px; /* Atur nilai tinggi sesuai kebutuhan */
  max-height: 80vh; /* Pastikan tidak melebihi viewport */
  border-radius: 12px;
  overflow: hidden;
}

.prose a {
  color: blue;
  text-decoration: underline;
}

/* Hover state for links */
.prose a:hover {
  color: darkblue;
}
</style>
