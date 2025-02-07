<template>
    <div class="max-w-lg mx-auto mt-10 p-5 bg-white rounded-xl shadow-md">
      <h2 class="text-2xl font-semibold mb-4">Upload Gambar</h2>
  
      <form @submit.prevent="submitImage">
        <div class="mb-4">
          <label class="block mb-2 text-gray-700">Pilih Gambar:</label>
          <input type="file" @change="onFileSelected" accept="image/*" class="border rounded-md p-2 w-full" />
        </div>
  
        <button 
          type="submit" 
          class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-700">
          Upload
        </button>
      </form>
  
      <div v-if="message" class="mt-4 text-green-600">
        {{ message }}
      </div>
    </div>
  </template>
  
  <script>
  import { Inertia } from '@inertiajs/inertia';
  
  export default {
    data() {
      return {
        selectedFile: null,
        message: '',
      };
    },
    props: {
      flash: Object,
    },
    mounted() {
      if (this.flash.message) {
        this.message = this.flash.message;
      }
    },
    methods: {
      onFileSelected(event) {
        this.selectedFile = event.target.files[0];
      },
      submitImage() {
        if (!this.selectedFile) {
          alert('Silakan pilih gambar terlebih dahulu.');
          return;
        }
  
        const formData = new FormData();
        formData.append('image', this.selectedFile);
  
        Inertia.post('/upload-image', formData);
      },
    },
  };
  </script>
  
  <style scoped>
  input[type="file"] {
    border: 1px solid #ddd;
    padding: 8px;
    width: 100%;
  }
  </style>
  