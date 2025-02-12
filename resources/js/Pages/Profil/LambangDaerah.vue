<template>
  <Navbar/>
  <div class="w-full min-h-screen bg-cover bg-center bg-no-repeat relative flex flex-col justify-center items-center text-white" >

    <!-- Breadcrumb -->
    <div class="absolute top-4 left-6 text-sm text-[#99CBC0] font-bold" style="margin-left: 75px;">
      <Link href="/" class="text-[#D4A017] no-underline">Beranda</Link> > Lambang Daerah
    </div>

    <!-- Content Section -->
    <div class="relative z-10 px-5 py-10 text-gray-800">
      <h1 class="text-center text-4xl text-[#D4A017] font-bold">LAMBANG DAERAH</h1>

      <!-- New section for the image and yellow lines -->
      <div class="flex items-center justify-center mt-3 w-full px-4">
        <div class="h-[2px] bg-[#D4A017] flex-1 rounded"></div>
        <img src="../../../../public/image/madiun_silat.png" alt="Gambar Lambang Daerah" class="mx-4 h-10 w-auto">
        <div class="h-[2px] bg-[#D4A017] flex-1 rounded"></div>
      </div>

      <p class="text-xl text-center mb-5 max-w-[85%] mx-auto">
        Berikut adalah informasi mengenai Lambang Kabupaten Madiun
      </p>

      <!-- Card that holds content -->
      <Card style="background-image: url('image/background.png');">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-6">
          <!-- Kolom kiri -->
          <div class="flex justify-center items-center">
            <img src="../../../../public/image/logo.png" alt="Gambar Lambang Daerah" class="w-full max-w-[60%] h-auto md:ml-[-20px]">
          </div>

          <!-- Kolom kanan -->
          <div class="text-left mt-5 md:mt-0 text-white">
            <h3 class="text-xl font-bold mb-4 ">Lambang Kabupaten Madiun terdiri dari:</h3>
            <p>Bentuk Seluruhnya merupakan Perisai : Lambang Pertahanan</p>
            <p>Bintang Bersudut 5 ( Lima ) : Lambang Pancasila</p>
            <p>Pohon Beringin : Lambang Kesentausaan dan Kehidupan</p>
            <p>Keris : Pusaka Nenek Moyang sebagai Lambang Kebudayaan</p>
            <p>Sayap : Lambang Kekuatan semangat mencapai cita-cita</p>
            <p>Padi dan Kapas : Lambang Kemakmuran Rakyat</p>
            <p class="mb-5">Warna-warna yang dipakai:</p>
            <ul class="list-disc ml-4">
              <li>Putih (Pita, Kapas) : Lambang Kesucian</li>
              <li>Hijau (Beringin, Daun Kapas, Padi) : Lambang Penghargaan</li>
              <li>Merah (Pangkal Sayap) : Lambang Keberanian</li>
              <li>Kuning (Emas) Sayap, Padi, Pinggiran, Pita, Bintang : Lambang Keluhuran</li>
              <li>Hitam (Warna dasar) : Lambang Keabadian</li>
            </ul>
          </div>
        </div>
      </Card>
    </div>
  </div>
  <Footer/>
</template>

<script>
import Navbar from '@/Components/Navbar.vue';
import Footer from '@/Components/Footer.vue';
import Background from '@/Components/Background.vue';
import Card from '@/Components/Card.vue';
import { Link } from '@inertiajs/vue3';

export default {
  components: {
    Background,
    Card,
    Navbar,
    Footer,
    Link,
  },
};
</script>
