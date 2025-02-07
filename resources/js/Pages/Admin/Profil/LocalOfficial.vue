<template>
    <div>
      <!-- Gunakan komponen Background di sini -->
      <Background />
  
      <!-- Breadcrumb -->
      <div class="text-sm text-[#99cbc0] font-bold p-5">
        <Link href="/" class="text-yellow-500 no-underline">Beranda</Link> > Local Official
      </div>
  
      <div class="relative z-1 p-5 text-gray-800">
        <h1 class="text-center text-4xl text-yellow-500 font-bold mt-12">LOCAL OFFICIAL</h1>
  
        <!-- Tombol untuk membuka modal Add -->
       
        <AddButton @click="openAddModal" buttonText="Add New Pejabat" />

  
        <!-- Card yang berisi daftar pejabat -->
        <Card>
          <table class="w-11/12 mx-auto table-auto border-collapse">
            <thead>
              <tr>
                <th class="py-2 px-4 border-b text-center">Nama</th>
                <th class="py-2 px-4 border-b text-center">Jabatan</th>
                <th class="py-2 px-4 border-b text-center" style="width: 150px;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="pejabat in pejabats" :key="pejabat.id">
                <td class="py-2 px-4 border-t border-b">{{ pejabat.name }}</td>
                <td class="py-2 px-4 border-t border-b">{{ pejabat.position }}</td>
                <td class="py-2 px-4 border-t border-b flex justify-center items-center gap-2">
                  <!-- Button untuk Edit -->
                  <button @click="openEditModal(pejabat)" class="bg-teal-700 text-white font-bold py-2 px-4 rounded transition duration-300 hover:bg-[#99cbc0]">
                    Edit
                  </button>
  
                  <!-- Button untuk Delete -->
                  <button @click="deletePejabat(pejabat.id)" class="bg-red-600 text-white font-bold py-2 px-4 rounded transition duration-300 hover:bg-red-400">
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
        <h2 class="text-center text-2xl font-bold text-yellow-500 mb-4">{{ formType === 'add' ? 'Add Pejabat' : 'Edit Pejabat' }}</h2>
        
        <form @submit.prevent="handleSubmit">
          <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
            <input v-model="formData.name" type="text" id="name" class="w-full mt-2 p-2 border rounded-md" placeholder="Nama Pejabat" required />
          </div>

          <div class="mb-4">
            <label for="address" class="block text-sm font-medium text-gray-700">Jabatan</label>
            <input v-model="formData.position" type="text" id="address" class="w-full mt-2 p-2 border rounded-md" placeholder="Jabatan Pejabat" required />
          </div>

          <div class="flex justify-end gap-4">
            <button type="button" @click="closeModal" class="bg-gray-300 text-black py-2 px-4 rounded">Batal</button>
         <button 
              type="submit" 
              class="w-full py-2 px-4 text-white font-bold bg-yellow-500 rounded-lg hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-offset-2 transition duration-150 ease-in-out"
            >
              {{ isEditMode ? 'Update' : 'Add' }}
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
import AddButton from "@/Components/AddButton.vue";

export default {
  components: {
    Background,
    Card,
    AddButton,
    Link,
  },
  props: {
    pejabats: Array,
  },
  data() {
    return {
      isModalOpen: false,
      formType: 'add', // Default to 'add' for adding new pejabat
      selectedPejabat: null,
      formData: {
        name: '',
        position: '',
      },
    };
  },
  methods: {
    async deletePejabat(id) {
      if (confirm("Apakah Anda yakin ingin menghapus pejabat ini?")) {
        await this.$inertia.delete(`/local_official/${id}`);
      }
    },
    openAddModal() {
      this.formType = 'add';
      this.selectedPejabat = null;
      this.formData = { name: '', position: '' }; // Reset form
      this.isModalOpen = true;
    },
    openEditModal(pejabat) {
      this.formType = 'edit';
      this.selectedPejabat = pejabat;
      this.formData = { name: pejabat.name, position: pejabat.position };
      this.isModalOpen = true;
    },
    closeModal() {
      this.isModalOpen = false;
    },
    async handleSubmit() {
      if (this.formType === 'add') {
        // Handle adding new pejabat
        await this.$inertia.post('/local_official', this.formData);
      } else {
        // Handle editing existing pejabat
        await this.$inertia.put(`/local_official/${this.selectedPejabat.id}`, this.formData);
      }
      this.closeModal();
    },
  },
};
</script>
