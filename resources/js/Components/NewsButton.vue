<template>
  <component 
    :is="tag" 
    :href="href" 
    class="news-button" 
    :class="{ active: isActive }"
  >
    <slot />
  </component>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  href: String, 
  isActive: Boolean, // Menerima status aktif dari parent
});

const tag = computed(() => (props.href ? 'a' : 'button'));
</script>

<style scoped>
.news-button {
  @apply inline-flex items-center gap-2 px-4 py-2 text-white rounded-full  text-xs md:text-sm transition duration-300;
  background: linear-gradient(to right, #268B79, #44b6a1);
  display: inline-flex; 
  align-items: center;  
  gap: 8px;             
}

.news-button:hover,
.news-button.active { 
  background: linear-gradient(to right, #396C6D, #396C6D); /* Warna saat aktif */
}
</style>
