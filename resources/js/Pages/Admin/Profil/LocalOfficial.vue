<template>
  <div>
    <Background />
    <div class="text-sm text-[#99cbc0] font-bold p-5">
      <Link href="/dashboard" class="text-yellow-500 no-underline">Beranda</Link> > Local Official
    </div>

    <div class="content relative z-10 p-4 sm:p-5 text-gray-800">
      <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-center text-[#D4A017] mb-4 sm:mb-6">LOCAL OFFICIAL</h1>
      <AddButton 
        @click="openAddModal" 
        buttonText="Add New Pejabat" 
        customClass="text-xs sm:text-sm py-1 px-2 sm:py-2 sm:px-4 w-24 sm:w-32"
      />

      <!-- Card yang berisi daftar pejabat -->
      <Card class="mt-4 sm:mt-6">
        <div class="overflow-x-auto">
          <table class="w-full mx-auto table-auto border-collapse">
            <thead>
              <tr>
                <th class="py-2 px-2 sm:px-4 border-b text-center text-sm sm:text-base min-w-[150px]">Nama</th>
                <th class="py-2 px-2 sm:px-4 border-b text-center text-sm sm:text-base min-w-[150px]">Jabatan</th>
                <th class="py-2 px-2 sm:px-4 border-b text-center text-sm sm:text-base" style="width: 150px;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="pejabat in pejabats" :key="pejabat.id">
                <td class="py-2 px-2 sm:px-4 border-t border-b text-sm sm:text-base">{{ pejabat.name }}</td>
                <td class="py-2 px-2 sm:px-4 border-t border-b text-sm sm:text-base">{{ pejabat.position }}</td>
                <td class="py-2 px-2 sm:px-4 border-t border-b text-center">
                  <div class="flex flex-col sm:flex-row justify-center items-center space-y-2 sm:space-y-0 sm:space-x-2">
                    <button
                      class="edit bg-teal-700 text-white font-bold py-1 sm:py-2 px-2 sm:px-4 rounded w-16 sm:w-20 text-xs sm:text-sm"
                      @click="openEditModal(pejabat)"
                    >
                      Edit
                    </button>
                    <button
                      class="delete bg-red-600 text-white font-bold py-1 sm:py-2 px-2 sm:px-4 rounded w-16 sm:w-20 text-xs sm:text-sm"
                      @click="deletePejabat(pejabat.id)"
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

    <!-- Modal untuk Add/Edit Pejabat -->
    <div v-if="isModalOpen" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
      <div class="bg-white p-4 sm:p-6 rounded-lg shadow-lg w-11/12 sm:w-full max-w-md">
        <h2 class="text-center text-xl sm:text-2xl font-bold text-yellow-500 mb-4">
          {{ formType === 'add' ? 'Add Pejabat' : 'Edit Pejabat' }}
        </h2>
        <form @submit.prevent="handleSubmit">
          <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
            <input v-model="formData.name" type="text" id="name" class="w-full mt-2 p-2 border rounded-md text-sm" placeholder="Nama Pejabat" required />
          </div>
          <div class="mb-4">
            <label for="position" class="block text-sm font-medium text-gray-700">Jabatan</label>
            <input v-model="formData.position" type="text" id="position" class="w-full mt-2 p-2 border rounded-md text-sm" placeholder="Jabatan Pejabat" required />
          </div>
          <div class="flex justify-end gap-4">
            <button
              type="button"
              @click="closeModal"
              class="bg-gray-300 text-black py-1 sm:py-2 px-2 sm:px-4 rounded text-xs sm:text-sm"
            >
              Batal
            </button>
            <button
              type="submit"
              class="w-full py-1 sm:py-2 px-2 sm:px-4 text-white font-bold bg-yellow-500 rounded-lg hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-offset-2 transition duration-150 ease-in-out text-xs sm:text-sm"
            >
              {{ formType === 'add' ? 'Add' : 'Update' }}
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
        await this.$inertia.delete(`/local-official/${id}`);
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
        await this.$inertia.post('/local-official', this.formData);
      } else {
        // Handle editing existing pejabat
        await this.$inertia.put(`/local-official/${this.selectedPejabat.id}`, this.formData);
      }
      this.closeModal();
    },
  },
};
</script>

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
  height: 60px;
  vertical-align: middle;
}

/* Tambahkan overflow-x: auto untuk tabel pada layar kecil */
.overflow-x-auto {
  overflow-x: auto;
}

/* Pastikan teks tidak keluar dari container */
.break-words {
  word-break: break-word;
}
</style>