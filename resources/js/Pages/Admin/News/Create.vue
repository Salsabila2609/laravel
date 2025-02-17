<script setup>
import { useForm } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import MyEditor from '@/Components/Layout/Berita/MyEditor.vue'; // Pastikan MyEditor di-import

// Form setup with default values
const form = useForm({
  penulis: 'Pemkab Madiun', // default penulis
  judul: '',
  isi_berita: '',
  gambar_utama: null,
  gambar_utama_keterangan: '', // Keterangan gambar utama
  gambar_lampiran: [],  // Array for multiple attachment images
  gambar_lampiran_keterangan: [], // Keterangan untuk setiap gambar lampiran
  kategori: [],
});

// Categories for checkbox options
const categories = [
  'Pemerintah',
  'Berita Daerah', 
  'Umum',
  'Ekonomi',
  'Seni Budaya dan Hiburan',
  'Lowongan',
];

// Submit function
const submit = () => {
  // Convert the categories array to a JSON string before submission
  form.kategori = JSON.stringify(form.kategori);

  // Create a FormData object
  const formData = new FormData();

  // Append form data to FormData object
  formData.append('penulis', form.penulis);
  formData.append('judul', form.judul);
  formData.append('isi_berita', form.isi_berita);
  formData.append('kategori', form.kategori);
  formData.append('gambar_utama_keterangan', form.gambar_utama_keterangan); // Menambahkan keterangan gambar utama

  // If gambar utama is selected, append it to FormData object
  if (form.gambar_utama) {
    formData.append('gambar_utama', form.gambar_utama);
  }

  // Append all images in gambar_lampiran array and their corresponding keterangan
  form.gambar_lampiran.forEach((file, index) => {
    formData.append(`gambar_lampiran[${index}]`, file); // Fixed string interpolation
    formData.append(`gambar_lampiran_keterangan[${index}]`, form.gambar_lampiran_keterangan[index]); // Fixed string interpolation
  });

  // Submit the form data to the Laravel backend
  form.post('/news', {
    data: formData,
    headers: {
      'Content-Type': 'multipart/form-data', // Important for file upload
    },
  });
};

// Method to add image to the form
const addImage = (event) => {
  if (event.target.files.length > 0) {
    form.gambar_lampiran.push(event.target.files[0]);
    form.gambar_lampiran_keterangan.push(''); // Menambahkan entri kosong untuk keterangan gambar
  }
};

// Method to remove an attachment image
const removeImage = (index) => {
  form.gambar_lampiran.splice(index, 1);
};

// Method to preview the uploaded image
const previewImage = (file) => {
  return URL.createObjectURL(file);
};
</script>

<template>
  <div class="min-h-screen bg-gray-100">
    <!-- Navbar -->
    <div class="text-sm text-[#99cbc0] font-bold p-5">
  <Link href="/dashboard" class="text-[#D4A017] no-underline">Beranda > </Link>
  <Link href="/news" class="text-[#D4A017] no-underline">Berita</Link> > 
  Tambah Berita
</div>


    <div class="container mx-auto p-3">
      <!-- Form dalam satu card -->
      <div class="bg-white shadow-lg rounded-lg p-6">
        <!-- Header dalam card dengan desain seperti gambar -->
        <div class="mb-8">
          <h1 class="text-2xl font-bold text-[#D4A017]">Create News</h1>
          <div class="h-1 bg-[#D4A017] w-2/8 mt-1"></div>
        </div>

        <form @submit.prevent="submit" enctype="multipart/form-data">
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <!-- Kiri: Form Inputs (Lebih Lebar) -->
            <div class="sm:col-span-2">
              <label class="block text-lg font-semibold text-[#D4A017]">Penulis</label>
              <input v-model="form.penulis" type="text" class="w-full p-2 border rounded-md" disabled />

              <label class="block mt-4 text-lg font-semibold text-[#D4A017]">Judul Berita</label>
              <input v-model="form.judul" type="text" class="w-full p-2 border rounded-md" required />

              <label class="block mt-4 text-lg font-semibold text-[#D4A017]">Isi Berita</label>
              <MyEditor v-model="form.isi_berita" class="w-full border rounded-md p-2" />
            </div>

            <!-- Kanan: Kategori & Uploads -->
            <div>
              <label class="block text-lg font-semibold text-[#D4A017]">Kategori</label>
              <div class="flex flex-wrap gap-2 mt-2">
                <label v-for="category in categories" :key="category" class="inline-flex items-center">
                  <input type="checkbox" :value="category" v-model="form.kategori" class="mr-2" />
                  {{ category }}
                </label>
              </div>

              <label class="block mt-4 text-lg font-semibold text-[#D4A017]">Gambar Utama</label>
              <input type="file" accept="image/*" @change="event => form.gambar_utama = event.target.files[0]" class="w-full border rounded-md p-2" required />
              <img v-if="form.gambar_utama" :src="previewImage(form.gambar_utama)" class="w-32 h-32 mt-2 rounded-md" />
              <input v-model="form.gambar_utama_keterangan" type="text" placeholder="Keterangan gambar utama" class="w-full p-2 border rounded-md mt-2" />

              <label class="block mt-4 text-lg font-semibold text-[#D4A017]">Gambar Lampiran</label>
              <input type="file" accept="image/*" @change="addImage" class="w-full border rounded-md p-2" />
              <div v-for="(gambar, index) in form.gambar_lampiran" :key="index" class="flex items-center mt-2">
                <img :src="previewImage(gambar)" class="w-16 h-16 rounded-md" />
                <input v-model="form.gambar_lampiran_keterangan[index]" type="text" placeholder="Keterangan gambar" class="ml-2 p-2 border rounded-md" />
                <button type="button" @click="removeImage(index)" class="ml-2 text-red-600">Hapus</button>
              </div>
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>
</template>
