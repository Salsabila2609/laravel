<template>
  <div>
    <Navbar/>
    <Background />

    <!-- Breadcrumb -->
    <div class="text-sm text-[#99CBC0] font-bold py-4 px-6 lg:ml-20 md:ml-10 sm:ml-5 ml-2">
      <Link href="/" class="text-[#D4A017] no-underline">Beranda</Link> > Daftar Nama Kecamatan Se-kabupaten
    </div>

    <div class="relative z-10 px-3 sm:px-5 py-6 sm:py-10 text-gray-800 max-w-7xl mx-auto">
      <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-center text-[#D4A017]">ORGANISASI PERANGKAT DAERAH (OPD)</h1>
      <p class="text-base sm:text-lg md:text-xl text-center text-white mt-3 sm:mt-4 mb-5 sm:mb-7 mx-auto px-4 sm:max-w-[80%]">
        Kenali instansi-instansi yang berperan dalam pelayanan publik dan pembangunan. Setiap OPD dan dinas memiliki fungsi penting untuk mendukung kesejahteraan masyarakat dan pertumbuhan wilayah.
      </p>
      <!-- Card yang berisi tabel -->
      <Card class="px-3 sm:px-4">
        <div class="overflow-x-auto">
          <table class="w-full border-collapse">
            <thead>
              <tr>
              <th class="py-2 sm:py-3 px-2 sm:px-4 border-b text-center text-xs sm:text-sm">Nama</th>
              <th class="py-2 sm:py-3 px-2 sm:px-4 border-b text-center text-xs sm:text-sm">Alamat</th>
              <th class="py-2 sm:py-3 px-2 sm:px-4 border-b text-center text-xs sm:text-sm">Kontak</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(opd) in opds" :key="opd.id">
              <td class="py-2 sm:py-3 px-2 sm:px-4 border-t border-b text-center text-xs sm:text-sm">
                <a :href="opd.link" class="text-blue-500 hover:underline" target="_blank">
                  {{ opd.name }}
                </a>
              </td>
              <td class="py-2 sm:py-3 px-2 sm:px-4 border-t border-b text-center text-xs sm:text-sm">{{ opd.address }}</td>
              <td class="py-2 sm:py-3 px-2 sm:px-4 border-t border-b text-center text-xs sm:text-sm">{{ opd.contact }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </Card>
    </div>
    <Footer/>
  </div>
</template>

<script>
import Navbar from '@/Components/Layout/Navbar.vue';
import Footer from '@/Components/Layout/Footer.vue';
import Background from '@/Components/Background.vue';
import Card from '@/Components/Card.vue';
import { Link } from '@inertiajs/vue3';

export default {
  components: {
    Navbar,
    Footer,
    Background, // Daftarkan komponen Background
    Card, // Daftarkan komponen Card
    Link,
  },
  props: {
    opds: Array, // Data OPD akan diterima dari server
  },
};
</script>