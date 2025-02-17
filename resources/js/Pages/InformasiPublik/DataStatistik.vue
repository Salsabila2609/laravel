<template>
  <div>
    <Navbar />
    <Background />


    <div class="text-sm text-[#99CBC0] font-bold py-4 px-6 lg:ml-20 md:ml-10 sm:ml-5 ml-2">
      <Link href="/" class="text-[#D4A017] no-underline">Beranda</Link>
      <span class="md:inline"> > </span>
      <span>Data Statistik</span>
    </div>


    <!-- Main Content Container -->
    <div class="relative z-10 px-3 sm:px-5 py-6 sm:py-10 text-gray-800">
      <!-- Responsive Header -->
      <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-center text-[#D4A017]">
        Data Statistik
      </h1>
      <p class="text-base sm:text-lg md:text-xl text-center text-white mt-3 sm:mt-4 mb-5 sm:mb-7 mx-auto px-4 sm:max-w-[85%]">
        Berikut adalah daftar Data Statistik yang tersedia untuk diunduh.
      </p>

      <!-- Responsive Search and Page Size Container -->
      <div class="w-full sm:w-[85%] md:w-[72%] mx-auto flex items-center justify-between mb-4 sm:mb-6 gap-3 sm:gap-4 px-4 sm:px-0">
        <!-- Page Size Selector -->
        <div class="flex items-center gap-2 shrink-0">
          <label for="pageSize" class="text-white text-sm sm:text-base whitespace-nowrap">Show items:</label>
          <select
            id="pageSize"
            v-model="itemsPerPage"
            @change="handlePageSizeChange"
            class="w-16 sm:w-24 px-2 py-2 text-sm sm:text-base rounded-full border border-gray-300 focus:border-[#396C6D] focus:ring focus:ring-[#396C6D] focus:ring-opacity-50 text-gray-500 cursor-pointer hover:border-[#396C6D] transition duration-200"
          >
            <option v-for="size in pageSizeOptions" :key="size" :value="size">
              {{ size }}
            </option>
          </select>
        </div>

        <!-- Search Input -->
        <div class="flex w-full max-w-[250px] sm:w-80 relative">
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Cari dokumen..."
            class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border rounded-full focus:outline-none focus:ring-2 focus:ring-[#396C6D] border-[#396C6D]"
          />
          <div class="absolute right-1 top-1/2 transform -translate-y-1/2">
            <svg 
              xmlns="http://www.w3.org/2000/svg" 
              viewBox="0 0 24 24" 
              fill="#396C6D" 
              class="size-6 sm:size-9 cursor-pointer"
              @click="searchNews"
            >
              <path d="M8.25 10.875a2.625 2.625 0 1 1 5.25 0 2.625 2.625 0 0 1-5.25 0Z" />
              <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm-1.125 4.5a4.125 4.125 0 1 0 2.338 7.524l2.007 2.006a.75.75 0 1 0 1.06-1.06l-2.006-2.007a4.125 4.125 0 0 0-3.399-6.463Z" clip-rule="evenodd" />
            </svg>
          </div>
        </div>
      </div>

      <!-- Responsive Table -->
      <Card class="px-3 sm:px-4">
        <div class="overflow-x-auto">
          <table class="w-full border-collapse min-w-[600px]">
            <thead>
              <tr>
                <th class="py-2 sm:py-3 px-2 sm:px-4 border-b text-center text-xs sm:text-sm">No.</th>
                <th class="py-2 sm:py-3 px-2 sm:px-4 border-b text-center text-xs sm:text-sm">Nama Dokumen</th>
                <th class="py-2 sm:py-3 px-2 sm:px-4 border-b text-center text-xs sm:text-sm">Tanggal Upload</th>
                <th class="py-2 sm:py-3 px-2 sm:px-4 border-b text-center text-xs sm:text-sm">Jumlah Unduhan</th>
                <th class="py-2 sm:py-3 px-2 sm:px-4 border-b text-center text-xs sm:text-sm">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(doc, index) in paginatedDocuments" :key="doc.id" class="hover:bg-gray-100">
                <td class="py-2 sm:py-3 px-2 sm:px-4 border-t border-b text-center text-xs sm:text-sm">
                  {{ (currentPage - 1) * itemsPerPage + index + 1 }}
                </td>
                <td class="py-2 sm:py-3 px-2 sm:px-4 border-t border-b text-xs sm:text-sm">
                  <div class="flex items-center">
                    <img src="image/File.png" alt="File Icon" class="w-6 h-6 sm:w-10 sm:h-10 mr-2">
                    <span class="break-words">{{ doc.document_name }}</span>
                  </div>
                </td>
                <td class="py-2 sm:py-3 px-2 sm:px-4 border-t border-b text-center text-xs sm:text-sm">
                  {{ doc.upload_date }}
                </td>
                <td class="py-2 sm:py-3 px-2 sm:px-4 border-t border-b text-center text-xs sm:text-sm">
                  {{ doc.download_count }}
                </td>
                <td class="py-2 sm:py-3 px-2 sm:px-4 border-t border-b text-center">
                  <NewsButton 
                    :href="`/unduh/${doc.id}`"
                    class="text-xs sm:text-sm px-2 sm:px-4 py-1 sm:py-2"
                  >
                    Download
                  </NewsButton>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </Card>

      <!-- Responsive Pagination -->
      <div class="mt-4 sm:mt-6">
        <PaginationComponent
          :current-page="currentPage"
          :total-pages="totalPages"
          @page-change="changePage"
          class="scale-90 sm:scale-100"
        />
      </div>

    </div>

    <Footer />
  </div>
</template>

<script>
import Navbar from '@/Components/Layout/Navbar.vue';
import Footer from '@/Components/Layout/Footer.vue';
import NewsButton from '@/Components/NewsButton.vue';
import Background from '@/Components/Background.vue';
import Card from '@/Components/Card.vue';
import PaginationComponent from '@/Components/Pagination.vue';
import { Link } from '@inertiajs/vue3';

export default {
  components: {
    Navbar,
    Footer,
    Background,
    Card,
    Link,
    NewsButton,
    PaginationComponent,
  },
  props: {
    documents: Array,
  },
  data() {
    return {
      searchQuery: '',
      currentPage: 1,
      itemsPerPage: 10,
      pageSizeOptions: [10, 20, 25, 50, 100],

    };
  },
  computed: {
    filteredDocuments() {
      return this.documents.filter(doc =>
        doc.category === "Data Statistik" &&
        doc.document_name.toLowerCase().includes(this.searchQuery.toLowerCase())
      );
    },
    paginatedDocuments() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      return this.filteredDocuments.slice(start, start + this.itemsPerPage);
    },
    totalPages() {
      return Math.ceil(this.filteredDocuments.length / this.itemsPerPage);
    },
  },
  methods: {
    changePage(page) {
      if (page >= 1 && page <= this.totalPages) {
        this.currentPage = page;
        window.scrollTo({ top: 0, behavior: 'smooth' });
      }
    },
    handlePageSizeChange() {

      this.currentPage = 1;
    },
  },
};
</script>

