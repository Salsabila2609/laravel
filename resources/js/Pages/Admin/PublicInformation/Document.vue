<template>
  <div>
    <Background />
    <div class="text-sm text-[#99cbc0] font-bold p-4 sm:p-5">
      <Link href="/dashboard" class="text-[#D4A017] no-underline">Beranda</Link> > Upload Document
    </div>

    <div class="content relative z-10 p-4 sm:p-5 text-gray-800">
      <h1 class="text-center text-2xl sm:text-3xl md:text-4xl font-bold text-[#D4A017] mt-8 sm:mt-12">UPLOAD DOCUMENT</h1>
      <AddButton 
        @click="openModal(false)" 
        buttonText="Upload New Document" 
        customClass="text-xs sm:text-sm py-1 px-2 sm:py-2 sm:px-4 w-32 sm:w-40"
      />

      <Card class="mt-4 sm:mt-6">
        <div class="overflow-x-auto">
          <table class="w-full mx-auto table-auto border-collapse">
            <thead>
              <tr>
                <th class="py-2 px-2 sm:px-4 border-b text-center text-sm sm:text-base min-w-[150px]">Document Name</th>
                <th class="py-2 px-2 sm:px-4 border-b text-center text-sm sm:text-base min-w-[120px]">Upload Date</th>
                <th class="py-2 px-2 sm:px-4 border-b text-center text-sm sm:text-base min-w-[120px]">Category</th>
                <th class="py-2 px-2 sm:px-4 border-b text-center text-sm sm:text-base min-w-[80px]">Year</th>
                <th class="py-2 px-2 sm:px-4 border-b text-center text-sm sm:text-base" style="width: 150px;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="doc in documents" :key="doc.id">
                <td class="py-2 px-2 sm:px-4 border-t border-b text-sm sm:text-base text-center">{{ doc.document_name }}</td>
                <td class="py-2 px-2 sm:px-4 border-t border-b text-sm sm:text-base text-center">{{ doc.upload_date }}</td>
                <td class="py-2 px-2 sm:px-4 border-t border-b text-sm sm:text-base text-center">{{ doc.category }}</td>
                <td class="py-2 px-2 sm:px-4 border-t border-b text-sm sm:text-base text-center">{{ doc.year || '-' }}</td>
                <td class="py-2 px-2 sm:px-4 border-t border-b text-center">
                  <div class="flex flex-col sm:flex-row justify-center items-center space-y-2 sm:space-y-0 sm:space-x-2">
                    <button
                      class="edit bg-teal-700 text-white font-bold py-1 sm:py-2 px-2 sm:px-4 rounded w-16 sm:w-20 text-xs sm:text-sm"
                      @click="openModal(true, doc)"
                    >
                      Edit
                    </button>
                    <button
                      class="delete bg-red-600 text-white font-bold py-1 sm:py-2 px-2 sm:px-4 rounded w-16 sm:w-20 text-xs sm:text-sm"
                      @click="deleteDocument(doc.id)"
                    >
                      Hapus
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </Card>
    </div>

    <!-- Modal -->
    <div v-if="isModalOpen" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
      <div class="bg-white p-4 sm:p-6 rounded-lg shadow-lg w-11/12 sm:w-full max-w-md">
        <h2 class="text-center text-xl sm:text-2xl font-bold text-yellow-500 mb-4">
          {{ isEditMode ? 'Edit Document' : 'Upload Document' }}
        </h2>
        <form @submit.prevent="handleSubmit">
          <div class="mb-4">
            <label for="document_name" class="block text-sm font-medium text-gray-700">Document Name</label>
            <input 
              v-model="document.document_name" 
              type="text" 
              id="document_name" 
              class="w-full mt-2 p-2 border rounded-md text-sm" 
              placeholder="Enter Document Name" 
              required 
            />
          </div>
          <div class="mb-4">
            <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
            <select 
              v-model="document.category" 
              id="category" 
              class="w-full mt-2 p-2 border rounded-md text-sm" 
              required
            >
              <option value="">Pilih Kategori</option>
              <option value="Laporan Keuangan">Laporan Keuangan</option>
              <option value="Peraturan Bupati">Peraturan Bupati</option>
              <option value="Data Statistik">Data Statistik</option>
            </select>
          </div>
          <div class="mb-4" v-if="document.category === 'Laporan Keuangan'">
            <label for="year" class="block text-sm font-medium text-gray-700">Year</label>
            <input 
              v-model="document.year" 
              type="number" 
              id="year" 
              class="w-full mt-2 p-2 border rounded-md text-sm" 
              placeholder="Enter Year" 
              min="2000"
              max="2030"
              required 
            />
          </div>
          <div class="mb-4">
            <label for="file" class="block text-sm font-medium text-gray-700">{{ isEditMode ? 'Replace File (Optional)' : 'Choose File' }}</label>
            <input 
              type="file" 
              ref="file" 
              id="file" 
              class="mt-2 w-full text-sm border rounded-md"
              :required="!isEditMode"
            />
            <p v-if="isEditMode && document.file_path" class="text-sm text-gray-500 mt-1">Current file: {{ document.file_path.split('/').pop() }}</p>
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

