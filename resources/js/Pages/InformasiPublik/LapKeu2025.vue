<template>
    <div>
      <Navbar />
      <Background />
  
      <!-- Breadcrumb -->
      <div class="text-sm text-[#99CBC0] font-bold py-4 px-6">
        <Link href="/" class="text-[#D4A017] no-underline">Beranda</Link> > Data Statistik
      </div>
  
      <div class="relative z-10 px-5 py-10 text-gray-800">
        <h1 class="text-4xl font-bold text-center text-[#D4A017] mt-12">DATA STATISTIK</h1>
        <p class="text-xl text-center text-white mb-12 max-w-[85%] mx-auto">
          Berikut adalah daftar Data Statistik yang tersedia untuk diunduh.
        </p>
  
        <!-- Search Input -->
        <div class="flex justify-center mb-8">
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Cari dokumen..."
            class="w-[50%] px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#D4A017]"
          />
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
                <td class="py-3 px-4 border-t border-b text-center">{{ currentPage * itemsPerPage + index + 1 }}</td>
                <td class="py-3 px-4 border-t border-b flex ">
                  <img src="/Users/apple/Documents/KerjaPraktik/pemkab/public/image/File.png" alt="File Icon" class="w-10 h-10 mr-2">
                  {{ doc.document_name }}
                </td>
                <td class="py-3 px-4 border-t border-b text-center">{{ doc.upload_date }}</td>
                <td class="py-3 px-4 border-t border-b text-center">{{ doc.download_count }}</td>
                <td class="py-3 px-4 border-t border-b text-center">
                  <a
                    :href="`/unduh/${doc.id}`"
                    class="inline-block bg-[#D4A017] text-white px-4 py-2 rounded-lg hover:bg-[#b3860e] transition duration-300"
                    target="_blank"
                  >
                    Download
                  </a>
                </td>
              </tr>
            </tbody>
          </table>
        </Card>
  
        <!-- Pagination -->
   <!-- Pagination -->
<div class="flex justify-center mt-6">
  <button 
    :disabled="currentPage === 0"
    @click="currentPage--"
    class="flex items-center px-4 py-2 mx-1 bg-[#D4A017] text-white rounded-lg hover:bg-[#b3860e] transition duration-300 disabled:opacity-50"
  >
    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <path d="M15 18l-6-6 6-6" />
    </svg>
    Previous
  </button>

  <span class="px-4 py-2 mx-1 text-lg text-white font-semibold">{{ currentPage + 1 }} / {{ totalPages }}</span>

  <button 
    :disabled="currentPage >= totalPages - 1"
    @click="currentPage++"
    class="flex items-center px-4 py-2 mx-1 bg-[#D4A017] text-white rounded-lg hover:bg-[#b3860e] transition duration-300 disabled:opacity-50"
  >
    Next
    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ml-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <path d="M9 18l6-6-6-6" />
    </svg>
  </button>
</div>

      </div>
  
      <Footer />
    </div>
  </template>
  
  <script>
  import Navbar from '@/Components/Navbar.vue';
  import Footer from '@/Components/Footer.vue';
  import Background from '@/Components/Background.vue';
  import Card from '@/Components/Card.vue';
  import { Link } from '@inertiajs/vue3';
  
  export default {
    components: {
      Navbar,
      Footer,
      Background,
      Card,
      Link,
    },
    props: {
      documents: Array,
    },
    data() {
      return {
        searchQuery: '',
        currentPage: 0,
        itemsPerPage: 10,
      };
    },
    computed: {
      // Filter dokumen berdasarkan input pencarian
      filteredDocuments() {
        return this.documents.filter(doc =>
          doc.category === "Laporan Keuangan 2025" &&
          doc.document_name.toLowerCase().includes(this.searchQuery.toLowerCase())
        );
      },
      // Ambil data berdasarkan halaman saat ini
      paginatedDocuments() {
        const start = this.currentPage * this.itemsPerPage;
        const end = start + this.itemsPerPage;
        return this.filteredDocuments.slice(start, end);
      },
      // Hitung total halaman
      totalPages() {
        return Math.ceil(this.filteredDocuments.length / this.itemsPerPage);
      },
    },
  };
  </script>
  
  <style scoped>
  </style>
  