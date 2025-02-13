<template>
  <div class="flex justify-end mb-5 pr-5 w-full max-w-6xl mx-auto">
    <button 
      @click="openModal" 
      class="add-button whitespace-nowrap text-xs sm:text-sm py-1 px-2 sm:py-2 sm:px-4 w-auto"
    >
      {{ buttonText }}
    </button>
  </div>
</template>

<script>
export default {
  props: {
    buttonText: {
      type: String,
      default: "Add New", // Teks tombol dengan default
    },
  },
  methods: {
    openModal() {
      this.$emit('open-modal'); // Emit event untuk membuka modal
    }
  }
};
</script>

<style scoped>
.add-button {
  @apply inline-block bg-yellow-600 text-white font-bold border-none rounded-md cursor-pointer shadow-md transition-all duration-300 ease-in-out;
}

.add-button:hover {
  @apply bg-gray-100 text-yellow-600 transform translate-y-[-2px];
}

.add-button:active {
  @apply bg-gray-200 transform translate-y-0;
}
</style>