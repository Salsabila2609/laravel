<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import Background from '@/Components/Background.vue';
import AddButton from '@/Components/UI/AddButton.vue';
import Card from '@/Components/Card.vue';
import { Link } from '@inertiajs/vue3';

defineProps({ videos: Array });

const form = ref({
  title: '',
  video: null,
});

const editForm = ref({
  id: null,
  title: '',
  video: null,
});

const isModalOpen = ref(false);
const isEditMode = ref(false);

const openModal = (isEdit, video = null) => {
  isModalOpen.value = true;
  isEditMode.value = isEdit;
  if (isEdit && video) {
    editForm.value = { ...video };
  } else {
    form.value = { title: '', video: null };
  }
};

const closeModal = () => {
  isModalOpen.value = false;
  isEditMode.value = false;
  editForm.value = { id: null, title: '', video: null };
  form.value = { title: '', video: null };
};

const uploadVideo = () => {
  const formData = new FormData();
  formData.append('title', form.value.title);
  formData.append('video', form.value.video);

  router.post('/videos', formData, {
    onSuccess: () => {
      alert('Video berhasil diupload!');
      closeModal();
    },
  });
};

const updateVideo = () => {
  const formData = new FormData();
  formData.append('title', editForm.value.title);
  if (editForm.value.video) {
    formData.append('video', editForm.value.video);
  }

  router.post(`/videos/${editForm.value.id}`, formData, {
    method: 'post',
    headers: { 'X-HTTP-Method-Override': 'PUT' },
    onSuccess: () => {
      alert('Video berhasil diperbarui!');
      closeModal();
    },
  });
};

const deleteVideo = (id) => {
  if (confirm('Yakin ingin menghapus video ini?')) {
    router.delete(`/videos/${id}`, {
      onSuccess: () => alert('Video berhasil dihapus!'),
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
    <div class="text-sm text-[#99CBC0] font-bold py-2 sm:py-4 px-4 sm:px-6 lg:ml-20 md:ml-10 sm:ml-5 ml-2">
      <Link href="/dashboard" class="text-[#D4A017] no-underline">Dashboard</Link> > Upload Video
    </div>

    <div class="content relative z-10 p-4 sm:p-5 text-gray-800">
      <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-center text-[#D4A017] mb-4 sm:mb-6">UPLOAD VIDEO</h1>
      <AddButton 
  buttonText="Upload New Video" 
  customClass="text-xs sm:text-sm py-1 px-2 sm:py-2 sm:px-4 w-24 sm:w-32"
  @open-modal="openModal(false)"
/>

      <Card class="mt-4 sm:mt-6">
        <table class="w-11/12 mx-auto table-auto border-collapse">
          <thead>
            <tr>
              <th class="py-2 px-2 sm:px-4 border-b text-center text-sm sm:text-base">Video Title</th>
              <th class="py-2 px-2 sm:px-4 border-b text-center text-sm sm:text-base">Video Preview</th>
              <th class="py-2 px-2 sm:px-4 border-b text-center text-sm sm:text-base">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="video in videos" :key="video.id">
              <td class="py-2 px-2 sm:px-4 border-t border-b text-center text-sm sm:text-base">{{ video.title }}</td>
              <td class="py-2 px-2 sm:px-4 border-t border-b text-center">
                <video :src="`/storage/${video.video_path}`" controls class="w-48 sm:w-64 h-28 sm:h-36 mx-auto"></video>
              </td>
                <td class="py-2 px-2 sm:px-4 border-t border-b text-center">
                <div class="flex flex-col sm:flex-row justify-center items-center space-y-2 sm:space-y-0 sm:space-x-2">
                    <button
                    class="edit bg-teal-700 text-white font-bold py-1 sm:py-2 px-2 sm:px-4 rounded w-16 sm:w-20 text-xs sm:text-sm"
                    @click="openModal(true, video)"
                    >
                    Edit
                    </button>
                    <button
                    class="delete bg-red-600 text-white font-bold py-1 sm:py-2 px-2 sm:px-4 rounded w-16 sm:w-20 text-xs sm:text-sm"
                    @click="deleteVideo(video.id)"
                    >
                    Delete
                    </button>
                </div>

              </td>
            </tr>
          </tbody>
        </table>
      </Card>
    </div>

    <!-- MODAL -->
    <div v-if="isModalOpen" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
      <div class="bg-white p-4 sm:p-6 rounded-lg shadow-lg w-11/12 sm:w-full max-w-md">
        <h2 class="text-center text-xl sm:text-2xl font-bold text-yellow-500 mb-4">
          {{ isEditMode ? 'Edit Video' : 'Upload Video' }}
        </h2>

        <form @submit.prevent="isEditMode ? updateVideo() : uploadVideo()">
          <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
            <input
              type="text"
              :value="getForm().title"
              @input="getForm().title = $event.target.value"
              id="title"
              class="mt-2 w-full text-sm border rounded-md p-2"
              placeholder="Masukkan Judul Video"
              required
            />
          </div>
          <div class="mb-4">
            <label for="video" class="block text-sm font-medium text-gray-700">Video</label>
            <input
              type="file"
              @change="(e) => (isEditMode ? (editForm.video = e.target.files[0]) : (form.video = e.target.files[0]))"
              id="video"
              class="mt-2 w-full text-sm border rounded-md"
              :required="!isEditMode"
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
.flex {
  display: flex;
  align-items: center;
}

td {
  height: 100px;
  vertical-align: middle;
}
</style>