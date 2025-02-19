<template>
  <div>
    <MasterLayout>

    <!-- Video Section -->
    <VideoSection :videoSource="videoSource" />
    <!-- Media Section -->
    <Media :media="media" />
    <!-- Berita Section -->
    <Berita
      :mainNews="mainNews"
      :newsCards="newsCards"
    />
    <!-- Popup Section -->
    <Popup v-if="showPopup" :popup="popup" />

    <!-- Visitor Section -->
    <Layanan :layanans="layanans" />
    <VideoGallery />
    <Visitor
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

import Video from '@/Components/Layout/Beranda/VideoSection.vue'; // Import Video.vue
import Visitor from "@/Components/Layout/Beranda/Visitor.vue";
import Berita from "@/Components/Layout/Beranda/Berita.vue";
import Layanan from "@/Components/Layout/Beranda/Layanan.vue";
import VideoGallery from "@/Components/Layout/Beranda/VideoGallery.vue";
import Popup from "@/Components/Layout/Beranda/Popup.vue";
import Media from '@/Components/Layout/Beranda/Media.vue';

export default {
  components: {

    Video, // Tambahkan Video.vue
    Visitor,
    MasterLayout,

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
    layanans: Array,
  },
  data() {
    return {
      showPopup: true,
    };
  },
};
</script>
