<template>
  <div>
    <MasterLayout>
      <!-- Video Section -->
      <Video v-if="isValidUrl(latestVideo)" :videoSource="latestVideo" />
      
      <!-- Media Section -->
      <Media v-if="isValidArray(media)" :media="media" />
      
      <!-- Berita Section -->
      <Berita v-if="isValidObject(mainNews) && isValidArray(newsCards)"
        :mainNews="mainNews"
        :newsCards="newsCards"
      />
      
      <!-- Popup Section -->
      <Popup v-if="showPopup && isValidObject(popup)" :popup="popup" />

      <!-- Visitor Section -->
      <Layanan v-if="isValidArray(layanans)" :layanans="layanans" />
      <VideoGallery />
      <Visitor v-if="isValidNumber(visitorCount) && isValidNumber(todayVisitorCount) && isValidNumber(monthlyVisitorCount) && isValidNumber(yearlyVisitorCount)"
        :visitorCount="visitorCount"
        :todayVisitorCount="todayVisitorCount"
        :monthlyVisitorCount="monthlyVisitorCount"
        :yearlyVisitorCount="yearlyVisitorCount"
      />
    </MasterLayout>
  </div>
</template>

<script>
import MasterLayout from '@/Layouts/MasterLayout.vue';
import Video from '@/Components/Layout/Beranda/VideoSection.vue';
import Visitor from "@/Components/Layout/Beranda/Visitor.vue";
import Berita from "@/Components/Layout/Beranda/Berita.vue";
import Layanan from "@/Components/Layout/Beranda/Layanan.vue";
import VideoGallery from "@/Components/Layout/Beranda/VideoGallery.vue";
import Popup from "@/Components/Layout/Beranda/Popup.vue";
import Media from '@/Components/Layout/Beranda/Media.vue';

export default {
  components: {
    Video,
    Visitor,
    MasterLayout,
    Berita,
    Layanan,
    VideoGallery,
    Popup,
    Media,
  },
  props: {
    latestVideo: { type: String, default: '' },
    media: { type: Array, default: () => [] },
    visitorCount: { type: Number, default: 0 },
    todayVisitorCount: { type: Number, default: 0 },
    monthlyVisitorCount: { type: Number, default: 0 },
    yearlyVisitorCount: { type: Number, default: 0 },
    mainNews: { type: Object, default: () => ({}) },
    newsCards: { type: Array, default: () => [] },
    popup: { type: Object, default: () => ({}) },
    layanans: { type: Array, default: () => [] },
  },
  data() {
    return {
      showPopup: true,
    };
  },
  methods: {
    isValidUrl(url) {
      return typeof url === 'string' && /^(https?:\/\/|\/storage\/).+\.(mp4|webm|mov)$/i.test(url);
    },
    isValidArray(arr) {
      return Array.isArray(arr) && arr.length > 0;
    },
    isValidObject(obj) {
      return obj !== null && typeof obj === 'object' && Object.keys(obj).length > 0;
    },
    isValidNumber(num) {
      return typeof num === 'number' && num >= 0;
    }
  }
};
</script>
