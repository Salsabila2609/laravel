<template>
  <div>
    <!-- Gunakan komponen Background di sini -->
    <Background />

    <!-- Breadcrumb -->
    <div class="text-sm text-[#99cbc0] font-bold p-5">
      <Link href="/dashboard" class="text-yellow-500 no-underline">Beranda</Link> > Subdistrict
    </div>

    <div class="relative z-1 p-5 text-gray-800">
      <h1 class="text-center text-4xl text-yellow-500 font-bold mt-12">KECAMATAN SE-KABUPATEN</h1>

      <!-- Gunakan tombol Add New Kecamatan -->
    <AddButton @click="openAddModal" buttonText="Add New Kecamatan" />
      <!-- Card yang berisi daftar pejabat -->
      <Card>
        <!-- Isi slot dengan tabel -->
        <table class="w-11/12 mx-auto table-auto border-collapse">
  <thead>
    <tr>
      <th class="py-2 px-4 border-b text-center">Kecamatan</th>
      <th class="py-2 px-4 border-b text-center">Alamat</th>
      <th class="py-2 px-4 border-b text-center">Kontak</th>
      <th class="py-2 px-4 border-b text-center" style="width: 150px;">Aksi</th>
    </tr>
  </thead>
  <tbody>
    <tr v-for="kecamatan in kecamatans" :key="kecamatan.id">
      <td class="py-2 px-4 border-t border-b">
        <a :href="kecamatan.link" class="text-blue-500 hover:underline" target="_blank">
          {{ kecamatan.name }}
        </a>
      </td>
      <td class="py-2 px-4 border-t border-b">{{ kecamatan.address }}</td>
      <td class="py-2 px-4 border-t border-b">{{ kecamatan.contact }}</td>
      <td class="py-2 px-4 border-t border-b flex justify-center items-center gap-2">
        <!-- Button untuk Edit -->
        <button @click="openEditModal(kecamatan)" class="bg-teal-700 text-white font-bold py-2 px-4 rounded transition duration-300 hover:bg-[#99cbc0]">Edit</button>

        <!-- Button untuk Delete -->
        <button class="bg-red-600 text-white font-bold py-2 px-4 rounded transition duration-300 hover:bg-red-400" @click="deleteKecamatan(kecamatan.id)">Hapus</button>
      </td>
    </tr>
  </tbody>
</table>
      </Card>
    </div>

    <!-- Modal untuk Add/Edit Kecamatan -->
    <div v-if="isModalOpen" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
      <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-center text-2xl font-bold text-yellow-500 mb-4">{{ formType === 'add' ? 'Add Kecamatan' : 'Edit Kecamatan' }}</h2>
        
        <form @submit.prevent="handleSubmit">
          <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Kecamatan</label>
            <input v-model="formData.name" type="text" id="name" class="w-full mt-2 p-2 border rounded-md" placeholder="Nama Kecamatan" required />
          </div>

          <div class="mb-4">
            <label for="address" class="block text-sm font-medium text-gray-700">Alamat</label>
            <input v-model="formData.address" type="text" id="address" class="w-full mt-2 p-2 border rounded-md" placeholder="Alamat Kecamatan" required />
          </div>

          <div class="mb-4">
            <label for="contact" class="block text-sm font-medium text-gray-700">Kontak</label>
            <input v-model="formData.contact" type="text" id="contact" class="w-full mt-2 p-2 border rounded-md" placeholder="Kontak Kecamatan" required />
          </div>

          <!-- Tambahkan input untuk link -->
          <div class="mb-4">
            <label for="link" class="block text-sm font-medium text-gray-700">Link</label>
            <input v-model="formData.link" type="text" id="link" class="w-full mt-2 p-2 border rounded-md" placeholder="Link Kecamatan" />
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
    AddButton,
    Card,
    Link,
  },
  props: {
    kecamatans: Array,
  },
  data() {
    return {
      isModalOpen: false,
      formType: 'add', // Default form type is 'add'
      selectedKecamatan: null,
      formData: {
        name: '',
        address: '',
        contact: '',
        link: '',
      },
    };
  },
  methods: {
    async deleteKecamatan(id) {
      if (confirm("Apakah Anda yakin ingin menghapus kecamatan ini?")) {
        await this.$inertia.delete(`/subdistrict/${id}`);
      }
    },
    openAddModal() {
      this.formType = 'add';
      this.selectedKecamatan = null;
      this.formData = { name: '', address: '', contact: '', link: '' };// Reset form
      this.isModalOpen = true;
    },
    openEditModal(kecamatan) {
      this.formType = 'edit';
      this.selectedKecamatan = kecamatan;
      this.formData = { name: kecamatan.name, address: kecamatan.address, contact: kecamatan.contact, link: kecamatan.link, };
      this.isModalOpen = true;
    },
    closeModal() {
      this.isModalOpen = false;
    },
    async handleSubmit() {
      if (this.formType === 'add') {
        // Handle adding new Kecamatan
        await this.$inertia.post('/subdistrict', this.formData);
      } else {
        // Handle editing existing Kecamatan
        await this.$inertia.put(`/subdistrict/${this.selectedKecamatan.id}`, this.formData);
      }
      this.closeModal();
    },
  },
};
</script>
