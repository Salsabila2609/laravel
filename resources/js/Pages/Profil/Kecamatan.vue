<template>
  <div>
    <MasterLayout>
      <Background />

      <!-- Breadcrumb -->
      <div class="text-sm text-[#99CBC0] font-bold py-4 px-6 lg:ml-20 md:ml-10 sm:ml-5 ml-2">
        <Link href="/" class="text-[#D4A017] no-underline">Beranda</Link> > Daftar Nama Kecamatan Se-kabupaten
      </div>

      <!-- Container Utama -->
      <div class="relative z-10 px-3 sm:px-5 py-6 sm:py-10 text-gray-800 max-w-7xl mx-auto">
        <!-- Judul -->
        <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-center text-[#D4A017]">
          KECAMATAN SE-KABUPATEN MADIUN
        </h1>
        <!-- Deskripsi -->
        <p class="text-base sm:text-lg md:text-xl text-center text-white mt-3 sm:mt-4 mb-5 sm:mb-7 mx-auto px-4 sm:max-w-[80%]">
          Berikut adalah daftar kecamatan yang ada di Kabupaten Madiun
        </p>

        <!-- Card yang berisi tabel -->
        <Card class="px-3 sm:px-4">
          <!-- Container untuk tabel dengan overflow -->
          <div class="overflow-x-auto">
            <table class="w-full border-collapse">
              <thead>
                <tr>
                  <th class="py-2 sm:py-3 px-2 sm:px-4 border-b text-center text-xs sm:text-sm">Kecamatan</th>
                  <th class="py-2 sm:py-3 px-2 sm:px-4 border-b text-center text-xs sm:text-sm">Alamat</th>
                  <th class="py-2 sm:py-3 px-2 sm:px-4 border-b text-center text-xs sm:text-sm">Kontak</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="kecamatan in kecamatans" :key="kecamatan.id">
                  <td class="py-2 sm:py-3 px-2 sm:px-4 border-t border-b text-center text-xs sm:text-sm">
                    <a
                      :href="sanitizeUrl(kecamatan.link)"
                      class="text-blue-500 hover:underline"
                      target="_blank"
                      rel="noopener noreferrer"
                    >
                      {{ kecamatan.name }}
                    </a>
                  </td>
                  <td class="py-2 sm:py-3 px-2 sm:px-4 border-t border-b text-center text-xs sm:text-sm">
                    {{ kecamatan.address }}
                  </td>
                  <td class="py-2 sm:py-3 px-2 sm:px-4 border-t border-b text-center text-xs sm:text-sm">
                    {{ kecamatan.contact }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </Card>
      </div>
    </MasterLayout>
  </div>
</template>

<script>
import Background from '@/Components/UI/Background.vue';
import Card from '@/Components/UI/Card.vue';
import MasterLayout from '@/Layouts/MasterLayout.vue';
import { Link } from '@inertiajs/vue3';

export default {
  components: {
    MasterLayout,
    Background,
    Card,
    Link,
  },
  props: {
    kecamatans: {
      type: Array,
      required: true,
      validator: (value) => {
        return value.every((kecamatan) => {
          return (
            kecamatan.id &&
            kecamatan.name &&
            kecamatan.address &&
            kecamatan.contact &&
            kecamatan.link
          );
        });
      },
    },
  },
  methods: {
    sanitizeUrl(url) {
      try {
        const parsedUrl = new URL(url);
        if (['http:', 'https:'].includes(parsedUrl.protocol)) {
          return url;
        }
      } catch (error) {
        console.error('Invalid URL:', error);
      }
      return '#';
    },
  },
};
</script>