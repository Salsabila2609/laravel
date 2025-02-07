<template>
  <div>
    <Navbar />
    <!-- Gunakan komponen Background di sini -->
    <Background />

    <!-- Breadcrumb -->
    <div class="text-sm text-[#99CBC0] font-bold py-4 px-6">
      <Link href="/" class="text-[#D4A017] no-underline">Beranda</Link> > Laporan Keuangan 2024
    </div>

    <div class="relative z-10 px-5 py-10 text-gray-800">
      <h1 class="text-4xl font-bold text-center text-[#D4A017] mt-12">LAPORAN KEUANGAN 2024</h1>
      <p class="text-xl text-center text-white mb-12 max-w-[85%] mx-auto">
        Berikut adalah daftar laporan keuangan 2024 yang tersedia untuk diunduh.
      </p>

      <!-- Card yang berisi tabel -->
      <Card>
        <table class="w-[95%] mx-auto border-collapse">
          <thead>
            <tr>
              <th class="py-3 px-4 border-b text-center">Nama Dokumen</th>
              <th class="py-3 px-4 border-b text-center">Tanggal Unggah</th>
              <th class="py-3 px-4 border-b text-center">Jumlah Unduhan</th>
              <th class="py-3 px-4 border-b text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <!-- Loop untuk menampilkan dokumen hanya dengan kategori "Data Statistik" -->
            <tr v-for="doc in filteredDocuments" :key="doc.id">
              <td class="py-3 px-4 border-t border-b text-left">{{ doc.document_name }}</td>
              <td class="py-3 px-4 border-t border-b text-left">{{ doc.upload_date }}</td>
              <td class="py-3 px-4 border-t border-b text-left">{{ doc.download_count }}</td>
              <td class="py-3 px-4 border-t border-b text-left">
                <a
                  :href="`/unduh/${doc.id}`"
                  class="text-pink-500 hover:underline"
                  target="_blank"
                >
                  Download
                </a>
              </td>
            </tr>
          </tbody>
        </table>
      </Card>
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
  computed: {
    // Filter dokumen berdasarkan kategori "Data Statistik"
    filteredDocuments() {
      return this.documents.filter(doc => doc.category === "Laporan Keuangan 2024");
    },
  },
};
</script>

<style scoped>
</style>
