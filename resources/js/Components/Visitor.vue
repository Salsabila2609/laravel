<template>
  <div
    class="visitor-card flex flex-col items-center text-white rounded-full p-5 mx-auto relative mb-10"
  >
    <div class="flex items-center justify-between space-x-4">
      <div class="flex flex-col items-start space-y-2 w-1/4">
        <button
          @click="changeView('harian')"
          :class="{ 'text-white border-b-4 border-white rounded': currentView === 'harian' }"
          class="text-xs sm:text-sm md:text-base font-semibold text-gray-300 py-2 px-2 md:px-4 hover:text-white focus:outline-none"
        >
          Harian
        </button>
        <button
          @click="changeView('bulanan')"
          :class="{ 'text-white border-b-4 border-white rounded': currentView === 'bulanan' }"
          class="text-xs sm:text-sm md:text-base font-semibold text-gray-300 py-2 px-2 md:px-4 hover:text-white focus:outline-none"
        >
          Bulanan
        </button>
        <button
          @click="changeView('tahunan')"
          :class="{ 'text-white border-b-4 border-white rounded': currentView === 'tahunan' }"
          class="text-xs sm:text-sm md:text-base font-semibold text-gray-300 py-2 px-2 md:px-4 hover:text-white focus:outline-none"
        >
          Tahunan
        </button>
      </div>

      <!-- Judul dan Data -->
      <div class="text-center flex-1" ref="countContainer">
        <h2 class="text-xl md:text-xl lg:text-2xl font-bold whitespace-nowrap">
  Jumlah Pengunjung
</h2>
<p class="test-xs mb-3 mt-2">
  {{ formattedDate }}
</p>


        <p
          v-if="currentView === 'harian' && isVisible"

          class="text-2xl md:text-3xl lg:text-4xl font-bold text-yellow-400 "
        >
          <count-up :end-val="todayVisitorCount" :duration="2.5"></count-up>
        </p>
        <p
          v-if="currentView === 'bulanan' && isVisible"
          class="text-2xl md:text-3xl lg:text-4xl font-bold text-[#D4A017] mt-4"
        >
          <count-up :end-val="monthlyVisitorCount" :duration="2.5"></count-up>
        </p>
        <p
          v-if="currentView === 'tahunan' && isVisible"
          class="text-2xl md:text-3xl lg:text-4xl font-bold text-[#D4A017] mt-4"
        >
          <count-up :end-val="yearlyVisitorCount" :duration="2.5"></count-up>
        </p>
      </div>
      <img :src="peopleImage" alt="Illustration" class="w-32 h-32" />
    </div>
  </div>
</template>

<script>
import CountUp from 'vue-countup-v3';
import peopleImage from "../../../public/img/people.png";

export default {
  components: {
    CountUp,
  },
  props: {
    todayVisitorCount: Number,
    monthlyVisitorCount: Number,
    yearlyVisitorCount: Number,
  },
  data() {
    return {
      currentView: "harian",
      peopleImage,
      isVisible: false,
      formattedDate: new Date().toLocaleDateString("id-ID", {
        weekday: "long",
        day: "numeric",
        month: "long",
        year: "numeric",
      }),
    };
  },
  mounted() {
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            this.isVisible = true;
            observer.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.5 }
    );

    observer.observe(this.$refs.countContainer);
  },
  methods: {
    changeView(view) {
      this.currentView = view;
    },
  },
};
</script>


<style scoped>
.visitor-card {
  height: 185px; /* Tinggi spesifik */
  background-color: #064e3b;
  background-image: url('/img/Visitor.png');
  background-size: cover;
  background-position: center;
  width: 70%;
  max-width: 700px;
  margin-left: auto;
  margin-right: auto;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

@media (max-width: 768px) {
  .visitor-card {
    height: auto; /* Ubah tinggi untuk lebih responsif */
    width: 90%; /* Atur ulang lebar untuk layar kecil */
  }

  .visitor-card h2 {
    font-size: 1.25rem; /* Atur ukuran font lebih kecil */
  }

  .visitor-card p {
    font-size: 2.5rem; /* Ubah ukuran angka menjadi lebih kecil di layar kecil */
  }

  .visitor-card img {
    width: 15vw; /* Gunakan vw untuk ukuran gambar yang proporsional */
    height: auto; /* Biarkan height menyesuaikan dengan aspect ratio */
  }
}

@media (max-width: 640px) {
  .visitor-card {
    width: 100%; /* Lebar penuh untuk layar sangat kecil */
    padding: 10px; /* Kurangi padding */
  }

  .visitor-card img {
    width: 20vw; /* Gunakan vw untuk ukuran gambar yang lebih besar */
    height: auto;
  }

  .visitor-card h2 {
    font-size: 1rem; /* Ubah ukuran judul */
  }

  .visitor-card p {
    font-size: 2rem; /* Sesuaikan ukuran angka */
  }
}

</style>
