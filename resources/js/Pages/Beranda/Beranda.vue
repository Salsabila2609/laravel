<template>
  <div>
    <Navbar />
    <Video :videoSource="latestVideo" /> <!-- Tambahkan ini -->
    <Media :media="media" />
    <Berita :mainNews="mainNews" :newsCards="newsCards" />
    <Popup v-if="showPopup" :popup="popup" />
    <Layanan />
    <VideoGallery />
    <Visitor 
      :visitorCount="visitorCount"
      :todayVisitorCount="todayVisitorCount"
      :monthlyVisitorCount="monthlyVisitorCount"
      :yearlyVisitorCount="yearlyVisitorCount"
    />
    <Footer />
  </div>
</template>

<script>
import Navbar from '@/Components/Navbar.vue';
import Video from '@/Components/VideoSection.vue'; // Import Video.vue
import Visitor from "@/Components/Visitor.vue";
import Footer from "@/Components/Footer.vue";
import Berita from "@/Components/Berita.vue";
import Layanan from "@/Components/Layanan.vue";
import VideoGallery from "@/Components/VideoGallery.vue";
import Popup from "@/Components/Popup.vue";
import Media from '@/Components/Media.vue';

export default {
  components: {
    Navbar,
    Video, // Tambahkan Video.vue
    Visitor,
    Footer,
    Berita,
    Layanan,
    VideoGallery,
    Popup,
    Media,
  },
  props: {
    latestVideo: String, // Tambahkan prop ini
    media: Array,
    visitorCount: Number,
    todayVisitorCount: Number,
    monthlyVisitorCount: Number,
    yearlyVisitorCount: Number,
    mainNews: Object,
    newsCards: Array,
    popup: Object,
  },
  data() {
    return {
      showPopup: true,
    };
  },
};
</script>
