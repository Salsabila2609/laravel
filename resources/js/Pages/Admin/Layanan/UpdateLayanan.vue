<template>
  <div>
    <Background />
    <div class="text-sm text-[#99cbc0] font-bold p-5">
      <Link href="/" class="text-[#D4A017] no-underline">Beranda</Link> > Kelola Layanan
    </div>

    <div class="content relative z-10 p-5 text-gray-800">
      <h1 class="text-center text-4xl text-[#D4A017] font-bold mt-12">KELOLA LAYANAN</h1>
      <AddButton @click="openModal(false)" buttonText="Tambah Layanan" />

      <Card>
        <table class="w-11/12 mx-auto table-auto border-collapse">
          <thead>
            <tr>
              <th class="py-2 px-4 border-b text-center">Icon</th>
              <th class="py-2 px-4 border-b text-center">Judul</th>
              <th class="py-2 px-4 border-b text-center">URL</th>
              <th class="py-2 px-4 border-b text-center">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="layanan in layanans" :key="layanan.id">
              <td class="py-2 px-4 border-t border-b text-center">
                <img :src="'/storage/' + layanan.icon" alt="Icon Layanan" width="50" />
              </td>
              <td class="py-2 px-4 border-t border-b text-center">{{ layanan.title }}</td>
              <td class="py-2 px-4 border-t border-b text-center">
                <a :href="layanan.url" target="_blank" class="text-blue-500 hover:underline">{{ layanan.url }}</a>
              </td>
              <td class="actions py-2 px-4 border-t border-b flex justify-center items-center space-x-2">
                <button class="edit bg-teal-700 text-white font-bold py-2 px-4 rounded" @click="openModal(true, layanan)">
                  Edit
                </button>
                <button class="delete bg-red-600 text-white font-bold py-2 px-4 rounded" @click="deleteLayanan(layanan.id)">
                  Delete
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
          {{ isEditMode ? 'Edit Layanan' : 'Tambah Layanan' }}
        </h2>
        
        <form @submit.prevent="handleSubmit">
          <input v-model="form.title" type="text" placeholder="Judul Layanan" class="w-full p-2 border rounded mb-4" required />
          <input v-model="form.url" type="url" placeholder="URL Layanan" class="w-full p-2 border rounded mb-4" required />
          <input @change="handleFileUpload" type="file" class="w-full p-2 border rounded mb-4" />
          
          <div class="flex justify-end gap-4">
            <button type="button" @click="closeModal" class="bg-gray-300 text-black py-2 px-4 rounded">Batal</button>
            <button type="submit" class="w-full py-2 px-4 text-white font-bold bg-yellow-500 rounded-lg hover:bg-yellow-600">
              {{ isEditMode ? 'Simpan' : 'Tambah' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import Background from "@/Components/Layout/Background.vue";
import Card from "@/Components/Layout/Card.vue";
import { Link } from "@inertiajs/vue3";
import AddButton from "@/Components/Layout/AddButton.vue";

export default {
  components: {
    Background,
    Card,
    Link,
    AddButton,
  },
  props: {
    layanans: Array,
  },
  data() {
    return {
      isModalOpen: false,
      isEditMode: false,
      form: {
        id: null,
        title: "",
        url: "",
        icon: null,
      },
    };
  },
  methods: {
    openModal(isEditMode, layanan = null) {
      this.isEditMode = isEditMode;
      this.isModalOpen = true;
      if (isEditMode && layanan) {
        this.form = { ...layanan, icon: null };
      } else {
        this.form = { title: "", url: "", icon: null, id: null };
      }
    },

    closeModal() {
      this.isModalOpen = false;
      this.isEditMode = false;
    },

    handleFileUpload(event) {
      this.form.icon = event.target.files[0] || null;
    },

    async handleSubmit() {
      if (this.isEditMode) {
        await this.updateLayanan();
      } else {
        await this.addLayanan();
      }
    },

    async addLayanan() {
      const formData = new FormData();
      formData.append("title", this.form.title);
      formData.append("url", this.form.url);
      if (this.form.icon) formData.append("icon", this.form.icon);
      
      await this.$inertia.post("/layanan", formData);
      this.closeModal();
    },

    async updateLayanan() {
      const formData = new FormData();
      formData.append("title", this.form.title);
      formData.append("url", this.form.url);
      if (this.form.icon) formData.append("icon", this.form.icon);
      formData.append("_method", "PUT");
      
      await this.$inertia.post(`/layanan/${this.form.id}`, formData);
      this.closeModal();
    },

    async deleteLayanan(id) {
      if (confirm("Yakin ingin menghapus layanan ini?")) {
        await this.$inertia.delete(`/layanan/${id}`);
      }
    },
  },
};
</script>