<script>
import Background from "@/Components/UI/Background.vue";
import Card from "@/Components/UI/Card.vue";
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
    documents: Array,
  },
  data() {
    return {
      isModalOpen: false,
      isEditMode: false,
      document: {
        id: null,
        document_name: '',
        category: '',
        year: null,
        file_path: '',
      },
    };
  },
  methods: {
    openModal(isEditMode, document = null) {
      this.isEditMode = isEditMode;
      this.isModalOpen = true;
      if (isEditMode && document) {
        this.document = { ...document };
      } else {
        this.document = { document_name: '', category: '', year: null, file_path: '', id: null };
      }
    },
    closeModal() {
      this.isModalOpen = false;
      this.isEditMode = false;
    },
    async handleSubmit() {
      if (this.isEditMode) {
        await this.updateDocument();
      } else {
        await this.uploadDocument();
      }
    },
    async uploadDocument() {
      const formData = new FormData();
      formData.append('document_name', this.document.document_name);
      formData.append('file', this.$refs.file.files[0]);
      formData.append('category', this.document.category);
      if (this.document.category === 'Laporan Keuangan') {
        formData.append('year', this.document.year);
      }

      if (!formData.get('file')) {
        alert('Please select a file to upload.');
        return;
      }

      try {
        await this.$inertia.post('/document', formData);
        this.closeModal();
      } catch (error) {
        alert('Upload failed. Please try again.');
      }
    },
    async updateDocument() {
      const formData = new FormData();
      formData.append('document_name', this.document.document_name);
      formData.append('category', this.document.category);
      if (this.document.category === 'Laporan Keuangan') {
        formData.append('year', this.document.year);
      }

      if (this.$refs.file.files[0]) {
        formData.append('file', this.$refs.file.files[0]);
      }

      try {
        await this.$inertia.post(`/document/${this.document.id}`, formData, {
          method: 'post',
          headers: { 'X-HTTP-Method-Override': 'PUT' },
        });
        this.closeModal();
      } catch (error) {
        alert('Update failed. Please try again.');
      }
    },
    async deleteDocument(id) {
      const confirmed = confirm('Are you sure you want to delete this document?');
      if (confirmed) {
        try {
          await this.$inertia.delete(`/document/${id}`);
        } catch (error) {
          alert('Delete failed. Please try again.');
        }
      }
    }
  },
};
</script>

<style scoped>
* {
  box-sizing: border-box;
}

.flex {
  display: flex;
  align-items: center;
}

td {
  height: 60px;
  vertical-align: middle;
}

.overflow-x-auto {
  overflow-x: auto;
}

.break-words {
  word-break: break-word;
}
</style>