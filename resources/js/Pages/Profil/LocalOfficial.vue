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

      <!-- Gunakan komponen AddButton -->
      <AddButton url="/local_official/create" buttonText="Add New Pejabat" />

      <!-- Card yang berisi daftar pejabat -->
      <Card>
        <!-- Isi slot dengan tabel -->
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
              <td class="py-2 px-4 border-t border-b flex justify-center items-center space-x-2">
                <!-- Button untuk Edit -->
                <Link :href="`/local_official/edit/${pejabat.id}`">
                  <button class="bg-teal-700 text-white font-bold py-2 px-4 rounded transition duration-300 hover:bg-[#99cbc0]">
                    Edit
                  </button>
                </Link>

                <!-- Button untuk Delete -->
                <button class="bg-red-600 text-white font-bold py-2 px-4 rounded transition duration-300 hover:bg-red-400" @click="deletePejabat(pejabat.id)">
                  Hapus
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </Card>
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
    pejabats: Array,
  },
  methods: {
    async deletePejabat(id) {
      if (confirm("Apakah Anda yakin ingin menghapus pejabat ini?")) {
        await this.$inertia.delete(`/local_official/${id}`);
      }
    },
  },
};
</script>
