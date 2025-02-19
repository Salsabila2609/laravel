<template>
  <section class="relative w-full h-screen overflow-hidden">
    <!-- Video Background -->
    <video
      v-if="isValidVideoSource"
      autoplay
      muted
      loop
      id="myVideo"
      :src="sanitizedVideoSource"
      class="absolute top-0 left-0 w-full h-full object-cover"
    ></video>

    <!-- Overlay Content -->
    <div class="absolute top-0 left-0 w-full h-full flex flex-col justify-center items-center bg-gradient-to-b from-black/50 to-transparent text-white text-center z-10">
      <h1 class="text-2xl font-bold">Selamat Datang di Portal Resmi</h1>
      <h2 class="text-5xl font-black mt-2">Pemerintah Kabupaten Madiun</h2>
    </div>
  </section>
</template>

<script>
export default {
  props: {
    videoSource: {
      type: String,
      required: false,
      default: null
    }
  },
  computed: {
    sanitizedVideoSource() {
      return this.isValidVideoSource ? this.videoSource : null;
    },
    isValidVideoSource() {
      return this.videoSource && /\.(mp4|mov|webm|ogg)$/i.test(this.videoSource);
    }
  }
};
</script>

<style scoped>
/* Smooth Video Rendering */
#myVideo {
  -webkit-transform: translate3d(0, 0, 0);
  transform: translate3d(0, 0, 0);
  will-change: transform, opacity;
  object-fit: cover;
  -webkit-backface-visibility: hidden;
  backface-visibility: hidden;
}
.video-section {
  position: relative;
  overflow: hidden;
  margin-top: 60px;
  z-index: 10;
}
</style>
