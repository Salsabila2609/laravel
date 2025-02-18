<template>
  <div>
    <Background />
    <div class="text-sm text-[#99cbc0] font-bold p-5">
      <Link href="/dashboard" class="text-[#D4A017] no-underline">Beranda</Link> > Upload Document
    </div>

    <div class="content relative z-10 p-5 text-gray-800">
      <h1 class="text-center text-4xl text-[#D4A017] font-bold mt-12">UPLOAD DOCUMENT</h1>
      <AddButton @click="openModal(false)" buttonText="Upload New Document" />

      <Card>
        <table class="w-11/12 mx-auto table-auto border-collapse">
          <thead>
            <tr>
              <th class="py-2 px-4 border-b text-center">Document Name</th>
              <th class="py-2 px-4 border-b text-center">Upload Date</th>
              <th class="py-2 px-4 border-b text-center">Category</th>
              <th class="py-2 px-4 border-b text-center">Year</th>
              <th class="py-2 px-4 border-b text-center" style="width: 150px;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="doc in documents" :key="doc.id">
              <td class="py-2 px-4 border-t border-b text-center">{{ doc.document_name }}</td>
              <td class="py-2 px-4 border-t border-b text-center">{{ doc.upload_date }}</td>
              <td class="py-2 px-4 border-t border-b text-center">{{ doc.category }}</td>
              <td class="py-2 px-4 border-t border-b text-center">{{ doc.year || '-' }}</td>
              <td class="actions py-2 px-4 border-t border-b flex justify-center items-center space-x-2">
                <button class="edit bg-teal-700 text-white font-bold py-2 px-4 rounded" @click="openModal(true, doc)">
                  Edit
                </button>
                <button class="delete bg-red-600 text-white font-bold py-2 px-4 rounded" @click="deleteDocument(doc.id)">
                  Hapus
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </Card>
    </div>

    <div v-if="isModalOpen" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
      <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-center text-2xl font-bold text-yellow-500 mb-4">
          {{ isEditMode ? 'Edit Document' : 'Upload Document' }}
        </h2>

        <form @submit.prevent="handleSubmit">
          <div class="mb-4">
            <label for="document_name" class="block text-sm font-medium text-gray-700">Document Name</label>
            <input 
              v-model="document.document_name" 
              type="text" 
              id="document_name" 
              class="w-full mt-2 p-2 border rounded-md" 
              placeholder="Enter Document Name" 
              required 
            />
          </div>

          <div class="mb-4">
            <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
            <select 
              v-model="document.category" 
              id="category" 
              class="w-full mt-2 p-2 border rounded-md" 
              required
            >
              <option value="">Pilih Kategori</option>
              <option value="Laporan Keuangan">Laporan Keuangan</option>
              <option value="Peraturan Bupati">Peraturan Bupati</option>
              <option value="Data Statistik">Data Statistik</option>
            </select>
          </div>

          <!-- Input Tahun Muncul Jika Kategori Laporan Keuangan -->
          <div class="mb-4" v-if="document.category === 'Laporan Keuangan'">
            <label for="year" class="block text-sm font-medium text-gray-700">Year</label>
            <input 
              v-model="document.year" 
              type="number" 
              id="year" 
              class="w-full mt-2 p-2 border rounded-md" 
              placeholder="Enter Year" 
              min="2000"
              max="2030"
              required 
            />
          </div>

          <div class="mb-4" v-if="!isEditMode">
            <label for="file" class="block text-sm font-medium text-gray-700">Choose File</label>
            <input 
              type="file" 
              ref="file" 
              id="file" 
              class="mt-2 w-full text-sm border rounded-md"
              required 
            />
          </div>

          <div class="flex justify-end gap-4">
            <button 
              type="button" 
              @click="closeModal" 
              class="bg-gray-300 text-black py-2 px-4 rounded"
            >
              Cancel
            </button>
            <button 
              type="submit" 
              class="w-full py-2 px-4 text-white font-bold bg-yellow-500 rounded-lg hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-offset-2 transition duration-150 ease-in-out"
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
        this.document = { document_name: '', category: '', year: null, id: null };
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
      try {
        await this.$inertia.put(`/upload-document/${this.document.id}`, {
          document_name: this.document.document_name,
          category: this.document.category,
          year: this.document.year,
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
          await this.$inertia.delete(`/upload-document/${id}`);
        } catch (error) {
          alert('Delete failed. Please try again.');
        }
      }
    }
  },
};
</script>
