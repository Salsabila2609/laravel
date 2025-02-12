<template>
  <div>
    <Navbar/>
    <!-- Gunakan komponen Background di sini -->
    <Background />

    <!-- Breadcrumb -->
    <div class="text-sm text-[#99CBC0] font-bold py-4 px-6" style="margin-left: 75px;">
      <Link href="/" class="text-[#D4A017] no-underline">Beranda</Link> > Daftar Nama Pejabat Daerah
    </div>

    <div class="relative z-10 px-5 py-10 text-gray-800">
      <h1 class="text-4xl font-bold text-center text-[#D4A017] mt-12">DAFTAR PEJABAT DAERAH</h1>
      <p class="text-xl text-center text-white mb-12 max-w-[85%] mx-auto">
        Berikut adalah daftar nama pejabat daerah yang bertanggung jawab atas berbagai sektor di wilayah ini, lengkap dengan jabatannya.
      </p>
      <!-- Card yang berisi tabel -->
      <Card>
        <table class="w-[95%] mx-auto border-collapse">
          <thead>
            <tr>
              <th class="py-3 px-4 border-b text-center">Nama</th>
              <th class="py-3 px-4 border-b text-center">Jabatan</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="pejabat in pejabats" :key="pejabat.id">
             <td class="py-3 px-4 border-t border-b text-left">{{ pejabat.name }}</td>
              <td class="py-3 px-4 border-t border-b text-left">{{ pejabat.position }}</td>
            </tr>
          </tbody>
        </table>
      </Card>
    </div>
    <Footer/>
  </div>
</template>

<script>
// Import komponen Background dan Card dari folder Components
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
    pejabats: Array, // Array pejabat yang diterima sebagai props
  },
};
</script>

<style scoped>
</style>
