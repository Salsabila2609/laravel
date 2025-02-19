<script>
import { Link } from "@inertiajs/vue3";
import Navbar from "@/Components/Layout/Navbar.vue";
import Footer from "@/Components/Layout/Footer.vue";

export default {
  components: {
    Navbar,
    Footer,
  },
};
</script>

<template>
    <Navbar />
    <slot />
    <Footer />

</template>
