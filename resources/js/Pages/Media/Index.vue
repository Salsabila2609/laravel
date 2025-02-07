<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

defineProps({ media: Array });

const form = ref({
  image: null,
  url: '',
});

const editForm = ref({
  id: null,
  image: null,
  url: '',
});

const uploadMedia = () => {
  const formData = new FormData();
  formData.append('image', form.value.image);
  formData.append('url', form.value.url);

  router.post('/media', formData, {
    onSuccess: () => {
      alert('File berhasil diupload!');
      form.value.image = null;
      form.value.url = '';
    }
  });
};

const startEdit = (item) => {
  editForm.value.id = item.id;
  editForm.value.url = item.url;
};

const updateMedia = () => {
  const formData = new FormData();
  formData.append('url', editForm.value.url);
  if (editForm.value.image) {
    formData.append('image', editForm.value.image);
  }

  router.post(`/media/${editForm.value.id}`, formData, {
    method: 'post',
    headers: { 'X-HTTP-Method-Override': 'PUT' }, // Override untuk PUT request
    onSuccess: () => {
      alert('Media berhasil diperbarui!');
      editForm.value.id = null;
      editForm.value.image = null;
      editForm.value.url = '';
    }
  });
};

const deleteMedia = (id) => {
  if (confirm('Yakin ingin menghapus media ini?')) {
    router.delete(`/media/${id}`, {
      onSuccess: () => alert('Media berhasil dihapus!')
    });
  }
};
</script>

<template>
  <div class="p-4">
    <h2 class="text-lg font-bold mb-4">Upload Media</h2>

    <form @submit.prevent="uploadMedia" class="space-y-4">
      <input type="file" @change="(e) => form.image = e.target.files[0]" required />
      <input type="text" v-model="form.url" placeholder="Masukkan URL" required class="border p-2 w-full" />
      <button type="submit" class="bg-blue-500 text-white px-4 py-2">Upload</button>
    </form>

    <h2 class="text-lg font-bold mt-6">Uploaded Media</h2>
    <div class="grid grid-cols-3 gap-4">
      <div v-for="item in media" :key="item.id" class="border p-2">
        <a :href="item.url" target="_blank">
          <img :src="`/storage/${item.image}`" class="w-full h-32 object-cover" />
        </a>
        <div class="mt-2">
          <button @click="startEdit(item)" class="bg-yellow-500 text-white px-2 py-1">Edit</button>
          <button @click="deleteMedia(item.id)" class="bg-red-500 text-white px-2 py-1 ml-2">Delete</button>
        </div>
      </div>
    </div>

    <!-- FORM EDIT -->
    <div v-if="editForm.id" class="mt-6 border p-4 rounded-lg bg-gray-100">
      <h2 class="text-lg font-bold mb-4">Edit Media</h2>
      <form @submit.prevent="updateMedia" class="space-y-4">
        <input type="file" @change="(e) => editForm.image = e.target.files[0]" class="border p-2 w-full" />
        <input type="text" v-model="editForm.url" required class="border p-2 w-full" />
        <button type="submit" class="bg-green-500 text-white px-4 py-2">Update</button>
        <button @click="editForm.id = null" type="button" class="bg-gray-500 text-white px-4 py-2 ml-2">Cancel</button>
      </form>
    </div>
  </div>
</template>
