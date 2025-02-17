<template>
  <div>
    <Background />
      <div class="text-sm text-[#99cbc0] font-bold p-5">
        <Link href="/dashboard" class="text-yellow-500 no-underline">Beranda</Link> > Kecamatan Se-Kabupaten
      </div>

    <div class="content relative z-10 p-4 sm:p-5 text-gray-800">
      <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-center text-[#D4A017] mb-4 sm:mb-6">KECAMATAN SE-KABUPATEN</h1>
      <AddButton 
        @click="openAddModal" 
        buttonText="Add New Kabupaten" 
        customClass="text-xs sm:text-sm py-1 px-2 sm:py-2 sm:px-4 w-24 sm:w-32"
      />

      <Card class="mt-4 sm:mt-6">
        <div class="overflow-x-auto">
          <table class="w-full mx-auto table-auto border-collapse">
            <thead>
              <tr>
                <th class="py-2 px-2 sm:px-4 border-b text-center text-sm sm:text-base min-w-[150px]">Kecamatan</th>
                <th class="py-2 px-2 sm:px-4 border-b text-center text-sm sm:text-base min-w-[150px]">Alamat</th>
                <th class="py-2 px-2 sm:px-4 border-b text-center text-sm sm:text-base">Kontak</th>
                <th class="py-2 px-2 sm:px-4 border-b text-center text-sm sm:text-base" style="width: 150px;">Aksi</th>
              </tr>
            </thead>

            
            <tbody>
              <tr v-for="kecamatan in kecamatans" :key="kecamatan.id">
                <td class="py-2 px-2 sm:px-4 border-t border-b text-sm sm:text-base">
                  <a :href="kecamatan.link || '#'" class="text-blue-500 hover:underline break-words" target="_blank">
                    {{ kecamatan.name }}
                  </a>
                </td>
                <td class="py-2 px-2 sm:px-4 border-t border-b text-sm sm:text-base">{{ kecamatan.address }}</td>
                <td class="py-2 px-2 sm:px-4 border-t border-b text-sm sm:text-base">{{ kecamatan.contact }}</td>
                <td class="py-2 px-2 sm:px-4 border-t border-b text-center">
                  <div class="flex flex-col sm:flex-row justify-center items-center space-y-2 sm:space-y-0 sm:space-x-2">
                    <button
                      class="edit bg-teal-700 text-white font-bold py-1 sm:py-2 px-2 sm:px-4 rounded w-16 sm:w-20 text-xs sm:text-sm"
                      @click="openEditModal(kecamatan)"
                    >
                      Edit
                    </button>
                    <button
                      class="delete bg-red-600 text-white font-bold py-1 sm:py-2 px-2 sm:px-4 rounded w-16 sm:w-20 text-xs sm:text-sm"
                      @click="deleteKecamatan(kecamatan.id)"
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

      
      <!-- Modal for Add/Edit Kecamatan -->
      <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
        <div class="bg-white p-4 sm:p-6 rounded-lg shadow-lg w-11/12 sm:w-full max-w-md">
          <h2 class="text-center text-xl sm:text-2xl font-bold text-yellow-500 mb-4">
            {{ isEditMode ? 'Edit Kecamatan' : 'Add New Kecamatan' }}
          </h2>
          <form @submit.prevent="handleSubmit">
            <div class="mb-4">
              <label for="name" class="block text-sm font-medium text-gray-700">Nama Kecamatan</label>
              <input v-model="form.name" type="text" id="name" class="w-full mt-2 p-2 border rounded-md text-sm" placeholder="Nama Kecamatan" required />
            </div>
            <div class="mb-4">
              <label for="address" class="block text-sm font-medium text-gray-700">Alamat</label>
              <input v-model="form.address" type="text" id="address" class="w-full mt-2 p-2 border rounded-md text-sm" placeholder="Alamat Kecamatan" required />
            </div>
            <div class="mb-4">
              <label for="contact" class="block text-sm font-medium text-gray-700">Kontak</label>
              <input v-model="form.contact" type="text" id="contact" class="w-full mt-2 p-2 border rounded-md text-sm" placeholder="Kontak Kecamatan" required />
            </div>
            <div class="mb-4">
              <label for="link" class="block text-sm font-medium text-gray-700">Link</label>
              <input v-model="form.link" type="text" id="link" class="w-full mt-2 p-2 border rounded-md text-sm" placeholder="Link Kecamatan" />
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
                {{ isEditMode ? 'Update' : 'Add' }}
              </button>
            </div>
          </form>
        </div>
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
    Link,
    AddButton,
  },
  props: {
    kecamatans: Array,
  },
  data() {
    return {
      showModal: false,
      isEditMode: false,
      form: {
        name: '',
        address: '',
        contact: '',
        link: '',
      },
      editId: null,
    };
  },
  methods: {
    openAddModal() {
      this.isEditMode = false;
      this.form = { name: '', address: '', contact: '', link: '' };
      this.showModal = true;
    },
    openEditModal(kecamatan) {
      this.isEditMode = true;
      this.form = { ...kecamatan };
      this.editId = kecamatan.id;
      this.showModal = true;
    },
    closeModal() {
      this.showModal = false;
      this.form = { name: '', address: '', contact: '', link: '' };
      this.editId = null;
    },
    deleteKecamatan(id) {
      if (confirm("Apakah Anda yakin ingin menghapus kecamatan ini?")) {
        this.$inertia.delete(`/subdistrict/${id}`);
      }
    },
    handleSubmit() {
      if (this.isEditMode) {
        this.$inertia.put(`/subdistrict/${this.editId}`, this.form);
      } else {
        this.$inertia.post('/subdistrict', this.form);
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
