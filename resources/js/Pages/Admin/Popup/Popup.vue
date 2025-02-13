<script>
import Background from "@/Components/Background.vue";
import Card from "@/Components/Card.vue";
import { Link } from "@inertiajs/vue3";
import AddButton from "@/Components/UI/AddButton.vue";

export default {
  components: {
    Background,
    Card,
    Link,
    AddButton,
  },
  props: {
    popups: Array,
  },
  data() {
    return {
      isModalOpen: false,
      isEditMode: false,
      popup: {
        id: null,
        image_popup: null,
      },
    };
  },
  methods: {
    openModal(isEditMode, popup = null) {
      this.isEditMode = isEditMode;
      this.isModalOpen = true;
      if (isEditMode && popup) {
        this.popup = { ...popup };
      } else {
        this.popup = { image_popup: '', id: null };
      }
      console.log('openModal:', this.popup);  // Log popup object
    },

    closeModal() {
      this.isModalOpen = false;
      this.isEditMode = false;
      console.log('Modal closed');
    },

    async handleSubmit() {
      console.log('handleSubmit called. Edit Mode:', this.isEditMode);
      if (this.isEditMode) {
        await this.updatePopup();
      } else {
        await this.uploadPopup();
      }
    },

    async uploadPopup() {
      console.log('Uploading Popup Image...');
      const formData = new FormData();
      formData.append('image_popup', this.$refs.file.files[0]);

      if (!formData.get('image_popup')) {
        alert('Please select an image to upload.');
        return;
      }

      console.log('Form data prepared for upload:', formData); // Log form data before sending
      try {
        await this.$inertia.post('/popup', formData);
        console.log('Image upload successful');
        this.closeModal();
      } catch (error) {
        console.error('Upload failed:', error);
        alert('Upload failed. Please try again.');
      }
    },

    async updatePopup() {
      console.log('Updating Popup Image...');
      const formData = new FormData();
      
      if (this.$refs.file.files[0]) {
          formData.append('image_popup', this.$refs.file.files[0]);
          // Tambahkan _method untuk override HTTP method
          formData.append('_method', 'PUT');
          
          console.log('Form data prepared for update:', formData);
          
          try {
              // Gunakan post instead of put karena mengirim file
              await this.$inertia.post(`/popup/${this.popup.id}`, formData);
              console.log(`Popup ${this.popup.id} updated successfully.`);
              this.closeModal();
          } catch (error) {
              console.error('Update failed:', error);
              alert('Update failed. Please try again.');
          }
      } else {
          alert('Please select an image to update.');
          return;
      }
    },

    async deletePopup(id) {
      const confirmed = confirm('Are you sure you want to delete this popup?');
      if (confirmed) {
        console.log(`Deleting Popup ID: ${id}`);
        try {
          await this.$inertia.delete(`/popup/${id}`);
          console.log(`Popup ID: ${id} deleted successfully.`);
        } catch (error) {
          console.error('Delete failed:', error);
          alert('Delete failed. Please try again.');
        }
      }
    },
  },
};
</script>

<template>
  <div>
    <Background />
    <div class="text-sm text-[#99cbc0] font-bold py-2 sm:py-4 px-4 sm:px-6 lg:ml-20 md:ml-10 sm:ml-5 ml-2">
      <Link href="/" class="text-[#D4A017] no-underline">Beranda</Link> > Upload Popup Images
    </div>

    <div class="content relative z-10 p-4 sm:p-5 text-gray-800">
      <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-center text-[#D4A017] mb-4 sm:mb-6">UPLOAD POPUP IMAGES</h1>
      <AddButton 
        @click="openModal(false)" 
        buttonText="Upload New Image" 
        customClass="text-xs sm:text-sm py-1 px-2 sm:py-2 sm:px-4 w-24 sm:w-32"
      />

      <Card class="mt-4 sm:mt-6">
        <div class="overflow-x-auto">
          <table class="w-full mx-auto table-auto border-collapse">
            <thead>
              <tr>
                <th class="py-2 px-2 sm:px-4 border-b text-center text-sm sm:text-base">Popup Image</th>
                <th class="py-2 px-2 sm:px-4 border-b text-center text-sm sm:text-base">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="popup in popups" :key="popup.id">
                <td class="py-2 px-2 sm:px-4 border-t border-b text-center">
                  <img :src="'/storage/' + popup.image_popup" alt="Popup Image" class="w-24 h-24 sm:w-32 sm:h-32 object-cover mx-auto max-w-full" />
                </td>
                <td class="py-2 px-2 sm:px-4 border-t border-b text-center">
                  <div class="flex flex-col sm:flex-row justify-center items-center space-y-2 sm:space-y-0 sm:space-x-2">
                    <button
                      class="edit bg-teal-700 text-white font-bold py-1 sm:py-2 px-2 sm:px-4 rounded w-16 sm:w-20 text-xs sm:text-sm"
                      @click="openModal(true, popup)"
                    >
                      Edit
                    </button>
                    <button
                      class="delete bg-red-600 text-white font-bold py-1 sm:py-2 px-2 sm:px-4 rounded w-16 sm:w-20 text-xs sm:text-sm"
                      @click="deletePopup(popup.id)"
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
          {{ isEditMode ? 'Edit Popup Image' : 'Upload Popup Image' }}
        </h2>

        <form @submit.prevent="handleSubmit">
          <div class="mb-4">
            <label for="image_popup" class="block text-sm font-medium text-gray-700">Image</label>
            <input 
              type="file" 
              ref="file" 
              id="image_popup" 
              class="mt-2 w-full text-sm border rounded-md" 
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

/* Pastikan gambar tidak melebihi lebar container */
img {
  max-width: 100%;
  height: auto;
}

/* Tambahkan overflow-x: auto untuk tabel pada layar kecil */
.overflow-x-auto {
  overflow-x: auto;
}
</style>