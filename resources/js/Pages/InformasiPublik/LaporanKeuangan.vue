<template>
  <div>
    <MasterLayout>
       <Background />

    <div class="text-sm text-[#99CBC0] font-bold py-4 px-6 lg:ml-20 md:ml-10 sm:ml-5 ml-2">
      <Link href="/" class="text-[#D4A017] no-underline">Beranda</Link>
      <span class="md:inline"> > </span>
      <span>Laporan Keuangan {{ selectedYear }}</span>
    </div>

    <!-- Main Content -->
    <div class="relative z-10 px-3 sm:px-5 py-6 sm:py-10 text-gray-800">
      <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-center text-[#D4A017]">
        LAPORAN KEUANGAN {{ selectedYear }}
      </h1>
      <p class="text-base sm:text-lg md:text-xl text-center text-white mt-3 sm:mt-4 mb-5 sm:mb-7 mx-auto px-4 sm:max-w-[85%]">
        Berikut adalah daftar Laporan Keuangan yang tersedia untuk diunduh.
      </p>

      <!-- Search & Year Selection -->
      <div class="w-full sm:w-[85%] md:w-[72%] mx-auto flex flex-wrap items-center justify-between mb-4 sm:mb-6 gap-3 sm:gap-4 px-4 sm:px-0">
        <!-- Pilih Tahun -->
        <div class="flex items-center gap-2">
          <label for="year" class="text-white text-sm sm:text-base">Pilih Tahun:</label>
          <select
            id="year"
            v-model="selectedYear"
            class="w-24 px-2 py-2 text-sm rounded-full border border-gray-300 focus:border-[#396C6D] focus:ring-[#396C6D] text-gray-500 cursor-pointer transition duration-200"
          >
            <option v-for="year in availableYears" :key="year" :value="year">
              {{ year }}
            </option>
          </select>
        </div>

        <!-- Search Input -->
        <div class="flex w-full max-w-[250px] sm:w-80 relative">
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Cari dokumen..."
            class="w-full px-3 sm:px-4 py-2 text-sm border rounded-full focus:ring-2 focus:ring-[#396C6D] border-[#396C6D]"
          />
          <div class="absolute right-1 top-1/2 transform -translate-y-1/2">
            <svg 
              xmlns="http://www.w3.org/2000/svg" 
              viewBox="0 0 24 24" 
              fill="#396C6D" 
              class="size-6 sm:size-9 cursor-pointer"
            >
              <path d="M8.25 10.875a2.625 2.625 0 1 1 5.25 0 2.625 2.625 0 0 1-5.25 0Z" />
              <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm-1.125 4.5a4.125 4.125 0 1 0 2.338 7.524l2.007 2.006a.75.75 0 1 0 1.06-1.06l-2.006-2.007a4.125 4.125 0 0 0-3.399-6.463Z" clip-rule="evenodd" />
            </svg>
          </div>
        </div>
      </div>

      <!-- Table -->
      <Card class="px-3 sm:px-4">
        <div class="overflow-x-auto">
          <table class="w-full border-collapse min-w-[600px]">
            <thead>
              <tr>
                <th class="py-2 px-4 border-b text-center text-xs sm:text-sm">No.</th>
                <th class="py-2 px-4 border-b text-center text-xs sm:text-sm">Nama Dokumen</th>
                <th class="py-2 px-4 border-b text-center text-xs sm:text-sm">Tanggal Upload</th>
                <th class="py-2 px-4 border-b text-center text-xs sm:text-sm">Jumlah Unduhan</th>
                <th class="py-2 px-4 border-b text-center text-xs sm:text-sm">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(doc, index) in paginatedDocuments" :key="doc.id" class="hover:bg-gray-100">
                <td class="py-2 px-4 border-t border-b text-center text-xs sm:text-sm">
                  {{ (currentPage - 1) * itemsPerPage + index + 1 }}
                </td>
                <td class="py-2 px-4 border-t border-b text-xs sm:text-sm">
                  <div class="flex items-center">
                    <img src="images/File.png" alt="File Icon" class="w-6 h-6 sm:w-10 sm:h-10 mr-2">
                    <span>{{ doc.document_name }}</span>
                  </div>
                </td>
                <td class="py-2 px-4 border-t border-b text-center text-xs sm:text-sm">
                  {{ doc.upload_date }}
                </td>
                <td class="py-2 px-4 border-t border-b text-center text-xs sm:text-sm">
                  {{ doc.download_count }}
                </td>
                <td class="py-2 px-4 border-t border-b text-center">
                  <NewsButton :href="`/unduh/${doc.id}`">Download</NewsButton>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </Card>

      <!-- Pagination -->
      <div class="mt-4 sm:mt-6">
        <PaginationComponent
          :current-page="currentPage"
          :total-pages="totalPages"
          @page-change="changePage"
        />
      </div>
    </div>
    </MasterLayout>
   

  </div>
</template>

<script>
import MasterLayout from '@/Layouts/MasterLayout.vue';
import NewsButton from '@/Components/UI/NewsButton.vue';
import Background from '@/Components/UI/Background.vue';
import Card from '@/Components/UI/Card.vue';
import PaginationComponent from '@/Components/UI/Pagination.vue';
import { Link } from '@inertiajs/vue3';

export default {
  components: {
    MasterLayout,
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
      selectedYear: new Date().getFullYear(),
      currentPage: 1,
      itemsPerPage: 10,
    };
  },
  computed: {
    availableYears() {
      return [...new Set(this.documents.map(doc => doc.year))].sort((a, b) => b - a);
    },
    filteredDocuments() {
      return this.documents.filter(doc =>
        doc.category === "Laporan Keuangan" &&
        doc.year == this.selectedYear &&
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
  },
};
</script>
