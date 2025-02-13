<template>
  <div>
    <Background />
    <div class="text-sm text-[#99cbc0] font-bold p-5">
      <Link href="/dashboard" class="text-[#D4A017] no-underline">Beranda</Link> > Local Government Organization
    </div>

    <div class="content relative z-10 p-5 text-gray-800">
      <h1 class="text-center text-4xl text-[#D4A017] font-bold mt-12">LOCAL GOVERNMENT ORGANIZATION</h1>
      <AddButton @click="openAddModal" buttonText="Add New OPD" />

      <Card>
        <table class="w-11/12 mx-auto table-auto border-collapse">
          <thead>
            <tr>
              <th class="py-2 px-4 border-b text-center">Nama OPD</th>
              <th class="py-2 px-4 border-b text-center">Alamat</th>
              <th class="py-2 px-4 border-b text-center">Kontak</th>
              <th class="py-2 px-4 border-b text-center" style="width: 150px;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="opd in opds" :key="opd.id">
              <td class="py-2 px-4 border-t border-b">
                <a :href="opd.link || '#'" class="text-blue-500 hover:underline" target="_blank">
                  {{ opd.name }}
                </a>
              </td>
              <td class="py-2 px-4 border-t border-b">{{ opd.address }}</td>
              <td class="py-2 px-4 border-t border-b">{{ opd.contact }}</td>
              <td class="actions py-2 px-4 border-t border-b flex justify-center items-center space-x-2">
                <button @click="openEditModal(opd)" class="edit bg-teal-700 text-white font-bold py-2 px-4 rounded transition duration-300 hover:bg-[#99cbc0]">
                  Edit
                </button>
                <button class="delete bg-red-600 text-white font-bold py-2 px-4 rounded transition duration-300 hover:bg-red-400" @click="deleteOpd(opd.id)">
                  Hapus
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </Card>

      <!-- Modal for Add/Edit OPD -->
      <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
          <h2 class="text-center text-2xl font-bold text-yellow-500 mb-4">{{ isEditMode ? 'Edit OPD' : 'Add New OPD' }}</h2>
          <form @submit.prevent="handleSubmit">
            <div class="mb-4">
              <label for="name" class="block text-sm font-medium text-gray-700">Nama OPD</label>
              <input v-model="form.name" type="text" id="name" class="w-full mt-2 p-2 border rounded-md" placeholder="Nama OPD" required />
            </div>
            <div class="mb-4">
              <label for="address" class="block text-sm font-medium text-gray-700">Alamat</label>
              <input v-model="form.address" type="text" id="address" class="w-full mt-2 p-2 border rounded-md" placeholder="Alamat OPD" required />
            </div>
            <div class="mb-4">
              <label for="contact" class="block text-sm font-medium text-gray-700">Kontak</label>
              <input v-model="form.contact" type="text" id="contact" class="w-full mt-2 p-2 border rounded-md" placeholder="Kontak OPD" required />
            </div>
            <div class="mb-4">
              <label for="link" class="block text-sm font-medium text-gray-700">Link</label>
              <input v-model="form.link" type="text" id="link" class="w-full mt-2 p-2 border rounded-md" placeholder="Link OPD" />
            </div>
            <div class="flex justify-end gap-4">
              <button type="button" @click="closeModal" class="bg-gray-300 text-black py-2 px-4 rounded">Batal</button>
              <button type="submit" class="w-full py-2 px-4 text-white font-bold bg-yellow-500 rounded-lg hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-offset-2 transition duration-150 ease-in-out">
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
import AddButton from "@/Components/UI/AddButton.vue";

export default {
  components: {
    Background,
    Card,
    Link,
    AddButton,
  },
  props: {
    opds: Array,
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
    openEditModal(opd) {
      this.isEditMode = true;
      this.form = { ...opd };
      this.editId = opd.id;
      this.showModal = true;
    },
    closeModal() {
      this.showModal = false;
      this.form = { name: '', address: '', contact: '', link: '' };
      this.editId = null;
    },
    deleteOpd(id) {
      if (confirm("Apakah Anda yakin ingin menghapus opd ini?")) {
        this.$inertia.delete(`/lgo/${id}`);
      }
    },
    handleSubmit() {
      if (this.isEditMode) {
        this.$inertia.put(`/lgo/${this.editId}`, this.form);
      } else {
        this.$inertia.post('/lgo', this.form);
      }
      this.closeModal();
    },
  },
};
</script>

<style scoped>
.modal-overlay {
  z-index: 1000;
}
.modal {
  max-width: 500px;
}
</style>