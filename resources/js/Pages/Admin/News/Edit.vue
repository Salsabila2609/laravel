<script setup>
import { useForm } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import MyEditor from '@/Components/Layout/Berita/MyEditor.vue';

const props = defineProps({
  news: {
    type: Object,
    required: true
  }
});

const categories = [
  'Pemerintah',
  'Berita Daerah', 
  'Umum',
  'Ekonomi',
  'Seni Budaya dan Hiburan',
  'Lowongan',
];

const form = useForm({
  _method: 'PUT',  
  penulis: props.news.penulis,
  judul: props.news.judul,
  isi_berita: props.news.isi_berita,
  kategori: props.news.kategori || [],
  gambar_utama: null,
  gambar_utama_keterangan: props.news.gambar_utama_keterangan || '',
  gambar_lampiran: [],
  gambar_lampiran_keterangan: [],
  kept_attachments: props.news.gambar_lampiran || [],
  kept_attachments_keterangan: props.news.gambar_lampiran_keterangan || [],
});

const submit = () => {
  const formKategori = JSON.stringify(form.kategori);
  
  const formData = new FormData();
  formData.append('_method', 'PUT');
  formData.append('penulis', form.penulis);
  formData.append('judul', form.judul);
  formData.append('isi_berita', form.isi_berita);
  formData.append('kategori', formKategori);
  formData.append('gambar_utama_keterangan', form.gambar_utama_keterangan);

  if (form.gambar_utama instanceof File) {
    formData.append('gambar_utama', form.gambar_utama);
  }

  form.kept_attachments.forEach((path, index) => {
    formData.append(`kept_attachments[]`, path);
    formData.append(`kept_attachments_keterangan[]`, form.kept_attachments_keterangan[index] || '');
  });

  form.gambar_lampiran.forEach((file, index) => {
    if (file instanceof File) {
      formData.append(`gambar_lampiran[]`, file);
      formData.append(`gambar_lampiran_keterangan[]`, form.gambar_lampiran_keterangan[index] || '');
    }
  });

  form.post(`/news/${props.news.id}`, {
    forceFormData: true,
    onSuccess: () => {},
    preserveScroll: true,
  });
};

const addImage = (event) => {
  const files = event.target.files;
  if (files.length > 0) {
    form.gambar_lampiran.push(files[0]);
    form.gambar_lampiran_keterangan.push('');
  }
};

const removeExistingImage = (index) => {
  form.kept_attachments.splice(index, 1);
  form.kept_attachments_keterangan.splice(index, 1);
};

const removeNewImage = (index) => {
  form.gambar_lampiran.splice(index, 1);
  form.gambar_lampiran_keterangan.splice(index, 1);
};

const previewImage = (file) => {
  return file instanceof File ? URL.createObjectURL(file) : `/storage/${file}`;
};
</script>

<template>
  <div class="min-h-screen bg-gray-100">
    <div class="text-sm text-[#99cbc0] font-bold p-5">
  <Link href="/dashboard" class="text-[#D4A017] no-underline">Beranda > </Link>
  <Link href="/news" class="text-[#D4A017] no-underline">Berita</Link> > 
  Edit Berita
</div>

    <div class="container mx-auto p-3">
      <div class="bg-white shadow-lg rounded-lg p-6">
        <h1 class="text-2xl font-bold text-[#D4A017]">Edit News</h1>
        <div class="h-1 bg-[#D4A017] w-2/8 mt-1"></div>

        <form @submit.prevent="submit" enctype="multipart/form-data" class="grid grid-cols-1 sm:grid-cols-3 gap-6 mt-4">
          <!-- Kolom Kiri (2/3) -->
          <div class="col-span-1 sm:col-span-2 space-y-2">
            <label class="block text-lg font-semibold text-[#D4A017]">Penulis</label>
            <input v-model="form.penulis" type="text" class="w-full p-2 border rounded-md" required />

            <label class="block text-lg font-semibold text-[#D4A017]">Judul Berita</label>
            <input v-model="form.judul" type="text" class="w-full p-2 border rounded-md" required />

            <label class="block text-lg font-semibold text-[#D4A017]">Isi Berita</label>
            <MyEditor v-model="form.isi_berita" class="w-full border rounded-md" />
          </div>

          <!-- Kolom Kanan (1/3) -->
          <div class="space-y-4">
            <label class="block text-lg font-semibold text-[#D4A017]">Kategori</label>
            <div class="flex flex-wrap gap-2">
              <label v-for="category in categories" :key="category" class="inline-flex items-center space-x-2">
                <input type="checkbox" :value="category" v-model="form.kategori" class="h-4 w-4" />
                <span class="text-sm text-gray-600">{{ category }}</span>
              </label>
            </div>

            <label class="block text-lg font-semibold text-[#D4A017]">Gambar Utama</label>
            <div v-if="news.gambar_utama" class="mb-2">
              <p class="text-sm text-gray-600">Gambar Saat Ini:</p>
              <img :src="`/storage/${news.gambar_utama}`" class="w-32 h-32 rounded-md" />
              <input v-model="form.gambar_utama_keterangan" type="text" placeholder="Keterangan gambar" class="w-full mt-2 p-2 border rounded-md" />
            </div>
            <input type="file" accept="image/*" @change="event => form.gambar_utama = event.target.files[0]" class="w-full border rounded-md p-2" />
            <div v-if="form.gambar_utama">
              <p class="text-sm text-gray-600">Gambar Baru:</p>
              <img :src="previewImage(form.gambar_utama)" class="w-32 h-32 rounded-md" />
              <input v-model="form.gambar_utama_keterangan" type="text" placeholder="Keterangan gambar" class="w-full mt-2 p-2 border rounded-md" />
            </div>

            <label class="block text-lg font-semibold text-[#D4A017]">Lampiran</label>
            <div v-for="(path, index) in form.kept_attachments" :key="index" class="flex items-center space-x-2">
              <img :src="`/storage/${path}`" class="w-16 h-16 rounded-md" />
              <button @click="removeExistingImage(index)" class="text-red-600 hover:text-red-800">Hapus</button>
              <input v-model="form.kept_attachments_keterangan[index]" type="text" placeholder="Keterangan gambar" class="w-full mt-2 p-2 border rounded-md" />
            </div>
            <input type="file" accept="image/*" @change="addImage" class="w-full border rounded-md p-2" />
          </div>
        </form>
      </div>
    </div>
  </div>
</template>
