<script setup>
import { computed } from 'vue';

const props = defineProps({
  currentPage: {
    type: Number,
    required: true,
  },
  totalPages: {
    type: Number,
    required: true,
  },
  onPageChange: {
    type: Function,
    required: true,
  },
});

const pageNumbers = computed(() => {
  const pages = [];
  const maxVisiblePages = 5;
  let startPage = Math.max(1, props.currentPage - Math.floor(maxVisiblePages / 2));
  let endPage = Math.min(props.totalPages, startPage + maxVisiblePages - 1);

  if (endPage - startPage + 1 < maxVisiblePages) {
    startPage = Math.max(1, endPage - maxVisiblePages + 1);
  }

  for (let i = startPage; i <= endPage; i++) {
    pages.push(i);
  }
  return pages;
});

const handlePageChange = (page) => {
  if (page !== props.currentPage) {
    props.onPageChange(page);
  }
};
</script>

<template>
  <div class="flex justify-center items-center gap-2 mt-8">
    <!-- First Page -->
    <button
      @click="handlePageChange(1)"
      :disabled="currentPage === 1"
      class="p-2 rounded-md transition-all duration-200"
      :class="currentPage === 1 ? 'text-gray-400 cursor-not-allowed' : 'text-[#268B79] hover:bg-gray-100'"
    >
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M15.707 15.707a1 1 0 01-1.414 0l-5-5a1 1 0 010-1.414l5-5a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 010 1.414zm-6 0a1 1 0 01-1.414 0l-5-5a1 1 0 010-1.414l5-5a1 1 0 011.414 1.414L5.414 10l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
      </svg>
    </button>

    <!-- Previous Page -->
    <button
      @click="handlePageChange(currentPage - 1)"
      :disabled="currentPage === 1"
      class="p-2 rounded-md transition-all duration-200"
      :class="currentPage === 1 ? 'text-gray-400 cursor-not-allowed' : 'text-[#268B79] hover:bg-gray-100'"
    >
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M12.707 15.707a1 1 0 01-1.414 0l-5-5a1 1 0 010-1.414l5-5a1 1 0 011.414 1.414L8.414 10l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
      </svg>
    </button>

    <!-- Page Numbers -->
    <div class="flex gap-1">
      <button
        v-for="page in pageNumbers"
        :key="page"
        @click="handlePageChange(page)"
        class="min-w-[2.5rem] h-10 rounded-md transition-all duration-200 text-sm font-medium"
        :class="currentPage === page ? 
          'bg-[#268B79] text-white' : 
          'bg-gray-100 text-gray-600 hover:bg-gray-200'"
      >
        {{ page }}
      </button>
    </div>

    <!-- Next Page -->
    <button
      @click="handlePageChange(currentPage + 1)"
      :disabled="currentPage === totalPages"
      class="p-2 rounded-md transition-all duration-200"
      :class="currentPage === totalPages ? 'text-gray-400 cursor-not-allowed' : 'text-[#268B79] hover:bg-gray-100'"
    >
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M7.293 15.707a1 1 0 001.414 0l5-5a1 1 0 000-1.414l-5-5a1 1 0 00-1.414 1.414L11.586 10l-4.293 4.293a1 1 0 000 1.414z" clip-rule="evenodd" />
        </svg>
    </button>

    <!-- Last Page -->
    <button
      @click="handlePageChange(totalPages)"
      :disabled="currentPage === totalPages"
      class="p-2 rounded-md transition-all duration-200"
      :class="currentPage === totalPages ? 'text-gray-400 cursor-not-allowed' : 'text-[#268B79] hover:bg-gray-100'"
    >
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M4.293 15.707a1 1 0 001.414 0l5-5a1 1 0 000-1.414l-5-5a1 1 0 00-1.414 1.414L8.586 10l-4.293 4.293a1 1 0 000 1.414zm6 0a1 1 0 001.414 0l5-5a1 1 0 000-1.414l-5-5a1 1 0 00-1.414 1.414L14.586 10l-4.293 4.293a1 1 0 000 1.414z" clip-rule="evenodd" />
      </svg>
    </button>
  </div>
</template>
