<template>
    <div class="p-4">
      <h2 class="text-lg font-bold mb-4">Uploaded Media</h2>
      <div class="grid grid-cols-3 gap-4">
        <div v-for="item in media" :key="item.id" class="border p-2">
          <a :href="item.url" target="_blank">
            <img :src="`/storage/${item.image}`" class="w-full h-32 object-cover" />
          </a>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  defineProps({ media: Array });
  </script>
  