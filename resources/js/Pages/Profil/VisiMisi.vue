<template>
  <MasterLayout>
    <div class="text-sm text-[#99CBC0] font-bold py-4 px-6 lg:ml-20 md:ml-10 sm:ml-5 ml-2">
      <Link href="/" class="text-[#D4A017] no-underline">Beranda</Link> > Kontak Kami
    </div>
    <div class="w-full min-h-screen bg-white relative flex flex-col justify-center items-center text-white">
      <!-- Content Section -->
      <div class="relative z-10 px-5 py-10 text-gray-800 w-full max-w-[1200px]">
        <h1 class="text-center text-4xl text-[#D4A017] font-bold">VISI MISI</h1>

        <!-- Divider with Image -->
        <div class="flex items-center justify-center mt-3 w-full px-4">
          <div class="h-[2px] bg-[#D4A017] flex-1 rounded"></div>
          <img 
            src="/images/madiun_silat.png" 
            alt="Gambar Lambang Daerah" 
            class="mx-4 h-14 w-auto max-w-full"
            loading="lazy"
          >
          <div class="h-[2px] bg-[#D4A017] flex-1 rounded"></div>
        </div>

        <p class="text-xl text-center text-[#D4A017] mb-5 max-w-[85%] mx-auto">
          Visi Misi Bupati dan Wakil Bupati Madiun Periode 2018 – 2023
        </p>

        <!-- Card with background image -->
        <Card class="p-6 mt-0 text-white relative bg-cover bg-center bg-no-repeat" style="background-image: url('/images/background.png');">
          <div class="p-6">
            <!-- Bagian atas dengan dua gambar yang berada di tengah -->
            <div class="flex justify-center items-center mb-4 lg:gap-20 md:gap-10 gap-5">
              <img 
                src="/images/bupati.png" 
                alt="Gambar Bupati" 
                class="w-36 h-auto max-w-full"
                loading="lazy"
              >
              <img 
                src="/images/bupati.png" 
                alt="Gambar Wakil Bupati" 
                class="w-36 h-auto max-w-full"
                loading="lazy"
              >
            </div>

            <!-- Tabel di bawah foto -->
            <div class="mt-6">
              <table class="min-w-full table-auto border-collapse w-full max-w-full">
                <thead>
                  <tr class="border-t flex flex-col md:flex-row">
                    <th class="border-0 p-6 text-center w-full md:w-1/3 md:align-top">VISI</th>
                    <td class="border-0 p-6 text-left italic w-full md:w-2/3">
                      “Terwujudnya Kabupaten Madiun Yang Aman, Mandiri, Sejahtera dan Berakhlak”
                    </td>
                  </tr>
                </thead>
                <tbody>
                  <tr class="border-t flex flex-col md:flex-row">
                    <th class="border-0 p-6 text-center w-full md:w-1/3 md:align-top">MISI</th>
                    <td class="border-0 p-6 w-full md:w-2/3">
                      <ol class="list-decimal pl-4">
                        <li>Mewujudkan rasa aman bagi seluruh masyarakat dan aparatur Pemerintah Kabupaten Madiun.</li>
                        <li>Mewujudkan Aparatur Pemerintah yang Profesional untuk meningkatkan Pelayanan Publik.</li>
                        <li>Meningkatkan Pembangunan Ekonomi yang Mandiri Berbasis Agrobisnis, Agroindustri, dan Pariwisata yang Berkelanjutan.</li>
                        <li>Meningkatkan Kesejahteraan yang Berkeadilan.</li>
                        <li>Mewujudkan Masyarakat Berakhlak Mulia dengan Meningkatkan Kehidupan Beragama, Menguatkan Budaya, dan Mengedepankan Kearifan Lokal.</li>
                      </ol>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </Card>
      </div>
    </div>
  </MasterLayout>
</template>

<script>
import Card from '@/Components/UI/Card.vue';
import { Link } from '@inertiajs/vue3';
import MasterLayout from '@/Layouts/MasterLayout.vue';

export default {
  components: {
    Card,
    MasterLayout,
    Link,
  },
};
</script>

<style scoped>
@media (max-width: 768px) {
  img {
    width: 50px;
    height: auto;
  }
}
</style>