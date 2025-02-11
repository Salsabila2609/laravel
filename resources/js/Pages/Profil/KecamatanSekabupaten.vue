<template>
  <div>
    <Navbar/>
    <!-- Gunakan komponen Background di sini -->
    <Background />


    <!-- Breadcrumb -->
    <div class="text-sm text-[#99CBC0] font-bold py-4 px-6">
      <Link href="/" class="text-[#D4A017] no-underline">Beranda</Link> > Daftar Nama Kecamatan Se-kabupaten
    </div>

    <div class="relative z-10 px-5 py-10 text-gray-800">
      <h1 class="text-4xl font-bold text-center text-[#D4A017] mt-12">KECAMATAN SE-KABUPATEN MADIUN</h1>
      <p class="text-xl text-center text-white mb-12">
        Berikut adalah daftar kecamatan yang ada di Kabupaten Madiun
      </p>
      <!-- Card yang berisi tabel -->
      <Card>
        <!-- Isi slot dengan tabel -->
        <table class="w-[95%] mx-auto border-collapse">
          <thead>
            <tr>
              <th class="py-2 px-4 border-b text-center">Kecamatan</th>
              <th class="py-2 px-4 border-b text-center">Alamat</th>
              <th class="py-2 px-4 border-b text-center">Kontak</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="kecamatan in kecamatans" :key="kecamatan.id">
              <td class="py-3 px-4 border-t border-b text-center">
                <a :href="kecamatan.link" class="text-blue-500 hover:underline" target="_blank">
                  {{ kecamatan.name }}
                </a>
              </td>
              <td class="py-3 px-4 border-t border-b text-center">{{ kecamatan.address }}</td>
              <td class="py- px-4 border-t border-b text-center">{{ kecamatan.contact }}</td>
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
import Background from '@/Components/Background.vue';
import Card from '@/Components/Card.vue';
import Navbar from '@/Components/Navbar.vue';
import Footer from '@/Components/Footer.vue';
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
    kecamatans: Array, // Array kecamatan yang diterima sebagai props
  },
};
</script>
