<script setup>
defineProps({ media: Array }); 
</script>

<template>
  <div class="p-4">
    <div class="grid grid-cols-3 gap-4">
      <div v-for="item in media" :key="item.id" class="border p-2 rounded-lg">
        <a :href="item.url" target="_blank">
          <img :src="`/storage/${item.image}`" class="w-full h-32 object-cover rounded-md" /> 
          <!-- ✅ Pastikan gunakan "item.image" jika di database kolomnya "image" -->
          <p class="mt-2 text-center font-semibold text-blue-600 hover:underline">{{ item.title }}</p>
        </a>
      </div>
    </div>
  </div>
</template>
