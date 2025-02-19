<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import Background from '@/Components/UI/Background.vue';
import AddButton from '@/Components/UI/AddButton.vue'; 
import Card from '@/Components/UI/Card.vue'; 
import { Link } from '@inertiajs/vue3'; 

defineProps({ media: Array });

const form = ref({
  title: '', // Menambahkan title
  image: null,
  url: '',
});

const editForm = ref({
  id: null,
  title: '', // Menambahkan title untuk edit
  image: null,
  url: '',
});

const isModalOpen = ref(false);
const isEditMode = ref(false);

const openModal = (isEdit, item = null) => {
  isModalOpen.value = true;
  isEditMode.value = isEdit;
  if (isEdit && item) {
    editForm.value = { ...item };
  } else {
    form.value = { title: '', image: null, url: '' };
  }
};

const closeModal = () => {
  isModalOpen.value = false;
  isEditMode.value = false;
  editForm.value = { id: null, title: '', image: null, url: '' };
  form.value = { title: '', image: null, url: '' };
};

const uploadMedia = () => {
  const formData = new FormData();
  formData.append('title', form.value.title); // Menambahkan title ke form data
  formData.append('image', form.value.image);
  formData.append('url', form.value.url);

  router.post('/media', formData, {
    onSuccess: () => {
      alert('File berhasil diupload!');
      closeModal();
    },
  });
};

const updateMedia = () => {
  const formData = new FormData();
  formData.append('title', editForm.value.title); // Menambahkan title untuk update
  formData.append('url', editForm.value.url);
  if (editForm.value.image) {
    formData.append('image', editForm.value.image);
  }

  router.post(`/media/${editForm.value.id}`, formData, {
    method: 'post',
    headers: { 'X-HTTP-Method-Override': 'PUT' },
    onSuccess: () => {
      alert('Media berhasil diperbarui!');
      closeModal();
    },
  });
};

const deleteMedia = (id) => {
  if (confirm('Yakin ingin menghapus media ini?')) {
    router.delete(`/media/${id}`, {
      onSuccess: () => alert('Media berhasil dihapus!'),
    });
  }
};

const getForm = () => {
  return isEditMode.value ? editForm.value : form.value;
};
</script>

<template>
  <div>
    <Background />  
      <div class="text-sm text-[#99cbc0] font-bold p-5">
        <Link href="/dashboard" class="text-yellow-500 no-underline">Beranda</Link> > Kecamatan Se-Kabupaten
      </div>

    <div class="content relative z-10 p-4 sm:p-5 text-gray-800">
      <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-center text-[#D4A017] mb-4 sm:mb-6">UPLOAD MEDIA</h1>
      <AddButton 
        buttonText="Upload New Media" 
        customClass="text-xs sm:text-sm py-1 px-2 sm:py-2 sm:px-4 w-24 sm:w-32"
        @click="openModal(false)"
      />

      <Card class="mt-4 sm:mt-6">
        <div class="overflow-x-auto">
          <table class="w-full mx-auto table-auto border-collapse">
            <thead>
              <tr>
                <th class="py-2 px-2 sm:px-4 border-b text-center text-sm sm:text-base">Title</th>
                <th class="py-2 px-2 sm:px-4 border-b text-center text-sm sm:text-base">Media Image</th>
                <th class="py-2 px-2 sm:px-4 border-b text-center text-sm sm:text-base">URL</th>
                <th class="py-2 px-2 sm:px-4 border-b text-center text-sm sm:text-base">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in media" :key="item.id">
                <td class="py-2 px-2 sm:px-4 border-t border-b text-center">
                  {{ item.title }} <!-- Menampilkan title -->
                </td>
                <td class="py-2 px-2 sm:px-4 border-t border-b text-center">
                  <img :src="`/storage/${item.image}`" alt="Media Image" class="w-24 h-24 sm:w-32 sm:h-32 object-cover mx-auto max-w-full" />
                </td>
                <td class="py-2 px-2 sm:px-4 border-t border-b text-center text-sm sm:text-base">
                  <a :href="item.url" target="_blank" class="text-blue-500 hover:underline break-words">{{ item.url }}</a>
                </td>
                <td class="py-2 px-2 sm:px-4 border-t border-b text-center">
                  <div class="flex flex-col sm:flex-row justify-center items-center space-y-2 sm:space-y-0 sm:space-x-2">
                    <button
                      class="edit bg-teal-700 text-white font-bold py-1 sm:py-2 px-2 sm:px-4 rounded w-16 sm:w-20 text-xs sm:text-sm"
                      @click="openModal(true, item)"
                    >
                      Edit
                    </button>
                    <button
                      class="delete bg-red-600 text-white font-bold py-1 sm:py-2 px-2 sm:px-4 rounded w-16 sm:w-20 text-xs sm:text-sm"
                      @click="deleteMedia(item.id)"
                    >
                      Delete
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </Card>
    </div>

    <!-- MODAL -->
    <div v-if="isModalOpen" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
      <div class="bg-white p-4 sm:p-6 rounded-lg shadow-lg w-11/12 sm:w-full max-w-md">
        <h2 class="text-center text-xl sm:text-2xl font-bold text-yellow-500 mb-4">
          {{ isEditMode ? 'Edit Media' : 'Upload Media' }}
        </h2>

        <form @submit.prevent="isEditMode ? updateMedia() : uploadMedia()">
          <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
            <input
              type="text"
              :value="getForm().title"
              @input="getForm().title = $event.target.value"
              id="title"
              class="mt-2 w-full text-sm border rounded-md p-2"
              placeholder="Masukkan Title"
              required
            />
          </div>

          <div class="mb-4">
            <label for="image" class="block text-sm font-medium text-gray-700">Image</label>
            <input
              type="file"
              @change="(e) => (isEditMode ? (editForm.image = e.target.files[0]) : (form.image = e.target.files[0]))"
              id="image"
              class="mt-2 w-full text-sm border rounded-md"
              required
            />
          </div>
          <div class="mb-4">
            <label for="url" class="block text-sm font-medium text-gray-700">URL</label>
            <input
              type="text"
              :value="getForm().url"
              @input="getForm().url = $event.target.value"
              id="url"
              class="mt-2 w-full text-sm border rounded-md p-2"
              placeholder="Masukkan URL"
              required
            />
          </div>

          <div class="flex justify-end gap-4">
            <button
              type="button"
              @click="closeModal"
              class="bg-gray-300 text-black py-1 sm:py-2 px-2 sm:px-4 rounded text-xs sm:text-sm"
            >
              Cancel
            </button>
            <button
              type="submit"
              class="w-full py-1 sm:py-2 px-2 sm:px-4 text-white font-bold bg-yellow-500 rounded-lg hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-offset-2 transition duration-150 ease-in-out text-xs sm:text-sm"
            >
              {{ isEditMode ? 'Save' : 'Upload' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Pastikan semua elemen menggunakan box-sizing: border-box */
* {
  box-sizing: border-box;
}

.flex {
  display: flex;
  align-items: center;
}

td {
  height: 100px;
  vertical-align: middle;
}

/* Pastikan gambar dan elemen lainnya tidak melebihi lebar container */
img, video {
  max-width: 100%;
  height: auto;
}

/* Tambahkan overflow-x: auto untuk tabel pada layar kecil */
.overflow-x-auto {
  overflow-x: auto;
}

/* Pastikan URL tidak keluar dari container */
.break-words {
  word-break: break-word;
}
</style>