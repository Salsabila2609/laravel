<template>
  <div class="visitor-card flex flex-col md:flex-row items-center justify-between text-white rounded-full p-4 md:p-6 lg:p-8 mx-auto relative mb-10">
    <div class="flex md:flex-col items-center md:items-start justify-center space-x-2 md:space-x-0 md:space-y-3 w-full md:w-1/5 mb-4 md:mb-0 ml-5 mr-3">
      <button
        v-for="view in views"
        :key="view.id"
        @click="changeView(view.id)"
        :class="[
          'text-xs sm:text-sm lg:text-base font-semibold py-2 px-3 lg:px-4 transition-all duration-200 whitespace-nowrap',
          currentView === view.id 
            ? 'text-white border-b-2 md:border-b-4 border-white rounded'
            : 'text-gray-300 hover:text-white'
        ]"
      >
        {{ view.label }}
      </button>
    </div>

    <div class="text-center flex-2 px-2 md:px-4 md:ml-4" ref="countContainer">
      <h2 class="text-lg sm:text-xl lg:text-2xl xl:text-3xl font-bold">
        Jumlah Pengunjung
      </h2>
      <p class="text-xs sm:text-sm lg:text-base mt-2 mb-3 text-gray-200">
        {{ sanitizedPeriod }}
      </p>
      
      <transition name="fade" mode="out-in">
        <p v-if="currentView === 'harian' && isVisible" class="visitor-count" key="daily">
          <count-up :end-val="safeNumber(todayVisitorCount)" :duration="2.5"></count-up>
        </p>
        <p v-else-if="currentView === 'bulanan' && isVisible" class="visitor-count" key="monthly">
          <count-up :end-val="safeNumber(monthlyVisitorCount)" :duration="2.5"></count-up>
        </p>
        <p v-else-if="currentView === 'tahunan' && isVisible" class="visitor-count" key="yearly">
          <count-up :end-val="safeNumber(yearlyVisitorCount)" :duration="2.5"></count-up>
        </p>
      </transition>
    </div>

    <div class="w-24 sm:w-28 md:w-32 lg:w-36 xl:w-40 flex-shrink-0 md:ml-4 mr-3">
      <img v-if="isValidImage(peopleImage)" :src="peopleImage" alt="Illustration" class="w-full h-auto object-contain" />
      <p v-else class="text-red-500">Invalid image format</p>
    </div>
  </div>
</template>

<script>
import CountUp from 'vue-countup-v3';
import peopleImage from "../../../../../public/images/people.png";

export default {
  components: {
    CountUp,
  },
  props: {
    todayVisitorCount: {
      type: Number,
      default: 0,
      validator: (value) => value >= 0
    },
    monthlyVisitorCount: {
      type: Number,
      default: 0,
      validator: (value) => value >= 0
    },
    yearlyVisitorCount: {
      type: Number,
      default: 0,
      validator: (value) => value >= 0
    },
  },
  data() {
    return {
      currentView: "harian",
      peopleImage,
      isVisible: false,
      views: [
        { id: 'harian', label: ' Harian' },
        { id: 'bulanan', label: 'Bulanan' },
        { id: 'tahunan', label: 'Tahunan' },
      ],
    };
  },
  computed: {
    sanitizedPeriod() {
      const date = new Date();
      const options = {
        weekday: "long", day: "numeric", month: "long", year: "numeric"
      };
      return date.toLocaleDateString("id-ID", options).replace(/[^a-zA-Z0-9 ]/g, "");
    }
  },
  mounted() {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          this.isVisible = true;
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.5 });
    observer.observe(this.$refs.countContainer);
  },
  methods: {
    changeView(view) {
      if (["harian", "bulanan", "tahunan"].includes(view)) {
        this.currentView = view;
      }
    },
    safeNumber(value) {
      return isNaN(value) ? 0 : Math.max(0, value);
    },
    isValidImage(imagePath) {
      return /\.(jpg|jpeg|png|webp|svg)$/i.test(imagePath);
    }
  }
};
</script>

<style scoped>
.visitor-count {
  font-size: 1.5rem;
  font-weight: bold;
  color: #D4A017;
}
</style>

<style scoped>
.visitor-card {
  background-color: #064e3b;
  background-image: url('/images/Visitor.png');
  background-size: cover;
  background-position: center;
  width: 90%;
  max-width: 900px; /* Reduced from 1200px */
  margin-left: auto;
  margin-right: auto;
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

@media (min-width: 768px) {
  .visitor-card {
    width: 85%;
  }
}

@media (min-width: 1024px) {
  .visitor-card {
    width: 70%;
  }
}

@media (min-width: 1280px) {
  .visitor-card {
    width: 60%;
  }
}
</style>
