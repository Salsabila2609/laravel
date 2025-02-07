<template>
  <section class="py-10 px-5 bg-white">
    <div class="max-w-5xl mx-auto flex flex-col md:flex-row items-center md:items-start text-center md:text-left gap-8">
      <!-- Logo dengan animasi -->
      <div class="flex justify-center md:justify-start">
  
        <img 
          src="/img/silat.jpg" 
          alt="Silat Icon" 
          class="w-40 h-auto md:w-45" 
          data-aos="fade-up" 
        />
      </div>

      <!-- Konten teks -->
      <div class="md:w-3/4">
        <h3 class="text-2xl font-extrabold text-[#396C6D] mb-4">Profil Kabupaten Madiun</h3>
        <p class="text-[#396C6D] leading-relaxed">
          Kabupaten Madiun adalah sebuah daerah terletak di Provinsi Jawa Timur,
          dikelilingi oleh Pegunungan Wilis di selatan dan memiliki bentang alam yang bervariasi,
          dari dataran rendah hingga pegunungan. Letak geografis ini mendukung pertanian
          dan industri yang berkembang pesat. Madiun pun terus berkembang sebagai kota kreatif
          dengan sumber daya manusia yang luar biasa, memberikan ruang bagi setiap individu
          untuk berinovasi dan meraih potensi terbaiknya.
        </p>
      </div>
    </div>
  </section>
</template>

<script>
import AOS from 'aos';
import 'aos/dist/aos.css';

export default {
  mounted() {
    AOS.init({
      duration: 1000,  // Durasi animasi dalam milidetik
      easing: 'ease',  // Easing effect (opsional)
      once: true,      // Animasi hanya terjadi sekali saat elemen muncul
    });
  },
};
</script>

<style scoped>
/* Tambahan opsional untuk memastikan tampilan gambar rapi */
img {
  border-radius: 8px; /* Opsional: menambahkan border radius untuk estetika */
}
</style>
