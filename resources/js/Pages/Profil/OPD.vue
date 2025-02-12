<template>
  <div>
    <Navbar/>
    <!-- Gunakan komponen Background di sini -->
    <Background />

    <!-- Breadcrumb -->
    <div class="text-sm text-[#99CBC0] font-bold py-4 px-6" style="margin-left: 75px;">
      <Link href="/" class="text-[#D4A017] no-underline">Beranda</Link> > Daftar OPD
    </div>

    <div class="relative z-10 px-5 py-10 text-gray-800">
      <h1 class="text-4xl font-bold text-center text-[#D4A017] mt-12">ORGANISASI PERANGKAT DAERAH (OPD)</h1>
      <p class="text-xl text-center text-white mb-12 max-w-[85%] mx-auto">
        Kenali instansi-instansi yang berperan dalam pelayanan publik dan pembangunan. Setiap OPD dan dinas memiliki fungsi penting untuk mendukung kesejahteraan masyarakat dan pertumbuhan wilayah.
      </p>
      <!-- Card yang berisi tabel -->
      <Card>
        <table class="w-[95%] mx-auto border-collapse">
          <thead>
            <tr>
              <th class="py-3 px-4 border-b text-center">Nama</th>
              <th class="py-3 px-4 border-b text-center">Alamat</th>
              <th class="py-3 px-4 border-b text-center">Kontak</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(opd) in opds" :key="opd.id">
              <td class="py-3 px-4 border-t border-b text-left">
                <a :href="opd.link" class="text-blue-500 hover:underline" target="_blank">
                  {{ opd.name }}
                </a>
              </td>
              <td class="py-3 px-4 border-t border-b text-left">{{ opd.address }}</td>
              <td class="py-3 px-4 border-t border-b text-left">{{ opd.contact }}</td>
            </tr>
          </tbody>
        </table>
      </Card>
    </div>
    <Footer/>
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
    Background, // Daftarkan komponen Background
    Card, // Daftarkan komponen Card
    Link,
  },
  props: {
    opds: Array, // Data OPD akan diterima dari server
  },
};
</script>