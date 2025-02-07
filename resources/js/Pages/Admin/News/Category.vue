<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { Link, usePage } from '@inertiajs/vue3';

const route = useRoute();
const { props } = usePage();
const newsItems = ref(props.newsItems);
const allNews = ref(props.newsItems); 
const activeCategory = ref(route.params.category); 
const searchQuery = ref(''); 

const searchNews = () => {
  activeCategory.value = 'all'; 
  newsItems.value = allNews.value.filter((news) => {
    const query = searchQuery.value.toLowerCase();
    return (
      news.judul.toLowerCase().includes(query) ||
      news.isi_berita.toLowerCase().includes(query) ||
      news.penulis.toLowerCase().includes(query)
    );
  });
};

const isActive = (category) => activeCategory.value === category;
</script>

<template>
  <div class="min-h-screen bg-gray-100">
    <nav class="bg-blue-600 text-white shadow">
      <div class="container mx-auto px-4 py-2 flex justify-between items-center">
        <Link href="/dashboard" class="text-xl font-bold hover:underline">
          Back to Dashboard
        </Link>
        <Link href="/news/create" class="text-sm bg-white text-blue-600 px-4 py-2 rounded hover:bg-gray-100">
          Create News
        </Link>
      </div>
    </nav>

    <main class="container mx-auto mt-10 p-6 bg-white shadow rounded">
      <h1 class="text-2xl font-bold text-center mb-6">News List - {{ activeCategory }}</h1>

      <!-- Search Bar -->
      <div class="flex justify-center items-center mb-4">
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Search news..."
          class="border border-gray-300 rounded-l px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600 w-2/3"
        />
        <button
          @click="searchNews"
          class="bg-blue-600 text-white px-4 py-2 rounded-r hover:bg-blue-700"
        >
          Search
        </button>
      </div>

      <!-- Kategori Carousel -->
      <div class="overflow-x-auto whitespace-nowrap py-4">
        <button
          @click="showAllNews"
          :class="['inline-block mx-2 px-4 py-2 rounded shadow', isActive('all') ? 'bg-blue-600 text-white' : 'bg-blue-100 text-blue-600', 'hover:bg-blue-200']"
        >
          Semua Berita ({{ props.totalNewsCount }})
        </button>

        <!-- Kategori Lainnya -->
        <button
          v-for="category in props.categoriesWithCount"
          :key="category.category"
          @click="filterByCategory(category.category)"
          :class="['inline-block mx-2 px-4 py-2 rounded shadow', isActive(category.category) ? 'bg-blue-600 text-white' : 'bg-blue-100 text-blue-600', 'hover:bg-blue-200']"
        >
          {{ category.category }} ({{ category.count }})
        </button>
      </div>

      <!-- News List -->
      <div v-if="newsItems.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="news in newsItems"
          :key="news.id"
          class="border border-gray-200 rounded-lg overflow-hidden shadow"
        >
          <!-- News Image -->
          <img
            v-if="news.gambar_utama"
            :src="`/storage/${news.gambar_utama}`"
            alt="News Image"
            class="w-full h-48 object-cover"
          />
          <div class="p-4">
            <!-- News Title -->
            <h2 class="font-bold text-lg truncate">{{ news.judul }}</h2>
            <!-- News Author -->
            <p class="text-sm text-gray-600">By: {{ news.penulis }}</p>
            <!-- News Date -->
            <p class="text-sm text-gray-500">Published on: {{ news.tanggal_terbit }}</p>
            <!-- News Categories -->
            <div class="mt-2 flex flex-wrap gap-2">
              <span
                v-for="kategori in news.kategori"
                :key="kategori"
                class="text-xs bg-blue-100 text-blue-600 px-2 py-1 rounded"
              >
                {{ kategori }}
              </span>
            </div>
            <!-- News Excerpt -->
            <p class="mt-2 text-gray-700 text-sm line-clamp-2" v-html="news.isi_berita"></p>
            <!-- View Details -->
            <Link
              :href="`/news/${news.id}`"
              class="block mt-4 text-blue-600 text-sm hover:underline"
            >
              Read More
            </Link>
          </div>
        </div>
      </div>

      <p v-else class="text-center text-gray-600">No news available.</p>
    </main>
  </div>
</template>
