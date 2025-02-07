<template>
  <div>
    <Navbar />

    <!-- Video Section -->
    <VideoSection :videoSource="videoSource" />

    <!-- Profile Section -->
    <ProfileSection />
    
    <!-- Berita Section -->
    <Berita
      :mainNews="mainNews"
      :newsCards="newsCards"
    />

    <!-- Popup Section -->
    <Popup v-if="showPopup" :popup="popup" />

    <!-- Visitor Section -->
    <Layanan/>
    <VideoGallery/>
    <Visitor
      :visitorCount="visitorCount"
      :todayVisitorCount="todayVisitorCount"
      :monthlyVisitorCount="monthlyVisitorCount"
      :yearlyVisitorCount="yearlyVisitorCount"
    />
    
    <Footer/>
  </div>
</template>

<script>
import Navbar from '@/Components/Navbar.vue';
import VideoSection from '@/Components/VideoSection.vue';
import ProfileSection from '@/Components/ProfileSection.vue';
import Visitor from "@/Components/Visitor.vue";
import Footer from "@/Components/Footer.vue";
import Berita from "@/Components/Berita.vue";
import Layanan from "@/Components/Layanan.vue";
import VideoGallery from "@/Components/VideoGallery.vue";
import Popup from "@/Components/Popup.vue";
import videoSource from '../../../../public/vid/VideoBeranda.mp4';

export default {
  components: {
    Navbar,
    VideoSection,
    ProfileSection,
    Visitor,
    Footer,
    Berita,
    Layanan,
    VideoGallery,
    Popup,
  },
  props: {
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
      videoSource,
      showPopup: true, // Default to true if you want it to show on initial load
    };
  },
};
</script>