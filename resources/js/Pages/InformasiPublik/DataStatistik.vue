<template>
  <div>
    <Navbar />
    <Background />

    <div class="text-sm md:text-base text-[#99cbc0] font-bold px-5 py-3 flex flex-wrap items-center gap-2 text-center md:text-left">
  <Link href="/" class="text-[#D4A017] no-underline">Beranda</Link>
  <span class="hidden md:inline">></span>
  <span>Data Statistik</span>
</div>


    <div class="relative z-10 px-5 py-10 text-gray-800">
      <h1 class="text-4xl font-bold text-center text-[#D4A017] mt-">DATA STATISTIK</h1>
      <p class="text-xl text-center text-white mt-4 mb-7 max-w-[85%] mx-auto">
        Berikut adalah daftar Data Statistik yang tersedia untuk diunduh.
      </p>

<!-- Container untuk Search Bar dan Page Size Selector -->
<div class="w-[80%] mx-auto flex flex-col  md:flex-row md:items-center md:justify-between mb-6 gap-4">
  <!-- Search Input -->
  <div class="w-full md:w-2/3 lg:w-1/2">
    <input
      v-model="searchQuery"
      type="text"
      placeholder="Cari dokumen..."
      class="w-full px-4 py-2 border-gray-300  rounded-full focus:outline-none focus:ring-2 focus:ring-[#D4A017]"
    />
  </div>

  <!-- Page Size Selector -->
  <div class="flex items-center gap-2">
    <label for="pageSize" class="text-white whitespace-nowrap">Show items:</label>
    <select
      id="pageSize"
      v-model="itemsPerPage"
      @change="handlePageSizeChange"
      class="w-24 px-2 py-1 rounded-full border border-gray-300 focus:border-[#396C6D] focus:ring focus:ring-[#396C6D] focus:ring-opacity-50 text-gray-500 cursor-pointer hover:border-[#396C6D] transition duration-200"
    >
      <option v-for="size in pageSizeOptions" :key="size" :value="size">
        {{ size }}
      </option>
    </select>
  </div>
</div>



      <!-- Table of Documents -->
      <Card>
        <table class="w-[95%] mx-auto border-collapse">
          <thead>
            <tr>
              <th class="py-3 px-4 border-b text-center">No.</th>
              <th class="py-3 px-4 border-b text-center">Nama Dokumen</th>
              <th class="py-3 px-4 border-b text-center">Tanggal Upload</th>
              <th class="py-3 px-4 border-b text-center">Jumlah Unduhan</th>
              <th class="py-3 px-4 border-b text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(doc, index) in paginatedDocuments" :key="doc.id" class="hover:bg-gray-100">
              <td class="py-3 px-4 border-t border-b text-center">
                {{ (currentPage - 1) * itemsPerPage + index + 1 }}
              </td>
              <td class="py-3 flex items-center px-4 border-t border-b flex">
                <img src="image/File.png" alt="File Icon" class="w-10 h-10 mr-2">
                {{ doc.document_name }}
              </td>
              <td class="py-3 px-4 border-t border-b text-center">{{ doc.upload_date }}</td>
              <td class="py-3 px-4 border-t border-b text-center">{{ doc.download_count }}</td>
              <td class="py-3 px-4 border-t border-b text-center">
                <NewsButton :href="`/unduh/${doc.id}`">Download</NewsButton>
              </td>
            </tr>
          </tbody>
        </table>
      </Card>

      <!-- Pagination -->
      <PaginationComponent
        :current-page="currentPage"
        :total-pages="totalPages"
        @page-change="changePage"
      />
    </div>

    <Footer />
  </div>
</template>

<script>
import Navbar from '@/Components/Navbar.vue';
import Footer from '@/Components/Footer.vue';
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
      pageSizeOptions: [10, 20, 25, 50, 100], // Opsi jumlah item per halaman
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
      this.currentPage = 1; // Reset ke halaman pertama saat page size berubah
    },
  },
};
</script>
